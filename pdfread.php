<?php
require "PDFExtracter.php";
require "ChatGPT.php";
require "DataBase.php";


// Specify the folder where your PDF files are located
$folderPath = './pdfs';
$errorFolder = "./error";
$archiveFolder = "./archive";

// Get all PDF files in the specified folder
$pdfFiles = glob("{$folderPath}/*.pdf");

$api_key = "";

// Process each PDF file in the folder
foreach ($pdfFiles as $pdfFile) {

    $textFromPDF = extractTextFromPDF($pdfFile);

    $returnedText = ReqResGPT($api_key, $textFromPDF);
    echo $returnedText;

    // Extracting report number
    $reportNumber = preg_match('/Report Number: ([0-9.]+)/', $returnedText, $matches) ? $matches[1] : null;

    // Extracting the item description
    $desc = preg_match('/Item Description: (.+)/', $returnedText, $matches) ? $matches[1] : null;

    // Extracting analytical summary
    $analyticalSummary = preg_match('/Analytical Summary:\n(.+)/', $returnedText, $matches) ? $matches[1] : null;

    // Name and Ratings
    $NamesAndRatingsText = preg_match('/Names and Ratings:\n(.+)/s', $returnedText, $matches) ? $matches[1] : null;

    // Split the text into lines
    $lines = explode("\n", $NamesAndRatingsText);

    // Initialize arrays to store analytical names and ratings
    $analyticalNames = [];
    $ratings = [];

    // Iterate through each line
    foreach ($lines as $line) {

        // Use regular expression to match analytical names and ratings
        preg_match('/(.+) - (.+)/', $line, $matches);

        // If a match is found, store the values in the arrays
        if (count($matches) === 3) {
            $analyticalNames[] = $matches[1];
            $ratings[] = $matches[2];
        }
    }

    // Check if all necessary data is available before saving to the database
    if ($reportNumber !== null && !empty($analyticalNames) && !empty($ratings) && $analyticalSummary !== null) {
        // Save To database
        saveToDatabase($pdfFile, $desc, $reportNumber, $analyticalNames, $ratings, $analyticalSummary);

        // Move the processed file to the archive folder
        $archiveFilePath = "{$archiveFolder}/" . basename($pdfFile);
        rename($pdfFile, $archiveFilePath);
    } else {
        // Move the file to the error folder
        $errorFilePath = "{$errorFolder}/" . basename($pdfFile);
        rename($pdfFile, $errorFilePath);
    }
}
