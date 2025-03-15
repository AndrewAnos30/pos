<?php
include('connection/conn.php');
include('includes/navbar.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }


        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        details {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 16px;
            margin: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        details[open] {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        summary {
            font-size: 1.2rem;
            font-weight: bold;
            color: #000;
            cursor: pointer;
            outline: none;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transaction ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .transaction ul li {
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            color: #555;
        }

        .addon {
            padding-left: 20px;
            color: #777;
            font-size: 0.9rem;
        }

        .total {
            font-weight: bold;
            color: #28a745;
            margin-top: 12px;
            font-size: 1rem;
            text-align: right;
        }

        .back-btn {
            display: block;
            margin: 20px auto;
            padding: 12px 24px;
            background-color: #e27a3f;
            color: white;
            text-align: center;
            border-radius: 32px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background 0.2s ease;
            width: 80%;
            max-width: 400px;
        }

        .back-btn:hover {
            background-color: #e27a3f;
        }
    </style>
</head>
<body>

<h2>Transaction History</h2>

<?php
$query = "SELECT * FROM bought ORDER BY transaction_id DESC, id ASC";
$result = mysqli_query($conn, $query);

$currentTransaction = null;
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    // If new transaction ID, create a new dropdown
    if ($currentTransaction !== $row['transaction_id']) {
        // Close previous transaction if exists
        if ($currentTransaction !== null) {
            echo "<p class='total'>Total: ₱" . number_format($total, 2) . "</p>";
            echo "</ul></details>";
        }

        // Start a new transaction
        $currentTransaction = $row['transaction_id'];
        $total = 0;

        echo "<details class='transaction'>";
        echo "<summary>Transaction ID: " . $currentTransaction . "</summary>";
        echo "<ul>";
    }

    // Display product
    if ($row['type'] === 'product') {
        $currentProductId = $row['id']; // Track product ID
        $productTotal = $row['product_price'] * $row['quantity'];

        echo "<li>";
        echo "<span>" . $row['product_name'] . " x" . $row['quantity'] . "</span>";
        echo "<span>₱" . number_format($productTotal, 2) . "</span>";
        echo "</li>";

        $total += $productTotal;
    }

    // Display add-on if it belongs to the current product
    if ($row['type'] === 'addon' && $row['parent_order_id'] == $currentProductId) {
        $addonTotal = $row['product_price'] * $row['quantity'];

        echo "<li class='addon'>";
        echo "<span>➔ " . $row['product_name'] . " x" . $row['quantity'] . "</span>";
        echo "<span>₱" . number_format($addonTotal, 2) . "</span>";
        echo "</li>";

        $total += $addonTotal;
    }
}

// Close last transaction section
if ($currentTransaction !== null) {
    echo "<p class='total'>Total: ₱" . number_format($total, 2) . "</p>";
    echo "</ul></details>";
}
?>

<a href="cashier.php" class="back-btn">Back to Cashier</a>

</body>

</html>
