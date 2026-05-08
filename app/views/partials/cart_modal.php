<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../models/Book.php';

$cart = $_SESSION['cart'] ?? [];
$bookIds = array_keys($cart);
$cartItems = Book::findByIds($bookIds);

$totalBs = 0.0;
foreach ($cartItems as &$item) {
    $qty = $cart[$item['id']] ?? 1;
    $item['cantidad'] = $qty;
    $item['subtotal_bs'] = $qty * (float) $item['precio_bs'];
    $totalBs += $item['subtotal_bs'];
}
unset($item);
?>

<div class="cart-modal-backdrop" id="modal-cart">
    <div class="cart-modal">
        <div class="cart-modal-header">
            <span class="cart-modal-title">Mi Carrito</span>
            <button class="cart-modal-close" data-close-modal="modal-cart">&times;</button>
        </div>
        <div class="cart-modal-body">
            <?php if (empty($cartItems)): ?>
                <p class="cart-empty">Tu carrito está vacío</p>
            <?php else: ?>
                <ul class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <li class="cart-item">
                            <div>
                                <div class="cart-item-name">
                                    <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="cart-item-qty">
                                    Cantidad: <?php echo (int) $item['cantidad']; ?>
                                </div>
                            </div>
                            <div class="cart-item-price">
                                <?php echo number_format($item['subtotal_bs'], 2, ',', '.'); ?> Bs
                                <form method="post" action="index.php?action=remove_from_cart" class="cart-remove-form">
                                    <input type="hidden" name="book_id" value="<?php echo (int) $item['id']; ?>">
                                    <button type="submit" class="cart-remove-button">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="cart-modal-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span><?php echo number_format($totalBs, 2, ',', '.'); ?> Bs</span>
            </div>
            <div class="cart-actions">
                <form method="get" action="index.php">
                    <input type="hidden" name="action" value="checkout">
                    <button type="submit" class="btn-primary cart-checkout-btn" <?php echo $totalBs <= 0 ? 'disabled' : ''; ?>>
                        Proceder al Pago
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

