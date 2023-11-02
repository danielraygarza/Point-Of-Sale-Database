
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
    if (isset($_GET['id'])) {
        $employeeId = $_GET['id'];
        $sql = "SELECT 
            e.`Employee_ID`, 
            e.`E_First_Name`, 
            e.`E_Last_Name`, 
            e.`Title_Role`, 
            e.`Hire_Date`, 
            e.`assigned_orders`, 
            e.`completed_orders`, 
            d.`Time_Delivered`, 
            d.`Delivery_Status`
        FROM `employee` e
        LEFT JOIN `delivery` d ON e.`Employee_ID` = d.`employee`
        WHERE e.`Employee_ID` = $employeeId";
        $result = mysqli_query($mysqli, $sql);

        if (!$result) {
            die("Error: " . mysqli_error($mysqli));
        }

        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1' class='table_update'>";
            echo "<tr><th>Employee ID</th><th>First Name</th><th>Last Name</th><th>Title/Role</th><th>Hire Date</th><th>Assigned Orders</th><th>Completed Orders</th><th>Time Delivered</th><th>Delivery Status</th></tr>";
    
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Employee_ID'] . "</td>";
                echo "<td>" . $row['E_First_Name'] . "</td>";
                echo "<td>" . $row['E_Last_Name'] . "</td>";
                echo "<td>" . $row['Title_Role'] . "</td>";
                echo "<td>" . $row['Hire_Date'] . "</td>";
                echo "<td>" . $row['assigned_orders'] . "</td>";
                echo "<td>" . $row['completed_orders'] . "</td>";
                echo "<td>" . $row['Time_Delivered'] . "</td>";
                echo "<td>" . $row['Delivery_Status'] . "</td>";
                echo "</tr>";
            }
    
            echo "</table>";
        }else {
            echo "Employee not found.";
        }

        mysqli_free_result($result);
    } else {
        echo "Employee ID not provided.";
    }
}

    

    ?>
</body>
</html>
