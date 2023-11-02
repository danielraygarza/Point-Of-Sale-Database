<?php
    // Start the session at the beginning of the file
    include 'database.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

     // Redirects if not manager/CEO or accessed directly via URL
     if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
        header("Location: employee_login.php");
        exit; // Make sure to exit so that the rest of the script won't execute
    }

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
        <h2>Update your POS Pizza Account</h2>
        <div>       
            <label for="first_name">Name  </label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['user']['first_name']; ?>" placeholder="First" style="width: 75px;" required>

            <label for="middle_initial"></label>
            <input type="text" id="middle_initial" name="middle_initial" maxlength="1"  value="<?php echo $_SESSION['user']['middle_initial']; ?>" placeholder="M.I." style="width: 40px;">

            <label for="last_name"></label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['user']['last_name']; ?>" placeholder="Last" style="width: 75px;" required>
        </div><br>



        <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
        ?>

        <div>
            <input class = button type="submit" value="Save Updates">
        </form>
</body>
</html>

