<?php
require_once "dbh.inc.php";
require_once "security.php";

if (!$conn) {
    die("Database connection failed");
}

// Sanitize inputs
$category_raw = $_GET['category'] ?? null;
$search_raw   = $_GET['search']   ?? null;

$category = $category_raw !== null ? sanitize_basic($category_raw) : null;
$search   = $search_raw   !== null ? sanitize_basic($search_raw)   : null;

/**
 * create escaped versions using mysqli_real_escape_string
 * used ONLY for the legacy fallback block (dynamic SQL)
 */
$category_esc = $category !== null ? $conn->real_escape_string($category) : null;
$search_esc   = $search   !== null ? $conn->real_escape_string($search)   : null;

/**
 * ===== PARAMETERIZED QUERIES =====
 * These are the live queries used by the page.
 */

// 1) No filters
if ($category === null && $search === null) {
    $sql = "
      SELECT items.id, items.item_name, items.price, items.stock_qty, items.description,
             items.image1, items.image2, items.image3, items.image4, items.image5,
             categories.category_name
      FROM items
      LEFT JOIN categories ON items.category_id = categories.id
      WHERE item_status = 'Active';
    ";
    $itemResult = $conn->query($sql);

// 2) Category only
} elseif ($category !== null && $search === null) {
    $sql = "
      SELECT items.id, items.item_name, items.price, items.stock_qty, items.description,
             items.image1, items.image2, items.image3, items.image4, items.image5,
             categories.category_name
      FROM items
      LEFT JOIN categories ON items.category_id = categories.id
      WHERE item_status = 'Active'
        AND LOWER(categories.category_name) = LOWER(?);
    ";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $category); // escaping not needed for prepared statements
        $stmt->execute();
        $itemResult = $stmt->get_result();
        $stmt->close();
    } else {
        /**
         * ===== Legacy Fallback (dynamic SQL) with mysqli_real_escape_string =====
         * Only used if prepare() fails — show proper escaping
         */
        $sql_legacy = "
          SELECT items.id, items.item_name, items.price, items.stock_qty, items.description,
                 items.image1, items.image2, items.image3, items.image4, items.image5,
                 categories.category_name
          FROM items
          LEFT JOIN categories ON items.category_id = categories.id
          WHERE item_status = 'Active'
            AND LOWER(categories.category_name) = LOWER('{$category_esc}');
        ";
        $itemResult = $conn->query($sql_legacy);
    }

// 3) Search (LIKE across multiple fields)
} else {
    $like = "%{$search}%";
    $sql = "
      SELECT items.id, items.item_name, items.price, items.stock_qty, items.description,
             items.image1, items.image2, items.image3, items.image4, items.image5,
             categories.category_name
      FROM items
      LEFT JOIN categories ON items.category_id = categories.id
      WHERE item_status = 'Active'
        AND (
          LOWER(items.item_name)             LIKE LOWER(?)
          OR LOWER(items.description)        LIKE LOWER(?)
          OR LOWER(categories.category_name) LIKE LOWER(?)
        );
    ";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $like, $like, $like); // escaping not needed for prepared statements
        $stmt->execute();
        $itemResult = $stmt->get_result();
        $stmt->close();
    } else {
        /**
         * ===== Legacy Fallback (dynamic SQL) with mysqli_real_escape_string =====
         * Build the wildcard in PHP, escape, then inject safely into the string
         * Note: when using dynamic SQL with LIKE, also escape '%' and '_' if needed.
         */
        $like_esc = $conn->real_escape_string("%{$search}%");
        $sql_legacy = "
          SELECT items.id, items.item_name, items.price, items.stock_qty, items.description,
                 items.image1, items.image2, items.image3, items.image4, items.image5,
                 categories.category_name
          FROM items
          LEFT JOIN categories ON items.category_id = categories.id
          WHERE item_status = 'Active'
            AND (
              LOWER(items.item_name)             LIKE LOWER('{$like_esc}')
              OR LOWER(items.description)        LIKE LOWER('{$like_esc}')
              OR LOWER(categories.category_name) LIKE LOWER('{$like_esc}')
            );
        ";
        $itemResult = $conn->query($sql_legacy);
    }
}

// Categories (no user input involved)
$categoryResult = $conn->query("SELECT category_name FROM categories;");
