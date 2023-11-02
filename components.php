<?php
function renderVideoSection()
{
    require 'db_connection.php';

    $sql = "SELECT videos.*, users.id AS user_id, users.username AS uploader_username, users.followers_count AS followers_count FROM videos
    LEFT JOIN users ON videos.user_id = users.id
    ORDER BY RAND()";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $first = true;
        echo '<div class="vertical-carousel" id="videoCarousel"  >';
        echo '<div class="carousel-inner  ">';

        while ($video = $result->fetch_assoc()) {
            $active = $first ? 'active' : '';
            echo '<div class="carousel-item ' . $active . '">';

            echo '<div class="video-container">';
            echo '<div class="fixed-video-wrapper">';
            echo '<video class="video card-img-top rounded-3" loop controls src="' . $video['video_url'] . '"></video>';
            echo '</div>';
            echo '</div>';



            echo '<div class="card-body custom-position rounded d-flex align-items-center flex-column p-4 mb-4 bg-warning">';

            $uploaderUsername = strstr($video['uploader_username'], '@', true);
            echo '<div class="uploader-info text-white"><strong> @' . $uploaderUsername . '</strong></div>';

            echo '<h5 class="card-title video-title text-white">' . $video['video_title'] . '</h5>';
            // echo '<p class="card-text">' . $video['video_description'] . '</p>';


            $truncatedDescription = substr($video['video_description'], 0, 50); // Adjust the character limit as needed
            echo '<div class="card-text desc description-toggle text-white" data-full-description="' . htmlspecialchars($video['video_description']) . '">' . $truncatedDescription . '...</div>';



            // Display the like and dislike icons with like count
            echo '<div class="like-dislike d-flex flex-column text-white fs-2 gap-3">';
            echo '<i class="fas fa-thumbs-up like-icon like-dislike-icon" data-action="like" data-video-id="' . $video['id'] . '"></i>';
            echo '<i class="fas fa-thumbs-down dislike-icon like-dislike-icon" data-action="dislike" data-video-id="' . $video['id'] . '"></i>';
            echo '</div>';
            // echo '<span class="like-count">' . $video['like_count'] . '</span>';




            // Display the Follow/Unfollow button
            if (isset($_SESSION['user_id'])) {
                echo '<form action="follow_user.php" method="post">';
                echo '<input type="hidden" name="followed_user_id" value="' . $video['user_id'] . '">';
                echo '<button type="submit" class="btn follow-btn btn-primary" id="follow-button">Follow</button>';
                echo '</form>';
            }





            echo '<form class="comment-form" action="comment_video.php" method="post">';
            echo '<input type="text" name="comment_text" placeholder="Add a comment" required>';
            echo '<input type="hidden" name="video_id" value="' . $video['id'] . '">';
            echo '<button type="submit" class="btn btn-primary">Post</button>';
            echo '</form>';
            // echo '<p class="follower-count">Followers: ' . $video['followers_count'] . '</p>';

            // Retrieve and display comments for the video
            $commentsQuery = "SELECT comments.comment_text, users.username
                  FROM comments
                  INNER JOIN users ON comments.user_id = users.id
                  WHERE comments.video_id = ?";
            $commentsStmt = $conn->prepare($commentsQuery);
            $commentsStmt->bind_param("i", $video['id']);
            $commentsStmt->execute();
            $commentsResult = $commentsStmt->get_result();

            if ($commentsResult->num_rows > 0) {
                echo '<div class="comments-section">';
                echo '<h5>Comments:</h5';
                echo '<br>';

                $commentCount = 0; // Counter for comments displayed

                while ($comment = $commentsResult->fetch_assoc()) {
                    if ($commentCount == 0) {
                        // echo '<p>click to see comments</p>';
                    } else {
                        // Extract the username before "@" symbol

                        $commenterUsername = strstr($comment['username'], '@', true);
                        echo '<p class="hidden-comment"><strong>' . $commenterUsername . ':</strong> ' . $comment['comment_text'] . '</p>';
                    }

                    $commentCount++;
                }

                if ($commentCount > 1) {
                    echo '<br>';
                    echo '<a class="see-more-link text-decoration-none text-dark">See More</a>';
                    echo '<a class="see-less-link text-decoration-none text-dark" style="display: none;">See Less</a>';
                }
                echo '</div>';
            } else {
                echo '<p>No comments</p>';
            }
            echo '</div>';
            echo '</div>';
            $first = false;

        }

        echo '</div>';
        echo '<button class="carousel-control-prev pe-none" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">';
        echo '<span class="carousel-control-prev-icon opacity-25" aria-hidden="true"></span>';
        // echo '</button>';
        echo '<button class="carousel-control-next pe-none" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">';
        echo '<span class="carousel-control-next-icon  opacity-25" aria-hidden="true"></span>';
        // echo '</button>';
        echo '</div>';
    } else {
        echo 'No videos available.';
    }

    // Close the database connection
    $conn->close();
}



function renderUserCard($fullUsername, $followersCount)
{
    // Extract the username before "@" symbol
    $username = strstr($fullUsername, '@', true);

    echo '<div class="card mb-3">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">User Profile</h5>';
    echo '<p class="card-text">Username: ' . $username . '</p>';
    echo '<p class="card-text">Followers: ' . $followersCount . '</p>';
    echo '</div>';
    echo '</div>';
}


// Function to get user card data from the database
function getUserCardData($conn, $userId)
{
    $username = "";
    $followersCount = 0;

    $query = "SELECT username, followers_count FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $followersCount);
    $stmt->fetch();

    return array('username' => $username, 'followers_count' => $followersCount);
}
?>