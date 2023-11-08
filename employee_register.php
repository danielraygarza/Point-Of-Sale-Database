<?php
    session_start();

    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not manager/CEO or accessed directly via URL
    if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
        // echo "<h2>You don't have permission to do this. You are being redirected.</h2>";
        // echo '<script>setTimeout(function(){ window.location.href="employee_login.php"; }, 1500);</script>';
        header("Location: employee_login.php");
        exit; // Make sure to exit so that the rest of the script won't execute
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $E_First_Name = $mysqli->real_escape_string($_POST['E_First_Name']);
        $E_Last_Name = $mysqli->real_escape_string($_POST['E_Last_Name']);
        $Hire_Date = $mysqli->real_escape_string($_POST['Hire_Date']);
        $Title_Role = $mysqli->real_escape_string($_POST['Title_Role']);
        $Supervisor_ID = isset($_POST['Supervisor_ID']) ? $mysqli->real_escape_string($_POST['Supervisor_ID']) : '12345678'; //Assigns CEO if no supervisor
        $Employee_ID = $mysqli->real_escape_string($_POST['Employee_ID']);
        $Store_ID = isset($_POST['Store_ID']) ? $mysqli->real_escape_string($_POST['Store_ID']) : '1'; //Assigns store one if not assigned
        $password = password_hash($mysqli->real_escape_string($_POST['password']), PASSWORD_DEFAULT); // Hashing the password before storing it in the database

        //check if duplicate employee ID. sends error message
        $checkID = $mysqli->query("SELECT Employee_ID FROM employee WHERE Employee_ID='$Employee_ID'");
        if($checkID->num_rows > 0) {
            echo "";
            $_SESSION['error'] = "Employee ID already exist";
        } else {
            // Inserting the data into the database. Accounting if supervisor is NULL when employee is a manager
            if ($Supervisor_ID !== null) {
                    $sql = "INSERT INTO employee (E_First_Name, E_Last_Name, Hire_Date, Title_Role, Supervisor_ID, Employee_ID, password, Store_ID) 
                            VALUES ('$E_First_Name', '$E_Last_Name','$Hire_Date', '$Title_Role', '$Supervisor_ID', '$Employee_ID','$password','$Store_ID')";
            } else{
                   $sql = "INSERT INTO employee (E_First_Name, E_Last_Name, Hire_Date, Title_Role, Employee_ID, password, Store_ID) 
                            VALUES ('$E_First_Name', '$E_Last_Name','$Hire_Date', '$Title_Role', '$Employee_ID','$password','$Store_ID')";
            }

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
        <h2>Create Employee Account</h2>
        <div>       
            <label for="E_First_Name">Name  </label>
            <input type="text" id="E_First_Name" name="E_First_Name" placeholder="First" style="width: 75px;" required>
            <label for="E_Last_Name"></label>
            <input type="text" id="E_Last_Name" name="E_Last_Name" placeholder="Last" style="width: 75px;" required>
        </div><br>

        <div>
            <label for="Title_Role">Role  </label>
            <select id="Title_Role" name="Title_Role" placeholder="Select role" style="width: 150px;"required onchange="roleRequirement()">
                <option value="" selected disabled>Select</option>
                <option value="TM">Team Member</option>
                <option value="SUP">Supervisor</option>
                <option value="MAN">Manager</option>
                <!-- <option value="CEO">CEO</option> -->
            </select>
        </div><br>

        <script>
            function roleRequirement() {
                const role = document.getElementById('Title_Role');
                const supervisor = document.getElementById('Supervisor_ID');
                const store = document.getElementById('Store_ID');
                const CEO = supervisor.querySelector('option[value="12345678"]');
                const HQ = store.querySelector('option[value="1"]');

                // if manager role is selected, supervisor is CEO and store ID is one
                if (role.value === 'MAN') {
                    supervisor.value = '12345678';
                    supervisor.setAttribute('disabled', '');
                    CEO.removeAttribute('disabled');
                    
                    //when CEO creates managers, store set to store 1 until new store created
                    store.value = '1';
                    store.setAttribute('disabled', '');
                    HQ.removeAttribute('disabled');
                } else {
                    supervisor.removeAttribute('disabled');
                    store.removeAttribute('disabled');
                    CEO.setAttribute('disabled', '');
                    HQ.setAttribute('disabled', '');
                    
                    //reset dropdown
                    supervisor.value = "";
                    store.value = "";
                }
            }
        </script>
        
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

        <!-- pulls current date and assigns to Hire_Date -->
        <input type="hidden" id="Hire_Date" name="Hire_Date">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentDate = new Date();
                const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('Hire_Date').value = formattedDate;
            });
        </script>


        <div>
            <label for="Supervisor_ID">Supervisor </label>
            <select id="Supervisor_ID" name="Supervisor_ID" required>
                <option value="" selected disabled>Assign Supervisor</option>
                <?php
                    $supervisors = $mysqli->query("SELECT * FROM employee WHERE Title_Role IN ('SUP', 'MAN', 'CEO') AND active_employee = '1'");
                    if ($supervisors->num_rows > 0) {
                        while($row = $supervisors->fetch_assoc()) {
                            echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                        }
                    }
                ?>
            </select>
        </div><br>
        
        
        <div>
            <label for="Employee_ID">Employee ID  </label>
            <input type="text" id="Employee_ID" name="Employee_ID" pattern="\d{6,8}" placeholder="6-8 digits required" required>
        </div><br>

        <div>
            <label for="password">Password  </label>
            <input type="password" id="password" name="password" placeholder="Create password" required>
        </div><br>
        
        <div>
            <label for="confirm_password">Confirm Password  </label>
            <input type="password" id="confirm_password" placeholder="Confirm password" required>
        </div><br>
        
        <script>
            // send alert if passwords do not match
            document.querySelector('form').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    e.preventDefault();  // stops form from submitting
                }
            });
        </script>

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



