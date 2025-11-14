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

            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }

            $result = mysqli_query($conn, "SELECT mylist.id, mylist.list_name, mylist.user_id,
        cpu.item_name AS cpu_name, cpu.price AS cpu_price,
        motherboard.item_name AS motherboard_name, motherboard.price AS motherboard_price,
        memory.item_name AS memory_name, memory.price AS memory_price,
        gpu.item_name AS gpu_name, gpu.price AS gpu_price,
        psu.item_name AS psu_name, psu.price AS psu_price,
        storage.item_name AS storage_name, storage.price AS storage_price
        FROM mylist
        LEFT JOIN items AS cpu ON mylist.cpu_id = cpu.id
        LEFT JOIN items AS motherboard ON mylist.motherboard_id = motherboard.id
        LEFT JOIN items AS memory ON mylist.memory_id = memory.id
        LEFT JOIN items AS gpu ON mylist.gpu_id = gpu.id
        LEFT JOIN items AS psu ON mylist.psu_id = psu.id
        LEFT JOIN items AS storage ON mylist.storage_id = storage.id
        WHERE mylist.id = $id;");
            $row = mysqli_fetch_assoc($result);
            ?>

            <div class="formDisplay">
                <form action="includes/editMylist.php" method="POST">
                    <?php createCSRFInput(); ?>
                    <label style="color:black" id="MyListName">List Name :</label>

                    <input required type="text" id="MyListName" name="myListName" value="<?php echo $row['list_name']; ?>" placeholder="Enter List Name">
                    <br><br>
                    <table class="categoryTable">
                        <tr class="tableRow">
                            <td class="componentData">Components</td>
                            <td style="font-weight:bold;" class="selectionData">Selection</td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">CPU</td>
                            <td class="selectionData">
                                <?php
                                $cpu = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.cpu_id WHERE mylist.id=$id; ";
                                $result = mysqli_query($conn, $cpu);
                                $cpu = mysqli_fetch_assoc($result);
                                ?>

                                <select required name="CPU">
                                    <?php if (!empty($cpu['id'])) : ?>
                                        <option value="<?php echo $cpu['id']; ?>"><?php echo $cpu['item_name'] . " - RM " . $cpu['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose CPU</option>
                                    <?php endif; ?>

                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('CPU');"); ?>

                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $cpu["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Memory</td>
                            <td class="selectionData">


                                <?php
                                $memory = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.memory_id WHERE mylist.id=$id; ";
                                $result = mysqli_query($conn, $memory);
                                $memory = mysqli_fetch_assoc($result);
                                ?>

                                <select required name="Memory">
                                    <?php if (!empty($memory['id'])) : ?>
                                        <option value="<?php echo $memory['id']; ?>"><?php echo $memory['item_name'] . " - RM " . $memory['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose Memory</option>
                                    <?php endif; ?>

                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Memory');"); ?>


                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $memory["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Mother Board</td>
                            <td class="selectionData">
                                <select required name="MotherBoard">

                                    <?php
                                    $motherboard = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.motherboard_id WHERE mylist.id=$id; ";
                                    $result = mysqli_query($conn, $motherboard);
                                    $motherboard = mysqli_fetch_assoc($result);
                                    ?>
                                    <?php if (!empty($motherboard['id'])) : ?>
                                        <option value="<?php echo $motherboard['id']; ?>"><?php echo $motherboard['item_name'] . " - RM " . $motherboard['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose Motherboard</option>
                                    <?php endif; ?>

                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Motherboard');"); ?>

                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $motherboard["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Storage</td>
                            <td class="selectionData">
                                <select required name="Storage">

                                    <?php
                                    $storage = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.storage_id WHERE mylist.id=$id; ";
                                    $result = mysqli_query($conn, $storage);
                                    $storage = mysqli_fetch_assoc($result);
                                    ?>
                                    <?php if (!empty($storage['id'])) : ?>
                                        <option value="<?php echo $storage['id']; ?>"><?php echo $storage['item_name'] . " - RM " . $storage['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose Storage</option>
                                    <?php endif; ?>
                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Storage');"); ?>
                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $storage["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">GPU</td>
                            <td class="selectionData">
                                <select required name="GPU">

                                    <?php
                                    $gpu = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.gpu_id WHERE mylist.id=$id; ";
                                    $result = mysqli_query($conn, $gpu);
                                    $gpu = mysqli_fetch_assoc($result);
                                    ?>
                                    <?php if (!empty($gpu['id'])) : ?>
                                        <option value="<?php echo $gpu['id']; ?>"><?php echo $gpu['item_name'] . " - RM " . $gpu['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose GPU</option>
                                    <?php endif; ?>

                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Graphic Card');"); ?>

                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $gpu["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr class="tableRow">
                            <td class="componentData">Power Supply Unit</td>
                            <td class="selectionData">
                                <select required name="PSU">

                                    <?php
                                    $psu = "SELECT items.id, items.item_name, items.price FROM items JOIN mylist ON items.id = mylist.psu_id WHERE mylist.id=$id; ";
                                    $result = mysqli_query($conn, $psu);
                                    $psu = mysqli_fetch_assoc($result);
                                    ?>
                                    <?php if (!empty($psu['id'])) : ?>
                                        <option value="<?php echo $psu['id']; ?>"><?php echo $psu['item_name'] . " - RM " . $psu['price']; ?></option>
                                    <?php else : ?>
                                        <option value="">Choose PSU</option>
                                    <?php endif; ?>

                                    <?php
                                    $rows = mysqli_query($conn, "SELECT items.id, items.item_name, items.price, categories.category_name FROM items LEFT JOIN categories ON items.category_id = categories.id WHERE item_status = 'Active' AND LOWER(categories.category_name) = LOWER('Power Supply Unit');"); ?>

                                    <?php foreach ($rows as $row) :
                                        if ($row["id"] != $psu["id"]) { ?>

                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["item_name"] . " - RM " . $row["price"]; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                    </table>
                    <div class="addButton">

                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button class="submitAddButton" type="submit" name="add">UPDATE</button>
                    </div>

                </form>
            </div>
            <br>



        </div>
        </div>
    </main>
    <?php
    include 'footer.php';
    ?>
</body>

</html>