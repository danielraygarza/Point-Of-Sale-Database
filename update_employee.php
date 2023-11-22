<!-- this page allows the CEO/managers to update employees accounts. managers can only see employees at their store. employee table is updated -->
<?php
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirects if not manager/CEO or accessed directly via URL
if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    header("Location: employee_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'get_employee_details') {
            // get info for selected employee
            $Employee_ID = $mysqli->real_escape_string($_POST['employee_id']);
            $sql = "SELECT * FROM employee WHERE Employee_ID = $Employee_ID";
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
        } else if ($_POST['action'] == 'get_supervisors_for_store') {
            $storeId = $mysqli->real_escape_string($_POST['store_id']);
            $role = $_POST['role'];

            $sql = "SELECT * FROM employee WHERE Store_ID = '$storeId' AND Title_Role IN ('SUP', 'MAN', 'CEO') AND active_employee = '1'";

            $result = $mysqli->query($sql);
            $supervisors = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // If the role is SUP, only include managers and CEOs
                    if ($role === 'SUP' && $row['Title_Role'] === 'TM') continue;
                    $supervisors[] = $row;
                }
                echo json_encode($supervisors);
            } else {
                echo json_encode(array('error' => $mysqli->error));
            }
            $mysqli->close();
            exit;
        }
    } else {
        // save all input when 'save update' is clicked
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
        <?php echo '<a href="logout.php">Logout</a>'; ?>
        <a id="cart-button" style="background-color: transparent;"><?php echo 'Employee Role: ' . $_SESSION['user']['Title_Role']; ?></a>
    </div>
    <form action="update_employee.php" method="post">
        <h2>Update Employee Accounts</h2>
        <div>
            <label for="Employee_ID">Employee </label>
            <select onchange="getEmployeeDetails(this.value)">
                <option value="" selected disabled>Select Employee</option>
                <?php
                $employee_ID = $_SESSION['user']['Employee_ID'];
                $employee_role = $_SESSION['user']['Title_Role'];

                // CEO can see all stores while manager only sees their own stores
                if ($employee_role == 'CEO') {
                    $query = "SELECT * FROM employee WHERE active_employee = '1'";
                } else {
                    $store_id = $_SESSION['user']['Store_ID'];
                    $query = "SELECT * FROM employee WHERE Store_ID = '$store_id' AND active_employee = '1'";
                }

                $allEmployees = $mysqli->query($query);
                if ($allEmployees->num_rows > 0) {
                    // display all current employees in dropdown
                    while ($row = $allEmployees->fetch_assoc()) {
                        echo '<option value="' . $row["Employee_ID"] . '" ' . $selected . '>' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                    }
                }
                ?>

            </select>
        </div><br>
        <script type="text/javascript">
            // get employee info for selected employee
            function getEmployeeDetails(employeeId) {
                if (employeeId) {
                    $.ajax({
                        type: 'POST',
                        url: '',
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
            <input type="text" id="E_First_Name" name="E_First_Name" placeholder="First" style="width: 100px;" required>

            <label for="E_Last_Name"></label>
            <input type="text" id="E_Last_Name" name="E_Last_Name" placeholder="Last" style="width: 100px;" required>
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

        <script>
            // supervisors adjust based on selected employee role
            function roleRequirement() {
                const role = document.getElementById('Title_Role');
                const store = document.getElementById('Store_ID');
                const supervisor = document.getElementById('Supervisor_ID');

                if (role.value === 'MAN') {
                    // if manager, set value to CEO and store 1
                    supervisor.value = '12345678';
                    supervisor.setAttribute('disabled', 'disabled');
                    store.value = '1';
                    store.setAttribute('disabled', 'disabled');
                } else {
                    // Enable the store and supervisor dropdowns for other roles
                    supervisor.removeAttribute('disabled');
                    store.removeAttribute('disabled');

                    if (store.value) {
                        updateSupervisorsForStore(store.value);
                    }

                    // reset supervisor value 
                    supervisor.value = '';
                }
            }
        </script>

        <div>
            <label for="Store_ID">Change Location </label>
            <select id="Store_ID" name="Store_ID" required onchange="updateSupervisorsForStore(this.value)">
                <option value="" selected disabled>Select</option>
                <?php
                $employee_ID = $_SESSION['user']['Employee_ID'];
                $employee_role = $_SESSION['user']['Title_Role'];

                // CEO can see all stores while manager only sees their own stores
                if ($employee_role == 'CEO') {
                    $query = "SELECT * FROM pizza_store";
                } else {
                    $query = "SELECT * FROM pizza_store WHERE Store_Manager_ID = '$employee_ID'";
                }
                $stores = $mysqli->query($query);
                if ($stores->num_rows > 0) {
                    while ($row = $stores->fetch_assoc()) {
                        if ($row["Pizza_Store_ID"] == 1) {
                            continue;
                        } //only shows store ID 1. can delete to show all
                        echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <script>
            // supervisors displayed based on selected store
            function updateSupervisorsForStore(storeId) {
                const role = document.getElementById('Title_Role').value;
                const supervisorDropdown = $('#Supervisor_ID');
                const selectedEmployeeId = $('#Employee_ID').val(); // Get current employee

                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {
                        action: 'get_supervisors_for_store',
                        store_id: storeId,
                        role: role,
                        exclude_employee_id: selectedEmployeeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        supervisorDropdown.empty();
                        supervisorDropdown.append('<option value="" disabled selected>Select</option>');

                        //disable the CEO if manager
                        if (role === 'MAN') {
                            supervisorDropdown.append('<option value="12345678" disabled>Shasta VII</option>');
                        }

                        //show supervisors excluding current employee
                        response.forEach(function(supervisor) {
                            if (supervisor.Employee_ID !== selectedEmployeeId) {
                                supervisorDropdown.append('<option value="' + supervisor.Employee_ID + '">' + supervisor.E_First_Name + ' ' + supervisor.E_Last_Name + '</option>');
                            }
                        });

                        if (role === 'MAN') {
                            supervisorDropdown.val('12345678'); //assign to CEO
                            supervisorDropdown.attr('disabled', 'disabled');
                        } else {
                            supervisorDropdown.removeAttr('disabled');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        </script>

        <div>
            <label for="Supervisor_ID">Change Supervisor </label>
            <select id="Supervisor_ID" name="Supervisor_ID" required>
                <option value="" selected disabled>Select</option>
                <?php
                $currentEmployeeId = 'employeeId';
                $supervisors = $mysqli->query("SELECT * FROM employee WHERE Title_Role IN ('SUP', 'MAN', 'CEO') AND active_employee = '1'");
                if ($supervisors->num_rows > 0) {
                    while ($row = $supervisors->fetch_assoc()) {
                        if ($row["Employee_ID"] == $currentEmployeeId) {
                            continue;
                        }
                        echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                    }
                }
                ?>
            </select>
        </div><br>

        <!-- deleting info: firing employee marks them as inactive and no longer assigned orders -->
        <div>
            <label for="active_employee">Remove Employee </label>
            <select id="active_employee" name="active_employee" onchange="showWarning(this.value)">
                <option value="1">Optional</option>
                <option value="0">Yes</option>
            </select>
            <p id="warning_message" style="display: none; color: black ; text-shadow: none;">Removing an employee will inactive their account. Please proceed with caution.
                <br>Removing an individual's supervisor will assign their store manager as new supervisor.
            </p>
        </div><br>

        <script>
            // display warning if remove employee is selected
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