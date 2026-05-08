<?php require __DIR__ . '/partials/header.php'; ?>

<main class="checkout-hero">
    <section class="hero-text">
        <h1>Mis libros comprados</h1>
        <p>Desde aquí puedes descargar los libros asociados a tu pedido actual.</p>
    </section>
</main>

<section class="checkout-section">
    <div class="checkout-layout">
        <div class="checkout-left" style="width:100%">
            <h2 class="checkout-title">Descargas disponibles</h2>
            <?php if (empty($downloads)): ?>
                <p class="empty-state">No hay libros comprados para descargar.</p>
            <?php else: ?>
                <ul class="cart-items">
                    <?php foreach ($downloads as $item): ?>
                        <li class="cart-item">
                            <div>
                                <div class="cart-item-name">
                                    <?php echo htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="cart-item-qty">
                                    Autor: <?php echo htmlspecialchars($item['autor'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                            <div>
                                <?php if (!empty($item['pdf_path'])): ?>
                                    <a
                                        class="btn-primary"
                                        href="<?php echo htmlspecialchars($item['pdf_path'], ENT_QUOTES, 'UTF-8'); ?>"
                                        download
                                    >
                                        Descargar PDF
                                    </a>
                                <?php else: ?>
                                    <span class="book-pdf-info">Sin archivo disponible</span>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="checkout-actions" style="margin-top:1.5rem">
                <a href="index.php?action=store" class="btn-secondary checkout-back">Volver a la tienda</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

