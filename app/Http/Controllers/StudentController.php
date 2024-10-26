<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequired;
use App\Models\DocumentTemplate;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Utilities\DocumentUtility;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\File;

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

        $template = DocumentTemplate::find($petition_id);

        $inputFilePath =  Storage::path($template->path); // Путь к входному документу
        $file = IOFactory::load($inputFilePath);



        dd($file);


        $outputFilePath = storage_path('/documents/output.docx'); // Путь для сохранения обработанного документа

        $replacements = [
            '{NAME}' => 'Иван Иванов',
            '{POSITION}' => 'Разработчик',
            '{DATE}' => now()->format('d.m.Y'),
        ];

        DocumentUtility::doc_fill($inputFilePath, $outputFilePath, $requiredData);

        return response()->download($outputFilePath, 'processed_document.docx');
    }
}
