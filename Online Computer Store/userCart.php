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
    <title>KAH TECH - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="addCart">
            <?php
            include "includes/dbh.inc.php";
            $id = $_SESSION['ID'];
            ?>
        </div>
        <div class="myCartTitle">
            <p style="width:100%; text-align:center; font-weight: 700;">MY CART</p>
        </div>
        <hr>
        <div class="tableContent">
            <form id="checkoutForm" action="checkOut.php" method="post">
                <table class="tableSize">
                    <thead>
                        <tr>
                            <th class="checkHeaderSize"></th>
                            <th colspan="2" class="itemHeaderSize">Item</th>
                            <th class="headerSize">Quantity</th>
                            <th class="headerSize">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = mysqli_query($conn, "SELECT cart.id, cart.item_id, items.stock_qty, items.item_name, items.image1, items.price, cart.qty FROM items INNER JOIN cart ON items.id = cart.item_id WHERE item_status = 'Active' AND cart.user_id = $id ORDER BY cart.id ASC;");
                        if (mysqli_num_rows($rows) > 0) {
                            while ($row = mysqli_fetch_assoc($rows)) {
                        ?>
                                <tr class="tableRow" id="<?php echo $row['id']; ?>">

                                    <td class="checkDataSize">
                                        <?php if ($row['stock_qty'] >= $row['qty']) : ?>
                                            <input class="checkbox" type="checkbox" name="item_id[]" value="<?php echo $row['id']; ?>">
                                        <?php endif; ?>
                                    </td>

                                    <td class="imageDataSize">
                                        <a href="itemDetails.php?item=<?php echo $row['item_id']; ?>">
                                            <img class="item_image" src="../Image/<?php echo $row['image1']; ?>" alt="<?php echo $row['item_name']; ?>">
                                        </a>
                                    </td>
                                    <td class="itemDataSize">
                                        <a href="itemDetails.php?item=<?php echo $row['item_id']; ?>">
                                            <p style="font-weight:500; font-size:3rem;"><?php echo $row['item_name']; ?></p>
                                            <p>RM <?php echo $row['price']; ?></p>
                                            <?php
                                            if ($row["stock_qty"] == 0) {
                                                echo '<p style="color: rgb(255, 52, 52);">Stock: ' . $row["stock_qty"] . '</p>';
                                            } else {
                                                echo '<p style="color: rgb(60, 179, 113);">Stock: ' . $row["stock_qty"] . '</p>';
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <td class="dataSize">
                                        <div class="quantityBox">
                                            <a class="decreaseButton" href="includes/updateCartQty.php?id=<?php echo $row['id']; ?>&data=decrease&stock=<?php echo $row['stock_qty']; ?>&qty=<?php echo $row['qty']; ?>">-</a>
                                            <div class="quantityForm">
                                                <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="stock[]" value="<?php echo $row['stock_qty']; ?>">
                                                <input type="number" class="quantityInput" name="quantity[]" value="<?php echo $row['qty']; ?>" onchange="submitForm(this)">
                                            </div>
                                            <a class="increaseButton" href="includes/updateCartQty.php?id=<?php echo $row['id']; ?>&data=increase&stock=<?php echo $row['stock_qty']; ?>&qty=<?php echo $row['qty']; ?>">+</a>
                                        </div>
                                    </td>
                                    <td class="dataSize">
                                        <p style="font-size: 2rem;">RM <?php echo number_format($row['price'] * $row['qty'], 2, ".", ""); ?></p>
                                    </td>
                                    <td class="dataSize">
                                        <div class="Button">
                                            <a class="removeButton" href="includes/deleteMycart.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-trash-can"></i> REMOVE</a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items in cart.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="checkoutButtonContainer">
                    <input class="checkoutButton" type="submit" name="submit" value="Checkout">
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function submitForm(input) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = 'includes/updateCartQty.php';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = input.closest('tr').id;

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'quantity';
                qtyInput.value = input.value;

                const stockInput = document.createElement('input');
                stockInput.type = 'hidden';
                stockInput.name = 'stock';
                stockInput.value = input.closest('tr').querySelector('[name="stock[]"]').value;

                form.appendChild(idInput);
                form.appendChild(qtyInput);
                form.appendChild(stockInput);

                document.body.appendChild(form);
                form.submit();
            }

            document.querySelectorAll('.quantityInput').forEach(function(input) {
                input.addEventListener('change', function() {
                    submitForm(this);
                });
            });

            document.querySelectorAll('.quantityInput').forEach(function(input) {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        submitForm(this);
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const checkoutButtonContainer = document.querySelector('.checkoutButtonContainer');
            const footer = document.querySelector('footer');
            const offset = 0; // Offset from the bottom

            function checkPosition() {
                const footerRect = footer.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                if (footerRect.top < windowHeight) {
                    // Footer is in view, move button above footer
                    checkoutButtonContainer.style.transform = `translateY(${footerRect.top - windowHeight + offset}px)`;
                } else {
                    // Footer is not in view, fix button at bottom
                    checkoutButtonContainer.style.transform = 'translateY(0)';
                }
            }

            window.addEventListener('scroll', checkPosition);
            window.addEventListener('resize', checkPosition);
            checkPosition();
        });
    </script>
</body>

</html>