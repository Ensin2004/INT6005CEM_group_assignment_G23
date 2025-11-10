<?php
session_start();
require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_SESSION['ID'];
  $file_name = $_FILES['image']['name'];
  $tempname = $_FILES['image']['tmp_name'];
  $folder = '../image/' . $file_name;

  $query = "UPDATE admins SET admin_image = '$file_name' WHERE id = '$id'";

  if (mysqli_query($conn, $query)) {
    move_uploaded_file($tempname, $folder);
    header("location: ../adminAccount.php");
  }
}
?>
