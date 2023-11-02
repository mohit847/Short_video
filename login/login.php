<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../db_connection.php'; // Include the database connection file

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database to check if the user exists
    $query = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $username, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Password is correct, user is logged in
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            header('Location: ../index.php'); // Redirect to the main page after login
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found. Please register or check your username.";
    }

    $stmt->close();
    $conn->close();
}
?>
