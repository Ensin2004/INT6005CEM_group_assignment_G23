<?php
require_once "dbh.inc.php";
require_once "audit.php"; 

if (isset($_POST["submit"])) {
    $file_name = $_FILES['image']['name'];
        $tempname= $_FILES['image']['tmp_name'];
        $filesize = $_FILES['image']['size'];
        $folder = '../../Image/'.$file_name;
    $description = $_POST['Description'];

    if($_FILES["image"]["error"]=== 4){
        echo 
        "<script>alert('Image does not exist'); window.location.href='../aboutUsForm.php?';</script>";
        
    }

    $validImageExtension = ['jpg','jpeg','png'];
    $imageExtension = explode('.',$file_name);
    $imageExtension = strtolower(end($imageExtension));

    if (!in_array($imageExtension,$validImageExtension)){
        echo 
        "<script>alert('Invalid image'); window.location.href='../aboutUsForm.php?';</script>";
       
    }
    else if($filesize>1000000){
        echo 
        "<script>alert('Image size too large'); window.location.href='../aboutUsForm.php?';</script>";
       
    }
    else{
        
        $query = "INSERT INTO aboutus (about_us_image, about_us_description) VALUES ('$file_name', '$description');";
        $result = mysqli_query($conn, $query);
        if ($result) {
            // Move the uploaded file to the specified folder
            if (move_uploaded_file($tempname, $folder)) {
                audit_log(
                  $conn,
                  $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                  'content_create', 'aboutus', $newId,
                  "Created About Us entry",
                  null,
                  ['about_us_image'=>$file_name, 'about_us_description'=>$description]
                );

                echo 
                "<script>alert('File uploaded successfully'); window.location.href='../homeEdit.php?';</script>";
                
            } else {
                audit_log(
                  $conn,
                  $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                  'content_create', 'aboutus', $newId,
                  "About Us DB row created but file move failed",
                  null,
                  ['about_us_image'=>$file_name, 'about_us_description'=>$description],
                  'partial'
                );

                echo 
                "<script>alert('File uploaded successfully but failed to move to destination folder'); window.location.href='../aboutUsForm.php?';</script>";
               
            }
        } 
    }
    



} 

              








