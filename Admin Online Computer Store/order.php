<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Order</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    include 'includes/orderGetVar.php';
    require_once 'includes/dbh.inc.php';
    ?>

    <main>
        <!-- category bar -->
        <div class="category_bar">
            <?php
            if (!isset($status)) { //no chosen status
                echo '<a class="category_button selected" href="order.php">ALL</a>';
                while ($row = mysqli_fetch_assoc($statusResult)) {
                    echo '<a class="category_button" href="order.php?status=' . $row["id"] . '">' . strtoupper($row["status_name"]) . '</a>';
                }
            } else { //got chosen status
                echo '<a class="category_button" href="order.php">ALL</a>';
                while ($row = mysqli_fetch_assoc($statusResult)) {
                    if ($row["id"] == $status) {
                        echo '<a class="category_button selected" href="order.php?status=' . $row["id"] . '">' . strtoupper($row["status_name"]) . '</a>';
                    } else {
                        echo '<a class="category_button" href="order.php?status=' . $row["id"] . '">' . strtoupper($row["status_name"]) . '</a>';
                    }
                }
            }
            ?>
        </div>
        <div class="order_body">
            <?php //no order
            if (mysqli_num_rows($orderResult) == 0) {
                echo '<p class="no_result">No Result</p>';
            } else { //got order
            ?>
                <table class="order_display">
                    <tr class="table_head">
                        <td style="width: 10%;">Order ID</td>
                        <td style="width: 25%;">Customer Name</td>
                        <td style="width: 12%; text-align: center;">Order At</td>
                        <td style="width: 8%; text-align: center;">Item Qty</td>
                        <td style="width: 12%; text-align: center;">Amount</td>
                        <td style="width: 8%; text-align: center;">Payment</td>
                        <td style="width: 12%; text-align: center;">Status</td>
                        <td style="width: 8%; text-align: center;">Action</td>
                        <td style="width: 5%;"></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($orderResult)) {
                        //get other required information
                        $totalItem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(qty) AS totalItem FROM orderitems WHERE order_id = " . $row['id'] . ";"))["totalItem"];
                        $custName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = " . $row['user_id'] . ";"))["user_name"];
                        $statusName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orderstatus WHERE id =" . $row['status_id'] . ";"))["status_name"];
                        list($date, $time) = explode(" ", $row["order_date"]);
                        $formatDate = strtotime($date);
                    ?>
                        <tr class="table_body">
                            <!-- Order ID -->
                            <td>
                                <p>#<?php echo $row["id"]; ?></p>
                            </td>

                            <!-- Customer Name -->
                            <td>
                                <p><?php echo $custName; ?></p>
                            </td>

                            <!-- Order At -->
                            <td style="text-align: center;">
                                <p><?php echo date("d M Y", $formatDate); ?></p>
                                <p><?php echo $time; ?></p>
                            </td>

                            <!-- Item Qty -->
                            <td style="text-align: center;">
                                <p><?php echo $totalItem; ?></p>
                            </td>

                            <!-- Amount -->
                            <td style="text-align: center;">
                                <p>RM <?php echo $row["total_price"]; ?></p>
                            </td>

                            <!-- Payment -->
                            <td style="text-align: center;">
                                <p><?php echo $row["payment_type"]; ?></p>
                            </td>

                            <!-- Status -->
                            <td>
                                <div class="status_col">
                                    <?php
                                    if (in_array($row['status_id'], [1])) {
                                        echo '<p class="blue">New Order</p>';
                                    } else if (in_array($row['status_id'], [2, 3, 4])) {
                                        echo '<p class="yellow">' . $statusName . '</p>';
                                    } else if (in_array($row['status_id'], [5])) {
                                        echo '<p class="green">' . $statusName . '</p>';
                                    } else {
                                        echo '<p class="red">' . $statusName . '</p>';
                                    }
                                    ?>
                                </div>
                            </td>

                            <!-- Action -->
                            <td>
                                <div class="action_col">
                                    <?php
                                    if (in_array($row['status_id'], [1])) { //Processing
                                        echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $row["id"] . '&action=nextProcess"><i class="fa-solid fa-circle-check"></i></a>';
                                        echo '<a class="enabled" href="" onclick="showRejectPopup(' . $row["id"] . '); return false;"><i class="fa-solid fa-circle-xmark"></i></a>';
                                    } else if (in_array($row['status_id'], [2, 3, 4])) { //Packaging, Delivering, To Receive
                                        echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $row["id"] . '&action=previousProcess"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                                        echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $row["id"] . '&action=nextProcess"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                                    } else if (in_array($row['status_id'], [5])) { //Completed
                                        echo '<a class="enabled" href="includes/updateStatus.php?orderID=' . $row["id"] . '&action=previousProcess"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                                        echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                                    } else { //Rejected, Not Received
                                        echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-left"></i></a>';
                                        echo '<a class="disabled"><i class="fa-solid fa-circle-chevron-right"></i></a>';
                                    }
                                    ?>
                                </div>
                            </td>

                            <!-- More -->
                            <td>
                                <a class="enabled" href="orderDetails.php?order=<?php echo $row['id']; ?>"><i class="fa-solid fa-circle-info"></i></a>
                            </td>
                        </tr>
                <?php
                    }
                    echo '</table>';
                }
                ?>
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