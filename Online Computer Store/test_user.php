<?php
require_once "includes/dbh.inc.php";
require_once "includes/crypto.php";

$id = 31; // user Ensin

// --- Expected real values ---
$expected = [
    'username'     => 'Ensin',
    'email'        => 'ensin2004@gmail.com',  
    'secondary_email' => null,          
    'phone'        => '0193818893',
    'user_address' => 'ensin is kawaii',
];

$res = mysqli_query($conn, "
    SELECT user_name, email, secondary_email, phone, user_address 
    FROM users 
    WHERE id = $id
");

if (!$res || mysqli_num_rows($res) === 0) {
    die("User not found or query error");
}

$row = mysqli_fetch_assoc($res);

// Helper to render row
function show_field($label, $encValue, $expectedPlain = null) {
    $dec = decrypt_field($encValue);

    // Determine match status
    if ($expectedPlain === null) {
        $matchText  = 'Not tested';
        $matchClass = 'status-na';
    } else {
        if ($dec === $expectedPlain) {
            $matchText  = 'MATCH';
            $matchClass = 'status-ok';
        } else {
            $matchText  = 'NOT MATCH';
            $matchClass = 'status-bad';
        }
    }

    echo "<tr>";
    echo "<td>" . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td><code>" . htmlspecialchars($encValue ?? '', ENT_QUOTES, 'UTF-8') . "</code></td>";
    echo "<td>" . htmlspecialchars($dec, ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($expectedPlain ?? '-', ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td class=\"$matchClass\">$matchText</td>";
    echo "</tr>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Encryption for User Ensin</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 40px 10px;
            color: #333;
        }
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px 28px 28px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }
        h1 {
            margin-top: 0;
            font-size: 1.8rem;
            margin-bottom: 4px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 18px;
            font-size: 0.95rem;
        }
        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 0.9rem;
        }
        th, td {
            padding: 8px 10px;
            vertical-align: top;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            text-align: left;
            background: #f3f4f6;
            font-weight: 600;
        }
        code {
            font-size: 0.8rem;
            word-break: break-all;
            background: #f9fafb;
            padding: 3px 5px;
            border-radius: 4px;
            display: inline-block;
        }
        .status-ok {
            color: #166534;
            background: #bbf7d0;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .status-bad {
            color: #b91c1c;
            background: #fecaca;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .status-na {
            color: #6b7280;
            background: #e5e7eb;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .legend {
            margin-top: 14px;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .legend span {
            display: flex;
            align-items: center;
            gap: 8px;          /* spacing between dot and text */
            margin-bottom: 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
            margin-right: 5px;
        }

        .dot-ok { background: #bbf7d0; }
        .dot-bad { background: #fecaca; }
        .dot-na { background: #e5e7eb; }

    </style>
</head>
<body>
<div class="page-wrap">
    <div class="card">
        <span class="pill">Test: AES-256-GCM decryption</span>
        <h1>User #<?php echo $id; ?> – Ensin</h1>
        <p class="subtitle">
            Compares the encrypted values in the database with the decrypted output and the expected real values
            (username, email, phone, address).
        </p>

        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Database value (encrypted)</th>
                    <th>After <code>decrypt_field()</code></th>
                    <th>Expected real value</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php
                show_field('username',        $row['user_name'],       $expected['username']);
                show_field('email',           $row['email'],           $expected['email']);
                show_field('secondary_email', $row['secondary_email'], $expected['secondary_email']); // not tested
                show_field('phone',           $row['phone'],           $expected['phone']);
                show_field('user_address',    $row['user_address'],    $expected['user_address']);
                ?>
            </tbody>
        </table>

        <div class="legend">
            <span><span class="dot dot-ok"></span> MATCH – decrypted value is exactly the same as the expected real value.</span>
            <span><span class="dot dot-bad"></span> NOT MATCH – decrypted value is different from the expected value.</span>
            <span><span class="dot dot-na"></span> Not tested – no expected value provided (e.g. secondary email not set).</span>
        </div>

    </div>
</div>
</body>
</html>
