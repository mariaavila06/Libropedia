<?php require __DIR__ . '/partials/header.php'; ?>

<main class="hero">
    <section class="hero-text">
        <h1>LA LIBROPEDIA</h1>
        <h2>Tu próxima gran historia, a un solo clic.</h2>
    </section>
</main>

<section class="collection">
    <h2>Nuestra Colección</h2>

    <div class="books-grid">
        <?php if (empty($books)): ?>
            <p class="empty-state">Aún no hay libros cargados.</p>
        <?php else: ?>
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
                        <p class="book-author"><?php echo htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="book-description">
                            <?php echo nl2br(htmlspecialchars($book['descripcion'], ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                        <p class="book-price">
                            <?php echo number_format((float)$book['precio_bs'], 2, ',', '.'); ?> Bs
                        </p>
                        <?php if (!empty($book['generos'])): ?>
                            <p class="book-genres">
                                <?php foreach (explode(',', $book['generos']) as $genre): ?>
                                    <span class="badge-genre"><?php echo htmlspecialchars(trim($genre), ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($book['pdf_path'])): ?>
                            <p class="book-pdf-info">
                                PDF almacenado para futura entrega.
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

