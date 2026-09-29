<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';

?>

<?php require_once __DIR__ . '/layout/header.php'; ?>

<main style="max-width: 500px; margin: 40px auto; padding: 20px;">

    <h1>Login</h1>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <form
        action="/shoppn/actions/login_action.php"
        method="POST"
    >

        <p>
            <label for="email">Email:</label><br>

            <input
                type="email"
                id="email"
                name="email"
                maxlength="50"
                required
                style="width: 100%; padding: 8px;"
            >
        </p>


        <p>
            <label for="pass">Password:</label><br>

            <input
                type="password"
                id="pass"
                name="pass"
                required
                style="width: 100%; padding: 8px;"
            >
        </p>


        <button
            type="submit"
            style="padding: 10px 20px;"
        >
            Login
        </button>

    </form>


    <p style="margin-top: 20px;">
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
