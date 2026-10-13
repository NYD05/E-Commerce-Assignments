<?php

require_once __DIR__ . '/../../core/core.php';

require_login();

require_once __DIR__ . '/../layout/header.php';

?>

<main style="max-width: 800px; margin: 40px auto; padding: 20px;">

    <h1>My Account</h1>

    <p>
        Welcome,
        <strong>
            <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer') ?>
        </strong>
    </p>

    <p>
        Email:
        <?= htmlspecialchars($_SESSION['customer_email'] ?? '') ?>
    </p>

    <p>
        You are successfully logged in to your Shoppn account.
    </p>

</main>

<?php

require_once __DIR__ . '/../layout/footer.php';

?>