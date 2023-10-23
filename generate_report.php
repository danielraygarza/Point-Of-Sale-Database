<?php 
    // // Check if the user is not logged in
    // if (!isset($_SESSION['first_name'])) {
    //     // Redirect them to the login page
    //     header('Location: login.php');
    //     exit();
    // } 
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

        </div>
        
    <form action="generate_report.php" method="post">
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reportType'])) {
            $reportType = $_POST['reportType'];
            
            if ($reportType === 'inventory') {
                // Include your database connection code here
                include 'db_connection.php'; // Replace with your actual database connection file

                // Define your SQL query to retrieve inventory data
                $sql = "SELECT product_name, quantity_in_stock FROM inventory";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    // Check if there are rows returned
                    if (mysqli_num_rows($result) > 0) {
                        echo '<h2>Inventory Report</h2>';
                        echo '<table>';
                        echo '<tr><th>Product Name</th><th>Quantity in Stock</th></tr>';

                        // Loop through the results and display them in a table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['product_name'] . '</td>';
                            echo '<td>' . $row['quantity_in_stock'] . '</td>';
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