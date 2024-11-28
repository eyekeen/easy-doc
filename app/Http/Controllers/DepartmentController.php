<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\RejectPetition;
use Inertia\Inertia;
use App\Models\Petition;
use App\Models\ReadyDocument;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->id;

        $petitions = DB::table('petitions AS p')
            ->join('document_templates AS dt', 'dt.id', '=', 'p.template_id')
            ->join('users AS u', 'u.id', '=', 'p.sender')
            ->join('documents AS d', 'd.id', '=', 'p.document_id')
            ->join('statuses as s', 's.id', '=', 'p.status')
            ->select([
                DB::raw('p.id as p_id'),
                DB::raw('u.name as s_name'),
                DB::raw('p.created_at as publish_date'),
                DB::raw('dt.origin_name as origin_name'),
                DB::raw('s.status as status'),
                DB::raw('p.status as status_code'),
                DB::raw('d.path as d_path'),
            ])
            ->where('p.status', '=', 2)
            ->get();

        $statuses = Status::where('id', '!=', 1)->get();

        return Inertia::render('Department/Dashboard', [
            'petitions' => $petitions,
            'statuses' => $statuses
        ]);
    }

    public function update(Request $request, $pid)
    {
        $new_status = $request->input('new_status');

        $petition = Petition::find($pid);

        $petition->status = $new_status;

        if ($petition->save()) {



            $file = $request->file('signedDoc');

            // Define the destination path within the 'public' directory
            $destinationPath = 'ready';

            // Create a new unique filename with the original extension
            $newFileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Move the file to the destination path in 'public' with the new name
            $filePath = $file->storeAs($destinationPath, $newFileName, 'public');

            $readyDoc = new ReadyDocument();

            $readyDoc->petition_id = $pid;
            $readyDoc->note = $request->input('note') ?? null;

            $readyDoc->name = $file->getClientOriginalName();
            $readyDoc->path = "ready/$newFileName";

            if ($readyDoc->save()) {
                return response()->json(['petition' => $petition, 'readyDoc' => $readyDoc]);
            } else {
                return response()->json(['msg' => 'erro'], 401);
            }
        } else {
            return response()->json(['msg' => 'erro'], 401);
        }
    }


    public function electronicKey(Request $request)
    {
        $new_status = $request->get('new_status');

        $petition = Petition::find($request->get('pid'));

        $petition->status = $new_status;

        $file = Document::find($petition->document_id);

        $filePath = storage_path('app/public/' . $file->path);

        if (!file_exists($filePath)) {
            throw new \Exception("Файл не найден: {$filePath}");
        }

        // Загружаем существующий документ
        $phpWord = IOFactory::load($filePath, 'Word2007');


        // Получаем последний раздел документа
        $sections = $phpWord->getSections();
        $lastSection = end($sections); // Последний раздел

        // Путь к изображению, которое нужно добавить
        $imagePath = storage_path('app/public/pechat.jpg');

        // Добавляем изображение в конец последнего раздела
        $lastSection->addImage(
            $imagePath,
            [
                'width' => 200,    // Ширина изображения
                'height' => 150,   // Высота изображения
                'alignment' => Jc::CENTER, // Центрирование
            ]
        );

        // Сохраняем обновленный документ с новым именем

        if (!is_dir(storage_path('app/public/ready'))) {
            mkdir(storage_path('app/public/ready'));
        }

        $outputDir = storage_path('app/public/ready/');
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

        $newFileName = Str::uuid() . '.docx';
        $newFilePath = storage_path('app/public/ready/' . $newFileName);
        $phpWord->save($newFilePath, 'Word2007');

        ////////////////////////////////////////////////////////////

        // Пути к файлу, который нужно подписать, и для сохранения подписи
        $documentPath = $newFilePath;
        $signaturePath = storage_path('app/private/' . 'document.sig');
        $privateKeyPath = storage_path('app/private/' . 'private.key');

        // Загрузка содержимого документа
        $documentData = file_get_contents($documentPath);

        // Загрузка приватного ключа
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if ($privateKey === false) {
            die("Ошибка загрузки приватного ключа");
        }

        // Создание хэша документа
        $hash = hash('sha256', $documentData, true);

        // Подпись хэша
        $signature = 'tarum';
        openssl_sign($hash, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // Сохранение подписи в файл
        file_put_contents($signaturePath, $signature);

        ////////////////////////////////////////////////////////////

        if ($petition->save()) {
            $readyDoc = new ReadyDocument();

            $readyDoc->petition_id = $request->get('pid');
            $readyDoc->note = $request->input('note') ?? null;

            $readyDoc->name = $newFileName;
            $readyDoc->path = 'ready/' . $newFileName;
            $readyDoc->electronKey = 1;

            if ($readyDoc->save()) {
                return response()->json(['petition' => $petition, 'readyDoc' => $readyDoc]);
            } else {
                return response()->json(['msg' => 'erro'], 401);
            }
        } else {
            return response()->json(['msg' => 'erro'], 401);
        }
    }

    public function reject(Request $request, $pid)
    {
        $new_status = $request->get('new_status');


        $petition = Petition::find($pid);

        $petition->status = $new_status;

        $reject = new RejectPetition();

        $reject->petition_id = $pid;
        $reject->reason = $request->get('reason');

        if ($petition->save() && $reject->save()) {
            return response()->json(['petition' => $petition, 'reject' => $reject]);
        } else {
            return response()->json(['msg' => 'erro'], 400);
        }
    }
}
