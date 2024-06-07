<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jayyr";
$port = 3306;

$con = new mysqli($servername, $username, $password, $dbname, $port);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
