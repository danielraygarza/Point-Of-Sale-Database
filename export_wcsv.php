<?php
    session_start();
    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

   // Redirects if not manager/CEO or accessed directly via URL
    if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
        header("Location: employee_login.php");
        exit; // Make sure to exit so that the rest of the script won't execute
    }

    if (isset($_POST['export'])) {
        // Debug
        echo 'Export button clicked!<br>';

        // Retrieve data
        $exportData = json_decode($_POST['export_data'], true);

        // // Set the headers for CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exported_data.csv"');

        // Debug
        echo 'Exported Data:<br>';
        print_r($exportData);

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

</body>