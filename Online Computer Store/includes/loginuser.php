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
require_once "crypto.php";

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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check CSRF token
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            echo "<script>alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1);</script>";
            exit;
        }

        if (!$conn) {
            handleErrorAndExit('Database connection failed.');
        }

        if (isset($_POST['login'])) {
            $username = htmlspecialchars($_POST['Username']);
            $password = htmlspecialchars($_POST['UserPassword']);
            $email    = htmlspecialchars($_POST['UserEmail']);

            // -----------------------------------------------------------------
            // Because user_name, email, secondary_email are encrypted,
            // cannot use them directly in WHERE.
            // Scan all users, decrypt, and find one matching BOTH username + email.
            // -----------------------------------------------------------------
            $sql = "SELECT id, user_name, email, secondary_email, pwd, wrong_pwd_count, lock_until 
                    FROM users";
            $res = mysqli_query($conn, $sql);
            if (!$res) {
                handleErrorAndExit('Failed to read users table for login.');
            }

            $matched = null;
            $plain_username = '';
            while ($row = mysqli_fetch_assoc($res)) {
                $dec_username  = decrypt_field($row['user_name']);
                if (strcasecmp($dec_username, $username) !== 0) {
                    continue; // username mismatch
                }

                $dec_email     = decrypt_field($row['email']);
                $dec_secondary = decrypt_field($row['secondary_email']);

                if (
                    strcasecmp($email, $dec_email) !== 0 &&
                    strcasecmp($email, $dec_secondary) !== 0
                ) {
                    continue; // email mismatch
                }

                // Username + email both match this row
                $matched        = $row;
                $plain_username = $dec_username;
                break;
            }

            if ($matched === null) {
                echo "<script>alert('Incorrect name or email.'); window.location.href='../login.php';</script>";
                exit;
            }

            $row = $matched;
            $current_time = date("Y-m-d H:i:s");

            // Account lock check
            if (!is_null($row['lock_until']) && $row['lock_until'] > $current_time) {
                $remaining = strtotime($row['lock_until']) - time();
                echo "<script> alert('Account is locked. Please try again after {$remaining} seconds.'); window.location.href='../login.php'; </script>";
                exit;
            }

            // Password check
            if (!password_verify($password, $row['pwd'])) {
                $wrong_pwd_count = $row['wrong_pwd_count'] + 1;
                $lock_until = null;
                $lock_message = "";

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

                $update = mysqli_query(
                    $conn,
                    "UPDATE users 
                     SET wrong_pwd_count = '$wrong_pwd_count', 
                         lock_until = " . ($lock_until ? "'$lock_until'" : "NULL") . " 
                     WHERE id = '{$row['id']}'"
                );

                if (!$update) {
                    handleErrorAndExit('Failed to update wrong password count/lock_until.');
                }

                echo "<script> alert('$lock_message'); window.location.href='../login.php'; </script>";
                exit;

            } else {
                // Reset lock + wrong count
                $reset = mysqli_query(
                    $conn,
                    "UPDATE users 
                     SET wrong_pwd_count = 0, lock_until = NULL 
                     WHERE id = '{$row['id']}'"
                );

                if (!$reset) {
                    handleErrorAndExit('Failed to reset wrong password count/lock_until.');
                }

                session_regenerate_id(true);

                $_SESSION['ID']       = $row['id'];
                // Store decrypted username in session
                $_SESSION['UserName'] = $plain_username;

                echo "<script> alert('Log in successfully'); window.location.href='../index.php'; </script>";
            }
        }
    }
} catch (Throwable $e) {
    handleErrorAndExit($e->getMessage());
}
?>
