<?php
// Database connection logic
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skillxpress";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>