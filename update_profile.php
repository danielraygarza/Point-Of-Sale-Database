<?php
    // Start the session at the beginning of the file
    include 'database.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    // Check if user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        //access customer attributes
        echo "<h2>Welcome, " .$_SESSION['user']['first_name']. "!</h2>";
    } else {
        //if not logged in, will send to default URL
        header("Location: index.php");
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
        $phone_number = $mysqli->real_escape_string($_POST['phone_number']);

        // Inserting the data into the database
        $sql = "UPDATE customers 
        SET first_name='$first_name', middle_initial='$middle_initial', last_name='$last_name',address='$address',
        address2='$address2', city='$city', state='$state', zip_code='$zip_code', phone_number='$phone_number'
        WHERE email='$email'"; //email is guranteed unique

        if ($mysqli->query($sql) === TRUE) {
            //if successful signup, mark user as logged in and send to home page
            $result = $mysqli->query("SELECT * FROM customers WHERE email='$email'");
            $user = $result->fetch_assoc(); // Assign user data to the session
            $_SESSION['user'] = $user;  //assigns all customer attributes inside an array
            
            $mysqli->close();
            header('Location: update_profile.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }

?>

<!DOCTYPE html>
<!-- Signup page for new users -->
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Order now</a>
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="update_profile.php">Profile</a>';
                echo '<a href="logout.php">Logout</a>';
            }
        ?>
    </div>
    <form action="signup.php" method="post">
        <h2>Update your POS Pizza Account</h2>
        <div>       
            <label for="first_name">Name  </label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['user']['first_name']; ?>" placeholder="First" style="width: 75px;" required>

            <label for="middle_initial"></label>
            <input type="text" id="middle_initial" name="middle_initial" maxlength="1"  value="<?php echo $_SESSION['user']['middle_initial']; ?>" placeholder="M.I." style="width: 30px;">

            <label for="last_name"></label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['user']['last_name']; ?>" placeholder="Last" style="width: 75px;" required>
        </div><br>

        <div>
            <label for="birthday_month">Birthday  </label>
            <input type="number" id="birthday_month" name="birthday_month" min="1" max="12" value="<?php echo $_SESSION['user']['birthday']; ?>"placeholder="Month" style="width: 100px;" readonly>
        </div><br>
        
        <div>
            <label for="address">Address  </label>
            <input type="text" id="address" name="address" value="<?php echo $_SESSION['user']['address']; ?>" placeholder="Enter address" required>
            
            <label for="address2">Address 2  </label>
            <input type="text" id="address2" name="address2" value="<?php echo $_SESSION['user']['address2']; ?>" placeholder="Optional">
        </div><br>

        <div>
            <label for="city">City  </label>
            <input type="text" id="city" name="city" value="<?php echo $_SESSION['user']['city']; ?>" placeholder="Enter city" style="width: 90px;"required>

            <label for="state">State  </label>
            <select id="state" name="state" value="<?php echo $_SESSION['user']['state']; ?>"placeholder="Select state" style="width: 100px;" required>
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
            <input type="text" id="zip_code" name="zip_code" value="<?php echo $_SESSION['user']['zip_code']; ?>" placeholder="Enter Zip Code" pattern="\d{5}(-\d{4})?" style="width: 100px;" required>
        </div><br>

        <div>
            <label for="phone_number">Phone Number  </label>
            <input type="tel" id="phone_number" name="phone_number" value="<?php echo $_SESSION['user']['phone_number']; ?>" placeholder="Enter 10 digits" pattern="[0-9]{10}" style="width: 120px;" required>
            <label for="email">Email  </label>
            <!-- input requires "@" and "." 
-->
            <input type="email" id="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>" placeholder="Enter email address" pattern=".*\..*" readonly required>
        </div><br>


        <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
        ?>

        <div>
            <input class = button type="submit" value="Sign Up" onclick="formatDate()">
        </form>
</body>
</html>

