<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../db_connection.php'; // Include the database connection file

    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        // Passwords do not match
        echo "Passwords do not match. Please try again.";
    } else {
        // Check if the username already exists
        $check_query = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Username is already taken
            echo "Username is already taken. Please choose a different one.";
        } else {
            // Insert user data into the 'users' table
            $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $insert_stmt->bind_param("ss", $username, $hashed_password);

            if ($insert_stmt->execute()) {
                // Registration successful
                echo "Registration successful!";
            } else {
                // Registration failed
                echo "Registration failed. Please try again.";
            }
        }

        $check_stmt->close();
        $insert_stmt->close();
    }

    $conn->close();
}
?>
