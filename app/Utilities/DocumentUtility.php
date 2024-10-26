<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;

class DocumentUtility
{
    static public $document;

    static public function doc_fill(
        $inputFilePath,
        $outputFilePath,
        array $replacements
    ) {
        self::$document = self::loadDocument($inputFilePath);
        // self::replaceWords(self::$document, $replacements);
        // self::saveDocument(self::$document, $outputFilePath);
    }

    static public function loadDocument($filePath)
    {
        self::$document = IOFactory::load($filePath);
        return self::$document;
    }

    static public function saveDocument($document, $outputPath)
    {
        $objWriter = IOFactory::createWriter(self::$document);
        $objWriter->save($outputPath);
    }

    static public function replaceWords($document, array $replacements)
    {
        foreach (self::$document->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($replacements as $search => $replace) {
                        $element->setText(str_replace($search, $replace, $element->getText()));
                    }
                }
            }
        }
    }
}
