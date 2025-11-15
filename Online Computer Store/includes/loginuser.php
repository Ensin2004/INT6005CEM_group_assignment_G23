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

/**
 * Generic error handler for this page
 * - Logs the error
 * - Sends ERROR 500 status
 * - Redirects to unified ERROR 500 page (no sensitive info to user)
 */
function handleErrorAndExit($message = 'Unexpected error during login.') {
    error_log('[LOGIN ERROR] ' . $message);
    http_response_code(500);
    header("Location: ../errors/500.php");
    exit;
}

try {
    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check CSRF token
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            echo "<script>alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1);</script>";
            exit;
        }

        if (!$conn) {
            // Avoid echoing DB error, just log + 500 page
            handleErrorAndExit('Database connection failed.');
        } else {
            if (isset($_POST['login'])) {
                // Collect form data
                $username = htmlspecialchars($_POST['Username']);
                $password = htmlspecialchars($_POST['UserPassword']);
                $email    = htmlspecialchars($_POST['UserEmail']); // Assuming email is also submitted

                $query = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$username' AND (email = '$email' OR secondary_email = '$email')");
                
                if (!$query) {
                    // If the query itself fails
                    handleErrorAndExit('Database query failed during login.');
                }

                $row = mysqli_fetch_assoc($query);

                // Check whether user exists or not
                if (mysqli_num_rows($query) == 0) {
                    echo "<script> alert('Incorrect name or email.'); window.location.href='../login.php'; </script>";
                    exit;
                }

                $current_time = date("Y-m-d H:i:s");

                // Check whether account is locked or not
                if (!is_null($row['lock_until']) && $row['lock_until'] > $current_time) {
                    $remaining = strtotime($row['lock_until']) - time();
                    echo "<script> alert('Account is locked. Please try again after {$remaining} seconds.'); window.location.href='../login.php'; </script>";
                    exit;
                }

                // Check whether password is correct or not
                if (!password_verify($password, $row['pwd'])) {
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
                    $update = mysqli_query(
                        $conn, 
                        "UPDATE users SET wrong_pwd_count = '$wrong_pwd_count', lock_until = " . ($lock_until ? "'$lock_until'" : "NULL") . " WHERE id = '{$row['id']}'"
                    );

                    if (!$update) {
                        handleErrorAndExit('Failed to update wrong password count/lock_until.');
                    }

                    // Display messages
                    echo "<script> alert('$lock_message'); window.location.href='../login.php'; </script>";

                } else {

                    // Update database
                    $reset = mysqli_query(
                        $conn, 
                        "UPDATE users SET wrong_pwd_count = 0, lock_until = NULL WHERE id = '{$row['id']}'"
                    );

                    if (!$reset) {
                        handleErrorAndExit('Failed to reset wrong password count/lock_until.');
                    }

                    // Regenerate session ID (prevent session fixation)
                    session_regenerate_id(true);
                    
                    // Update session
                    $_SESSION['ID'] = $row['id'];
                    $_SESSION['UserName'] = $row['user_name'];

                    // Display messages
                    echo "<script> alert('Log in successfully'); window.location.href='../index.php'; </script>";
                }
            }
        }
    }
} catch (Throwable $e) {
    // Catch any unexpected PHP error/exception
    handleErrorAndExit($e->getMessage());
}
?>