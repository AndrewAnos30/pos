<?php
include('connection/conn.php');
include('includes/navbar.php');

// Get today's income
$dailyQuery = "SELECT DATE_FORMAT(created_at, '%M %d, %Y') as date, SUM(product_price * quantity) as total FROM bought WHERE DATE(created_at) = CURDATE() GROUP BY DATE(created_at)";
$dailyResult = mysqli_query($conn, $dailyQuery);

// Get this month's income
$monthlyQuery = "SELECT DATE_FORMAT(created_at, '%M %Y') as month, SUM(product_price * quantity) as total FROM bought WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) GROUP BY DATE_FORMAT(created_at, '%Y-%m')";
$monthlyResult = mysqli_query($conn, $monthlyQuery);

// Get this year's income
$annualQuery = "SELECT YEAR(created_at) as year, SUM(product_price * quantity) as total FROM bought WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY YEAR(created_at)";
$annualResult = mysqli_query($conn, $annualQuery);

// Get all-time income
$allTimeQuery = "SELECT SUM(product_price * quantity) as total FROM bought";
$allTimeResult = mysqli_query($conn, $allTimeQuery);
$allTimeRow = mysqli_fetch_assoc($allTimeResult);
$allTimeTotal = $allTimeRow['total'];

// Get monthly income for the graph
$monthlyGraphQuery = "SELECT DATE_FORMAT(created_at, '%M') as month, SUM(product_price * quantity) as total FROM bought WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at) ORDER BY MONTH(created_at)";
$monthlyGraphResult = mysqli_query($conn, $monthlyGraphQuery);

$graphData = [];
while ($row = mysqli_fetch_assoc($monthlyGraphResult)) {
    $graphData[] = $row;
}

// Calculate annual total
$annualTotal = 0;
while ($row = mysqli_fetch_assoc($annualResult)) {
    $annualTotal += $row['total'];
    $annualData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Dashboard </h2>

<div class="dashboard-container">

    <div class="dashboard-card">
        <h3>Today's Income</h3>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($dailyResult)) { ?>
                <li>
                    <span><?php echo $row['date']; ?></span>
                    <span>₱<?php echo number_format($row['total'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="dashboard-card">
        <h3>This Month's Income</h3>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($monthlyResult)) { ?>
                <li>
                    <span><?php echo $row['month']; ?></span>
                    <span>₱<?php echo number_format($row['total'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>




    <div class="dashboard-card">
        <h3>Monthly Income Graph</h3>
        <canvas id="incomeChart"></canvas>
    </div>

</div>


<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo implode(',', array_map(fn($item) => "'" . $item['month'] . "'", $graphData)); ?>],
            datasets: [{
                label: 'Monthly Income',
                data: [<?php echo implode(',', array_map(fn($item) => $item['total'], $graphData)); ?>],
                borderColor: 'rgb(226, 122, 63)',
                backgroundColor: 'rgb(226, 122, 63)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Monthly Income Overview' }
            }
        }
    });
</script>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fafafa;
        margin: 0;
        padding: 0;
    }

    

    h2 {
        color: #333;
        text-align: center;
        margin-top: 20px;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 16px;
    }

    .dashboard-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        padding: 16px;
        transition: transform 0.2s ease;
    }


    .back-btn {
        display: block;
        margin: 20px auto;
        padding: 12px 24px;
        background-color: #ff4500;
        color: white;
        text-align: center;
        border-radius: 32px;
        text-decoration: none;
    }
</style>

</body>
</html>