<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "hireme"; // change if your DB name is different

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

