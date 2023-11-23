<!-- gets configuration information from Azure web app to connect to database -->
<?php
$host = "pospizza.mysql.database.azure.com";
$username = "danielgarza";
$password = "#drgarza8";
$dbname = "pos";
$port = 3306;
$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
if (!$mysqli->real_connect($host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection error: " . $mysqli->connect_error);
}
