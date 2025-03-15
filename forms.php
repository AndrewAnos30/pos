<?php
include('connection/conn.php');
include('includes/navbar.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = $_POST['type'];

    if ($type == 'product' || $type == 'addon') {
        $price = floatval($_POST['price']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);

        if ($type == 'product') {
            $query = "INSERT INTO products (name, price, category) VALUES ('$name', '$price', '$category')";
        } elseif ($type == 'addon') {
            $query = "INSERT INTO addons (name, price, category) VALUES ('$name', '$price', '$category')";
        }
    } elseif ($type == 'category') {
        $query = "INSERT INTO category (name) VALUES ('$name')";
    }

    mysqli_query($conn, $query);
    header("Location: products.php");
    exit();
}

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT name FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="styles.css">
    <style>
   

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            background-color: #e27a3f;
            color: white;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #e27a3f;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
                max-width: 100%;
            }

            input, select, button {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Item</h2>
    <form action="forms.php" method="POST">

        <div class="form-group">
            <label for="type">Item Type:</label>
            <select name="type" id="type" required onchange="toggleFields()">
                <option value="">Select Type</option>
                <option value="product">Product</option>
                <option value="addon">Add-on</option>
                <option value="category">Category</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div id="productFields" style="display: none;">
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price">
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category">
                    <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo htmlspecialchars($row['name']); ?>">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <button type="submit">Add Item</button>
    </form>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('type').value;
        const productFields = document.getElementById('productFields');

        if (type === 'product' || type === 'addon') {
            productFields.style.display = 'block';
            document.getElementById('price').required = true;
        } else {
            productFields.style.display = 'none';
            document.getElementById('price').required = false;
        }
    }
</script>

</body>
</html>