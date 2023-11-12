<?php
    session_start();

    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not CEO or accessed directly via URL
    if (!isset($_SESSION['user']['Title_Role']) || $_SESSION['user']['Title_Role'] !== 'CEO') {
        // echo "<h2>You don't have permission to do this. You are being redirected.</h2>";
        // echo '<script>setTimeout(function(){ window.location.href="employee_login.php"; }, 1500);</script>';
        header("Location: employee_login.php");
        exit; // Make sure to exit so that the rest of the script won't execute
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $Item_Type = $mysqli->real_escape_string($_POST['Item_Type']);
        $Item_Name = $mysqli->real_escape_string($_POST['Item_Name']);
        $Item_Cost = $mysqli->real_escape_string($_POST['Item_Cost']);
        $Reorder_Threshold = $mysqli->real_escape_string($_POST['Reorder_Threshold']);
        $Days_to_expire = $mysqli->real_escape_string($_POST['Days_to_expire']);
        $Amount_per_order = $mysqli->real_escape_string($_POST['Amount_per_order']);
        
        $existingItems = $mysqli->query("SELECT * FROM items WHERE Item_Name='$Item_Name'");

        if($existingItems->num_rows > 0) {
            echo "";
            $_SESSION['error'] = "Item already exist!";
        } else {
            // Inserting the data into the database if item doesnt exist already
            $sql = "INSERT INTO items (Item_Type, Item_Name, Item_Cost, Reorder_Threshold, Days_to_expire, Amount_per_order) 
                    VALUES ('$Item_Type', '$Item_Name','$Item_Cost', '$Reorder_Threshold', '$Days_to_expire', '$Amount_per_order')";

            if ($mysqli->query($sql) === TRUE) {
                $mysqli->close();
                header('Location: employee_home.php');
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        }
    }
?>
<!DOCTYPE html>
<!-- Page for creating new items on menu -->
<head>
    <title>POS Menu Item</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="employee_home.php">Employee Home</a>
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
    </div>
    <form action="create_menuItem.php" method="post">
        <h2>Create New Menu Item</h2>
            <div>
                <label for="Item_Type">Item Type </label>
                <select id="Item_Type" name="Item_Type" required>
                    <option value="" selected disabled>Select Item Type</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Topping">Topping</option>
                    <option value="Side">Side</option>
                </select>
            </div><br>

        <div>
            <label for="Item_Name">Item Name  </label>
            <input type="text" id="Item_Name" name="Item_Name" placeholder="Enter name" required>
        </div><br>

        <div>
            <label for="Item_Cost">Item Cost  </label>
            <input type="number" id="Item_Cost" name="Item_Cost" min=0 placeholder="Enter cost" step="0.01" required>
        </div><br>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.querySelector('form');
                form.addEventListener('submit', function() {
                    var costInput = document.getElementById('Item_Cost');
                    var costValue = parseFloat(costInput.value);
                    if (!isNaN(costValue) && !Number.isInteger(costValue * 100)) {
                        costInput.value = costValue.toFixed(2);
                    }
                });
            });
        </script>

        <div>
            <label for="Reorder_Threshold">Reorder Threshold  </label>
            <input type="number" id="Reorder_Threshold" name="Reorder_Threshold" min=0 placeholder="Enter reorder threshold" required>
        </div><br>

        <div>
            <label for="Days_to_expire">Days to Expire  </label>
            <input type="number" id="Days_to_expire" name="Days_to_expire" min=0 placeholder="Enter days to expire" required>
        </div><br>

        <div>
            <label for="Amount_per_order">Amount per Order </label>
            <input type="number" id="Amount_per_order" name="Amount_per_order" min=1 placeholder="Enter amount" required>
        </div><br>

        <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
        ?>
        <div>
            <input class = button type="submit" value="Add menu item">
        </form>
</body>
</html>



