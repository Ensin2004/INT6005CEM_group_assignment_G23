<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/headerFooter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <header>
        <div>
            <!-- Logo -->
            <?php
            if (isset($_SESSION['ID'])) {
                echo '<a class="nav_button" href="home.php"><img class="logo" src="../Image/logo.png" class="logo" alt="KAH TECH Logo"></a>';
            } else {
                echo '<a class="nav_button" href="index.php"><img src="../Image/logo.png" class="logo" alt="KAH TECH Logo"></a>';
            }
            ?>
        </div>

        <div>
            <?php 
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') {
               echo '<p class="admin">Admin</P>';
            } else if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager') {
                echo '<p class="admin">Manager</p>';
            }
            else{
                echo '<p class="admin">Admin/ Manager</p>';
            }
            ?>

        </div>

        <div>
            <!-- Order -->
            <!-- <a class="nav_button" href="updateuser.php"><i class="fa-solid fa-receipt"></i></a> -->

            <!-- Cart -->
            <!-- <a class="nav_button" href=""><i class="fa-solid fa-cart-shopping"></i></a> -->

            <!-- Account -->
            <a class="nav_button" href="adminAccount.php"><i class="fa-solid fa-circle-user"></i> Account</a>
        </div>
    </header>
</body>

</html>