<?php
session_start();

if (isset($_SESSION['user_id'])) {
    require 'db_connection.php'; // Include the database connection script

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $videoId = $_POST["video_id"];
        $commentText = $_POST["comment_text"];
        $userId = $_SESSION['user_id']; // Use the user's ID from the session

        // Insert the comment into the 'comments' table with the user's ID
        $commentQuery = "INSERT INTO comments (user_id, video_id, comment_text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($commentQuery);
        $stmt->bind_param("iis", $userId, $videoId, $commentText);

        if ($stmt->execute()) {
            echo "Comment posted!";
        } else {
            echo "Posting the comment failed. Please try again.";
        }
    }
} else {
    echo "You must be logged in to post a comment.";
}
?>

