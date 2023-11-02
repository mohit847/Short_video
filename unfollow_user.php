<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $unfollowedUserId = $_POST["unfollowed_user_id"];
    $followerUserId = $_SESSION['user_id'];

    require 'db_connection.php';

    // Check if the user is following this user
    $query = "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $followerUserId, $unfollowedUserId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Unfollow the user
        $deleteQuery = "DELETE FROM followers WHERE follower_id = ? AND following_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $followerUserId, $unfollowedUserId);

        if ($deleteStmt->execute()) {
            // Update the follower count for the user who got unfollowed
            $updateCountQuery = "UPDATE users SET followers_count = followers_count - 1 WHERE id = ?";
            $updateCountStmt = $conn->prepare($updateCountQuery);
            $updateCountStmt->bind_param("i", $unfollowedUserId);
            $updateCountStmt->execute();

            echo "You've unfollowed this user.";
        } else {
            echo "Unfollowing this user failed. Please try again.";
        }
    } else {
        // User is not following this user, this should not occur.
        echo "Unexpected error: You're not following this user.";
    }

    $conn->close();
} else {
    echo "Unauthorized access or user not logged in.";
}
?>
