<?php
// Include database connection
include 'connection/conn.php';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bought_data.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV header
fputcsv($output, ['id', 'product_name', 'type', 'created_at', 'quantity', 'product_price', 'final_price']);

$totalFinalPrice = 0;

// Fetch and process data from the 'bought' table
$query = "SELECT id, product_name, type, created_at, quantity, product_price FROM bought";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $finalPrice = $row['quantity'] * $row['product_price'];
        $totalFinalPrice += $finalPrice;

        // Output the created_at value as it is
        fputcsv($output, [
            $row['id'],
            $row['product_name'],
            $row['type'],
            $row['created_at'], // No date conversion
            $row['quantity'],
            $row['product_price'],
            $finalPrice
        ]);
    }
}

// Add a "Total" row for the final price
fputcsv($output, ['', '', '', '', '', 'Total', $totalFinalPrice]);

// Close resources
fclose($output);
$conn->close();
exit();
