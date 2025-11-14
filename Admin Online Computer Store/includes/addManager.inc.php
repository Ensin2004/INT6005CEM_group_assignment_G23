<?php
require_once "dbh.inc.php";
require_once "csrf.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

// Check CSRF token
if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
    die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
}

if (isset($_POST['addManager'])) {
    $name = htmlspecialchars(trim($_POST['admin_name']));
    $email = htmlspecialchars(trim($_POST['admin_email']));
    $password = password_hash($_POST['admin_pwd'], PASSWORD_ARGON2ID, $ARGON_OPTS);

    // Default profile image
    $defaultImg = "no_profile_pic.png";

    $sql = "INSERT INTO admins (admin_name, admin_email, admin_pwd, role, admin_image)
            VALUES ('$name', '$email', '$password', 'manager', '$defaultImg')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Manager added successfully!'); window.location.href='../managers.php';</script>";
    } else {
        echo "<script>alert('Error adding manager: " . mysqli_error($conn) . "'); window.history.go(-1);</script>";
    }
}
?>
