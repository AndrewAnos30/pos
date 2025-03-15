<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fafafa;
        margin: 0;
        padding: 0;
        -webkit-tap-highlight-color: transparent;
    }

    .top-bar {
        padding: 12px 16px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .logo {
        height: 40px;
        cursor: pointer;
    }

    .menu-container {
        position: relative;
    }

    .icon {
        font-size: 1.8rem;
        color: #555;
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 40px;
        right: 0;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        z-index: 20;
        overflow: hidden;
        width: 180px;
    }

    .dropdown-menu a {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background 0.2s ease;
    }

    .dropdown-menu a:hover {
        background-color: #f0f0f0;
    }

    .disabled {
        pointer-events: none;
        color: #aaa;
    }
</style>

<!-- Top Bar -->
<div class="top-bar">
    <img src="./logo.png" alt="Logo" class="logo" onclick="window.location.href='index.php'">
    <div class="menu-container">
        <div class="icon" onclick="toggleMenu()">☰</div>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="dashboard.php">Dashboard</a>
            <a href="cashier.php">Cashier</a>
            <a href="products.php">Products</a>
            <a href="forms.php">Add Items</a>
            <a href="transaction.php">Transaction</a>
            <a id="downloadLink" href="download.php">Download</a>

        </div>
    </div>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('dropdownMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    window.onclick = function(event) {
        if (!event.target.closest('.menu-container')) {
            document.getElementById('dropdownMenu').style.display = 'none';
        }
    }

    // Disable download link unless it's the last day of the month
    document.addEventListener('DOMContentLoaded', function () {
        const downloadLink = document.getElementById('downloadLink');
        const today = new Date();
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();

        if (today.getDate() !== lastDay) {
            downloadLink.classList.add('disabled');
            downloadLink.removeAttribute('href');
        } else {
            // Show reminder on the last day of the month
            alert("Reminder: Please download your data today.");
        }
    });
</script>
