<?php
session_start();
require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["newAdminName"]);
    $email = htmlspecialchars($_POST["newAdminEmail"]);
    $password = htmlspecialchars($_POST["newAdminPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $img = $_FILES["adminimg"];

    if ($confirmPassword != $password) {
        echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
        exit();
    }

    $id = $_SESSION['ID'];
    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT admin_image FROM admins WHERE id = $id;"));
    $query = "UPDATE admins SET admin_name='$name', admin_email='$email', admin_pwd='$password' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        if (!empty($img["name"])) {
            $img_file_name = uniqid("admin_", true) . "." . pathinfo($img["name"], PATHINFO_EXTENSION);
            $query = "UPDATE admins SET admin_image = '$img_file_name' WHERE id = $id;";
            
            if (mysqli_query($conn, $query)) {
                move_uploaded_file($img["tmp_name"], "../Image/" . $img_file_name);

                //Delete the old image only if it's NOT the default
                if (!empty($oriImgPath["admin_image"]) && $oriImgPath["admin_image"] != "no_profile_pic.png") {
                    $file = "../Image/" . $oriImgPath["admin_image"];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }

        echo "<script>alert('Admin account updated successfully'); window.location.href='../adminAccount.php';</script>";
    } else {
        echo "<script>alert('Failed to update admin account'); window.location.href='../editAdminAccount.php';</script>";
    }
}
?>
