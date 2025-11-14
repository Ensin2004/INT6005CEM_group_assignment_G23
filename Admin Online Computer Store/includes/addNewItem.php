<?php
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "audit.php";

// Debugging: Check if the database connection is successful
if (!$conn) {
    die("Database connection failed");
} else {
    if (isset($_POST["submit"])) {

        // Check CSRF token
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
        }

        // Collect form data
        $category = htmlspecialchars(trim($_POST["category"]));
        $name = htmlspecialchars(trim($_POST["name"]));
        $price = htmlspecialchars(trim($_POST["price"]));
        $stock = htmlspecialchars(trim($_POST["stock"]));
        $description = htmlspecialchars(trim($_POST["description"]));
        $img1 = $_FILES["image1"];
        $img2 = $_FILES["image2"];
        $img3 = $_FILES["image3"];
        $img4 = $_FILES["image4"];
        $img5 = $_FILES["image5"];

        //New file name (unique)
        if (!empty($img1["name"])) {
            $img1_file_name = uniqid("", true) . "." . pathinfo($img1["name"], PATHINFO_EXTENSION);
        }
        if (!empty($img2["name"])) {
            $img2_file_name = uniqid("", true) . "." . pathinfo($img2["name"], PATHINFO_EXTENSION);
        }
        if (!empty($img3["name"])) {
            $img3_file_name = uniqid("", true) . "." . pathinfo($img3["name"], PATHINFO_EXTENSION);
        }
        if (!empty($img4["name"])) {
            $img4_file_name = uniqid("", true) . "." . pathinfo($img4["name"], PATHINFO_EXTENSION);
        }
        if (!empty($img5["name"])) {
            $img5_file_name = uniqid("", true) . "." . pathinfo($img5["name"], PATHINFO_EXTENSION);
        }

        //Query
        $query = "INSERT INTO items (category_id, item_name, price, stock_qty, description, image1, image2, image3, image4, image5) VALUES ($category, '$name', $price, $stock, '$description', '$img1_file_name', '$img2_file_name', '$img3_file_name', '$img4_file_name', '$img5_file_name');";

        //Insert into database and image folder
        if (mysqli_query($conn, $query)) {
            move_uploaded_file($img1["tmp_name"], "../../Image/" . $img1_file_name);
            move_uploaded_file($img2["tmp_name"], "../../Image/" . $img2_file_name);
            move_uploaded_file($img3["tmp_name"], "../../Image/" . $img3_file_name);
            move_uploaded_file($img4["tmp_name"], "../../Image/" . $img4_file_name);
            move_uploaded_file($img5["tmp_name"], "../../Image/" . $img5_file_name);

            $after = [
            'category_id'=>$category,'item_name'=>$name,'price'=>$price,'stock_qty'=>$stock,
            'description'=>$description,
            'images'=>[$img1_file_name ?? null,$img2_file_name ?? null,$img3_file_name ?? null,$img4_file_name ?? null,$img5_file_name ?? null]
            ];

            audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
              'item_create','items',$conn->insert_id,
              "Created item '{$name}'", null, $after);

            echo "<script>alert('Item added successfully'); window.location.href='../store.php';</script>";
        } else {
            echo "<script>alert('Item added unsuccessful'); window.location.href='../newItem.php';</script>";
        }
    } else {
        echo "<script>alert('Item added unsuccessful'); window.location.href='../newItem.php';</script>";
    }
}
