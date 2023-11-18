<?php

if (isset($_POST['export'])) {
    // Set the headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="exported_data.csv"');

    // Open a file pointer
    $output = fopen('php://output', 'w');

    // Assume $yourFetchedResultsArray is an array of associative arrays
    // Each associative array represents a row from the query result

    // Output the column headings dynamically
    if (!empty($yourFetchedResultsArray)) {
        $firstRow = reset($yourFetchedResultsArray);
        fputcsv($output, array_keys($firstRow));
    }

    // Loop through the fetched results and output data
    foreach ($yourFetchedResultsArray as $row) {
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);
}
?>