<?php
require_once "dbh.inc.php";
require_once "csrf.php";

//Debugging: Check if the database connection is successful
if (!$conn) {
    die("Database connection failed");
} else {
    if (isset($_POST["submit"])) {

        // Check CSRF token
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
        }

        //Collect form data
        $itemID = htmlspecialchars($_POST["itemID"]);
        $category = htmlspecialchars($_POST["category"]);
        $name = htmlspecialchars($_POST["name"]);
        $price = htmlspecialchars($_POST["price"]);
        $stock = htmlspecialchars($_POST["stock"]);
        $description = htmlspecialchars($_POST["description"]);
        $img1 = $_FILES["image1"];
        $img2 = $_FILES["image2"];
        $img3 = $_FILES["image3"];
        $img4 = $_FILES["image4"];
        $img5 = $_FILES["image5"];

        //Get the original image path (for deletion purpose)
        $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image1, image2, image3, image4, image5 FROM items WHERE id = $itemID;"));

        //Query to update details only
        $query = "UPDATE items SET category_id = $category, item_name = '$name', price = $price, stock_qty = $stock, description = '$description' WHERE id = $itemID;";

        //Update database (details only)
        if (mysqli_query($conn, $query)) {

            //Update image if got changes
            if (!empty($img1["name"])) {
                $img1_file_name = uniqid("", true) . "." . pathinfo($img1["name"], PATHINFO_EXTENSION);
                $query = "UPDATE items SET image1 = '$img1_file_name' WHERE id = $itemID;";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($img1["tmp_name"], "../../Image/" . $img1_file_name);
                    if ($oriImgPath["image1"] != "") {
                        $file = "../../Image/" . $oriImgPath["image1"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
                }
            }
            if (!empty($img2["name"])) {
                $img2_file_name = uniqid("", true) . "." . pathinfo($img2["name"], PATHINFO_EXTENSION);
                $query = "UPDATE items SET image2 = '$img2_file_name' WHERE id = $itemID;";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($img2["tmp_name"], "../../Image/" . $img2_file_name);
                    if ($oriImgPath["image2"] != "") {
                        $file = "../../Image/" . $oriImgPath["image2"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
                }
            }
            if (!empty($img3["name"])) {
                $img3_file_name = uniqid("", true) . "." . pathinfo($img3["name"], PATHINFO_EXTENSION);
                $query = "UPDATE items SET image3 = '$img3_file_name' WHERE id = $itemID;";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($img3["tmp_name"], "../../Image/" . $img3_file_name);
                    if ($oriImgPath["image3"] != "") {
                        $file = "../../Image/" . $oriImgPath["image3"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
                }
            }
            if (!empty($img4["name"])) {
                $img4_file_name = uniqid("", true) . "." . pathinfo($img4["name"], PATHINFO_EXTENSION);
                $query = "UPDATE items SET image4 = '$img4_file_name' WHERE id = $itemID;";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($img4["tmp_name"], "../../Image/" . $img4_file_name);
                    if ($oriImgPath["image4"] != "") {
                        $file = "../../Image/" . $oriImgPath["image4"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
                }
            }
            if (!empty($img5["name"])) {
                $img5_file_name = uniqid("", true) . "." . pathinfo($img5["name"], PATHINFO_EXTENSION);
                $query = "UPDATE items SET image5 = '$img5_file_name' WHERE id = $itemID;";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($img5["tmp_name"], "../../Image/" . $img5_file_name);
                    if ($oriImgPath["image5"] != "") {
                        $file = "../../Image/" . $oriImgPath["image5"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
                }
            }
            echo "<script>alert('Item updated successfully'); window.history.go(-2);</script>";
        } else {
            echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
        }
    } else {
        echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
    }
}
