<?php
require_once "includes/security.php";

// Restrict access to Super Admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    echo "<script>alert('Access denied. Super Admins only.'); window.location.href='home.php';</script>";
    exit();
}

require_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Managers</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/managers.css"> <!-- reuse same styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class="category_bar">
            <a class="category_button <?= !isset($_GET['filter']) ? 'selected' : '' ?>" href="managers.php">ALL</a>
            <a class="category_button <?= (isset($_GET['filter']) && $_GET['filter'] === 'super_admin') ? 'selected' : '' ?>" href="managers.php?filter=super_admin">SUPER ADMINS</a>
            <a class="category_button <?= (isset($_GET['filter']) && $_GET['filter'] === 'manager') ? 'selected' : '' ?>" href="managers.php?filter=manager">MANAGERS</a>
        </div>

        <div class="order_body">
            <?php
            $filter = $_GET['filter'] ?? ''; // get filter value (if any)

            if ($filter === 'super_admin') {
                $query = "SELECT * FROM admins WHERE role = 'super_admin'";
            } elseif ($filter === 'manager') {
                $query = "SELECT * FROM admins WHERE role = 'manager'";
            } else {
                $query = "SELECT * FROM admins";
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                echo '<p class="no_result">No Managers Found</p>';
            } else {
                echo '<table class="order_display">
                    <tr class="table_head">
                        <td style="width: 10%;">ID</td>
                        <td style="width: 25%;">Name</td>
                        <td style="width: 25%;">Email</td>
                        <td style="width: 15%; text-align: center;">Role</td>
                        <td style="width: 15%; text-align: center;">Action</td>
                    </tr>';

                while ($row = mysqli_fetch_assoc($result)) {
                    // Add a CSS class for banned accounts
                    $rowClass = ($row['account_status'] === 'banned') ? 'banned_row' : 'table_body';
                    echo '<tr class="' . $rowClass . '">';
                    echo '<td><p>#' . $row['id'] . '</p></td>';
                    echo '<td><p>' . htmlspecialchars($row['admin_name']);
                    if ($row['account_status'] === 'banned') {
                        echo ' <span class="banned_tag">(BANNED)</span>';
                    }
                    echo '</p></td>';

                    echo '<td><p>' . htmlspecialchars($row['admin_email']) . '</p></td>';

                    // Role
                    if ($row['role'] === 'super_admin') {
                        echo '<td style="text-align:center;"><p class="green">SUPER ADMIN</p></td>';
                    } else {
                        echo '<td style="text-align:center;"><p class="blue">MANAGER</p></td>';
                    }

                    // Actions (prevent deleting super admin)
                    echo '<td class="action_col">';
                    if ($row['role'] !== 'super_admin') {
                        if ($row['account_status'] === 'active') {
                            echo '<a class="enabled" href="includes/banManager.php?id=' . $row['id'] . '&action=ban"
                                onclick="return confirm(\'Are you sure you want to ban this manager?\');" title="Ban Manager">
                                <i class="fa-solid fa-user-slash"></i></a>';
                        } else {
                            echo '<a class="enabled" href="includes/banManager.php?id=' . $row['id'] . '&action=unban"
                                onclick="return confirm(\'Are you sure you want to unban this manager?\');" title="Unban Manager">
                                <i class="fa-solid fa-user-check"></i></a>';
                        }
                        echo '<a class="enabled" href="includes/deleteManager.php?id=' . $row['id'] . '" 
                            onclick="return confirm(\'Are you sure you want to delete this manager?\');">
                            <i class="fa-solid fa-trash"></i></a>';
                    } else {
                        echo '<a class="disabled"><i class="fa-solid fa-ban"></i></a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            }
            ?>
        </div>
            <a class="add_button" href="addManager.php">
                <i class="fa-solid fa-user-plus"></i> Add Manager
            </a>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('.add_button');
            const footer = document.querySelector('footer');
            const offset = 0; // how high above the footer to float

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

    <?php include 'footer.php'; ?>
</body>
</html>
