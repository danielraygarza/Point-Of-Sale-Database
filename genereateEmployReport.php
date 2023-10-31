
<?php
 $setHeader = "TEST";
include_once("./../../database.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="./../../css/styles.css">
    <link rel="icon" href="./../../img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="./../../index.php">Home</a>
        <a href="./../../employee_home.php">Employee Home</a>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="logout.php">Logout</a>';
        }
        ?>
    </div>
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'generateReport') {
        $employeeId = $_GET['id'];
    
        $sql = "SELECT `Employee_ID`, `E_First_Name`, `E_Last_Name`, `Title_Role`, `Hire_Date`, `assigned_orders`, `completed_orders` FROM `employee` WHERE `Employee_ID` = $employeeId";
        $result = mysqli_query($mysqli, $sql);
    
        if (!$result) {
            die("Error: " . mysqli_error($connection));
        }
        if ($row = mysqli_fetch_assoc($result)) {
            echo "<table border='1' class='table_update'>";
            echo "<tr>".$row['E_First_Name']." ".$row['E_Last_Name']."</tr>";
            echo "<tr><th>Employee ID</th><th>First Name</th><th>Last Name</th><th>Title/Role</th><th>Hire Date</th><th>Assigned Orders</th><th>Completed Orders</th></tr>";
            echo "<tr>";
            echo "<td>" . $row['Employee_ID'] . "</td>";
            echo "<td>" . $row['E_First_Name'] . "</td>";
            echo "<td>" . $row['E_Last_Name'] . "</td>";
            echo "<td>" . $row['Title_Role'] . "</td>";
            echo "<td>" . $row['Hire_Date'] . "</td>";
            echo "<td>" . $row['assigned_orders'] . "</td>";
            echo "<td>" . $row['completed_orders'] . "</td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "Employee not found.";
        }
        mysqli_free_result($result);
    }
    

    ?>
</body>
</html>
