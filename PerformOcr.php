<?php

include 'vendor/autoload.php';

function performOCR($imagePath)
{
    // Specify the path to the Tesseract executable
    $tesseractExecutable = 'C:\xampp\htdocs\pdf-gpt-master\Tesseract-OCR\tesseract.exe';

    // Specify the language for OCR 
    $language = 'eng';

    // Output file path for the OCR result
    $timestamp = time();
    $outputFile = 'output';

    // Run Tesseract OCR
    $command = "{$tesseractExecutable} {$imagePath} {$outputFile} -l {$language}";
    shell_exec($command);

    // Read and return the OCR result
    $ocrResult = file_get_contents($outputFile . ".txt");

    unlink($outputFile);

    return $ocrResult;
}
