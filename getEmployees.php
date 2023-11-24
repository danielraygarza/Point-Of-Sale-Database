<?php
// function used in reports.php to get employee date
session_start();
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch employee data based on the selected store from the database
$storeId = $_GET['storeId'];
$emp_status = $_GET['emp_status'];

$stmt = $mysqli->prepare("SELECT * FROM employee WHERE store_id = ? AND active_employee = ?");
$stmt->bind_param("ii", $storeId, $emp_status);
$stmt->execute();

$result = $stmt->get_result();

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = ['id' => $row['Employee_ID'], 'name' => $row['E_First_Name'] . " " . $row['E_Last_Name']];
}
// Return data in JSON format
header('Content-Type: application/json');

// Output JSON data
echo json_encode($employees);
