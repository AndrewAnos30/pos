<?php
include('connection/conn.php');
include('includes/navbar.php');

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM category");

// Get products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Calculate total amount and item count (considering only 'product' type and its quantities)
$orderQuery = "SELECT 
                SUM(o.product_price * o.quantity) AS totalAmount,
                SUM(CASE WHEN o.type = 'product' THEN o.quantity ELSE 0 END) AS itemCount
              FROM orders o";

$orderResult = mysqli_query($conn, $orderQuery);
$orderData = mysqli_fetch_assoc($orderResult);

$itemCount = $orderData['itemCount'] ?? 0;  // Sum of 'product' quantities
$totalAmount = $orderData['totalAmount'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        /* Category Nav */
        .category-nav {
            display: flex;
            overflow-x: auto;
            padding: 10px;
            background-color: #ffffff;
            gap: 12px;
            white-space: nowrap;
            scrollbar-width: none;
        }

        .category-nav::-webkit-scrollbar {
            display: none;
        }

        .category-item {
            padding: 8px 16px;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 16px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .category-item.active,
        .category-item:hover {
            background-color: #e27a3f;
            color: #fff;
        }

        /* Product Grid */
        .product-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 16px;
        }

        .product {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .product:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .product h3 {
            font-size: 1rem;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .product p {
            font-size: 0.9rem;
            color: #888;
            margin: 4px 0;
        }

        /* Bottom Bar */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #ffffff;
            padding: 16px;
            box-shadow: 0 -4px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
            border-top: 1px solid #e0e0e0;
        }

        .bottom-bar .total {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        .bottom-bar .checkout-btn {
            background-color: #e27a3f;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 32px;
            border: none;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .bottom-bar .checkout-btn:hover {
            background-color: #cc6232;
        }
    </style>
</head>
<body>

<!-- Category Nav -->
<div class="category-nav">
    <div class="category-item active" onclick="filterProducts('all')">All</div>
    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
        <div class="category-item" onclick="filterProducts('<?= $category['id'] ?>')">
            <?= $category['name'] ?>
        </div>
    <?php endwhile; ?>
</div>

<!-- Product List -->
<div class="product-list" id="product-list">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product" data-category="<?= $row['category'] ?>" onclick="window.location.href='customize.php?id=<?= $row['id'] ?>'">
            <h3><?= $row['name'] ?></h3>
            <p>₱<?= number_format($row['price'], 2) ?></p>
        </div>
    <?php endwhile; ?>
</div>

<!-- Bottom Bar -->
<div class="bottom-bar">
    <div class="total">
        <span><?= $itemCount ?> item<?= $itemCount > 1 ? 's' : '' ?> selected</span> - ₱<?= number_format($totalAmount, 2) ?>
    </div>
    <button class="checkout-btn" onclick="window.location.href='cart.php'">
        Checkout
    </button>
</div>

<script>
    function filterProducts(categoryId) {
        const products = document.querySelectorAll('.product');

        products.forEach(product => {
            const productCategory = product.getAttribute('data-category');
            
            if (categoryId === 'all' || productCategory === categoryId) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });

        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active');
        });

        document.querySelector(`.category-item[onclick="filterProducts('${categoryId}')"]`).classList.add('active');
    }
</script>

</body>
</html>