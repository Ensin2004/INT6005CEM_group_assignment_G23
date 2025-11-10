<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Edit Item</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/newItem_editItem.css">
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
        $itemEditing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT items.id, items.item_name, items.price, items.stock_qty, items.description, items.image1, items.image2, items.image3, items.image4, items.image5, items.category_id, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE items.id = $itemID;"));
        $categoryResult = mysqli_query($conn, "SELECT * FROM categories;");
    }
    ?>

    <main>
        <form class="item_form" action="includes/editItemDetails.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
            <input type="hidden" name="itemID" value="<?php echo $itemEditing['id']; ?>">
            <div class="item_image">
                <div class="first_image">
                    <div class="img_zoom_container">
                        <img class="img_zoom" id="img_zoom">
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemEditing["image1"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img.png" id="img1_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemEditing["image1"] . '" id="img1_preview">';
                        }
                        ?>
                        <label for="image1"></label>
                        <input type="file" id="image1" name="image1" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event);">
                    </div>
                </div>
                <div class="remaining_image">
                    <div class="img_container">
                        <?php
                        if ($itemEditing["image2"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img.png" id="img2_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemEditing["image2"] . '" id="img2_preview">';
                        }
                        ?>
                        <label for="image2"></label>
                        <input type="file" id="image2" name="image2" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event);">
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemEditing["image3"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img.png" id="img3_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemEditing["image3"] . '" id="img3_preview">';
                        }
                        ?>
                        <label for="image3"></label>
                        <input type="file" id="image3" name="image3" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event);">
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemEditing["image4"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img.png" id="img4_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemEditing["image4"] . '" id="img4_preview">';
                        }
                        ?>
                        <label for="image4"></label>
                        <input type="file" id="image4" name="image4" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event);">
                    </div>
                    <div class="img_container">
                        <?php
                        if ($itemEditing["image5"] == "") {
                            echo '<img class="img_preview" src="../Image/no_img.png" id="img5_preview">';
                        } else {
                            echo '<img class="img_preview" src="../Image/' . $itemEditing["image5"] . '" id="img5_preview">';
                        }
                        ?>
                        <label for="image5"></label>
                        <input type="file" id="image5" name="image5" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="showPreview(event);">
                    </div>
                </div>
            </div>
            <div class="item_details">
                <div class="details">
                    <label for="name">Item Name</label>
                    <p>:</p>
                    <input required type="text" id="name" name="name" maxlength="255" placeholder="e.g. Logitech Mouse..." value="<?php echo $itemEditing['item_name'] ?>">
                </div>
                <div class="details">
                    <label for="description">Description</label>
                    <p>:</p>
                    <textarea required id="description" name="description" placeholder="e.g. Logitech Mouse is a mouse..."><?php echo $itemEditing['description']; ?></textarea>
                </div>
                <div class="details">
                    <label for="category">Category</label>
                    <p>:</p>
                    <select required id="category" name="category">
                        <option value="<?php echo $itemEditing['category_id']; ?>"><?php echo $itemEditing['category_name']; ?></option>
                        <?php
                        while ($row = mysqli_fetch_assoc($categoryResult)) {
                            if ($row["id"] != $itemEditing["category_id"]) {
                                echo '<option value="' . $row["id"] . '">' . $row["category_name"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="details">
                    <label for="price">Price (RM)</label>
                    <p>:</p>
                    <input required type="number" id="price" name="price" step="0.01" min="0" placeholder="e.g. 99.90" value="<?php echo $itemEditing['price'] ?>">
                </div>
                <div class="details">
                    <label for="stock">Stock</label>
                    <p>:</p>
                    <input required type="number" id="stock" name="stock" step="1" min="0" placeholder="e.g. 99" value="<?php echo $itemEditing['stock_qty'] ?>">
                </div>
            </div>
            <button class="submit_button" type="submit" name="submit">SAVE</button>
        </form>
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

        function showPreview(event) {
            if (event.target.files.length > 0) {
                var file = event.target.files[0];
                if (file.size <= 1024 * 1024) {
                    var src = URL.createObjectURL(event.target.files[0]);
                    if (event.target.id == "image1") {
                        document.getElementById("img1_preview").src = src;
                    } else if (event.target.id == "image2") {
                        document.getElementById("img2_preview").src = src;
                    } else if (event.target.id == "image3") {
                        document.getElementById("img3_preview").src = src;
                    } else if (event.target.id == "image4") {
                        document.getElementById("img4_preview").src = src;
                    } else {
                        document.getElementById("img5_preview").src = src;
                    }
                } else {
                    alert("Image size exceed the limit (1MB)");
                    event.target.value = "";
                    if (event.target.id == "image1") {
                        document.getElementById("img1_preview").src = "../Image/no_img.png";
                    } else if (event.target.id == "image2") {
                        document.getElementById("img2_preview").src = "../Image/no_img.png";
                    } else if (event.target.id == "image3") {
                        document.getElementById("img3_preview").src = "../Image/no_img.png";
                    } else if (event.target.id == "image4") {
                        document.getElementById("img4_preview").src = "../Image/no_img.png";
                    } else {
                        document.getElementById("img5_preview").src = "../Image/no_img.png";
                    }
                }
            }
        }

        function validateForm() {
            var nameInput = document.getElementById("name").value.trim();
            var descInput = document.getElementById("description").value.trim();
            if (nameInput == "") {
                alert('Item Name is empty!');
                return false;
            } else if (descInput == "") {
                alert('Description is empty!');
                return false;
            } else {
                return true;
            }
        }
    </script>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>