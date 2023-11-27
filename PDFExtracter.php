<?php

function extractTextFromPDF($filename)
{
    $command = "poppler\bin\pdftotext -layout {$filename} -"; // -layout preserves the layout structure
    $output = [];
    exec($command, $output);

    // Combine lines into a single string
    $text = implode("\n", $output);

    return $text;
}
