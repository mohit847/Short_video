<!DOCTYPE html>
<html>

<head>
    <title>Creator Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .scrollable-comments {
            max-height: 300px;
            /* Adjust the maximum height as needed */
            overflow-y: auto;
            /* Enable vertical scrolling */
            border: 1px solid #ccc;
            /* Add a border for clarity */
        }

        .comment {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .username {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="creator-home d-flex justify-content-center align-items-center justify-content-between mt-5 mb-4 ">
            <h1 class="">Welcome to Your Creator Dashboard</h1>
            <a href="index.php"><button class="btn btn-primary">Go to Home Page</button></a>
        </div>

        <ul class="list-group">
            <?php while ($video = $videos->fetch_assoc()) { ?>
                <li class="list-group-item mb-3">
                    <h2>
                        <?= $video['video_title']; ?>
                    </h2>

                    <?php if (!empty($video['video_description'])) { ?>
                        <p><strong>Current Description:</strong>
                            <?= $video['video_description']; ?>
                        </p>
                    <?php } ?>

                    <!-- Display Comments -->
                    <h3>Comments:</h3>

                    <div class="scrollable-comments">
                        <ul class="list-group">
                            <?php
                            // Fetch comments for the current video
                            $commentsSql = "SELECT c.*, u.username FROM comments c
                        JOIN users u ON c.user_id = u.id
                        WHERE c.video_id = ?";
                            $commentsStmt = $conn->prepare($commentsSql);
                            $commentsStmt->bind_param("i", $video['id']);
                            $commentsStmt->execute();
                            $commentsForVideo = $commentsStmt->get_result();

                            $commentCount = 0; // Initialize a comment count
                        
                            while ($comment = $commentsForVideo->fetch_assoc()) {
                                $commentCount++;
                                ?>
                                <?php if ($commentCount > 2) { // Add scroll if more than 2 comments ?>
                                    <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#scrollable-comments"
                                        role="button" aria-expanded="false" aria-controls="scrollable-comments">
                                        Show More
                                    </a>
                                    <div id="scrollable-comments" class="collapse">
                                        <ul class="list-group">
                                            <?php
                                            // Re-fetch and re-display all comments
                                            $commentsForVideo->data_seek(0);
                                            while ($comment = $commentsForVideo->fetch_assoc()) {
                                                $username = $comment['username'];
                                                $trimmedUsername = strstr($username, '@') ? substr($username, 0, strpos($username, '@')) : $username;
                                                ?>
                                                <li class="list-group-item">
                                                    <span class="username"><strong>@
                                                            <?= $trimmedUsername ?>:
                                                        </strong></span>
                                                    <?= $comment['comment_text']; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </ul>


                    </div>



                    <form method="post" action="">
                        <input type="hidden" name="video_id" value="<?= $video['id']; ?>">
                        <div class="form-group">
                            <input type="text" name="new_title" class="form-control" placeholder="New Title">
                        </div>
                        <div class="form-group">
                            <input type="text" name="new_description" class="form-control" placeholder="New Description">
                        </div>
                        <button type="submit" class="btn btn-danger" name="delete_video">Delete</button>
                        <button type="submit" class="btn btn-primary" name="edit_video">Edit</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Include Bootstrap JS (optional, for some features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>