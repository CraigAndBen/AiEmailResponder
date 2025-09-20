<?php
// db.php — works in PHP 7.3
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "email_responder";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
