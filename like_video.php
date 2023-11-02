<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Handle the case where the user is not logged in
    echo 'NotLoggedIn';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db_connection.php';

    $videoId = $_POST['video_id'];
    $userId = $_SESSION['user_id'];

    // Check if the user has already liked the video
    $checkLikedQuery = "SELECT COUNT(*) FROM likes WHERE user_id = ? AND video_id = ?";
    $checkLikedStmt = $conn->prepare($checkLikedQuery);
    $checkLikedStmt->bind_param("ii", $userId, $videoId);
    $checkLikedStmt->execute();
    $checkLikedStmt->bind_result($likeCount);
    $checkLikedStmt->fetch();

    if ($likeCount === 0) {
        // The user hasn't liked the video yet, so insert the like with 'created_at' timestamp
        $insertLikeQuery = "INSERT INTO likes (user_id, video_id, created_at) VALUES (?, ?, NOW())";
        $insertLikeStmt = $conn->prepare($insertLikeQuery);
        $insertLikeStmt->bind_param("ii", $userId, $videoId);

        if ($insertLikeStmt->execute()) {
            // Liked successfully
            echo 'Liked';
        } else {
            // Handle the case where the like couldn't be inserted
            echo 'Error';
        }
    } else {
        // The user has already liked the video, do nothing
        echo 'AlreadyLiked';
    }

    $conn->close();
}
?>