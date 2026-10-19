<?php
// controllers/CartController.php — thin layer between Actions/Views and the Cart Model.
require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private CartClass $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    /* Task 11 — add to cart & summary */
    public function isInCart(int $product_id, string $ip): bool
    {
        return $this->cart->isInCart($product_id, $ip);
    }

    public function addToCart(int $product_id, int $qty, string $ip, ?int $customer_id = null): bool
    {
        return $this->cart->addToCart($product_id, $qty, $ip, $customer_id);
    }

    public function getCartItems(string $ip): array { return $this->cart->getCartItems($ip); }
    public function getCartCount(string $ip): int   { return $this->cart->getCartCount($ip); }
    public function getCartTotal(string $ip): float { return $this->cart->getCartTotal($ip); }

    /* Task 12 — cart management */
    public function removeFromCart(int $product_id, string $ip): bool
    {
        return $this->cart->removeFromCart($product_id, $ip);
    }

    public function updateQty(int $product_id, int $new_qty, string $ip): bool
    {
        return $this->cart->updateQty($product_id, $new_qty, $ip);
    }
}
