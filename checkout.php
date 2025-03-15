<?php
include('connection/conn.php');

// Start transaction to ensure data consistency
mysqli_begin_transaction($conn);

try {
    // ✅ Generate unique transaction ID using uniqid()
    $transactionId = uniqid('txn_');
    $createdAt = date('Y-m-d H:i:s');

    // Fetch main products
    $query = "SELECT * FROM orders WHERE type = 'product' AND parent_order_id IS NULL";
    $products = mysqli_query($conn, $query);

    while ($product = mysqli_fetch_assoc($products)) {
        $productId = $product['id'];
        $productName = $product['product_name'];
        $productPrice = $product['product_price'];
        $quantity = $product['quantity'];

        // ✅ Insert product into `bought`
        $stmt = mysqli_prepare($conn, "INSERT INTO bought (transaction_id, product_name, product_price, quantity, type, created_at) VALUES (?, ?, ?, ?, 'product', ?)");
        mysqli_stmt_bind_param($stmt, 'ssdis', $transactionId, $productName, $productPrice, $quantity, $createdAt);
        mysqli_stmt_execute($stmt);

        // ✅ Get parent ID for linking add-ons
        $parentOrderId = mysqli_insert_id($conn);

        // ✅ Fetch and insert add-ons linked to this product
        $addonQuery = "SELECT * FROM orders WHERE parent_order_id = '$productId'";
        $addons = mysqli_query($conn, $addonQuery);

        while ($addon = mysqli_fetch_assoc($addons)) {
            $addonName = $addon['product_name'];
            $addonPrice = $addon['product_price'];
            $addonQuantity = $addon['quantity'];

            $stmt = mysqli_prepare($conn, "INSERT INTO bought (transaction_id, product_name, product_price, quantity, type, parent_order_id, created_at) VALUES (?, ?, ?, ?, 'addon', ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssdiis', $transactionId, $addonName, $addonPrice, $addonQuantity, $parentOrderId, $createdAt);
            mysqli_stmt_execute($stmt);
        }
    }

    // ✅ Clear the orders after processing
    mysqli_query($conn, "DELETE FROM orders");

    // ✅ Commit transaction
    mysqli_commit($conn);

    // ✅ Redirect back to cashier
    header("Location: cashier.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error processing order: " . $e->getMessage();
}
?>
