<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Order Details</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/orderDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    require_once 'includes/dbh.inc.php';
    if (!$conn) {
        die("Database connection failed");
    } else {
        $orderID = $_GET["order"];
        $orderDetails = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = $orderID;"));
        $statusName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orderstatus WHERE id =" . $orderDetails['status_id'] . ";"))["status_name"];
        $itemDetails = mysqli_query($conn, "SELECT * FROM orderitems LEFT JOIN items ON orderitems.item_id = items.id WHERE orderitems.order_id = $orderID;");
        list($date, $time) = explode(" ", $orderDetails["order_date"]);
        $formatDate = strtotime($date);
    }
    ?>

    <main>
        <div class="order_head">
            <div>
                <p class="order_id">Order ID : #<?php echo $orderID; ?></p>
            </div>
            <div class="order_status">
                <?php
                if (in_array($orderDetails['status_id'], [1])) {
                    echo '<p class="blue">New Order</p>';
                } else if (in_array($orderDetails['status_id'], [2, 3, 4])) {
                    echo '<p class="yellow">' . $statusName . '</p>';
                } else if (in_array($orderDetails['status_id'], [5])) {
                    echo '<p class="green">' . $statusName . '</p>';
                } else {
                    echo '<p class="red">' . $statusName . '</p>';
                }
                ?>
                <?php
                if (in_array($orderDetails['status_id'], [1])) { //Processing
                    echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $orderID . '&action=nextProcess"><i class="fa-solid fa-circle-check"></i></a>';
                    echo '<a class="enabled" href="" onclick="showRejectPopup(' . $orderID . '); return false;"><i class="fa-solid fa-circle-xmark"></i></a>';
                } else if (in_array($orderDetails['status_id'], [2, 3, 4])) { //Packaging, Delivering, To Receive
                    echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $orderID . '&action=previousProcess"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                    echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $orderID . '&action=nextProcess"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                } else if (in_array($orderDetails['status_id'], [5])) { //Completed
                    echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $orderID . '&action=previousProcess"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                    echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                } else { //Rejected, Not Received
                    echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                    echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                }
                ?>
            </div>
        </div>
        <hr>

        <?php
        if (in_array($orderDetails["status_id"], [6, 7])) {
        ?>
            <div class="remarks_display">
                <div class="remarks_container">
                    <?php
                    if ($orderDetails["status_id"] == 6) {
                        echo '<p class="title">This order is rejected due to : </p>';
                    } else {
                        echo '<p class="title">This order is incomplete due to : </p>';
                    }
                    ?>
                    <p><?php echo $orderDetails['remarks']; ?></p>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="order_body">

            <!-- Left Side -->
            <div class="left_side">
                <div class="delivery_address">
                    <p class="title">Delivery Address</p>
                    <p><?php echo $orderDetails['delivery_address']; ?></p>
                </div>
                <br>
                <div class="items_details">
                    <table class="items_display">
                        <tr class="table_head">
                            <td style="width: 55%; font-size: 2rem;">Item(s) Summary</td>
                            <td style="width: 15%; text-align: center;">Price</td>
                            <td style="width: 15%; text-align: center;">Qty</td>
                            <td style="width: 15%; text-align: center;">Total Price</td>
                        </tr>
                        <?php
                        while ($row = mysqli_fetch_assoc($itemDetails)) {
                        ?>
                            <tr class="table_body">
                                <!-- Items Summary -->
                                <td>
                                    <a href="itemDetails.php?item=<?php echo $row['item_id']; ?>">
                                        <img src="../Image/<?php echo $row['image1']; ?>" alt="<?php echo $row['item_name']; ?>">
                                        <p><?php echo $row['item_name']; ?></p>
                                    </a>
                                </td>

                                <!-- Price -->
                                <td style="text-align: center;">
                                    <p>RM <?php echo $row['item_price']; ?></p>
                                </td>

                                <!-- Qty -->
                                <td style="text-align: center;">
                                    <p><?php echo $row['qty']; ?></p>
                                </td>

                                <!-- Total Price -->
                                <td style="text-align: center;">
                                    <p>RM <?php echo number_format($row['item_price'] * $row['qty'], 2, ".", ""); ?></p>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>

            <!-- Right Side -->
            <div class="right_side">
                <div class="customer_details">
                    <img src="../Online Computer Store/image/<?php echo $orderDetails['user_image']; ?>" alt="<?php echo $orderDetails['user_name']; ?>">
                    <div>
                        <p class="cust_name"><?php echo $orderDetails['user_name']; ?></p>
                        <p><?php echo $orderDetails['phone']; ?></p>
                    </div>
                </div>
                <br>
                <div class="order_summary">
                    <p class="title">Order Summary</p>
                    <div class="summary_container">
                        <p class="label">Order Created</p>
                        <p class="summary"><?php echo date("D, d M Y", $formatDate); ?></p>
                    </div>
                    <div class="summary_container">
                        <p class="label">Order Time</p>
                        <p class="summary"><?php echo $time; ?></p>
                    </div>
                    <div class="summary_container">
                        <p class="label">Subtotal</p>
                        <p class="summary">RM <?php echo number_format($orderDetails['total_price'] - 10, 2, ".", ""); ?></p>
                    </div>
                    <div class="summary_container">
                        <p class="label">Delivery Fee</p>
                        <p class="summary">RM 10.00</p>
                    </div>
                    <div class="summary_container">
                        <p class="label">Total</p>
                        <p class="summary">RM <?php echo $orderDetails['total_price']; ?></p>
                    </div>
                </div>
                <br>
                <div class="payment_details">
                    <p class="title">Payment Method</p>
                    <?php
                    if ($orderDetails['payment_type'] == "COD") {
                        echo '<p class="pay_method">Cash on Delivery (COD)</p>';
                        echo '<img src="../Image/cod_img.png" alt="COD">';
                    } else {
                        echo '<p class="pay_method">TnG E-Wallet (TNG)</p>';
                        echo '<a href="../Image/' . $orderDetails["screenshot"] . '" target="_blank"><img style="cursor: pointer;" src="../Image/' . $orderDetails["screenshot"] . '" alt="Screenshot"></a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <div class="reject_popup" id="reject_popup">
        <form class="reject_form" id="reject_form" action="includes/updateStatus.php" method="GET">
            <input type="hidden" id="orderID_input" name="orderID" value="">
            <input type="hidden" name="action" value="rejectOrder">
            <p id="orderID_display"></p>
            <br>
            <label for="remarks">Reason : </label>
            <textarea required id="remarks" name="remarks" placeholder="e.g. wrong payment amount / out of stock / ..."></textarea>
            <div class="buttons">
                <a class="cancel_button" href="" onclick="closeRejectPopup(); return false;">CANCEL</a>
                <button class="submit_button" type="submit" name="submit">SUBMIT</button>
            </div>
        </form>
    </div>

    <script>
        function showRejectPopup(orderID) {
            document.getElementById("reject_form").reset();
            document.getElementById("orderID_input").value = orderID;
            document.getElementById("orderID_display").innerText = "Order ID : #" + orderID;
            document.getElementById("reject_popup").style.display = "block";
        }

        function closeRejectPopup() {
            document.getElementById("reject_form").reset();
            document.getElementById("reject_popup").style.display = "none";
        }
    </script>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>