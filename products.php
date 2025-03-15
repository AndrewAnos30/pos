<?php
include('connection/conn.php');
include('includes/navbar.php');

// Delete item with confirmation
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    $type = $_GET['type'];

    $table = ($type == 'product') ? 'products' : (($type == 'addon') ? 'addons' : 'category');
    mysqli_query($conn, "DELETE FROM $table WHERE id = $id");
    header("Location: products.php");
    exit();
}

// Fetch data
$products = mysqli_query($conn, "SELECT id, name, price FROM products");
$addons = mysqli_query($conn, "SELECT id, name, price FROM addons");
$categories = mysqli_query($conn, "SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products & Add-ons</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

    <!-- Products Table -->
    <h2>Products</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($products)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <a href="forms.php?edit=<?php echo $row['id']; ?>&type=product" class="btn-edit">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>, 'product')" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add-ons Table -->
    <h2>Add-ons</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($addons)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>₱<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <a href="forms.php?edit=<?php echo $row['id']; ?>&type=addon" class="btn-edit">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>, 'addon')" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Categories Table -->
    <h2>Categories</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="forms.php?edit=<?php echo $row['id']; ?>&type=category" class="btn-edit">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>, 'category')" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    function confirmDelete(id, type) {
        if (confirm('Are you sure you want to delete this ' + type + '?')) {
            window.location.href = '?delete=' + id + '&type=' + type;
        }
    }
</script>

<style>
    .container {
        max-width: 1000px;
        margin: auto;
    }
    h2 {
        text-align: center;
    }
    .table-container {
        overflow-x: auto;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background: #e27a3f;
        color: white;
    }
    .btn-edit, .btn-delete {
        margin-right: 5px;
        text-decoration: none;
    }
    .btn-edit {
        color: gray;
    }
    .btn-delete {
        color: red;
    }
</style>

</body>
</html>