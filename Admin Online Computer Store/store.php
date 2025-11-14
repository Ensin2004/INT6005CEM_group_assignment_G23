<?php
require_once "includes/security.php";   // contains sanitize_basic(), escape_sql()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Store</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<?php
    include 'header.php';
    include 'includes/storeGetVar.php'; // provides $itemResult and $categoryResult
?>

<main>
    <!-- add new item button -->
    <a class="add_button" href="newItem.php"><i class="fa-solid fa-circle-plus"></i></a>

    <div class="store_head">
        <!-- search bar -->
        <form class="search_bar" action="store.php" method="get">
            <div class="search_box">
                <input
                    type="text"
                    name="search"
                    placeholder="Search"
                    value="<?php echo isset($_GET['search']) ? sanitize_basic($_GET['search']) : ''; ?>"
                >
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <!-- category bar -->
        <div class="category_bar">
            <?php
            // read selected category
            $selectedCat = isset($_GET['category']) ? sanitize_basic($_GET['category']) : null;

            if (empty($selectedCat)) { // no chosen category
                echo '<a class="category_button selected" href="store.php">ALL</a>';
                while ($row = mysqli_fetch_assoc($categoryResult)) {
                    $catName = sanitize_basic($row["category_name"]);
                    $catHref = "store.php?category=" . $catName;
                    echo '<a class="category_button" href="'.$catHref.'">' .
                         strtoupper($catName) . '</a>';
                }
            } else { // chosen category
                echo '<a class="category_button" href="store.php">ALL</a>';
                mysqli_data_seek($categoryResult, 0); // reset pointer
                while ($row = mysqli_fetch_assoc($categoryResult)) {
                    $catName = sanitize_basic($row["category_name"]);
                    $isSelected = (mb_strtolower($catName) === mb_strtolower($selectedCat));
                    $catHref = "store.php?category=" . $catName;
                    $cls = $isSelected ? 'category_button selected' : 'category_button';
                    echo '<a class="'.$cls.'" href="'.$catHref.'">' .
                         strtoupper($catName) . '</a>';
                }
            }
            ?>
        </div>
    </div>

    <div class="store_body">
        <?php
            $term = isset($_GET['search']) ? sanitize_basic($_GET['search']) : '';
            $cat  = isset($_GET['category']) ? sanitize_basic($_GET['category']) : '';

            $count = ($itemResult instanceof mysqli_result) ? $itemResult->num_rows : 0;

            // summary label
            if ($term !== '') {
                $label = "Results for ‘" . $term . "’ — {$count} " . ($count === 1 ? "item" : "items");
            } elseif ($cat !== '') {
                $label = "Category: " . $cat . " — {$count} " . ($count === 1 ? "item" : "items");
            } else {
                $label = "All items — {$count} " . ($count === 1 ? "item" : "items");
            }

            echo '<p class="result_summary" style="margin: 8px 0 16px; color:#555;">' . $label . '</p>';

            if (!$itemResult || $count === 0) {
                if ($term !== '') {
                    echo '<p class="no_result">No results for ‘' . $term . '’</p>';
                } elseif ($cat !== '') {
                    echo '<p class="no_result">No items in category ‘' . $cat . '’</p>';
                } else {
                    echo '<p class="no_result">No Result</p>';
                }
            } else {
                echo '<table class="item_display">';
                while ($row = mysqli_fetch_assoc($itemResult)) {
                    $id    = (int)$row['id'];
                    $name  = sanitize_basic($row['item_name']);
                    $desc  = sanitize_basic($row['description']);
                    $price = sanitize_basic($row['price']);
                    $img1f = sanitize_basic($row['image1'] ?? '');
                    $imgSrc = "../Image/" . $img1f;
                    $stock = (int)$row['stock_qty'];
                    $detailsHref = "itemDetails.php?item={$id}";
                    ?>
                    <tr>
                        <!-- image -->
                        <td style="width: 20%;">
                            <a href="<?php echo $detailsHref; ?>">
                                <img class="item_image" src="<?php echo $imgSrc; ?>" alt="<?php echo $name; ?>">
                            </a>
                        </td>

                        <!-- details -->
                        <td style="width: 70%;">
                            <a class="item_details" href="<?php echo $detailsHref; ?>">
                                <p class="item_name"><?php echo $name; ?></p>
                                <p class="item_desc"><?php echo $desc; ?></p>
                                <br><br><br>
                                <p class="item_price"><?php echo 'RM ' . $price; ?></p>
                                <?php
                                if ($stock === 0) {
                                    echo '<p class="item_stock" style="color: rgb(255, 52, 52);">Stock: ' . $stock . '</p>';
                                } else {
                                    echo '<p class="item_stock" style="color: rgb(60, 179, 113);">Stock: ' . $stock . '</p>';
                                }
                                ?>
                            </a>
                        </td>

                        <!-- edit and delete button -->
                        <td style="width: 10%; border-left: 2px solid lightgray;">
                            <div class="manage_item">
                                <a class="edit_button" href="editItem.php?item=<?php echo $id; ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> EDIT
                                </a>
                                <a class="delete_button" href="includes/deleteItem.php?item=<?php echo $id; ?>">
                                    <i class="fa-solid fa-trash-can"></i> DELETE
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                echo '</table>';
            }
        ?>
    </div>

</main>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.querySelector('.add_button');
    const footer = document.querySelector('footer');
    const offset = 0;

    function checkPosition() {
        const footerRect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (footerRect.top < windowHeight) {
            addButton.style.transform = `translateY(${footerRect.top - windowHeight + offset}px)`;
        } else {
            addButton.style.transform = 'translateY(0)';
        }
    }

    window.addEventListener('scroll', checkPosition);
    window.addEventListener('resize', checkPosition);
    checkPosition();
});
</script>

<script src="js/sessionTimeout.js"></script>
</body>
</html>
