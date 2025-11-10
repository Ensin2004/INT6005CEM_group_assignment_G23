<?php
require_once "dbh.inc.php";

if (isset($_POST['addManager'])) {
    $name = $_POST['admin_name'];
    $email = $_POST['admin_email'];
    $password = $_POST['admin_pwd'];

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
