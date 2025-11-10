<?php
session_start();
require_once "dbh.inc.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$conn) {
        die("Database connection failed");
    }

    if (isset($_POST['login'])) {
        // Collect and sanitize input
        $username = trim($_POST['Username']);
        $email = trim($_POST['UserEmail']);
        $password = trim($_POST['UserPassword']);

        // Prepared statement (prevents SQL injection)
        $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_name = ? AND admin_email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if admin exists
        if ($result->num_rows === 0) {
            echo "<script>alert('No admin found with that name or email'); window.history.go(-1);</script>";
            exit();
        }

        $row = $result->fetch_assoc();

        // Check if account is banned
        if (isset($row['account_status']) && strtolower($row['account_status']) === 'banned') {
            echo "<script>alert('Your account has been banned. Please contact the system administrator.'); window.history.go(-1);</script>";
            exit();
        }

        // Verify password securely
            if ($password !== $row['admin_pwd']) {
            echo "<script>alert('Incorrect password'); window.history.go(-1);</script>";
            exit();
        }

        // Store session data after login
        $_SESSION['ID'] = $row['id'];
        $_SESSION['AdminName'] = $row['admin_name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['status'] = $row['account_status']; 

        echo "<script>alert('Login successful!'); window.location.href='../home.php';</script>";
        exit();
    }
}
?>
