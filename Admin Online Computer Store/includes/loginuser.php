<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
require_once "dbh.inc.php"; 
require_once "csrf.php";
require_once "audit.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check CSRF token
    if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
        die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
    }

    if (!$conn) {
        die("Database connection failed");
    }

    if (isset($_POST['login'])) {
        // Collect and sanitize input
        $username = htmlspecialchars($_POST['Username']);
        $email = htmlspecialchars($_POST['UserEmail']);
        $password = $_POST['UserPassword'];

        // Prepared statement (prevents SQL injection)
        $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_name = ? AND admin_email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if admin exists
        if ($result->num_rows === 0) {
            echo "<script>alert('No admin found with that name or email'); window.location.href='../index.php';</script>";

            audit_log($conn, null, null, 'login_failure', null, null,
          'Admin login failed: name=' . $username . ', email=' . $email,
          null, null, 'failure');

            exit();
        }

        $row = $result->fetch_assoc();

        // Check if account is banned
        if (isset($row['account_status']) && strtolower($row['account_status']) === 'banned') {
            audit_log($conn, $row['id'], $row['role'] ?? null,
              'login_blocked','admins',$row['id'],
              'Login attempt on banned account');

            echo "<script>alert('Your account has been banned. Please contact the system administrator.'); window.location.href='../index.php';</script>";
            exit();
        }

        $current_time = date("Y-m-d H:i:s");

        // Check whether account is locked or not
        if (!is_null($row['lock_until']) && $row['lock_until'] > $current_time) {
            $remaining = strtotime($row['lock_until']) - time();
            echo "<script> alert('Account is locked. Please try again after {$remaining} seconds.'); window.location.href='../index.php'; </script>";
            exit;
        }

        // Check whether password is correct or not
        if (!password_verify($password, $row['admin_pwd'])) {
            $wrong_pwd_count = $row['wrong_pwd_count'] + 1;
            $lock_until = null;
            $lock_message = "";

            // Account lock time
            if ($wrong_pwd_count < 6) {
                $lock_until = null;
                $lock_message = "Incorrect password.";
            } elseif ($wrong_pwd_count == 6) {
                $lock_until = date("Y-m-d H:i:s", strtotime("+30 seconds"));
                $lock_message = "Incorrect password. Account locked for 30 seconds.";
            } elseif ($wrong_pwd_count == 7) {
                $lock_until = date("Y-m-d H:i:s", strtotime("+1 minute"));
                $lock_message = "Incorrect password. Account locked for 1 minute.";
            } elseif ($wrong_pwd_count == 8) {
                $lock_until = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                $lock_message = "Incorrect password. Account locked for 5 minutes.";
            } elseif ($wrong_pwd_count == 9) {
                $lock_until = date("Y-m-d H:i:s", strtotime("+10 minutes"));
                $lock_message = "Incorrect password. Account locked for 10 minutes.";
            } elseif ($wrong_pwd_count > 9) {
                $lock_until = date("Y-m-d H:i:s", strtotime("+30 minutes"));
                $lock_message = "Incorrect password. Account locked for 30 minutes.";
            }

            // Update database
            mysqli_query(
                $conn, 
                "UPDATE admins SET wrong_pwd_count = '$wrong_pwd_count', lock_until = " . ($lock_until ? "'$lock_until'" : "NULL") . " WHERE id = '{$row['id']}'"
            );

            // log for wrong password
            audit_log(
                $conn, 
                $row['id'], 
                $row['role'] ?? null, 
                'login_failure', 
                null,
                null,
                'Wrong password for admin #' . $row['id'],
                null, 
                null, 
                'failure'
            );


            // Display messages
            echo "<script> alert('$lock_message'); window.location.href='../index.php'; </script>";

        } else {

            // Update database
            mysqli_query(
                $conn, 
                "UPDATE admins SET wrong_pwd_count = 0, lock_until = NULL WHERE id = '{$row['id']}'"
            );

            // Regenerate session ID (prevent session fixation)
            session_regenerate_id(true);
            
            // Update session
            $_SESSION['ID'] = $row['id'];
            $_SESSION['AdminName'] = $row['admin_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['status'] = $row['account_status'];

            // log for success login
            audit_log(
                $conn, 
                $row['id'], 
                $row['role'], 
                'login_success', 
                null, 
                null,
                'Admin logged in'
            );

            // Display messages
            echo "<script> alert('Log in successfully'); window.location.href='../home.php'; </script>";
        }
    }
}
?>
