<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "short_video_platform";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch video data from the database
$sql = "SELECT * FROM videos";
$result = $conn->query($sql);

$videos = array();


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return video data as JSON
echo json_encode($videos);

?>