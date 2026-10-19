<?php
// classes/CartClass.php — Model: the ONLY place SQL runs for the cart.
// Cart rows are tied to the visitor's IP address, so guests can shop without an account.
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database
{
    /* ---------------- ADD TO CART (Task 11) ---------------- */

    // true if this product is already in this visitor's cart
    public function isInCart(int $product_id, string $ip): bool
    {
        $stmt = $this->conn->prepare("SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ? LIMIT 1");
        $stmt->bind_param("is", $product_id, $ip);
        $stmt->execute();
        $found = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $found;
    }

    // $customer_id is accepted for later tasks (checkout); the cart itself is keyed by IP.
    public function addToCart(int $product_id, int $qty, string $ip, ?int $customer_id = null): bool
    {
        if ($qty < 1) {
            return false;
        }
        $stmt = $this->conn->prepare("INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $product_id, $ip, $qty);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getCartItems(string $ip): array
    {
        $stmt = $this->conn->prepare(
            "SELECT cart.*, products.product_title, products.product_price, products.product_image
               FROM cart
               JOIN products ON cart.p_id = products.product_id
              WHERE cart.ip_add = ?"
        );
        $stmt->bind_param("s", $ip);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows ?: [];
    }

    public function getCartCount(string $ip): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cart WHERE ip_add = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $count = (int)$stmt->get_result()->fetch_row()[0];
        $stmt->close();
        return $count;
    }

    // SUM(qty * product_price) over this visitor's cart items
    public function getCartTotal(string $ip): float
    {
        $total = 0.0;
        foreach ($this->getCartItems($ip) as $item) {
            $total += (int)$item['qty'] * (float)$item['product_price'];
        }
        return $total;
    }

    /* ---------------- CART MANAGEMENT (Task 12) ---------------- */

    public function removeFromCart(int $product_id, string $ip): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE p_id = ? AND ip_add = ?");
        $stmt->bind_param("is", $product_id, $ip);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateQty(int $product_id, int $new_qty, string $ip): bool
    {
        if ($new_qty < 1) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?");
        $stmt->bind_param("iis", $new_qty, $product_id, $ip);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
