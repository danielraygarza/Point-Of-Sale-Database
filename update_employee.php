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

    $allEmployees = $mysqli->query("SELECT * FROM employee WHERE Title_Role='MAN' OR Title_Role='SUP' OR Title_Role='TM'");
    $employeesNotManagers = $mysqli->query("SELECT * FROM employee WHERE Title_Role='SUP' OR Title_Role='TM'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $E_First_Name = $mysqli->real_escape_string($_POST['E_First_Name']);
        $E_Last_Name = $mysqli->real_escape_string($_POST['E_Last_Name']);
        $Title_Role = $mysqli->real_escape_string($_POST['Title_Role']);
        $Supervisor_ID = $mysqli->real_escape_string($_POST['Supervisor_ID']);
        $Store_ID = $mysqli->real_escape_string($_POST['Store_ID']);
        $Employee_ID = $mysqli->real_escape_string($_POST['Empl$Employee_ID']);

        // Inserting the data into the database
        $sql = "UPDATE employee 
        SET E_First_Name='$E_First_Name', E_Last_Name='$E_Last_Name',Title_Role='$Title_Role',Supervisor_ID='$Supervisor_ID',Store_ID='$E_Last_Name'
        WHERE Employee_ID = $Employee_ID";

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
<!-- Signup page for new users -->
<head>
    <title>POS Employee Management</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="employee_home.php">Employee Home</a>
        <a href="reports.php">Reports</a>
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
        ?>
    </div>
    <form action="update_employee.php" method="post">
        <h2>Update Employee Accounts</h2>
        <div>
            <label for="Employee_ID">Employee </label>
            <select id="Employee_ID" name="Employee_ID" required>
                <option value="" selected disabled>Select Employee</option>
                <?php
                    if ($allEmployees->num_rows > 0) {
                        while($row = $allEmployees->fetch_assoc()) {
                            echo '<option value="' . $row["Employee"] . '" ' . $selected . '>' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                        }
                    }
                ?>
            </select>
        </div><br>
        
        <div>       
            <label for="E_First_Name">Name  </label>
            <input type="text" id="E_First_Name" name="E_First_Name" value="<?php echo $_SESSION['user']['E_First_Name']; ?>" placeholder="First" style="width: 75px;" required>

            <label for="E_Last_Name"></label>
            <input type="text" id="E_Last_Name" name="E_Last_Name" value="<?php echo $_SESSION['user']['E_Last_Name']; ?>" placeholder="Last" style="width: 75px;" required>
        </div><br>

        <div>
            <label for="Employee_ID">Employee ID </label>
            <input type="text" id="Employee_ID" value="<?php echo $_SESSION['user']['Employee_ID']; ?>" placeholder="Employee_ID" style="width: 150px;" readonly>
        </div><br>
        
        <?php
            //re-format date for friendly front end
            $date = new DateTime($_SESSION['user']['Hire_Date']);
            $formattedDate = $date->format('F j, Y');
            ?>
        <div>
            <label for="Hire_Date">Hire Date  </label>
            <input type="text" id="Hire_Date"value="<?php echo $formattedDate; ?>" placeholder="Hire_Date" style="width: 150px;" readonly>
        </div><br>

        <div>
            <label for="Title_Role">Change Role  </label>
            <select id="Title_Role" name="Title_Role" placeholder="Select role" style="width: 150px;"required onchange="roleRequirement()">
                <option value="" selected disabled>Select</option>
                <option value="TM">Team Member</option>
                <option value="SUP">Supervisor</option>
                <option value="MAN">Manager</option>
            </select>
        </div><br>

        <div>
            <label for="Store_ID">Change Location </label>
            <select id="Store_ID" name="Store_ID" required>
                <option value="" selected disabled>Select Store</option>
                <?php
                    if ($stores->num_rows > 0) {
                        while($row = $stores->fetch_assoc()) {
                            echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                ?>
            </select>
        </div><br>

        <div>
            <label for="Supervisor_ID">Change Supervisor </label>
            <select id="Supervisor_ID" name="Supervisor_ID" required>
                <option value="" selected disabled>Assign Supervisor</option>
                <?php
                    if ($supervisors->num_rows > 0) {
                        while($row = $supervisors->fetch_assoc()) {
                            echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
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
            <input class = button type="submit" value="Save Updates">
        </form>
</body>
</html>

