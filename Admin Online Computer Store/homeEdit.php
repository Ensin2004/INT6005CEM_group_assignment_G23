<?php 
session_start(); 
require_once "includes/csrf.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/homeEdit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <?php include 'includes/dbh.inc.php' ?>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM homepage");
        $row = mysqli_fetch_assoc($result);
        ?>

        <div class="wholeFormContainer">
            <form action="includes/editHomePage.php" method="post" enctype="multipart/form-data">
                <?php createCSRFInput(); ?>
                <div class="imageCardContainer">
                    <div>
                        <p class="welcomeHome">Home Motto</p>
                    </div>
                    <div class="img_container">
                        <img class="img_preview" src="../Image/<?php echo $row['image1'] ?>" id="img1_preview">
                        <label class="label" for="image1"></label>
                        <input class="imageInput" type="file" id="image1" name="image1" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img1_preview', '../Image/<?php echo $row['image1'] ?>');">
                    </div>
                    <div class="img_container">
                        <img class="img_preview" src="../Image/<?php echo $row['image2'] ?>" id="img2_preview">
                        <label class="label" for="image2"></label>
                        <input class="imageInput" type="file" id="image2" name="image2" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img2_preview', '../Image/<?php echo $row['image2'] ?>');">
                    </div>
                    <div class="img_container">
                        <img class="img_preview" src="../Image/<?php echo $row['image3'] ?>" id="img3_preview">
                        <label class="label" for="image3"></label>
                        <input class="imageInput" type="file" id="image3" name="image3" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img3_preview', '../Image/<?php echo $row['image3'] ?>');">
                    </div>
                    <div class="img_container">
                        <img class="img_preview" src="../Image/<?php echo $row['image4'] ?>" id="img4_preview">
                        <label class="label" for="image4"></label>
                        <input class="imageInput" type="file" id="image4" name="image4" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img4_preview', '../Image/<?php echo $row['image4'] ?>');">
                    </div>
                </div>

                <div class="aboutUsContainer">
                    <p class="aboutUsTittle">About Us</p>
                    <div class="aboutUsDescription">
                        <label for="Description">Description :</label><br>
                        <textarea id="Description" class="input" name="aboutUs" maxlength="1500"><?php echo $row['aboutus_description'] ?></textarea>
                        <div class="AboutUs_img_container">
                            <img class="AboutUs_img_preview" src="../Image/<?php echo $row['image5'] ?>" id="img5_preview">
                            <label class="label" for="image5"></label>
                            <input class="imageInput" type="file" id="image5" name="image5" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img5_preview', '../Image/<?php echo $row['image5'] ?>');">
                        </div>
                    </div>
                </div>

                <div class="aboutUsContainer">
                    <p class="aboutUsTittle">Our Mission</p>
                    <div class="aboutUsDescription">
                        <label for="MissionDescription">Description :</label><br>
                        <textarea id="MissionDescription" class="input" name="mission" maxlength="1500"><?php echo $row['mission_description'] ?></textarea>
                        <div class="AboutUs_img_container">
                            <img class="AboutUs_img_preview" src="../Image/<?php echo $row['image6'] ?>" id="img6_preview">
                            <label class="label" for="image6"></label>
                            <input class="imageInput" type="file" id="image6" name="image6" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img6_preview', '../Image/<?php echo $row['image6'] ?>');">
                        </div>
                    </div>
                </div>

                <div class="WWDContainer">
                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image7'] ?>" id="img7_preview">
                            <label class="label" for="image7"></label>
                            <input class="imageInput" type="file" id="image7" name="image7" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img7_preview', '../Image/<?php echo $row['image7'] ?>');">
                        </div>
                        <div class="WWD_DES">
                            <label>Description: </label><br>
                            <textarea class="WWD_DES_Input" name="WWD1" maxlength="255"><?php echo $row['whatwedo1'] ?></textarea>
                        </div>
                    </div>

                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image8'] ?>" id="img8_preview">
                            <label class="label" for="image8"></label>
                            <input class="imageInput" type="file" id="image8" name="image8" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img8_preview', '../Image/<?php echo $row['image8'] ?>');">
                        </div>
                        <div class="WWD_DES">
                            <label>Description: </label><br>
                            <textarea class="WWD_DES_Input" name="WWD2" maxlength="255"><?php echo $row['whatwedo2'] ?></textarea>
                        </div>
                    </div>

                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image9'] ?>" id="img9_preview">
                            <label class="label" for="image9"></label>
                            <input class="imageInput" type="file" id="image9" name="image9" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img9_preview', '../Image/<?php echo $row['image9'] ?>');">
                        </div>
                        <div class="WWD_DES">
                            <label>Description: </label><br>
                            <textarea class="WWD_DES_Input" name="WWD3" maxlength="255"><?php echo $row['whatwedo3'] ?></textarea>
                        </div>
                    </div>

                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image10'] ?>" id="img10_preview">
                            <label class="label" for="image10"></label>
                            <input class="imageInput" type="file" id="image10" name="image10" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event, 'img10_preview', '../Image/<?php echo $row['image10'] ?>');">
                        </div>
                        <div class="WWD_DES">
                            <label>Description: </label><br>
                            <textarea class="WWD_DES_Input" name="WWD4" maxlength="255"><?php echo $row['whatwedo4'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="submit">
                        <i class="fa-solid fa-floppy-disk"></i> Save
                    </button>
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

        document.addEventListener('DOMContentLoaded', function() {
            const submitButton = document.querySelector('.submit');
            const footer = document.querySelector('footer');
            const offset = 0; // Offset from the bottom

            function checkPosition() {
                const footerRect = footer.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                if (footerRect.top < windowHeight) {
                    // Footer is in view, move button above footer
                    submitButton.style.transform = `translateY(${footerRect.top - windowHeight + offset}px)`;
                } else {
                    // Footer is not in view, fix button at bottom
                    submitButton.style.transform = 'translateY(0)';
                }
            }

            window.addEventListener('scroll', checkPosition);
            window.addEventListener('resize', checkPosition);
            checkPosition();
        });
    </script>
</body>

</html>