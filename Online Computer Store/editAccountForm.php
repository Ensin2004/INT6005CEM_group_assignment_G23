<?php
require_once "includes/security.php";
require_once "includes/csrf.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Account</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/editAccount.css">
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <main>
        <?php
        include 'includes/dbh.inc.php';
        $id = $_SESSION['ID'];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
        $image = '../Image/no_img_customer.png'; 
        if (!empty($row['user_image'])) {
            $image = 'image/'.$row['user_image']; 
        }
        ?>

        <!-- Biggest box in body to set background -->
        <div class="accDisplay">
            <!-- Set up sign up content -->
            <form class="accBox" action="includes/updateUserAccount.php" method="post" enctype="multipart/form-data">
                <?php createCSRFInput(); ?>
                <div class="signUpLogo">
                    
                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="acc_preview">
                        <label class="label" for="accountimg"></label>
                        <input class="imageInput" type="file" id="accountimg" name="accountimg" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'acc_preview', '<?php echo $image; ?>');">
                    </div>
                </div>
                <div class="accInfo">
                    <label for="Username">Name :</label>
                    <input required type="text" id="Username" name="newUsername" value="<?php echo $row['user_name'] ?>">
                    
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="newEmail" value="<?php echo $row['email'] ?>" readonly>
                    
                    <label for="SecondaryEmail">Secondary Email (Optional) :</label>
                    <div class="secondaryEmail">
                        <input required type="email" id="SecondaryEmail" name="secondaryEmail" value="<?php echo $row['secondary_email'] ?>" readonly>
                        <a href="secondaryEmailPage.php" class="editBtn" id="editSecondary">Edit</a>
                    </div>
                    
                    <label for=" Phone">Phone Number :</label>
                    <input required type="text" id="Phone" name="newPhone" value="<?php echo $row['phone'] ?>">
                    
                    <label for="Address">Address :</label>
                    <input required type="text" id="Address" name="newAddress" value="<?php echo $row['user_address'] ?>" >
                    
                    <label for="newPassword">New Password :</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
                    
                    <label for="confirmPassword"> Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter new password">
                    
                    <button class="acc" type="submit">Update</button>


                </div>
            </form>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>
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