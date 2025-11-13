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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            echo "<script>alert('No admin found with that name or email'); window.history.go(-1);</script>";
            exit();
        }

        $row = $result->fetch_assoc();

        // Check if account is banned
        if (isset($row['account_status']) && strtolower($row['account_status']) === 'banned') {
            echo "<script>alert('Your account has been banned. Please contact the system administrator.'); window.history.go(-1);</script>";
            exit();
        }

        $current_time = date("Y-m-d H:i:s");

        // Check whether account is locked or not
        if (!is_null($row['lock_until']) && $row['lock_until'] > $current_time) {
            $remaining = strtotime($row['lock_until']) - time();
            echo "<script> alert('Account is locked. Please try again after {$remaining} seconds.'); window.history.go(-1); </script>";
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

            // Display messages
            echo "<script> alert('$lock_message'); window.history.go(-1); </script>";

        } else {

            // Update database
            mysqli_query(
                $conn, 
                "UPDATE admins SET wrong_pwd_count = 0, lock_until = NULL WHERE id = '{$row['id']}'"
            );
            
            // Update session
            $_SESSION['ID'] = $row['id'];
            $_SESSION['AdminName'] = $row['admin_name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['status'] = $row['account_status'];

            // Display messages
            echo "<script> alert('Log in successfully'); window.location.href='../home.php'; </script>";
        }
    }
}
?>
