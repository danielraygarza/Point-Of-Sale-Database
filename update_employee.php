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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'get_employee_details') {
            $Employee_ID = $mysqli->real_escape_string($_POST['employee_id']);
            $sql = "SELECT E_First_Name, E_Last_Name, Employee_ID, Title_Role, Store_ID, Supervisor_ID, Hire_Date 
                    FROM employee 
                    WHERE Employee_ID = $Employee_ID";
            $result = $mysqli->query($sql);

            if ($result) {
                $employeeData = $result->fetch_assoc();
                echo json_encode($employeeData); 
                $mysqli->close();
                exit;
            } else {
                echo json_encode(array('error' => $mysqli->error));
                $mysqli->close();
                exit;
            }
        } else {
            $E_First_Name = $mysqli->real_escape_string($_POST['E_First_Name']);
            $E_Last_Name = $mysqli->real_escape_string($_POST['E_Last_Name']);
            $Title_Role = $mysqli->real_escape_string($_POST['Title_Role']);
            $Supervisor_ID = $mysqli->real_escape_string($_POST['Supervisor_ID']);
            $Store_ID = $mysqli->real_escape_string($_POST['Store_ID']);
            $Employee_ID = $mysqli->real_escape_string($_POST['Employee_ID']);
            $active_employee = $mysqli->real_escape_string($_POST['active_employee']);
    
            // Check if Employee_ID and Supervisor_ID match
            if ($Employee_ID == $Supervisor_ID) {
                echo "";
                $_SESSION['error'] = "Employee cannot be their own supervisor";
            } else {
                $mysqli->begin_transaction();

                $storeManagerId = null;
                // If the employee is set to inactive, get the store manager's ID
                if ($active_employee == '0') {
                    $storeManagerId = $mysqli->query("SELECT Store_Manager_ID FROM pizza_store WHERE Pizza_Store_ID = $Store_ID")->fetch_object()->Store_Manager_ID;
                    
                    // if employee is a supervisor, it will assign the manager of that store as new supervisor
                    $mysqli->query("UPDATE employee SET Supervisor_ID = $storeManagerId WHERE Supervisor_ID = $Employee_ID");
                }

                $stmt = $mysqli->prepare("UPDATE employee 
                                        SET E_First_Name=?, E_Last_Name=?, Title_Role=?, Supervisor_ID=?, Store_ID=?, active_employee=? 
                                        WHERE Employee_ID = ?");
                $stmt->bind_param("sssiisi", $E_First_Name, $E_Last_Name, $Title_Role, $Supervisor_ID, $Store_ID, $active_employee, $Employee_ID);
                $stmt->execute();
                $stmt->close();

                $mysqli->commit();
                $mysqli->close();
                header('Location: employee_home.php');
                exit;
            }
        }
    }

?>

