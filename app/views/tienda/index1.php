<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="store-hero">
    <section class="hero-text">
        <h1>Explora todos nuestros libros disponibles</h1>
    </section>
</main>

<section class="store-section">
    <div class="store-layout">
        <aside class="store-filters">
            <h3>Filtros</h3>
            <form method="get" action="index.php" class="store-filters-form">
                <input type="hidden" name="action" value="store">

                <div class="form-group">
                    <label for="filtro-q">Buscar:</label>
                    <input
                        type="text"
                        id="filtro-q"
                        name="q"
                        placeholder="Nombre o autor..."
                        value="<?php echo isset($q) ? htmlspecialchars($q, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        maxlength="30"
                    >
                </div>

                <div class="form-group">
                    <span class="filter-label">Género:</span>
                    <div class="filter-genres">
                        <?php if (!empty($genres)): ?>
                            <?php foreach ($genres as $genre): ?>
                                <?php $checked = !empty($selectedGenres) && in_array((int) $genre['id'], $selectedGenres, true); ?>
                                <label class="filter-genre-item">
                                    <input
                                        type="checkbox"
                                        name="generos[]"
                                        value="<?php echo (int) $genre['id']; ?>"
                                        <?php echo $checked ? 'checked' : ''; ?>
                                    >
                                    <span><?php echo htmlspecialchars($genre['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-state">No hay géneros configurados.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="store-filters-actions">
                    <button type="submit" class="btn-primary">Filtrar</button>
                    <a href="index.php?action=store" class="btn-secondary store-clear-btn">Limpiar</a>
                </div>
            </form>
        </aside>

        <div class="store-results">
            <div class="store-results-header">
                <span class="store-results-count">
                    (<?php echo count($books); ?> items)
                </span>
            </div>

            <div class="books-grid">
                <?php if (empty($books)): ?>
                    <p class="empty-state">No se encontraron libros con esos filtros.</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <article
                            class="book-card"
                            data-book-title="<?php echo htmlspecialchars($book['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-book-author="<?php echo htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-book-pdf="<?php echo htmlspecialchars($book['pdf_path'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        >
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
                                <?php if (!empty($book['generos'])): ?>
                                    <p class="book-genres">
                                        <?php foreach (explode(',', $book['generos']) as $genreName): ?>
                                            <span class="badge-genre"><?php echo htmlspecialchars(trim($genreName), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php endforeach; ?>
                                    </p>
                                <?php endif; ?>
                                <p class="book-price">
                                    <?php echo number_format((float)$book['precio_bs'], 2, ',', '.'); ?> Bs
                                </p>
                                <form class="form-actions" method="post" action="index.php?action=add_to_cart">
                                    <input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>">
                                    <button class="btn-primary" type="submit">Añadir al carrito</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>

