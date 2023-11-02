<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $followedUserId = $_POST["followed_user_id"];
    $followerUserId = $_SESSION['user_id'];

    require 'db_connection.php';

    // Check if the user is already following this user
    $query = "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $followerUserId, $followedUserId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User is already following this user, so unfollow them
        $deleteQuery = "DELETE FROM followers WHERE follower_id = ? AND following_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $followerUserId, $followedUserId);

        if ($deleteStmt->execute()) {
            // Update the follower count for the user who got unfollowed
            $updateCountQuery = "UPDATE users SET followers_count = followers_count - 1 WHERE id = ?";
            $updateCountStmt = $conn->prepare($updateCountQuery);
            $updateCountStmt->bind_param("i", $followedUserId);
            $updateCountStmt->execute();

            // Redirect back to index.php on successful unfollow
            header("Location: index.php");
            exit();
        } else {
            echo "Unfollowing this user failed. Please try again.";
        }
    } else {
        // Insert a new follower relationship into the 'followers' table
        $insertQuery = "INSERT INTO followers (follower_id, following_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $followerUserId, $followedUserId);

        if ($insertStmt->execute()) {
            // Update the follower count for the user who got followed
            $updateCountQuery = "UPDATE users SET followers_count = followers_count + 1 WHERE id = ?";
            $updateCountStmt = $conn->prepare($updateCountQuery);
            $updateCountStmt->bind_param("i", $followedUserId);
            $updateCountStmt->execute();

            // Redirect back to index.php on successful follow
            header("Location: index.php");
            exit();
        } else {
            echo "Following this user failed. Please try again.";
        }
    }

    $conn->close();
} else {
    echo "Unauthorized access or user not logged in.";
}

?>
