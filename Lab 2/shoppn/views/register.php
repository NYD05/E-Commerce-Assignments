<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';

?>

<?php require_once __DIR__ . '/layout/header.php'; ?>

<main style="max-width: 600px; margin: 40px auto; padding: 20px;">

    <h1>Create an Account</h1>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <form
        id="registerForm"
        action="/~nuku.dzakuma/E-Commerce/shoppn/actions/register_action.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <p>
            <label for="name">Full Name:</label><br>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label for="email">Email:</label><br>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="50"
                required
            >
        </p>

        <p>
            <label for="pass">Password:</label><br>
            <input
                type="password"
                id="pass"
                name="pass"
                required
            >
        </p>

        <p>
            <label for="country">Country:</label><br>
            <input
                type="text"
                id="country"
                name="country"
                maxlength="30"
                required
            >
        </p>

        <p>
            <label for="city">City:</label><br>
            <input
                type="text"
                id="city"
                name="city"
                maxlength="30"
                required
            >
        </p>

        <p>
            <label for="contact">Contact Number:</label><br>
            <input
                type="text"
                id="contact"
                name="contact"
                maxlength="15"
                required
            >
        </p>

        <p>
            <label for="address">Address:</label><br>
            <input
                type="text"
                id="address"
                name="address"
            >
        </p>

        <p>
            <label for="image">Profile Image (Optional):</label><br>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
            >
        </p>

        <button type="submit">
            Register
        </button>

    </form>

    <p>
        Already have an account?
        <a href="/~nuku.dzakuma/E-Commerce/shoppn/views/login.php">
            Login here
        </a>
    </p>

</main>

<script src="/~nuku.dzakuma/E-Commerce/shoppn/js/validate.js"></script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>