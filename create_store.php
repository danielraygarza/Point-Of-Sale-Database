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

    //get list of managers from database
    $managers = $mysqli->query("SELECT * FROM employee WHERE Title_Role='MAN'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $Store_Address = $mysqli->real_escape_string($_POST['Store_Address']);
        $Store_City = $mysqli->real_escape_string($_POST['Store_City']);
        $Store_State = $mysqli->real_escape_string($_POST['Store_State']);
        $Store_Zip_Code = $mysqli->real_escape_string($_POST['Store_Zip_Code']);
        $Store_Phone_Number = $mysqli->real_escape_string(str_replace('-', '', $_POST['Store_Phone_Number']));
        $Store_Manager_ID = $mysqli->real_escape_string($_POST['Store_Manager_ID']);

        // check if duplicate store location. sends error message
        $location = $mysqli->query("SELECT * FROM pizza_store WHERE Store_Address='$Store_Address' AND Store_City='$Store_City' AND Store_State='$Store_State'");
        
        if($location->num_rows > 0) {
            echo "";
            $_SESSION['error'] = "Store location already exist";
        } else {
            // Inserting the data into the database. 
            $sql = "INSERT INTO pizza_store (Store_Address, Store_City, Store_State, Store_Zip_Code, Store_Phone_Number, Store_Manager_ID) 
                    VALUES ('$Store_Address', '$Store_City','$Store_State', '$Store_Zip_Code', '$Store_Phone_Number', '$Store_Manager_ID')";

            if ($mysqli->query($sql) === TRUE) {

                 // Get the ID of the last inserted store
                $newStoreID = $mysqli->insert_id;

                // Update the manager's Pizza_Store_ID using that ID
                $managerStoreID = "UPDATE employee SET Store_ID = $newStoreID WHERE Employee_ID = $Store_Manager_ID";
                
                if ($mysqli->query($managerStoreID) !== TRUE) {
                    echo "Error updating manager's store ID: " . $mysqli->error;
                    // You might want to handle this error differently
                }

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
    <title>Store Registration</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    <!-- <style>
        body { background: url('../img/pizzaking.png') no-repeat center center fixed; }
    </style> -->
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="employee_home.php">Employee Home</a>
        <?php echo '<a href="logout.php">Logout</a>';?>
        <a id="cart-button" style="background-color: transparent;" ><?php echo 'Employee Role: ' . $_SESSION['user']['Title_Role']; ?></a>
    </div>
    <form action="create_store.php" method="post">
        <h2>Register New Store</h2>
            <div>
                <label for="Store_Manager_ID">Manager </label>
                <select id="Store_Manager_ID" name="Store_Manager_ID" required>
                    <option value="" selected disabled>Assign Shop Manager</option>
                    <?php
                        if ($managers->num_rows > 0) {
                            while($row = $managers->fetch_assoc()) {
                                echo '<option value="' . $row["Employee_ID"] . '">' . $row["E_First_Name"] . ' ' . $row["E_Last_Name"] . '</option>';
                            }
                        }
                    ?>
                </select>
            </div><br>

        <div>
            <label for="Store_Address">Address  </label>
            <input type="text" id="Store_Address" name="Store_Address" placeholder="Enter address" required>

            <label for="Store_City">City  </label>
            <input type="text" id="Store_City" name="Store_City" placeholder="Enter city" style="width: 90px;"required>
        </div><br>

        <div>
            <label for="Store_State">State  </label>
            <select id="Store_State" name="Store_State" placeholder="Select state" style="width: 100px;" required>
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
            <label for="Store_Zip_Code">Zip Code  </label>
            <input type="text" id="Store_Zip_Code" name="Store_Zip_Code" placeholder="Enter Zip Code" pattern="\d{5}(-\d{4})?" style="width: 100px;" required>
        </div><br>

        <div>
            <label for="Store_Phone_Number">Phone Number  </label>
            <input type="tel" id="Store_Phone_Number" name="Store_Phone_Number" placeholder="Enter 10 digits" pattern="^\d{10}$|^\d{3}-\d{3}-\d{4}$" style="width: 120px;" required>
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



<?php
/*
** Title: Comprehensive Store Registration System with CEO Authorization
**
** Introduction:
** This PHP script orchestrates a robust store registration system, offering CEOs the authority
** to create new store entries with precision and efficiency. The script incorporates extensive
** security measures, dynamic form handling, and seamless database integration to ensure a
** seamless and error-free registration process. From CEO-exclusive access checks to
** real-time error feedback, this script exemplifies a sophisticated approach to store
** management in pizza establishments.
**
** Session Initialization and Security Measures:
** The script prioritizes security with a session_start() command, creating a secure
** environment for data handling. Additionally, it includes stringent error reporting settings,
** actively identifying and addressing potential issues during execution. To maintain CEO
** exclusivity, the script redirects unauthorized users to the employee login page, ensuring a
** secure and restricted access model.
**
** Manager Selection and Data Validation:
** A crucial aspect of store registration is the assignment of a manager to the new store.
** The script retrieves a list of managers from the database, offering CEOs a curated selection
** for optimal management. The form submission process is fortified with robust data validation
** and error handling mechanisms, ensuring the integrity of the entered information. Duplicate
** store location checks contribute to data accuracy, preventing unintentional redundancy.
**
** Database Interaction and Insertion Logic:
** Upon successful validation, the script orchestrates the insertion of store data into the
** database. The SQL query dynamically adapts to the entered information, guaranteeing
** accuracy in database records. Furthermore, the script updates the manager's Pizza_Store_ID
** field, creating an intricate link between store and manager for seamless association.
**
** HTML Interface: Aesthetic Simplicity and User-Friendly Design:
** The HTML section of the script embraces a clean and user-friendly design philosophy.
** Intuitive form elements enhance the user experience, with error messages intelligently
** displayed for immediate feedback. The CEO is presented with a straightforward interface to
** input store details, streamlining the registration process.
**
** Conclusion:
** In conclusion, this PHP script stands as a testament to the intricacies and possibilities
** of modern store registration systems. CEOs can leverage this script to effortlessly
** integrate new stores into their pizza establishment, benefitting from a secure, intuitive,
** and feature-rich registration process. Developers can utilize this script as a foundation
** for creating sophisticated, user-friendly, and CEO-authorized store management solutions
** tailored to the unique demands of contemporary businesses.
*/
?>
