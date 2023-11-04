<?php
require 'db_connection.php'; // Include the database connection file
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete_video'])) {
            // Handle video deletion
            $videoId = $_POST['video_id'];
            // Delete related likes and comments
            $deleteLikesSql = "DELETE FROM likes WHERE video_id = ?";
            $deleteLikesStmt = $conn->prepare($deleteLikesSql);
            $deleteLikesStmt->bind_param("i", $videoId);
            $deleteLikesStmt->execute();
            
            $deleteCommentsSql = "DELETE FROM comments WHERE video_id = ?";
            $deleteCommentsStmt = $conn->prepare($deleteCommentsSql);
            $deleteCommentsStmt->bind_param("i", $videoId);
            $deleteCommentsStmt->execute();
            
            // Delete the video
            $deleteVideoSql = "DELETE FROM videos WHERE id = ?";
            $deleteVideoStmt = $conn->prepare($deleteVideoSql);
            $deleteVideoStmt->bind_param("i", $videoId);
            $deleteVideoStmt->execute();
        } elseif (isset($_POST['edit_video'])) {
            // Handle video editing
            $videoId = $_POST['video_id'];
            $newTitle = $_POST['new_title'];
            $newDescription = $_POST['new_description'];
            
            // Update video details
            $updateVideoSql = "UPDATE videos SET video_title = ?, video_description = ? WHERE id = ?";
            $updateVideoStmt = $conn->prepare($updateVideoSql);
            $updateVideoStmt->bind_param("ssi", $newTitle, $newDescription, $videoId);
            $updateVideoStmt->execute();
        }
    }

    // Fetch user's uploaded videos
    $videoSql = "SELECT * FROM videos WHERE user_id = ?";
    $videoStmt = $conn->prepare($videoSql);
    $videoStmt->bind_param("i", $userId);
    $videoStmt->execute();
    $videos = $videoStmt->get_result();

    // Get the total number of uploaded videos
    $totalVideosSql = "SELECT COUNT(id) as total FROM videos WHERE user_id = ?";
    $totalVideosStmt = $conn->prepare($totalVideosSql);
    $totalVideosStmt->bind_param("i", $userId);
    $totalVideosStmt->execute();
    $totalVideosResult = $totalVideosStmt->get_result();
    $totalVideos = $totalVideosResult->fetch_assoc();
    

    // Fetch user's likes
    $likesSql = "SELECT v.video_title, l.* FROM likes l
                 JOIN videos v ON l.video_id = v.id
                 WHERE l.user_id = ?";
    $likesStmt = $conn->prepare($likesSql);
    $likesStmt->bind_param("i", $userId);
    $likesStmt->execute();
    $likes = $likesStmt->get_result();

    // Fetch user's comments
    $commentsSql = "SELECT v.video_title, c.* FROM comments c
                   JOIN videos v ON c.video_id = v.id
                   WHERE c.user_id = ?";
    $commentsStmt = $conn->prepare($commentsSql);
    $commentsStmt->bind_param("i", $userId);
    $commentsStmt->execute();
    $comments = $commentsStmt->get_result();

    // Display the Creator Dashboard
    include 'dashboard_template.php'; // Create a template for the dashboard
} else {
    echo 'You must be logged in to access the Creator Dashboard.';
}

// Close the database connection
$conn->close();
?>
