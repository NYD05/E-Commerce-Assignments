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

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>

<header>

    <nav>

        <a class="brand" href="<?= BASE_URL ?>/index.php">Shoppn</a>

        <div class="nav-links">
            <a href="<?= BASE_URL ?>/index.php">Home</a>

            <?php if (is_admin()): ?>
                <ul>
                    <li><a href="<?= BASE_URL ?>/views/admin/brand.php">Brand</a></li>
                    <li><a href="<?= BASE_URL ?>/views/admin/category.php">Category</a></li>
                    <li><a href="<?= BASE_URL ?>/views/admin/product.php">Products</a></li>
                </ul>
            <?php endif; ?>

            <?php if (is_logged_in()): ?>

                <span class="welcome">
                    Hi,
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

<div class="page">

<?php

if (isset($_SESSION['error'])) {
    echo '<div class="error">'
        . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8')
        . '</div>';

    unset($_SESSION['error']);
}

?>
