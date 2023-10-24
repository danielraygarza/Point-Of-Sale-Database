<?php 
    /*
    session_start();
    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not manager or accessed directly via URL
    if (!isset($_SESSION['user']['Title_Role']) || $_SESSION['user']['Title_Role'] !== 'MAN') {
        //if not logged in, will send to default URL
        header("Location: employee_login.php");
        exit(); //ensures code is killed
    }
    // */
?>
<!-- Welcome page after user creates new account -->
<!DOCTYPE html>
<html>
    <head>
        <title>POS Pizza</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    </head>
    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="reports.php">Back to Reports</a>
            <?php
                //shows logout button if logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo '<a href="logout.php">Logout</a>';
                }
            ?>
        </div>
        
    <form action="generate_report.php" method="post">
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reportType'])) {
            $reportType = $_POST['reportType'];
            
            if ($reportType === 'inventory') {
                // Database connection code:
                include 'database.php'; // Database connection file

                // Define your SQL query to retrieve inventory data
                $sql = "SELECT Inventory_ID, Inventory_Amount FROM inventory";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    // Check if there are rows returned
                    if (mysqli_num_rows($result) > 0) {
                        echo '<h2>Inventory Report</h2>';
                        echo '<table>';
                        echo '<tr><th>Product ID</th><th>Quantity in Stock</th></tr>';

                        // Loop through the results and display them in a table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['Inventory_ID'] . '</td>';
                            echo '<td>' . $row['Inventory_Amount'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No inventory data available.';
                    }
                } else {
                    echo 'Error executing the SQL query: ' . mysqli_error($conn);
                }

                // Close the database connection
                mysqli_close($conn);
            }
            // Add more cases for other report types as needed
        }
    }
    ?>

    </form> 

    </body>
</html>