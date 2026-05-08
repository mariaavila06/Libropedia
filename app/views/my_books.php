<?php require __DIR__ . '/partials/header.php'; ?>

<main class="store-hero">
    <section class="hero-text">
        <h1>Mis Libros</h1>
    </section>
</main>

<section class="store-section">
    <div class="my-books-container">
        <?php if (empty($books)): ?>
            <p class="empty-state">Aún no has comprado ningún libro. Visita la <a href="index.php?action=store">tienda</a> para encontrar tu próxima lectura.</p>
        <?php else: ?>
            <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <article class="book-card">
                        <div class="book-image">
                            <?php if (!empty($book['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($book['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($book['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php else: ?>
                                <div class="book-image-placeholder">Sin imagen</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-content">
                            <h3><?php echo htmlspecialchars($book['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="book-author">Por: <?php echo htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="book-description">
                                <?php echo nl2br(htmlspecialchars($book['descripcion'], ENT_QUOTES, 'UTF-8')); ?>
                            </p>
                            <p class="book-price">
                                <?php echo number_format((float)$book['precio_bs'], 2, ',', '.'); ?> Bs
                            </p>
                            <p class="book-purchase-date">
                                <small>Comprado el: <?php echo date('d/m/Y', strtotime($book['fecha_venta'])); ?></small>
                            </p>
                            <?php if (!empty($book['pdf_path'])): ?>
                                <a href="<?php echo htmlspecialchars($book['pdf_path'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-primary" target="_blank">Descargar PDF</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
