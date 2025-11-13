<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckOut</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/checkOut.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    if (isset($_POST['item_id'])) {
        $selected_items = $_POST['item_id'];
        $totalPrice = 0;
    ?>

        <main>
            <?php
            include "includes/dbh.inc.php";
            $id = $_SESSION['ID'];
            ?>
            <div class="wholeContainer">
                <form id="checkoutForm" action="payment.php" method="POST">
                    <div class="addOrder">
                        <?php
                        $id = $_SESSION['ID'];
                        $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id;");
                        $row = mysqli_fetch_array($result);
                        ?>
                        <div class="addressContainer">
                            <label> Delivery Address : </label>
                            <input required class="addressInput" type="text" name="newAddress" value="<?php echo $row['user_address'] ?>" readonly>
                            <div class="editIcon">
                                <i class="fas fa-edit editAddressIcon"></i>
                                <p class="editText">Edit</p>
                            </div>
                        </div>
                        <button type="button" class="OrderBarButton" onclick="goBack()">Cart</button>
                    </div>
                    <hr>
                    <div class="myOrderTitle">
                        <p style="width:100%; text-align:center;">My Order</p>
                    </div>
                    <hr>
                    <div class="OrderDisplayBox">
                        <div class="displayLeftBox">
                            <div class="displayItemBox">
                                <?php foreach ($selected_items as $cart_id) : ?>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT items.item_name, items.image1, items.price, cart.qty FROM items INNER JOIN cart ON items.id = cart.item_id WHERE cart.id = $cart_id");
                                    $row = mysqli_fetch_assoc($result);
                                    $totalPrice += ($row['price'] * $row['qty']);
                                    ?>
                                    <div class="displayItem">
                                        <div>
                                            <img class="item_image" src="../Image/<?php echo $row['image1']; ?>" alt="<?php echo $row['item_name']; ?>">
                                        </div>

                                        <div class="displayItemNameQty">
                                            <div class="itemNameAndPrice">
                                                <div style="width:80%">
                                                    <p class="itemName"><?php echo $row['item_name']; ?></p>
                                                </div>
                                                <div style="width:20%">
                                                    <p>RM: <?php echo number_format($row['price'] * $row['qty'], 2, ".", ""); ?></p>
                                                </div>
                                            </div>
                                            <div>
                                                <p>Quantity: <?php echo $row['qty']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hr">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="displayRightBox">
                            <div class="displayPriceBox">
                                <div class="displayTotal">
                                    <div class="labelTotal">
                                        <p>Sub-Total</p>
                                    </div>
                                    <div class="labelTotal">
                                        <p>RM <?php echo number_format($totalPrice, 2, ".", ""); ?></p>
                                    </div>
                                </div>
                                <hr class="hr">
                                <div class="displayTotal">
                                    <div class="labelTotal">
                                        <p>Delivery Fee</p>
                                    </div>
                                    <div class="labelTotal">
                                        <p>RM 10.00</p>
                                    </div>
                                </div>
                                <hr class="hr">
                                <div class="displayTotal">
                                    <div class="labelTotal">
                                        <p>Total</p>
                                    </div>
                                    <div class="labelTotal">
                                        <p>RM <?php echo number_format($totalPrice + 10, 2, ".", ""); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="displayDeliveryMethod">
                                <div class="labelTotal">
                                    <p>Payment Method</p>
                                </div>
                                <hr class="hr">
                                <div>
                                    <?php foreach ($selected_items as $item_id) : ?>
                                        <input type="hidden" name="item_id[]" value="<?php echo $item_id; ?>">
                                    <?php endforeach; ?>
                                    <input type="hidden" name="user" value="<?php echo $id ?>">
                                    <input type="hidden" name="totalPrice" value="<?php echo number_format($totalPrice + 10, 2, ".", "") ?>">
                                    <div class="paymentOption">
                                        <input required type="radio" id="tng" name="payment" value="TNG">
                                        <label for="tng">TnG E-Wallet (TNG)</label>
                                    </div>
                                    <div class="paymentOption">
                                        <input required type="radio" id="cod" name="payment" value="COD">
                                        <label for="cod">Cash on Delivery (COD)</label>
                                    </div>
                                    <hr class="hr">
                                    <div class="submitButton">
                                        <input type="submit" name="submit" value="PLACE ORDER">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php
    } else {
        echo "<script>alert('No item is selected'); window.location.href='userCart.php';</script>";
    }
        ?>
        </main>
        <?php include 'footer.php'; ?>
        <script>
            function goBack() {
                window.history.back();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('checkoutForm');
                const paymentRadios = document.querySelectorAll('input[name="payment"]');

                paymentRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'COD') {
                            form.action = 'includes/placeOrder.php';
                        } else {
                            form.action = 'payment.php';
                        }
                    });
                });

                const addressInput = document.querySelector('.addressInput');
                const editIcon = document.querySelector('.editAddressIcon');

                editIcon.addEventListener('click', function() {
                    addressInput.removeAttribute('readonly');
                    addressInput.style.border = "1px solid black";
                    addressInput.focus();
                });
            });
        </script>

</body>

</html>