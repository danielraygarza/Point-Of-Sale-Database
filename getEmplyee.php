<?php

function getEmployeeData() {
    include_once("./database.php"); 
    $sql = "SELECT `Employee_ID`, `E_First_Name`, `E_Last_Name` FROM `employee`";
    $result = mysqli_query($mysqli, $sql);

    if (!$result) {
        die("Error: " . mysqli_error($connection));
    }

    $employeeData = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $employeeData[] = [
            'Employee_ID' => $row['Employee_ID'],
            'Name' => $row['E_First_Name'] . ' ' . $row['E_Last_Name'],
        ];
    }
    mysqli_free_result($result);
    return $employeeData;
}


?>