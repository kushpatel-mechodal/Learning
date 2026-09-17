<?php

require("vendor/autoload.php");

use Dompdf\Dompdf;

ob_start(); // Start output buffering and test.php to capture generate html 

require("test.php"); // Execute test.php and capture its HTML

$html = ob_get_clean(); // Get the buffered HTML

$pdf = new Dompdf();

$pdf->loadHtml($html);

$pdf->render(); // Render the HTML as PDF

$pdf->stream("dompdf.pdf", array("Attachment" => 0)); // Output the generated PDF to Browser

file_put_contents("employee_data.pdf", $pdf->output());
