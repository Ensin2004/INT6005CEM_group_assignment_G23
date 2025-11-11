<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Item Details</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/itemDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    require_once 'includes/dbh.inc.php';
    if (!$conn) {
        die("Database connection failed");
    } else {
        $itemID = $_GET["item"];
        $itemResult = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM items WHERE id = $itemID;"));
    }
    ?>

    <main>
        <div class="item_removed">
            <?php
            if ($itemResult["item_status"] == "Deleted") {
                echo '<p class="removed_msg">Item has been removed from store</p>';
            }
            ?>
        </div>
        <div class="item_form">
            <div class="item_image">
                <div class="first_image">
                    <div class="img_zoom_container">
                        <img class="img_zoom" id="img_zoom">
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemResult["image1"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img_customer.png" id="img1_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemResult["image1"] . '" id="img1_preview">';
                        }
                        ?>
                    </div>
                </div>
                <div class="remaining_image">
                    <div class="img_container">
                        <?php
                        if ($itemResult["image2"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img_customer.png" id="img2_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemResult["image2"] . '" id="img2_preview">';
                        }
                        ?>
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemResult["image3"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img_customer.png" id="img3_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemResult["image3"] . '" id="img3_preview">';
                        }
                        ?>
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemResult["image4"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img_customer.png" id="img4_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemResult["image4"] . '" id="img4_preview">';
                        }
                        ?>
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemResult["image5"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img_customer.png" id="img5_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemResult["image5"] . '" id="img5_preview">';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="item_details">
                <p class="item_name"><?php echo $itemResult["item_name"]; ?></p>
                <br>
                <p class="item_desc"><?php echo $itemResult["description"]; ?></p>
                <br><br>
                <hr><br><br>
                <p class="item_price"><?php echo 'RM' . $itemResult["price"]; ?></p>
                <br>
                <?php
                if ($itemResult["stock_qty"] == 0) {
                    echo '<p class="item_stock" style="color: rgb(255, 52, 52);">Stock: ' . $itemResult["stock_qty"] . '</p>';
                } else {
                    echo '<p class="item_stock" style="color: rgb(60, 179, 113);">Stock: ' . $itemResult["stock_qty"] . '</p>';
                }
                ?>
            </div>
            <?php
            if ($itemResult["item_status"] == "Active") {
                echo '<a class="edit_button" href="editItem.php?item=' . $itemResult["id"] . '">EDIT</a>';
            }
            ?>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const originalImage = document.querySelectorAll(".remaining_image > .img_container");
            const zoomContainer = document.querySelector(".img_zoom_container");
            const zoomImage = document.getElementById("img_zoom");

            originalImage.forEach(originalImage => {
                originalImage.addEventListener("mouseenter", function() {
                    const src = originalImage.querySelector(".img_preview").getAttribute("src");
                    zoomImage.src = src;
                    zoomContainer.style.display = "flex";
                });

                originalImage.addEventListener("mouseleave", function() {
                    zoomContainer.style.display = "none";
                    zoomImage.src = "";
                });
            });
        });
    </script>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>