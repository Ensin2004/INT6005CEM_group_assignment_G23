<?php
require_once "includes/security.php";
require_once "includes/csrf.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Customize PC</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/customize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class="main">

            <div class="customizebutton">
                <a class="buttonCustomize" href="mylist.php"> My List </a>
                <a class="buttonCustomize g" href=""> Reset </a>
            </div>
            <hr>
            <br>

            <?php
            include "includes/dbh.inc.php";
            ?>

            <div class="formDisplay">
                <form action="includes/addMylist.php" method="POST">
                    <?php createCSRFInput(); ?>
                    <label style="color: black;" for="MyListName">List Name : </label>
                    <input required class="name_input" type="text" id="MyListName" name="myListName" placeholder="Enter List Name">
                    <br><br>
                    <table class="categoryTable">
                        <tr class="tableRow">
                            <td class="componentData">Components</td>
                            <td style="font-weight:bold;" class="selectionData">Selection</td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">CPU</td>
                            <td class="selectionData">
                                <select required name="CPU">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('CPU');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option class="option" value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Memory</td>
                            <td class="selectionData">
                                <select required name="Memory">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Memory');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Motherboard</td>
                            <td class="selectionData">
                                <select required name="MotherBoard">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Motherboard');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Storage</td>
                            <td class="selectionData">
                                <select required name="Storage">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Storage');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">GPU</td>
                            <td class="selectionData">
                                <select required name="GPU">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Graphic Card');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Power Supply Unit</td>
                            <td class="selectionData">
                                <select required name="PSU">
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Power Supply Unit');"); ?>
                                    <option value="">Please select an option ⬇️</option>
                                    <?php foreach ($rows as $row) : ?>
                                        <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                    </table>
                    <div class="addButton">
                        <button class="submitAddButton" type="submit" name="add">ADD</button>
                    </div>
                </form>
            </div>
            <br>

        </div>
    </main>
    <?php
    include 'footer.php';
    ?>
</body>

</html>

<!-- <div class="tableHead">
            <p class = "tableTitle">Components </p>
            <p class = "tableTitle k">Selection </p>
           
        </div>
    <hr> 
        <div class="customizeCategory">
            <div class="categoryDisplay">
                <P>CPU</P>
                <P>Memory Card</P>
                <P>Mother Board</P>
                <P>Storage</P>
                <P>GPU</P>
                <P>Power Supply Unit</P>
            </div> -->