<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C&C POS System</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 360px;
            padding: 20px;
            box-sizing: border-box;
        }

        .logo {
            width: 300px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .option {
            background-color: #fff;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .option:hover {
            background-color: #ffe600;
            border-color: #ffcc00;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 1.8rem;
        }

        @media (max-width: 400px) {
            h1 {
                font-size: 1.5rem;
            }

            .option {
                font-size: 1rem;
                padding: 12px;
            }

            .icon {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="logo.png" alt="Logo" class="logo">
        <h1>Welcome to C&C</h1>

        <div class="options">
            <a href="cashier.php" class="option">
                <i class="fa-solid fa-cash-register icon"></i>
                <span>Cashier</span>
            </a>
            <a href="adminDashboard.php" class="option">
                <i class="fa-solid fa-chart-line icon"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

</body>

</html>
