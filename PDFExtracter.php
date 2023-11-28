<?php

include 'vendor/autoload.php';

function extractTextFromPDF($filename)
{

    // Initialize and load PDF Parser library 
    $parser = new \Smalot\PdfParser\Parser();

    // Parse pdf file using Parser library 
    $pdf = $parser->parseFile($filename);

    // Extract text from PDF 
    $textContent = $pdf->getText();

    return $textContent;
}
