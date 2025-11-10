<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Edit Admin Account</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/editAccount.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <?php
        include 'includes/dbh.inc.php';
        $id = $_SESSION['ID'];
        $result = mysqli_query($conn, "SELECT * FROM admins WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
        $image = '../Image/no_img_customer.png';
        if (!empty($row['admin_image'])) {
            $image = 'image/' . $row['admin_image'];
        }
        ?>

        <div class="accDisplay">
            <form class="accBox" action="includes/updateAdminAccount.php" method="post" enctype="multipart/form-data">
                <div class="signUpLogo">
                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="admin_preview">
                        <label class="label" for="adminimg"></label>
                        <input class="imageInput" type="file" id="adminimg" name="adminimg" accept=".jpg, .jpeg, .png" onchange="showPreview(event, 'admin_preview', '<?php echo $image; ?>');">
                    </div>
                </div>

                <div class="accInfo">
                    <label for="adminName">Name :</label>
                    <input required type="text" id="adminName" name="newAdminName" value="<?php echo $row['admin_name'] ?>">

                    <label for="adminEmail">Email :</label>
                    <input required type="email" id="adminEmail" name="newAdminEmail" value="<?php echo $row['admin_email'] ?>" readonly>

                    <label for="adminPassword">Password :</label>
                    <input required type="password" id="adminPassword" name="newAdminPassword" value="<?php echo $row['admin_pwd'] ?>">

                    <label for="confirmPassword">Confirm Password :</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" value="<?php echo $row['admin_pwd'] ?>">

                    <button class="acc" type="submit">Update</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function showPreview(event, previewId, originalSrc) {
            if (event.target.files.length > 0) {
                var file = event.target.files[0];
                if (file.size <= 1024 * 1024) {
                    var preview = document.getElementById(previewId);
                    preview.src = URL.createObjectURL(file);
                } else {
                    alert("Image size exceeds the limit (1MB)");
                    event.target.value = "";
                    document.getElementById(previewId).src = originalSrc;
                }
            }
        }
    </script>
</body>

</html>
