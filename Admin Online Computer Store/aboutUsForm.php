<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Edit Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/aboutUsForm.css">
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <main>
        <div class="mainDisplayBox">
            <div class="backButton">
                <a href="homeEdit.php"><- BACK</a>
            </div>
            <div class="AboutUsForm">
                <form action="includes/aboutUsAdd.php" method="POST" enctype="multipart/form-data">
                    <label for id="Image">IMAGE HERE :</label><br>
                    <input type="file" id="Image" name="image" />
                    <br><br>
                    <label for id="Description">Description :</label><br>
                    <textarea style="word-wrap:break-word;" class="input" id="Description" name="Description"></textarea>
                    <br><br>

                    <button class="submitButton" type="submit" name="submit">Confirm </button>

                </form>
            </div>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>