<?php
require_once "includes/security.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    echo "<script>alert('Access denied. Super Admins only.'); window.location.href='home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KAH TECH Admin - Add Manager</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/addManager.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <form class="item_form" action="includes/addManager.inc.php" method="POST">
            <div class="item_details">
                <div class="details">
                    <label for="admin_name">Name</label>
                    <p>:</p>
                    <input type="text" id="admin_name" name="admin_name" maxlength="255" required placeholder="Manager Username">
                </div>

                <div class="details">
                    <label for="admin_email">Email</label>
                    <p>:</p>
                    <input type="email" id="admin_email" name="admin_email" maxlength="255" required placeholder="Manager Email">
                </div>

                <div class="details">
                    <label for="admin_pwd">Password</label>
                    <p>:</p>
                    <input type="password" id="admin_pwd" name="admin_pwd" maxlength="255" required placeholder="Enter password">
                </div>
            </div>

            <button class="submit_button" type="submit" name="addManager">ADD MANAGER</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/sessionTimeout.js"></script>
</body>
</html>
