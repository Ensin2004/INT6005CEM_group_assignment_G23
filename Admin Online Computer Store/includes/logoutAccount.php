<?php
session_start();

// Clear all session variables and destroy session data
session_unset();
session_destroy();

// Start new empty session with new session id
session_start();
session_regenerate_id(true);

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    echo "<script> alert('Session expired. Please log in again.'); window.location.href='../index.php'; </script>";
} else {
    echo "<script> alert('Log out successfully'); window.location.href='../index.php'; </script>";
}


