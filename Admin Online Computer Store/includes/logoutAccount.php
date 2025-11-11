<?php

require_once "security.php";

session_destroy();

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    echo "<script> alert('Session expired due to inactivity. Please log in again.'); window.location.href='../index.php'; </script>";
} else {
    echo "<script> alert('Log out successfully'); window.location.href='../index.php'; </script>";
}


