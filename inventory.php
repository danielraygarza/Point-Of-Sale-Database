<?php
    // Start the session at the beginning of the file
    include 'database.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

     // Redirects if not manager/CEO or accessed directly via URL
    //  if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    //     header("Location: employee_login.php");
    //     exit; // Make sure to exit so that the rest of the script won't execute
    // }

    $stores = $mysqli->query("SELECT * FROM pizza_store");
    $vendors = $mysqli->query("SELECT * FROM vendor");

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $first_name = $mysqli->real_escape_string($_POST['first_name']);
        $middle_initial = $mysqli->real_escape_string($_POST['middle_initial']);
        $last_name = $mysqli->real_escape_string($_POST['last_name']);
        $address = $mysqli->real_escape_string($_POST['address']);
        $address2 = $mysqli->real_escape_string($_POST['address2']);
        $city = $mysqli->real_escape_string($_POST['city']);
        $state = $mysqli->real_escape_string($_POST['state']);
        $zip_code = $mysqli->real_escape_string($_POST['zip_code']);
        $phone_number = $mysqli->real_escape_string(str_replace('-', '', $_POST['phone_number']));
        $email = $mysqli->real_escape_string($_POST['email']);

        // Inserting the data into the database
        if ($mysqli->query($sql) === TRUE) {
            $mysqli->close();
            header('Location: employee_home.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }

?>

<!DOCTYPE html>
<head>
    <title>Inventory</title>
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
    <form action="inventory.php" method="post">
        <h2>Inventory</h2>
        <div>
            <label for="Store_ID">Store Location </label>
            <select id="Store_ID" name="Store_ID" required>
                <option value="" selected disabled>Select Store</option>
                <?php
                    $stores = $mysqli->query("SELECT * FROM pizza_store");

                    if ($stores->num_rows > 0) {
                        while($row = $stores->fetch_assoc()) {
                            echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                ?>
            </select>
        </div><br>

        <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
        ?>

        <div>
            <input class = button type="submit" value="Place Inventory Order">
        </form>
</body>
</html>

