<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequired;
use App\Models\DocumentTemplate;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Document;
use App\Models\Petition;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->id;

        $statuses = Status::where('id', '!=', 4)->get();

        $first_check = $this->getPetitions($user, 1);
        $department = $this->getPetitions($user, 2);
        $ready = $this->getPetitions($user, 3);
        $reject = $this->getPetitions($user, 5);


        return Inertia::render('Student/Dashboard', [
            'statuses' => $statuses,
            'first_check' => $first_check,
            'department' => $department,
            'ready' => $ready,
            'reject' => $reject,  
        ]);
    }

    function show()
    {
        $templates = DocumentTemplate::all();
        $method = User::where('role', 2)->get();
        $required = DocumentRequired::where('data_for', 1)->get();

        return Inertia::render('Student/Send', ['templates' => $templates, 'method' => $method, 'required' => $required]);
    }

    function store(Request $request)
    {
        $requiredData = $request->get('requiredData');

        $petition_id = $request->get('petition_id');
        $meth = $request->get('meth');

        $filename = DocumentTemplate::find($petition_id);

        $res = $this->replaceAndSaveDocument($request, $filename->path, $requiredData, $filename->id);


        if ($res->getStatusCode() == 500) {
            return 'error';
        } elseif ($res->getStatusCode() == 200) {

            $sender = auth()->id();
            $receiver = $meth;
            $template_id = $filename->id;
            $document_id = $res->getData(true)['document_id'];
            $status = 1;

            $new_petition = Petition::create([
                'sender' => $sender,
                'receiver' => $receiver,
                'template_id' => $template_id,
                'document_id' => $document_id,
                'status' => $status,
            ]);

            return 'success';
        }
    }

    public function replaceAndSaveDocument(Request $request, $filename, array $replacements, $template_id)
    {
        try {
            // $filePath = storage_path('app/public/' . $filename);
            $filePath = storage_path('app/public/' . $filename);


            if (!file_exists($filePath)) {
                throw new \Exception("Файл не найден: {$filePath}");
            }

            $document = IOFactory::load($filePath);


            // Проверяем, содержит ли документ данные
            if (count($document->getSections()) === 0) {
                throw new \Exception('Документ пустой');
            }

            $file = $filePath;

            $phpword = new TemplateProcessor($file);

            $replacements['sname'] = $request->user()->name;


            if(isset($replacements['ostart'])){
                $ostart_date = explode('.', $replacements['ostart']);

                $replacements['ostartd'] = $ostart_date[0];
                $replacements['ostartm'] = $ostart_date[1];
                $replacements['ostarty'] = $ostart_date[2];

                unset($replacements['ostart']);
            }

            if(isset($replacements['oend'])){
                $oend_date = explode('.', $replacements['oend']);

                $replacements['oendd'] = $oend_date[0];
                $replacements['oendm'] = $oend_date[1];
                $replacements['oendy'] = $oend_date[2];

                unset($replacements['oend']);
            }

            $phpword->setValues($replacements);



            $outputDir = storage_path('app/public/documents/');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Проверяем права доступа
            if (!is_writable($outputDir)) {
                throw new \Exception("Директория {$outputDir} недоступна для записи");
            }

            $new_file_name = 'document_' . time() . '.docx';

            $outputPath = $outputDir . '/' . $new_file_name;

            try {
                $writer = IOFactory::createWriter($document, 'Word2007');
                $writer->save($outputPath);
                \Log::info('Документ с заменами успешно сохранен', ['path' => $outputPath]);
            } catch (\Exception $e) {
                \Log::error("Ошибка при сохранении документа с заменами: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'path' => $outputPath,
                ]);
                throw $e;
            }

            $phpword->saveAs($outputPath);

            $new_document = Document::create([
                'template_id' => $template_id,
                'name' => $new_file_name,
                'path' => 'documents/' . $new_file_name,
            ]);

            $path = 'documents/' . $new_file_name;

            return response()->json(['document_id' => $new_document->id]);
        } catch (\Exception $e) {

            \Log::error("Ошибка при загрузке или обработке документа: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'filename' => $filename,
            ]);
            return response()->json(['error' => 'Не удалось загрузить или обработать документ'], 500);
        }
    }

    protected function getPetitions($user, $status) {
        $petitions = DB::table('petitions AS p')
            ->join('document_templates AS dt', 'dt.id', '=', 'p.template_id')
            ->join('users AS u', 'u.id', '=', 'p.receiver')
            ->join('documents AS d', 'd.id', '=', 'p.document_id')
            ->join('statuses as s', 's.id', '=', 'p.status')
            ->leftJoin('reject_petitions as rp', 'rp.petition_id', '=', 'p.id')
            ->leftJoin('ready_documents as rd', 'rd.petition_id', '=', 'p.id')
            ->select([
                DB::raw('p.id as p_id'),
                DB::raw('u.name as m_name'),
                DB::raw('p.created_at as publish_date'),
                DB::raw('dt.origin_name as origin_name'),
                DB::raw('dt.path as t_path'),
                DB::raw('d.path as d_path'),
                DB::raw('s.id as status'),
                DB::raw('rp.reason as reason'),
                DB::raw('rp.created_at as reject_date'),
                DB::raw('rd.note as note'),
                DB::raw('rd.created_at as ready_date'),
                DB::raw('rd.path as rd_path'),
                DB::raw('rd.name as rd_name'),
                DB::raw('rd.electronKey as rd_ekey'),
            ])
            ->where('p.sender', '=', $user)
            ->where('p.status', '=', $status)
            ->get();

        return $petitions;
    }
}
