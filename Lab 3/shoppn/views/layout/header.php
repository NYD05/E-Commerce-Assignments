<?php

require_once __DIR__ . '/../../core/core.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background: #222;
            color: white;
            padding: 15px 30px;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: inline;
        }

        nav li {
            display: inline;
        }

        .search {
            margin-top: 15px;
        }

        .search input {
            padding: 8px;
            width: 250px;
        }

        .search button {
            padding: 8px 15px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            margin: 15px;
        }

        /* Sidebar + product grid (Task 10) */
        .sidebar {
            width: 220px;
            padding: 20px;
            float: left;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin-bottom: 6px;
        }

        .sidebar a {
            color: #222;
        }

        main.with-sidebar {
            margin-left: 260px;
            padding: 20px 30px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .product-card img,
        .product-card .no-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f2f2f2;
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
        }

        .product-card h3 {
            font-size: 16px;
            margin: 10px 0 5px;
        }

        .price {
            font-weight: bold;
            color: #0a7c3a;
        }

        .card-links a {
            margin: 0 6px;
        }

        .product-detail {
            display: grid;
            grid-template-columns: minmax(200px, 350px) 1fr;
            gap: 30px;
        }

        .product-detail img,
        .product-detail .no-image {
            width: 100%;
            min-height: 250px;
            object-fit: cover;
        }

        a.button {
            display: inline-block;
            background: #222;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
        }

        @media (max-width: 700px) {
            .sidebar { float: none; width: auto; }
            main.with-sidebar { margin-left: 0; }
            .product-detail { grid-template-columns: 1fr; }
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

<header>

    <nav>

        <div>
            <strong>Shoppn</strong>
        </div>

        <div>
            <a href="<?= BASE_URL ?>/index.php">Home</a>

            <?php if (is_admin()): ?>
                <ul>
                    <li><a href="<?= BASE_URL ?>/views/admin/brand.php">Brand</a></li>
                    <li><a href="<?= BASE_URL ?>/views/admin/category.php">Category</a></li>
                    <li><a href="<?= BASE_URL ?>/views/admin/product.php">Products</a></li>
                </ul>
            <?php endif; ?>

            <?php if (is_logged_in()): ?>

                <span>
                    Welcome,
                    <?= htmlspecialchars(
                        $_SESSION['customer_name'] ?? 'User',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

                <a href="<?= BASE_URL ?>/views/account/my_account.php">My Account</a>
                <a href="<?= BASE_URL ?>/logout.php">Logout</a>

            <?php else: ?>

                <a href="<?= BASE_URL ?>/views/register.php">Register</a>
                <a href="<?= BASE_URL ?>/views/login.php">Login</a>

            <?php endif; ?>
        </div>

    </nav>

    <div class="search">
        <form method="GET" action="<?= BASE_URL ?>/views/search_results.php">
            <input type="text" name="user_query" placeholder="Search products..."
                   value="<?= htmlspecialchars($_GET['user_query'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Search</button>
        </form>
    </div>

</header>

<?php

if (isset($_SESSION['error'])) {
    echo '<div class="error">'
        . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8')
        . '</div>';

    unset($_SESSION['error']);
}

?>
