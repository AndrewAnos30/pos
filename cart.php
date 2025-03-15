<?php
include('connection/conn.php');

// Fetch main products (where parent_order_id is NULL)
$query = "SELECT * FROM orders WHERE type = 'product' AND parent_order_id IS NULL";
$products = mysqli_query($conn, $query);

$subtotal = 0;
$hasProducts = mysqli_num_rows($products) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width:90%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .empty-cart {
            text-align: center;
            color: #777;
            font-size: 18px;
            padding: 30px 0;
        }

        .order-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .item-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quantity {
            font-weight: bold;
            color: #e27a3f;
            min-width: 30px;
            text-align: center;
        }

        .name {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .label {
            background-color: #eee;
            color: #555;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .price {
            font-weight: bold;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #e27a3f;
            font-size: 18px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .remove-btn:hover {
            color: #e27a3f;
        }

        .addon-list {
            padding-left: 30px;
            margin-top: 5px;
        }

        .addon-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #777;
        }

        .order-summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 8px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .summary-item.total {
            font-weight: bold;
            border-top: 1px solid #eee;
            margin-top: 10px;
            padding-top: 10px;
        }

        .checkout-btn {
            background-color: #e27a3f;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.2s ease;
        }

        .checkout-btn:hover {
            background-color: #e27a3f;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #e27a3f;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>Your Order</h2>
    </header>

    <div class="order-list">
        <?php if ($hasProducts): ?>
            <?php while ($product = mysqli_fetch_assoc($products)): ?>
                <?php 
                    $product_total = $product['product_price'] * $product['quantity'];
                    $subtotal += $product_total;
                ?>
                <div class="order-item">
                    <div class="item-details">
                        <span class="quantity">x<?= $product['quantity'] ?></span>
                        <span class="name">
                            <span><?= htmlspecialchars($product['product_name']) ?></span>
                            <span class="label">Product</span>
                        </span>
                        <span class="price">₱<?= number_format($product['product_price'], 2) ?></span>
                        <!-- Remove Button -->
                        <form method="POST" action="removeOrder.php" class="remove-form">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="submit" class="remove-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Add-ons -->
                    <div class="addon-list">
                        <?php
                        $addons = mysqli_query($conn, "SELECT * FROM orders WHERE parent_order_id = '{$product['id']}'");
                        while ($addon = mysqli_fetch_assoc($addons)):
                            $addon_total = $addon['product_price'] * $addon['quantity'];
                            $subtotal += $addon_total;
                        ?>
                            <div class="addon-item">
                                <span class="name">
                                    <span><?= htmlspecialchars($addon['product_name']) ?></span>
                                    <span class="label">Add-on</span>
                                </span>
                                <span class="price">₱<?= number_format($addon['product_price'], 2) ?></span>
                                <form method="POST" action="removeOrder.php" class="remove-form">
                                    <input type="hidden" name="id" value="<?= $addon['id'] ?>">
                                    <button type="submit" class="remove-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-2x"></i>
                <p>Your cart is empty!</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($hasProducts): ?>
        <div class="order-summary">
            <div class="summary-item total">
                <span>Total</span>
                <span>₱<?= number_format($subtotal, 2) ?></span>
            </div>
        </div>

        <form method="POST" action="checkout.php">
            <button type="submit" class="checkout-btn">Process Order</button>
        </form>
    <?php endif; ?>

    <a href="cashier.php" class="back-link">Back to Cashier</a>
</div>

</body>
</html>
