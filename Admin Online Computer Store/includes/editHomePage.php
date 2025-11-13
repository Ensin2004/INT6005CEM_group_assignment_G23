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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
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

    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image1, image2, image3, image4, image5, image6, image7, image8, image9, image10 FROM homepage WHERE id = 1"));

    $query = "UPDATE homepage SET aboutus_description = '$aboutUsDes', mission_description = '$missionDes', whatwedo1 = '$WWD1', whatwedo2 = '$WWD2', whatwedo3 = '$WWD3', whatwedo4 = '$WWD4' WHERE id = 1";

    if (mysqli_query($conn, $query)) {
        $updatedSuccessfully = true;

        // Function to handle image upload and update
        function handleImageUpload($file, $columnName, $originalImagePath) {
            global $conn, $updatedSuccessfully;

            if (!empty($file["name"])) {
                $newFileName = uniqid("", true) . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
                $query = "UPDATE homepage SET $columnName = '$newFileName' WHERE id = 1";
                if (mysqli_query($conn, $query)) {
                    move_uploaded_file($file["tmp_name"], "../../Image/" . $newFileName);
                    if (!empty($originalImagePath) && file_exists("../../Image/" . $originalImagePath)) {
                        unlink("../../Image/" . $originalImagePath);
                    }
                } else {
                    $updatedSuccessfully = false;
                }
            }
        }

        
        handleImageUpload($img1, "image1", $oriImgPath["image1"]);
        handleImageUpload($img2, "image2", $oriImgPath["image2"]);
        handleImageUpload($img3, "image3", $oriImgPath["image3"]);
        handleImageUpload($img4, "image4", $oriImgPath["image4"]);
        handleImageUpload($img5, "image5", $oriImgPath["image5"]);
        handleImageUpload($img6, "image6", $oriImgPath["image6"]);
        handleImageUpload($img7, "image7", $oriImgPath["image7"]);
        handleImageUpload($img8, "image8", $oriImgPath["image8"]);
        handleImageUpload($img9, "image9", $oriImgPath["image9"]);
        handleImageUpload($img10, "image10", $oriImgPath["image10"]);

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