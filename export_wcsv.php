<?php

if (isset($_POST['export'])) {
    // Debug
    echo 'Export button clicked!';

    // Retrieve data
    $exportData = json_decode($_POST['export_data'], true);

    // Set the headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_data.csv"');

    // Open a file pointer
    $output = fopen('php://output', 'w');

    // Output the column headings dynamically
    if (!empty($exportData)) {
        $firstRow = reset($exportData);
        fputcsv($output, array_keys($firstRow));
    }

    // Loop through the fetched results and output data
    foreach ($exportData as $row) {
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);
} else{
    echo 'Form not submitted';
}
?>