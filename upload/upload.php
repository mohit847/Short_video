<?php
session_start();

$videoUploaded = false; // Flag to track whether the video was uploaded successfully

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or display an error message indicating that the user must be logged in to upload videos
        header("Location: ../login/login.html"); // Redirect to the login page
        exit; // Stop execution if the user is not logged in
    }

    $videoTitle = $_POST["videoTitle"];
    $videoDescription = $_POST["videoDescription"];
    $videoFile = $_FILES["videoFile"];

    // Check if the file is a valid video file
    $allowedExtensions = ["mp4", "avi", "mov"]; // Add more extensions as needed
    $fileExtension = pathinfo($videoFile["name"], PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "Invalid file format. Please upload a valid video.";
    } elseif (strlen($videoDescription) > 60) {
        echo "Video description must not exceed 60 characters.";
    }else {
        // Define the directory where uploaded videos will be stored
        $uploadDir = "uploads/";
        $uploadPath = $uploadDir . basename($videoFile["name"]);

        if (move_uploaded_file($videoFile["tmp_name"], $uploadPath)) {
            // Insert video data into the 'videos' table
            $user_id = $_SESSION['user_id'];

            $DB_HOST = "localhost";
            $DB_USER = "root"; // Your database username
            $DB_PASS = ""; // Your database password
            $DB_NAME = "short_video_platform";

            // Create a new database connection
            $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $uploadPath = "upload/".$uploadDir . basename($videoFile["name"]);

            $query = "INSERT INTO videos (video_title, video_description, video_url, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $videoTitle, $videoDescription, $uploadPath, $user_id);

            if ($stmt->execute()) {
                $videoUploaded = true; // Set the flag to indicate successful upload
               
            } else {
                echo "Video upload failed. Please try again.";
            }
        } else {
            echo "File upload failed. Please try again.";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
</head>

<body>
<?php
    if (!$videoUploaded) { // Display the form only if the video was not uploaded successfully
        echo '<h1>Upload a Video</h1>';
        echo '<form action="upload.php" method="post" enctype="multipart/form-data">';
        echo '    <label for="videoTitle">Video Title:</label>';
        echo '    <input type="text" name="videoTitle" id="videoTitle" required><br><br>';
        echo '    <label for="videoDescription">Video Description:</label>';
        echo '    <textarea name="videoDescription" id="videoDescription" required></textarea><br><br>';
        echo '    <label for="videoFile">Choose a Video:</label>';
        echo '    <input type="file" name="videoFile" id="videoFile" accept="video/*" required><br><br>';
        echo '    <input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
        echo '    <input type="submit" value="Upload Video">';
        echo '</form>';
        echo '<a href="../index.php"><button>Go to Home Page</button></a>';
    } else {
        echo '<h1>Video uploaded successfully</h1>';
        echo '<a href="../index.php"><button>Go to Home Page</button></a>';
    }
?>

</body>
</html>
