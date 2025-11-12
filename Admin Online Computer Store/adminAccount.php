<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Admin Account</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/accountPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="accDisplay">
            <div class="accBox">
                <!-- Welcome box -->
                <div class="FirstAccBox">
                    <p>My Admin Account</p>
                </div>

                <!-- content box -->
                <div class="MainAccBox">
                    <div class="accImage">
                        <?php
                        require_once "includes/dbh.inc.php";
                        $id = $_SESSION['ID'];
                        $query = mysqli_query($conn, "SELECT admin_image FROM admins WHERE id = '$id'");
                        $row = mysqli_fetch_assoc($query);
                        ?>
                        <img class="accountImg" src="image/<?php echo $row['admin_image'] ?? 'no_profile_pic.png'; ?>?t=<?php echo time(); ?>">
                    </div>

                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM admins WHERE id = '$id'");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="showAccountDetails">
                        <div style="margin-bottom: 5px;">
                            <label for="adminName">Name :</label>
                            <input required type="text" id="adminName" value="<?php echo $row['admin_name'] ?>" readonly>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label for="adminEmail">Email :</label>
                            <input required type="email" id="adminEmail" value="<?php echo $row['admin_email'] ?>" readonly>
                        </div>
                        <div class="buttons">
                            <a class="editButton" href="editAdminAccount.php">EDIT</a>
                            <a class="logOutButton" href="includes/logoutAccount.php">LOG OUT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>
