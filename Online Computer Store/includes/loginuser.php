<?php
session_start();
require_once "dbh.inc.php";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$conn) {
        die("Database connection failed");
    } else {
        if (isset($_POST['login'])) {
            // Collect form data
            $username = htmlspecialchars($_POST['Username']);
            $password = htmlspecialchars($_POST['UserPassword']);
            $email = htmlspecialchars($_POST['UserEmail']); // Assuming email is also submitted

            $query = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$username' AND (email = '$email' OR secondary_email = '$email')");
            $row = mysqli_fetch_assoc($query);

            if (mysqli_num_rows($query) == 0 || $row['pwd'] != $password) {
                echo "<script> alert('Wrong name, email or password'); window.history.go(-1); </script>";
            } else {
                $_SESSION['ID'] = $row['id'];
                $_SESSION['UserName'] = $row['user_name'];
                echo "<script> alert('Log in successfully'); window.location.href='../index.php';</script>";
            }
        }
    }
}
