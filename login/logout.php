<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
}
header('Location: ../index.php'); 
?>
