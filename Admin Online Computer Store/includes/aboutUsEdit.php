<?php
require_once "dbh.inc.php";
require_once "audit.php";

if (isset($_POST["submit"])) {
    $file_name = $_FILES['image']['name'];
        $tempname= $_FILES['image']['tmp_name'];
        $filesize = $_FILES['image']['size'];
        $folder = '../../Image/'.$file_name;
    $description = $_POST['Description'];
    $aboutusid = $_POST['aboutUsID'];

    $before = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT about_us_image, about_us_description FROM aboutus WHERE about_us_id = '".intval($aboutusid)."'"
    ));

    // if image dont change in the update form
    if($_FILES["image"]["error"]=== 4){
        
       $query = "UPDATE aboutus SET about_us_description = '$description' WHERE about_us_id = '$aboutusid'";
       $result = mysqli_query($conn, $query);

       if ($result) {
           $after = $before; $after['about_us_description'] = $description;
           audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                     'content_update', 'aboutus', (int)$aboutusid,
                     "Updated About Us description", $before, $after);
       }

       echo
       "<script>alert('Description updated successfully'); window.location.href='../homeEdit.php';</script>";
     
    }

    $validImageExtension = ['jpg','jpeg','png'];
    $imageExtension = explode('.',$file_name);
    $imageExtension = strtolower(end($imageExtension));

    if (!in_array($imageExtension,$validImageExtension)){
        echo 
        "<script> alert('Invalid image ');window.location.href='../editAboutUsForm.php?id=$aboutusid';</script>";
        
    }
    else if($filesize>1000000){
        echo "<script>alert('Image size too large'); window.location.href='../editAboutUsForm.php?id=$aboutusid';</script>";
    }
    else{
        
        $query = "UPDATE aboutus SET about_us_image ='$file_name', about_us_description='$description' WHERE about_us_id = '$aboutusid';";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            // Move the uploaded file to the specified folder
            if (move_uploaded_file($tempname, $folder)) {

                $after = ['about_us_image'=>$file_name,'about_us_description'=>$description];
                audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                          'content_update','aboutus',(int)$aboutusid,
                          "Updated About Us image & description", $before, $after);
                echo "<script>alert('File uploaded successfully');window.location.href='../homeEdit.php';</script>";
                
            } else {
                $after = ['about_us_image'=>$file_name,'about_us_description'=>$description];
                audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                          'content_update','aboutus',(int)$aboutusid,
                          "DB updated but image move failed", $before, $after, 'partial');
                echo "<script>alert('File uploaded successfully but failed to move to destination folder');window.location.href='../editAboutUsForm.php?id=$aboutusid';</script>";
 
            }
        } 
    }
   

} 
