<?php
include('connection/conn.php');

$product_id = $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id"));
$addons = mysqli_query($conn, "SELECT * FROM addOns");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedAddOns = $_POST['addons'] ?? [];
    $product_price = $product['price'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO orders (product_id, product_name, product_price, quantity, type) 
            VALUES ('$product_id', '{$product['name']}', '$product_price', '$quantity', 'product')";
    mysqli_query($conn, $sql);
    $order_id = mysqli_insert_id($conn);

    foreach ($selectedAddOns as $addon_id) {
        $addon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM addOns WHERE id = $addon_id"));
        $addon_name = $addon['name'];
        $addon_price = $addon['price'];

        $sql = "INSERT INTO orders (product_id, product_name, product_price, quantity, type, parent_order_id) 
                VALUES ('$addon_id', '$addon_name', '$addon_price', '$quantity', 'addon', '$order_id')";
        mysqli_query($conn, $sql);
    }

    header("Location: cashier.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Order</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            color: #e27a3f;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f0f0f0;
            padding: 8px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .quantity-btn {
            background-color: #e27a3f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .quantity-btn:hover {
            background-color: #cc6936;
        }

        .quantity-value {
            font-size: 18px;
            font-weight: 600;
        }

        .addon-list {
            margin-bottom: 20px;
        }

        .addon-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background-color: #fafafa;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: background 0.3s ease;
            border: 1px solid #eee;
        }

        .addon-item:hover {
            background-color: #f0f0f0;
        }

        .addon-item input[type="checkbox"] {
            margin-right: 12px;
            accent-color: #e27a3f;
            transform: scale(1.2);
            cursor: pointer;
        }

        button[type="submit"] {
            background-color: #e27a3f;
            color: #fff;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #cc6936;
        }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #555;
            font-size: 16px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .cancel-link:hover {
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Customize <?= htmlspecialchars($product['name']) ?></h2>

    <form method="POST">
        <label>Quantity:</label>
        <div class="quantity-selector">
            <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
            <span class="quantity-value" id="quantity">1</span>
            <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
            <input type="hidden" name="quantity" id="quantity-input" value="1">
        </div>

        <h3>Select Add-ons:</h3>
        <div class="addon-list">
            <?php while ($addon = mysqli_fetch_assoc($addons)): ?>
                <div class="addon-item">
                    <label>
                        <input type="checkbox" name="addons[]" value="<?= $addon['id'] ?>">
                        <?= htmlspecialchars($addon['name']) ?> - ₱<?= number_format($addon['price'], 2) ?>
                    </label>
                </div>
            <?php endwhile; ?>
        </div>

        <button type="submit">Add to Cart</button>
    </form>

    <a href="cashier.php" class="cancel-link">Cancel</a>
</div>

<script>
    function changeQuantity(amount) {
        const quantityInput = document.getElementById('quantity-input');
        let currentValue = parseInt(quantityInput.value);
        currentValue += amount;
        if (currentValue < 1) currentValue = 1;
        quantityInput.value = currentValue;
        document.getElementById('quantity').textContent = currentValue;
    }
</script>

</body>
</html>