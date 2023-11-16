<?php
// Include the FPDF library
require('fpdf/fpdf.php');

// Get the table data from the POST data
$tableData = $_POST['data'];

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font for custom row
$pdf->SetFont('Arial', 'B', 12);

// Add custom row to the PDF
$pdf->Cell(25, 10, 'ID', 1);
$pdf->Cell(25, 10, 'Date', 1);
$pdf->Cell(25, 10, 'Time', 1);
$pdf->Cell(25, 10, 'Type', 1);
$pdf->Cell(25, 10, 'Status', 1);
$pdf->Cell(25, 10, 'Amount', 1);
$pdf->Cell(25, 10, 'Cust ID', 1);
$pdf->Cell(25, 10, 'Store ID', 1);


// Set font for data rows
$pdf->SetFont('Arial', '', 12);

// Parse table data and add it to the PDF
$rows = explode('</tr>', $tableData);
foreach ($rows as $index => $row) {
    //if ($index > 0) { // Skip the first row (header row)
        $cols = explode('</td>', $row);
        array_pop($cols); // Remove the last element (empty string)

        // Iterate over columns and add to the PDF
        foreach ($cols as $col) {
            $pdf->Cell(25, 10, strip_tags($col), 1);
        }

        $pdf->Ln(); // Move to the next line for the next row
    //}
}

// Set the content type and headers
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename=table.pdf');

// Output the JSON response
$pdfData = $pdf->Output('', 'S');
echo json_encode(['fileData' => base64_encode($pdfData)]);

exit();
?>