<!DOCTYPE html>
<head>
    <title>POS Employee Management</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
            <select onchange="getEmployeeDetails(this.value)">
                <option value="" selected disabled>Select Employee</option>
                <?php
                 if ($_SESSION['user']['Title_Role'] == 'CEO') {
                     $allEmployees = $mysqli->query("SELECT * FROM employee WHERE active_employee = '1'");
                     if ($allEmployees->num_rows > 0) {
                        while ($row = $allEmployees->fetch_assoc()) {
                            echo '<option value="' . $row["Employee_ID"] . '" ' . $selected . '>' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                        }
                    }
                } else {
                    $managerStore = $_SESSION['user']['Store_ID'];
                    $allEmployees = $mysqli->query("SELECT * FROM employee WHERE Store_ID = $managerStore");
                    if ($allEmployees->num_rows > 0) {
                       while ($row = $allEmployees->fetch_assoc()) {
                           echo '<option value="' . $row["Employee_ID"] . '" ' . $selected . '>' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                       }
                   }
                }
                ?>
            </select>
        </div><br>
        <script type="text/javascript">
            function getEmployeeDetails(employeeId) {
                if (employeeId) {
                    $.ajax({
                        type: 'POST',
                        url: '', // Empty means current file in this case
                        data: {
                            action: 'get_employee_details',
                            employee_id: employeeId
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#Employee_ID').val(response.Employee_ID);
                            $('#E_First_Name').val(response.E_First_Name);
                            $('#E_Last_Name').val(response.E_Last_Name);
                            $('#Title_Role').val(response.Title_Role);
                            $('#Supervisor_ID').val(response.Supervisor_ID);
                            $('#Hire_Date').val(response.Hire_Date);
                            $('#Store_ID').val(response.Store_ID);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            }
        </script>

        <div>
            <label for="E_First_Name">Name </label>
            <input type="text" id="E_First_Name" name="E_First_Name" placeholder="First" style="width: 75px;" required>
            <!-- <input type="text" id="E_First_Name" name="E_First_Name" value="<?php //echo $_SESSION['user']['E_First_Name']; ?>" placeholder="First" style="width: 75px;" required> -->

            <label for="E_Last_Name"></label>
            <input type="text" id="E_Last_Name" name="E_Last_Name" placeholder="Last" style="width: 75px;" required>
        </div><br>

        <div>
            <label for="Employee_ID">Employee ID </label>
            <input type="text" id="Employee_ID" name="Employee_ID" placeholder="Employee ID" style="width: 150px;" readonly>
        </div><br>

        <div>
            <label for="Hire_Date">Hire Date </label>
            <input type="text" id="Hire_Date" placeholder="Hire Date" style="width: 150px;" readonly>
        </div><br>

        <div>
            <label for="Title_Role">Change Role </label>
            <select id="Title_Role" name="Title_Role" placeholder="Select role" style="width: 150px;" required onchange="roleRequirement()">
                <option value="" selected disabled>Select</option>
                <option value="TM">Team Member</option>
                <option value="SUP">Supervisor</option>
                <option value="MAN">Manager</option>
            </select>
        </div><br>

        <div>
            <label for="Store_ID">Change Location </label>
            <select id="Store_ID" name="Store_ID" required>
                <option value="" selected disabled>Select</option>
                <?php
                $stores = $mysqli->query("SELECT * FROM pizza_store");
                if ($stores->num_rows > 0) {
                    while ($row = $stores->fetch_assoc()) {
                        echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <div>
            <label for="Supervisor_ID">Change Supervisor </label>
            <select id="Supervisor_ID" name="Supervisor_ID" required>
                <option value="" selected disabled>Select</option>
                <?php
                    $currentEmployeeId = 'employeeId';
                    $supervisors = $mysqli->query("SELECT * FROM employee WHERE Title_Role IN ('SUP', 'MAN', 'CEO') AND active_employee = '1'");
                    if ($supervisors->num_rows > 0) {
                        while ($row = $supervisors->fetch_assoc()) {
                            if ($row["Employee_ID"] == $currentEmployeeId) { continue; }
                            echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                        }
                    }
                ?>
            </select>
        </div><br>
        
        <div>
            <label for="active_employee">Remove Employee </label>
            <select id="active_employee" name="active_employee" onchange="showWarning(this.value)">
                <option value="1">Optional</option>
                <option value="0">Yes</option>
            </select>
            <p id="warning_message" style="display: none; color: black ; text-shadow: none;">Removing an employee will inactive their account. Please proceed with caution.
        <br>You cannot remove a manager without assigning a new one to that location.<br>Removing an individual's supervisor will assign their store manager as new supervisor.</p>
        </div><br>

        <script>
            function showWarning(value) {
                var warningMessage = document.getElementById('warning_message');
                if (value === '0') {
                    warningMessage.style.display = 'block';
                } else {
                    warningMessage.style.display = 'none';
                }
            }
        </script>



        <?php
        //displays error messages here 
        if (isset($_SESSION['error'])) {
            echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);  // Unset the error message after displaying it
        }
        ?>

        <div>
            <input class=button type="submit" value="Save Updates">
    </form>
</body>

</html>