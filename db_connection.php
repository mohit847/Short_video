<?php
$DB_HOST = "localhost";
$DB_USER = "root"; // Your database username
$DB_PASS = ""; // Your database password
$DB_NAME = "short_video_platform";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>