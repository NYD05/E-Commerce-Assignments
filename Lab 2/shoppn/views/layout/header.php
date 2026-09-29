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
    </style>
</head>

<body>

<header>

    <nav>

        <div>
            <strong>Shoppn</strong>
        </div>

        <div>
            <a href="/shoppn/index.php">Home</a>

            <?php if (is_logged_in()): ?>

                <span>
                    Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'User') ?>
                </span>

                <a href="/shoppn/views/account/my_account.php">
                    My Account
                </a>

                <a href="/shoppn/logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a href="/shoppn/views/register.php">
                    Register
                </a>

                <a href="/shoppn/views/login.php">
                    Login
                </a>

            <?php endif; ?>

        </div>

    </nav>

    <div class="search">
        <form method="GET" action="/shoppn/index.php">
            <input
                type="text"
                name="search"
                placeholder="Search products..."
            >
            <button type="submit">Search</button>
        </form>
    </div>

</header>

<?php

if (isset($_SESSION['error'])) {
    echo '<div class="error">'
        . htmlspecialchars($_SESSION['error'])
        . '</div>';

    unset($_SESSION['error']);
}

?>