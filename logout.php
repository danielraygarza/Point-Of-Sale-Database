<?php
// function runs when logout is clicked. logs user out and sends to home or employee_home based on user

//Logout file for nav bar. Will kill session and send to main page
session_start();
include 'database.php';
$redirectTo = 'index.php'; // Default redirect location

// checks if the user logged in is an employee
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['user']['Employee_ID'])) {
    $Employee_ID = $_SESSION['user']['Employee_ID'];

    // mark employee as clocked out
    $mysqli->query("UPDATE employee SET clocked_in=0 WHERE Employee_ID='$Employee_ID'");

    //send to employee login
    $redirectTo = 'employee_login.php';

    //close connection
    $mysqli->close();
}

session_destroy(); // This will clear the session
header("Location: $redirectTo"); // Redirecting to appropriate page
exit();
