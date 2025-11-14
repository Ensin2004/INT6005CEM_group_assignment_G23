<?php 
//connection to database
// Inside dbh.inc.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "computer_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    echo "Connection to database failed";
    exit();
  }

// Always use utf8mb4 for safety
$conn->set_charset('utf8mb4');

?>