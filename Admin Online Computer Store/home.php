<?php
require_once "includes/security.php";
include "includes/homeGraph.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <!-- Load chart -->
    <script>
        window.onload = function() {

            //Top items chart
            var chart = new CanvasJS.Chart("top_items_chart", {
                axisY: {
                    gridThickness: 0,
                    lineThickness: 0,
                    tickLength: 0,
                    labelFormatter: function(e) {
                        return "";
                    }
                    // title: "Qty",
                    // includeZero: true,
                    // titleFontFamily: "'Poppins', sans-serif",
                    // titleFontSize: 18,
                    // labelFontFamily: "'Poppins', sans-serif",
                    // labelFontSize: 14
                },
                axisX: {
                    reversed: true,
                    labelFontFamily: "'Poppins', sans-serif",
                    labelFontSize: 14,
                    labelMaxWidth: 90,
                    labelWrap: false,
                    labelAngle: 0 // Adjust angle for better alignment
                },
                data: [{
                    type: "bar",
                    dataPoints: <?php echo json_encode($topItems_dataPoints, JSON_NUMERIC_CHECK); ?>
                }],
                toolTip: {
                    enabled: true,
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: 14,
                    fontStyle: "italic",
                    fontColor: "black",
                    contentFormatter: function(e) {
                        return content = e.entries[0].dataPoint.item_name + "<br>Sold : " + e.entries[0].dataPoint.y + "<br>Sales : RM " + e.entries[0].dataPoint.item_sales.toFixed(2);
                    }
                },
                animationEnabled: true,
                animationDuration: 1000,
                zoomEnabled: true,
                backgroundColor: "ghostwhite",
                exportEnabled: true,
                exportFileName: "Top_Sales"
            });
            chart.render();
        }
    </script>
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class="menu_box">
            <a class="menu_button" href="userHome.php">
                <i class="fa-solid fa-house"></i>
                <p class="menu_text">HOME</p>
            </a>
            <a class="menu_button" href="store.php">
                <i class="fa-solid fa-store"></i>
                <p class="menu_text">STORE</p>
            </a>

            <?php
            //Get no of processing order
            require_once 'includes/dbh.inc.php';
            if (!$conn) {
                die("Database connection failed");
            } else {
                $processingOrder = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status_id = 1;"));
            }
            ?>

            <a class="menu_button" href="order.php">
                <i class="fa-solid fa-clipboard-list"></i>
                <p class="menu_text">ORDER</p>
                <?php
                if ($processingOrder > 0) {
                    echo '<p class="red_dot"></p>';
                }
                ?>
            </a>
            <a class="menu_button" href="statistic.php">
                <i class="fa-solid fa-chart-line"></i>
                <p class="menu_text">STATISTIC</p>
            </a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') { ?>
                <a class="menu_button" href="managers.php">
                    <i class="fa-solid fa-users-gear"></i>
                    <p class="menu_text">MANAGERS</p>
                </a>

                <a class="menu_button" href="logging.php">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <p class="menu_text">AUDIT LOGS</p>
                </a>
            <?php } ?>
        </div>
        <hr>
        <div class="statistic_body">
            <div class="row_container">

                <!-- Customer visited -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="stats"><?php echo $custVisited; ?></p>
                    <p class="chart_title">customers visited our website</p>
                </div>

                <!-- Customer placed order -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="stats"><?php echo $custPlacedOrder; ?>%</p>
                    <p class="chart_title">of customers placed order</p>
                </div>

                <!-- Customer customized PC -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="stats"><?php echo $CustCustomizedPC; ?>%</p>
                    <p class="chart_title">of customers customize their own PC</p>
                </div>
            </div>

            <!-- Top items chart -->
            <div class="chart_container" style="width: 100%;">
                <p class="chart_title">Top Item Sold</p>
                <div class="chart" id="top_items_chart"></div>
            </div>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>