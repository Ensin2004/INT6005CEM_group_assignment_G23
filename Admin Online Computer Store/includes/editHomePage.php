<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "audit.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check CSRF token
    if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
        die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
    }
    
    $aboutUsDes = htmlspecialchars($_POST["aboutUs"]);
    $missionDes = htmlspecialchars($_POST["mission"]);
    $WWD1 = htmlspecialchars($_POST["WWD1"]);
    $WWD2 = htmlspecialchars($_POST["WWD2"]);
    $WWD3 = htmlspecialchars($_POST["WWD3"]);
    $WWD4 = htmlspecialchars($_POST["WWD4"]);

    
    $img1 = $_FILES["image1"];
    $img2 = $_FILES["image2"];
    $img3 = $_FILES["image3"];
    $img4 = $_FILES["image4"];
    $img5 = $_FILES["image5"];
    $img6 = $_FILES["image6"];
    $img7 = $_FILES["image7"];
    $img8 = $_FILES["image8"];
    $img9 = $_FILES["image9"];
    $img10 = $_FILES["image10"];

    $before = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT aboutus_description, mission_description, whatwedo1, whatwedo2, whatwedo3, whatwedo4,
               image1,image2,image3,image4,image5,image6,image7,image8,image9,image10
        FROM homepage WHERE id = 1
    "));

    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image1, image2, image3, image4, image5, image6, image7, image8, image9, image10 FROM homepage WHERE id = 1"));

    $query = "UPDATE homepage SET aboutus_description = '$aboutUsDes', mission_description = '$missionDes', whatwedo1 = '$WWD1', whatwedo2 = '$WWD2', whatwedo3 = '$WWD3', whatwedo4 = '$WWD4' WHERE id = 1";

    if (mysqli_query($conn, $query)) {
        $updatedSuccessfully = true;

        // Collect AFTER state incrementally
        $after = $before;
        $after['aboutus_description'] = $aboutUsDes;
        $after['mission_description'] = $missionDes;
        $after['whatwedo1'] = $WWD1;
        $after['whatwedo2'] = $WWD2;
        $after['whatwedo3'] = $WWD3;
        $after['whatwedo4'] = $WWD4;

        // Function to handle image upload and update
        function handleImageUpload($file, $columnName, $originalImagePath, &$after) {
            global $conn, $updatedSuccessfully;
            if (!empty($file["name"])) {
                $newFileName = uniqid("", true) . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
                $query = "UPDATE homepage SET $columnName = '$newFileName' WHERE id = 1";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($file["tmp_name"], "../../Image/" . $newFileName);
                    if (!empty($originalImagePath) && file_exists("../../Image/" . $originalImagePath)) {
                        @unlink("../../Image/" . $originalImagePath);
                    }
                    $after[$columnName] = $newFileName; // <-- record AFTER
                } else {
                    $updatedSuccessfully = false;
                }
            }
        }


        $ori = $before; // original images
        handleImageUpload($img1, "image1", $ori["image1"], $after);
        handleImageUpload($img2, "image2", $ori["image2"], $after);
        handleImageUpload($img3, "image3", $ori["image3"], $after);
        handleImageUpload($img4, "image4", $ori["image4"], $after);
        handleImageUpload($img5, "image5", $ori["image5"], $after);
        handleImageUpload($img6, "image6", $ori["image6"], $after);
        handleImageUpload($img7, "image7", $ori["image7"], $after);
        handleImageUpload($img8, "image8", $ori["image8"], $after);
        handleImageUpload($img9, "image9", $ori["image9"], $after);
        handleImageUpload($img10,"image10",$ori["image10"],$after);

        audit_log(
            $conn,
            $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
            'content_update', 'homepage', 1,
            $updatedSuccessfully ? 'Updated homepage content & images'
                                 : 'Homepage updated with some image failures',
            $before, $after,
            $updatedSuccessfully ? null : 'partial'
        );


        if ($updatedSuccessfully) {
            echo "<script>alert('Item updated successfully'); window.location.href='../userHome.php';</script>";
        } else {
            echo "<script>alert('Update unsuccessful'); window.location.href='../userHome.php';</script>";
        }
    } else {
        echo "<script>alert('Update unsuccessful'); window.location.href='../homeEdit.php';</script>";
    }
} else {
    echo "<script>alert('Update unsuccessful'); window.location.href='../homeEdit.php';</script>";
}
?>