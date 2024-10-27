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

use PhpOffice\PhpWord\TemplateProcessor;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $statuses = Status::all(['id', 'status']);
        return Inertia::render('Student/Dashboard', ['statuses' => $statuses]);
    }

    function show()
    {
        $templates = DocumentTemplate::all();
        $method = User::where('role', 2)->get();
        $required = DocumentRequired::all();

        return Inertia::render('Student/Send', ['templates' => $templates, 'method' => $method, 'required' => $required]);
    }

    function store(Request $request)
    {
        $petition_id = $request->get('petition_id');
        $meth = $request->get('meth');
        $requiredData = $request->get('requiredData');

        $filename = DocumentTemplate::find($petition_id);

        $this->replaceAndSaveDocument($filename->path, $requiredData);
    }

    public function replaceAndSaveDocument($filename, array $replacements)
    {
        try {
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

            $phpword->setValues($replacements);


            
            $outputDir = storage_path('app/public/documents/');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            // Проверяем права доступа
            if (!is_writable($outputDir)) {
                throw new \Exception("Директория {$outputDir} недоступна для записи");
            }
            
            $outputPath = $outputDir . '/document_' . time() . '.docx';
            
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
            
            return response()->download($outputPath, 'replaced_document.docx');
        } catch (\Exception $e) {
            \Log::error("Ошибка при загрузке или обработке документа: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'filename' => $filename,
            ]);
            return response()->json(['error' => 'Не удалось загрузить или обработать документ'], 500);
        }
    }
}
