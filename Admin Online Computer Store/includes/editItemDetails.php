<?php
require_once "dbh.inc.php";
require_once "csrf.php";

/**
 * Generic error handler for this page
 * - Logs the error
 * - Sends ERROR 500 status
 * - Redirects to unified ERROR 500 page (no sensitive info to user)
 */
function handleErrorAndExit($message = 'Unexpected error during login.') {
    error_log('[ACCOUNT UPDATE ERROR] ' . $message);
    http_response_code(500);
    header("Location: ../errors/500.php");
    exit;
}

try {

    // Only handle proper form submissions
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        // No POST → send back to store (or another safe page)
        header("Location: ../store.php");
        exit;
    }

    // Check DB connection
    if (!$conn) {
        handleErrorAndExit("Database connection failed.");
    }

    if (isset($_POST["submit"])) {

        // Check CSRF token (user-level error)
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            echo "<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>";
            exit;
        }

        // Collect form data
        $itemID      = htmlspecialchars($_POST["itemID"]);
        $category    = htmlspecialchars(trim($_POST["category"]));
        $name        = htmlspecialchars(trim($_POST["name"]));
        $price       = htmlspecialchars(trim($_POST["price"]));
        $stock       = htmlspecialchars(trim($_POST["stock"]));
        $description = htmlspecialchars(trim($_POST["description"]));
        $img1        = $_FILES["image1"];
        $img2        = $_FILES["image2"];
        $img3        = $_FILES["image3"];
        $img4        = $_FILES["image4"];
        $img5        = $_FILES["image5"];

        // Get the original image path (for deletion purpose)
        $oriResult = mysqli_query(
            $conn,
            "SELECT image1, image2, image3, image4, image5 FROM items WHERE id = $itemID;"
        );
        if (!$oriResult) {
            handleErrorAndExit("Failed to fetch original item images.");
        }
        $oriImgPath = mysqli_fetch_assoc($oriResult);

        // Query to update details only
        $query = "UPDATE items SET category_id = $category, item_name = '$name', price = $price, stock_qty = $stock, description = '$description' WHERE id = $itemID;";

        // Update database (details only)
        $updateDetails = mysqli_query($conn, $query);

        if ($updateDetails) {

            // Update image1 if changed
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
                    exit;
                }
            }

            // Update image2 if changed
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
                    exit;
                }
            }

            // Update image3 if changed
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
                    exit;
                }
            }

            // Update image4 if changed
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
                    exit;
                }
            }

            // Update image5 if changed
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
                    exit;
                }
            }

            echo "<script>alert('Item updated successfully'); window.history.go(-2);</script>";
            exit;

        } else {
            echo "<script>alert('Item updated unsuccessful'); window.location.href='../editItem.php?item=" . $itemID . "';</script>";
            exit;
        }

    } else {
        // submit not set → same message but no itemID (safer redirect)
        echo "<script>alert('Item updated unsuccessful'); window.location.href='../store.php';</script>";
        exit;
    }

} catch (Throwable $e) {
    // Any unexpected PHP / mysqli error
    handleErrorAndExit($e->getMessage());
}
