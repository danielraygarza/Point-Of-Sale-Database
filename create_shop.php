<?php
    session_start();

    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not CEO or accessed directly via URL
    // if (!isset($_SESSION['user']['Title_Role']) || $_SESSION['user']['Title_Role'] !== 'CEO') {
    //     echo "<h2>You don't have permission to do this. You are being redirected.</h2>";
    //     echo '<script>setTimeout(function(){ window.location.href="employee_login.php"; }, 1500);</script>';
    //     exit; // Make sure to exit so that the rest of the script won't execute
    // }

    //get list of managers from database
    $supervisors = $mysqli->query("SELECT * FROM employee WHERE Title_Role='MAN'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $E_First_Name = $mysqli->real_escape_string($_POST['E_First_Name']);

        //check if duplicate employee ID. sends error message
        $checkID = $mysqli->query("SELECT Employee_ID FROM employee WHERE Employee_ID='$Employee_ID'");
        if($checkID->num_rows > 0) {
            echo "";
            $_SESSION['error'] = "Employee ID already exist";
        } else {
            // Inserting the data into the database. Accounting if supervisor is NULL when employee is a manager
            $sql = "INSERT INTO employee (E_First_Name, E_Last_Name, Hire_Date, Title_Role, Supervisor_ID, Employee_ID, password) 
                    VALUES ('$E_First_Name', '$E_Last_Name','$Hire_Date', '$Title_Role', '$Supervisor_ID', '$Employee_ID','$password')";

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
<!-- Page for creating new employees -->
<head>
    <title>Employee Registration</title>
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
    <form action="employee_register.php" method="post">
        <h2>Register New Store</h2>
            <div>
                <label for="Supervisor_ID">Manager </label>
                <select id="Supervisor_ID" name="Supervisor_ID" required>
                    <option value="" selected disabled>Assign Shop Manager</option>
                    <?php
                    if ($supervisors->num_rows > 0) {
                        while($row = $supervisors->fetch_assoc()) {
                            echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div><br>

        <div>
            <label for="address">Address  </label>
            <input type="text" id="address" name="address" placeholder="Enter address" required>

            <label for="city">City  </label>
            <input type="text" id="city" name="city" placeholder="Enter city" style="width: 90px;"required>
        </div><br>

        <div>
            <label for="state">State  </label>
            <select id="state" name="state" placeholder="Select state" style="width: 100px;" required>
                <option value="" selected disabled>Select</option>
                <option value="AL">Alabama</option> <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option> <option value="AR">Arkansas</option>
                <option value="CA">California</option> <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option> <option value="DE">Delaware</option>
                <option value="FL">Florida</option> <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option> <option value="ID">Idaho</option>
                <option value="IL">Illinois</option> <option value="IN">Indiana</option>
                <option value="IA">Iowa</option> <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option> <option value="LA">Louisiana</option>
                <option value="ME">Maine</option> <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option> <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option> <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option> <option value="MT">Montana</option>
                <option value="NE">Nebraska</option> <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option> <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option> <option value="NY">New York</option>
                <option value="NC">North Carolina</option> <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option> <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option> <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option> <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option> <option value="TN">Tennessee</option>
                <option value="TX">Texas</option> <option value="UT">Utah</option>
                <option value="VT">Vermont</option> <option value="VA">Virginia</option>
                <option value="WA">Washington</option> <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option> <option value="WY">Wyoming</option>
        </select>
            <label for="zip_code">Zip Code  </label>
            <input type="text" id="zip_code" name="zip_code" placeholder="Enter Zip Code" pattern="\d{5}(-\d{4})?" style="width: 100px;" required>
        </div><br>

        <div>
            <label for="phone_number">Phone Number  </label>
            <input type="tel" id="phone_number" name="phone_number" placeholder="Enter 10 digits" pattern="^\d{10}$|^\d{3}-\d{3}-\d{4}$" style="width: 120px;" required>
        </div><br>

        <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
        ?>
        <div>
            <input class = button type="submit" value="Register">
        </form>
</body>
</html>



