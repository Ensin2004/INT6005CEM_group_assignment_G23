<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
include "includes/dbh.inc.php";
require_once "includes/csrf.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selected_items = $_POST['item_id'];
    $method = $_POST['payment'];
    $id = $_POST['user'];
    $address = $_POST['newAddress'];
    $totalPrice = $_POST['totalPrice'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <?php
    $name = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $name = mysqli_fetch_assoc($name);
    ?>

    <main>
        <p style="font-weight:500; font-size:3rem; margin-top:20px;">TOUCH N GO E-WALLET PAYMENT</p>
        <div class="paymentContainer">
            <div class="touchNGoContainer">
                <div class="QrAndDescription">
                    <div style="width:35%;">
                        <img class="QrCodeImage" src="../Image/TouchNGo.jpg" alt="Touch And Go Qr Code">
                    </div>
                    <div style="width:61%;">
                        <p class="DescriptionHead">Pay with your Touch N GO e-Wallet</p>
                        <p class="normalDescription">1. Download and register for the Touch 'n Go e-Wallet app if you haven't. If you have, launch your Touch 'n Go e-Wallet app.</p>
                        <p class="normalDescription">2. Tap on the Scan icon</p>
                        <p class="normalDescription">3. Scan the Qr Code here and complete the payment!</p>
                    </div>
                </div>
                <div>
                    <p class="secureDescription"><i class="fa-solid fa-shield"></i> Your payments will be processed in a safe and secure environment!</p>
                </div>
            </div>

            <div class="bigPaymentContainer">
                <div class="paymentScreenshot" > 
                    <div class="paymentDetailHeader">
                        <p>Payment Details</p>
                    </div>
                    <div class="paymentDetailsContainer">
                        <div style="width:20%">
                            <p class="paymentDetails">PAY BY</p>
                        </div>
                        <div style="width:75%">
                            <p class="paymentDetails">: <?php echo $name['user_name'] ?></p>
                        </div>
                    </div>
                    <div class="paymentDetailsContainer">
                        <div style="width:20%">
                            <p class="paymentDetails">ADDRESS</p>
                        </div>
                        <div style="width:75%">
                            <p class="paymentDetails">: <?php echo $address; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="totalPriceContainer">
                        <div style="width:20%">
                            <p class="paymentDetails">TOTAL</p>
                        </div>
                        <div style="width:35%">
                            <p class="paymentDetails" style="font-weight:600">RM <?php echo $totalPrice; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p class="uploadText">Please upload your payment Screenshot here:</p>
                    </div>
                    <form id="orderForm" action="includes/placeOrder.php" method="post" enctype="multipart/form-data">
                        <?php createCSRFInput(); ?>
                        <div class="inputButton">
                            <?php foreach ($selected_items as $item) { ?>
                                <input type="hidden" name="item_id[]" value="<?php echo $item; ?>">
                            <?php } ?>
                            <input type="hidden" name="payment" value="<?php echo $method ?>">
                            <input type="hidden" name="newAddress" value="<?php echo $address ?>">
                            <input type="hidden" name="totalPrice" value="<?php echo $totalPrice ?>">
                            <input type="hidden" name="user" value="<?php echo $id ?>">
                            <input required type="file" class="uploadInput" name="proofImage" accept=".jpg, .jpeg, .png, image/jpeg, image/png" onchange="validateFile(event);">
                        </div>
                        <div class="submit">
                            <input type="submit" name="submit" value="Confirm">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function validateFile(event) {
            if (event.target.files.length > 0) {
                var file = event.target.files[0];
                if (file.size <= 1024 * 1024) {
                    // File size is within limit
                } else {
                    alert("Image size exceeds the limit (1MB)");
                    event.target.value = "";
                }
            }
        }
    </script>
</body>

</html>