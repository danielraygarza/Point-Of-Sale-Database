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

    $getUser = $mysqli->query("SELECT * FROM customers WHERE email='$email'");
    $user = $getUser->fetch_assoc();
    $_SESSION['user'] = $user;  //assigns all customer attributes inside an array

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

        // Extracting data from the form
        $first_name = $mysqli->real_escape_string($_POST['first_name']);
        $middle_initial = $mysqli->real_escape_string($_POST['middle_initial']);
        $last_name = $mysqli->real_escape_string($_POST['last_name']);
        $birthday = $mysqli->real_escape_string($_POST['birthday']);
        $join_date = $mysqli->real_escape_string($_POST['join_date']);
        $address = $mysqli->real_escape_string($_POST['address']);
        $address2 = $mysqli->real_escape_string($_POST['address2']);
        $city = $mysqli->real_escape_string($_POST['city']);
        $state = $mysqli->real_escape_string($_POST['state']);
        $zip_code = $mysqli->real_escape_string($_POST['zip_code']);
        $phone_number = $mysqli->real_escape_string($_POST['phone_number']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $password = password_hash($mysqli->real_escape_string($_POST['password']), PASSWORD_DEFAULT); // Hashing the password before storing it in the database

        // Inserting the data into the database
        $sql = "UPDATE customers 
        SET first_name='$first_name', middle_initial='$middle_initial', last_name='$last_name', birthday='$birthday',address='$address',
        address2='$address2', city='$city', state='$state', zip_code='$zip_code', phone_number='$phone_number', email='$email', password='$password'
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
            <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" placeholder="First" style="width: 75px;" required>

            <label for="middle_initial"></label>
            <input type="text" id="middle_initial" name="middle_initial" maxlength="1"  placeholder="M.I." style="width: 30px;">

            <label for="last_name"></label>
            <input type="text" id="last_name" name="last_name" placeholder="Last" style="width: 75px;" required>
        </div><br>

        <!-- need to set up restictions on days for certain months (i.e. feb cant have more than 28 as day)
        leap year: if year % 4 = 0 , then feb has 29 days 
        year max is the current year -->
        <div>
            <label for="birthday_month">Birthday  </label>
            <input type="number" id="birthday_month" name="birthday_month" min="1" max="12" placeholder="Month" style="width: 55px;">
            <label for="birthday_day"></label>
            <input type="number" id="birthday_day" name="birthday_day" min="1" max="31" placeholder = "Day" style="width: 55px;">
            <label for="birthday_year"></label>
            <input type="number" id="birthday_year" name="birthday_year" min="1900" max="2023" pattern="[0-9]{4}" placeholder = "Year" style="width: 55px;">
        </div><br>
        
        <!-- hidden input to hold the concatenated date -->
        <input type="hidden" id="birthday" name="birthday">
        <script>
            // javascript funciton to format date. function called when form submitted
            function formatDate() {
                var day = document.getElementById('birthday_day').value;
                var month = document.getElementById('birthday_month').value;
                var year = document.getElementById('birthday_year').value;

                if (day && month && year) {
                    var formatted = year + '/' + month.padStart(2, '0') + '/' + day.padStart(2, '0');
                    document.getElementById('birthday').value = formatted;
                } else {
                    alert("Please fill all date fields");
                }
            }
        </script>

        <!-- pulls current date and assigns to join_date -->
        <input type="hidden" id="join_date" name="join_date">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentDate = new Date();
                const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('join_date').value = formattedDate;
            });
        </script>

        <div>
            <label for="address">Address  </label>
            <input type="text" id="address" name="address" placeholder="Enter address" required>
            
            <label for="address2">Address 2  </label>
            <input type="text" id="address2" name="address2" placeholder="Optional">
        </div><br>

        <div>
            <label for="city">City  </label>
            <input type="text" id="city" name="city" placeholder="Enter city" style="width: 90px;"required>

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
            <input type="tel" id="phone_number" name="phone_number" placeholder="Enter 10 digits" pattern="[0-9]{10}" style="width: 120px;" required>
            <label for="email">Email  </label>
            <!-- input requires "@" and "." 
            currently casues fatal eror if not unique -->
            <input type="email" id="email" name="email" placeholder="Enter email address" pattern=".*\..*" required>
        </div><br>

        <div>
            <label for="password">Password  </label>
            <input type="password" id="password" name="password" placeholder="Create password" required>
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
            <input class = button type="submit" value="Sign Up" onclick="formatDate()">
        </form>
</body>
</html>

