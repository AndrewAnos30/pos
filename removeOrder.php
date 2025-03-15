<?php
include('connection/conn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Remove product and any linked add-ons
    mysqli_query($conn, "DELETE FROM orders WHERE id = '$id' OR parent_order_id = '$id'");

    // Redirect back to cart
    header('Location: cart.php');
    exit;
}
