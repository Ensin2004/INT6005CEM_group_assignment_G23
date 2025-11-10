<?php
require_once "includes/security.php";
include "includes/statisticGraph.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Statistic</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/statistic.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <!-- Load chart -->
    <script>
        window.onload = function() {

            //Total sales chart
            var chart1 = new CanvasJS.Chart("total_sales_chart", {
                axisY: {
                    title: "Sales (RM)",
                    titleFontFamily: "'Poppins', sans-serif",
                    titleFontSize: 18,
                    labelFontFamily: "'Poppins', sans-serif",
                    labelFontSize: 14,
                    labelMaxWidth: 90,
                    labelFormatter: function(e) {
                        return e.value.toString().replace(/,/g, ""); // Remove commas from labels
                    }
                },
                axisX: {
                    labelFontFamily: "'Poppins', sans-serif",
                    labelFontSize: 14,
                },
                data: [{
                    type: "line",
                    markerType: "circle",
                    markerSize: 12,
                    dataPoints: <?php echo json_encode($totalSales_dataPoints, JSON_NUMERIC_CHECK); ?>
                }],
                toolTip: {
                    enabled: true,
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: 14,
                    fontStyle: "italic",
                    fontColor: "black",
                    contentFormatter: function(e) {
                        return content = e.entries[0].dataPoint.label + " : RM " + e.entries[0].dataPoint.y.toFixed(2);
                    }
                },
                animationEnabled: true,
                animationDuration: 1000,
                zoomEnabled: true,
                backgroundColor: "ghostwhite",
                exportEnabled: true,
                exportFileName: "<?php echo $summaryBy . "_Total_Sales_" . $fromDate . "_to_" . $toDate; ?>"
            });
            chart1.render();

            //Category sales chart
            var chart2 = new CanvasJS.Chart("category_sales_chart", {
                axisY: {
                    title: "Sales (RM)",
                    titleFontFamily: "'Poppins', sans-serif",
                    titleFontSize: 18,
                    labelFontFamily: "'Poppins', sans-serif",
                    labelFontSize: 14,
                    labelMaxWidth: 90,
                    labelFormatter: function(e) {
                        return e.value.toString().replace(/,/g, ""); // Remove commas from labels
                    }
                },
                axisX: {
                    labelFontFamily: "'Poppins', sans-serif",
                    labelFontSize: 14,
                },
                data: [{
                    type: "column",
                    dataPoints: <?php echo json_encode($categorySales_dataPoints, JSON_NUMERIC_CHECK); ?>
                }],
                toolTip: {
                    enabled: true,
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: 14,
                    fontStyle: "italic",
                    fontColor: "black",
                    contentFormatter: function(e) {
                        return content = e.entries[0].dataPoint.label + " : RM " + e.entries[0].dataPoint.y.toFixed(2);
                    }
                },
                animationEnabled: true,
                animationDuration: 1000,
                zoomEnabled: true,
                backgroundColor: "ghostwhite",
                exportEnabled: true,
                exportFileName: "<?php echo "Category_Sales_" . $fromDate . "_to_" . $toDate; ?>"
            });
            chart2.render();

            //Payment distribution chart
            var chart3 = new CanvasJS.Chart("payment_distribution_chart", {
                data: [{
                    type: "pie",
                    indexLabel: "{label} : {percent}%",
                    indexLabelFontFamily: "'Poppins', sans-serif",
                    indexLabelFontSize: 14,
                    indexLabelFontWeight: 500,
                    indexLabelPlacement: "outside",
                    showInLegend: true,
                    legendText: "{label} : RM {y}",
                    yValueFormatString: "0.00",
                    dataPoints: <?php echo json_encode($paymentDistribution_dataPoints, JSON_NUMERIC_CHECK); ?>
                }],
                toolTip: {
                    enabled: true,
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: 14,
                    fontStyle: "italic",
                    fontColor: "black",
                    contentFormatter: function(e) {
                        return content = e.entries[0].dataPoint.label + " : RM " + e.entries[0].dataPoint.y.toFixed(2);
                    }
                },
                animationEnabled: true,
                animationDuration: 1000,
                zoomEnabled: true,
                backgroundColor: "ghostwhite",
                exportEnabled: true,
                exportFileName: "<?php echo "Payment_Distribution_" . $fromDate . "_to_" . $toDate; ?>",
                legend: {
                    fontFamily: "'Poppins', sans-serif",
                    fontSize: 14,
                    fontWeight: 400,
                    fontColor: "black",
                    horizontalAlign: "center",
                    verticalAlign: "bottom",
                }
            });
            chart3.render();
        }
    </script>
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class="statistic_head">
            <p class="title">STATISTICS</p>
            <form class="statistic_form" action="statistic.php" method="POST">
                <!-- From when to when -->
                <div class="filter_container">
                    <p>From : </p>
                    <input required type="date" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>" onchange="checkFromDate()">
                </div>
                <div class="filter_container">
                    <p>To : </p>
                    <input required type="date" id="toDate" name="toDate" value="<?php echo $toDate; ?>" onchange="checkToDate()">
                </div>
                <!-- Summary by what -->
                <div class="filter_container">
                    <p>By : </p>
                    <div class="radio_container">
                        <?php
                        if ($summaryBy == "Daily") {
                            echo '<input type="radio" id="daily" name="summaryBy" value="Daily" checked>';
                        } else {
                            echo '<input type="radio" id="daily" name="summaryBy" value="Daily">';
                        }
                        ?>
                        <label class="radio_label" for="daily">Daily</label>
                        <?php
                        if ($summaryBy == "Monthly") {
                            echo '<input type="radio" id="monthly" name="summaryBy" value="Monthly" checked>';
                        } else {
                            echo '<input type="radio" id="monthly" name="summaryBy" value="Monthly">';
                        }
                        ?>
                        <label class="radio_label" for="monthly">Monthly</label>
                        <?php
                        if ($summaryBy == "Yearly") {
                            echo '<input type="radio" id="yearly" name="summaryBy" value="Yearly" checked>';
                        } else {
                            echo '<input type="radio" id="yearly" name="summaryBy" value="Yearly">';
                        }
                        ?>
                        <label class="radio_label" for="yearly">Yearly</label>
                    </div>
                </div>

                <!-- Submit button -->
                <button class="submit_button" type="submit" name="submit">Submit</button>
            </form>
        </div>

        <div class="statistic_body">
            <div class="row_container">

                <!-- Summary sales -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="chart_title">Total Sales</p>
                    <p class="stats">RM <?php echo $summarySales; ?></p>
                </div>

                <!-- Summary item sold -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="chart_title">Total Item Sold</p>
                    <?php
                    if ($summaryItemSold == "") {
                        echo '<p class="stats">0</p>';
                    } else {
                        echo '<p class="stats">' . $summaryItemSold . '</p>';
                    }
                    ?>
                </div>

                <!-- Summary orders -->
                <div class="chart_container" style="width: 32.27%;">
                    <p class="chart_title">Total Orders</p>
                    <p class="stats"><?php echo $summaryOrders; ?></p>
                </div>
            </div>

            <!-- Total sales chart -->
            <div class="chart_container" style="width: 100%;">
                <?php
                if ($summaryBy == "Daily") {
                    echo '<p class="chart_title">Daily Sales</p>';
                } else if ($summaryBy == "Monthly") {
                    echo '<p class="chart_title">Monthly Sales</p>';
                } else if ($summaryBy == "Yearly") {
                    echo '<p class="chart_title">Yearly Sales</p>';
                }
                ?>
                <div class="chart" id="total_sales_chart"></div>
            </div>

            <div class="row_container">

                <!-- Category sales chart -->
                <div class="chart_container" style="width: 49.2%;">
                    <p class="chart_title">Category Sales</p>
                    <div class="chart" id="category_sales_chart"></div>
                </div>

                <!-- Payment distribution chart -->
                <div class="chart_container" style="width: 49.2%;">
                    <p class="chart_title">Payment Distribution</p>
                    <div class="chart" id="payment_distribution_chart"></div>
                </div>
            </div>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <script>
        function checkFromDate() {
            var fromDate = document.getElementById("fromDate").value;
            var toDate = document.getElementById("toDate").value;

            if (fromDate != "" && toDate != "") {
                if (fromDate >= toDate) {
                    var toDateObj = new Date(document.getElementById("toDate").value)
                    toDateObj.setDate(toDateObj.getDate() - 1);

                    // Format as yyyy-mm-dd and change the fromDate value
                    document.getElementById("fromDate").value = toDateObj.toISOString().split("T")[0];
                }
            }
        }

        function checkToDate() {
            var fromDate = document.getElementById("fromDate").value;
            var toDate = document.getElementById("toDate").value;

            if (fromDate != "" && toDate != "") {
                if (fromDate >= toDate) {
                    var fromDateObj = new Date(document.getElementById("fromDate").value)
                    fromDateObj.setDate(fromDateObj.getDate() + 1);

                    // Format as yyyy-mm-dd and change the fromDate value
                    document.getElementById("toDate").value = fromDateObj.toISOString().split("T")[0];
                }
            }
        }
    </script>

    <script src="js/sessionTimeout.js"></script>
</body>

</html>