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
        <div class="EditAboutUsMainDisplayBox">
            <div class="EditAboutUsForm">
                <div>

                    <form style="display:flex; flex-direction: column;" action="includes/aboutUsEdit.php" method="POST" enctype="multipart/form-data">
                        <?php
                        require_once "includes/dbh.inc.php";
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];

                            $result = mysqli_query($conn, "SELECT * FROM aboutus WHERE about_us_id = '$id';");
                            $row = mysqli_fetch_assoc($result);
                        }

                        ?>
                        <input type="hidden" name="aboutUsID" value="<?php echo $row["about_us_id"]; ?>">
                        <label for id="Image">PREVIOUS IMAGE :</label>
                        <img class="EditaboutUsImage" src="../Image/<?php echo $row["about_us_image"]; ?>" />

                        <input type="file" id="Image" name="image" value="<?php echo $row["about_us_image"]; ?>" />
                        <br>
                        <label for id="Description">Description :</label><br>

                        <textarea style="word-wrap:break-word;" class="input" id="Description" name="Description"><?php echo $row['about_us_description']; ?></textarea>
                        <br>

                        <button class="submitButton" type="submit" name="submit">Confirm </button>

                    </form>
                </div>
            </div>


        </div>

        </div>




        </div>


    </main>

    <?php
    include 'footer.php';
    ?>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>