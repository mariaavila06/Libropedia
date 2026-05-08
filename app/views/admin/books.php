<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="checkout-hero">
    <section class="hero-text">
        <h1>Administración de libros</h1>
        <p>Gestión del catálogo.</p>
    </section>
</main>

<section class="checkout-section">
    <div class="checkout-layout admin-layout">
        <div class="checkout-left">
            <h2 class="checkout-title">Listado de libros</h2>

            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Precio (Bs)</th>
                    <th>Géneros</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo (int) $book['id']; ?></td>
                        <td><?php echo htmlspecialchars($book['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format($book['precio_bs'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($book['generos'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <button
                                class="btn-secondary admin-edit-book"
                                data-book='<?php echo json_encode($book, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>'
                            >
                                Editar
                            </button>
                            <form method="post" action="index.php?action=admin_delete_book" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo (int) $book['id']; ?>">
                                <button type="submit" class="btn-secondary" onclick="return confirm('¿Eliminar este libro?');">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h2 class="checkout-title" style="margin-top:2rem;">Agregar / editar libro</h2>

            <form class="admin-book-form" method="post" action="index.php?action=admin_save_book" enctype="multipart/form-data">
                <input type="hidden" name="id" id="admin-book-id">
                <input type="hidden" name="imagen_actual" id="admin-imagen-actual">
                <input type="hidden" name="pdf_actual" id="admin-pdf-actual">

                <div class="form-group">
                    <label for="admin-nombre">Nombre *</label>
                    <input type="text" id="admin-nombre" name="nombre" required maxlength="40">
                    <span class="field-error" id="error-nombre" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-autor">Autor *</label>
                    <input type="text" id="admin-autor" name="autor" required maxlength="40">
                    <span class="field-error" id="error-autor" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-descripcion">Descripción *</label>
                    <textarea id="admin-descripcion" name="descripcion" rows="3" required maxlength="80"></textarea>
                    <span class="field-error" id="error-descripcion" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-editorial">Editorial *</label>
                    <input type="text" id="admin-editorial" name="editorial" required maxlength="100">
                    <span class="field-error" id="error-editorial" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="admin-anio">Año de publicación *</label>
                        <input type="text" id="admin-anio" name="anio_publicacion" required maxlength="4">
                        <span class="field-error" id="error-anio" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                    </div>

                    <div class="form-group">
                        <label for="admin-edicion">Número de edición *</label>
                        <input type="text" id="admin-edicion" name="numero_edicion" required maxlength="5">
                        <span class="field-error" id="error-edicion" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="admin-lugar">Lugar de publicación *</label>
                    <input type="text" id="admin-lugar" name="lugar_publicacion" required maxlength="100">
                    <span class="field-error" id="error-lugar" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-precio">Precio (Bs) *</label>
                    <input type="text" id="admin-precio" name="precio_bs" required maxlength="10" placeholder="Ej: 250,00">
                    <span class="field-error" id="error-precio" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label>Géneros *</label>
                    <div class="filter-genres">
                        <?php foreach ($genres as $genre): ?>
                            <label class="filter-genre-item">
                                <input type="checkbox" name="generos[]" value="<?php echo (int) $genre['id']; ?>">
                                <span><?php echo htmlspecialchars($genre['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="field-error" id="error-generos" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-nuevo-genero">Agregar otro género</label>
                    <input type="text" id="admin-nuevo-genero" name="nuevo_genero" maxlength="50" placeholder="Nombre del nuevo género">
                    <span class="field-error" id="error-nuevo-genero" style="color: #dc3545; font-size: 0.8rem; display: none;"></span>
                </div>

                <div class="form-group">
                    <label for="admin-imagen">Imagen del libro</label>
                    <input type="file" id="admin-imagen" name="imagen" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="admin-pdf">Archivo PDF del libro</label>
                    <input type="file" id="admin-pdf" name="pdf" accept="application/pdf">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Guardar libro</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.admin-edit-book');
    const idField = document.getElementById('admin-book-id');
    const nombreField = document.getElementById('admin-nombre');
    const autorField = document.getElementById('admin-autor');
    const descField = document.getElementById('admin-descripcion');
    const editorialField = document.getElementById('admin-editorial');
    const anioField = document.getElementById('admin-anio');
    const edicionField = document.getElementById('admin-edicion');
    const lugarField = document.getElementById('admin-lugar');
    const precioField = document.getElementById('admin-precio');
    const nuevoGeneroField = document.getElementById('admin-nuevo-genero');
    const imagenActual = document.getElementById('admin-imagen-actual');
    const pdfActual = document.getElementById('admin-pdf-actual');
    const form = document.querySelector('.admin-book-form');

    const generoCheckboxes = document.querySelectorAll('.admin-book-form input[name="generos[]"]');

    // Funciones de validación
    function showFieldError(fieldId, msg) {
        const errorSpan = document.getElementById('error-' + fieldId);
        if (errorSpan) {
            errorSpan.textContent = msg;
            errorSpan.style.display = 'block';
        }
    }

    function hideFieldError(fieldId) {
        const errorSpan = document.getElementById('error-' + fieldId);
        if (errorSpan) {
            errorSpan.style.display = 'none';
        }
    }

    function hideAllErrors() {
        const errors = document.querySelectorAll('.field-error');
        errors.forEach(e => e.style.display = 'none');
    }

    function validateNombre(value) {
        if (!value.trim()) return 'El nombre es obligatorio.';
        if (value.length > 40) return 'El nombre no puede tener más de 40 caracteres.';
        if (/\d/.test(value)) return 'El nombre no puede contener números.';
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El nombre solo puede contener letras.';
        return null;
    }

    function validateAutor(value) {
        if (!value.trim()) return 'El autor es obligatorio.';
        if (value.length > 40) return 'El autor no puede tener más de 40 caracteres.';
        if (/\d/.test(value)) return 'El autor no puede contener números.';
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El autor solo puede contener letras.';
        return null;
    }

    function validateDescripcion(value) {
        if (!value.trim()) return 'La descripción es obligatoria.';
        if (value.length > 80) return 'La descripción no puede tener más de 80 caracteres.';
        return null;
    }

    function validateEditorial(value) {
        if (!value.trim()) return 'La editorial es obligatoria.';
        if (value.length > 100) return 'La editorial no puede tener más de 100 caracteres.';
        return null;
    }

    function validateAnio(value) {
        if (!value.trim()) return 'El año de publicación es obligatorio.';
        if (!/^\d{4}$/.test(value)) return 'El año debe tener exactamente 4 dígitos numéricos.';
        const anio = parseInt(value);
        if (anio < 1000 || anio > 2100) return 'Ingresa un año válido.';
        return null;
    }

    function validateEdicion(value) {
        if (!value.trim()) return 'El número de edición es obligatorio.';
        if (!/^\d+$/.test(value)) return 'La edición debe contener solo números.';
        if (value.length > 5) return 'La edición no puede tener más de 5 dígitos.';
        return null;
    }

    function validateLugar(value) {
        if (!value.trim()) return 'El lugar de publicación es obligatorio.';
        if (value.length > 100) return 'El lugar no puede tener más de 100 caracteres.';
        if (/\d/.test(value)) return 'El lugar no puede contener números.';
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El lugar solo puede contener letras.';
        return null;
    }

    function validatePrecio(value) {
        if (!value.trim()) return 'El precio es obligatorio.';
        // Permitir formato con coma o punto decimal
        const precioLimpio = value.replace(',', '.').replace(/[^\d.]/g, '');
        if (!/^\d+(\.\d{1,2})?$/.test(precioLimpio)) return 'El precio debe ser numérico (use coma para decimales, ej: 250,00).';
        if (parseFloat(precioLimpio) <= 0) return 'El precio debe ser mayor a 0.';
        return null;
    }

    function validateGeneros() {
        const checked = document.querySelectorAll('input[name="generos[]"]:checked');
        if (checked.length === 0) return 'Debe seleccionar al menos un género.';
        return null;
    }

    function validateNuevoGenero(value) {
        if (!value.trim()) return null; // Opcional
        if (value.length > 50) return 'El género no puede tener más de 50 caracteres.';
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) return 'El género solo puede contener letras.';
        return null;
    }

    // Event listeners para validación en tiempo real
    nombreField.addEventListener('blur', function() {
        const error = validateNombre(this.value);
        if (error) showFieldError('nombre', error);
        else hideFieldError('nombre');
    });

    autorField.addEventListener('blur', function() {
        const error = validateAutor(this.value);
        if (error) showFieldError('autor', error);
        else hideFieldError('autor');
    });

    descField.addEventListener('blur', function() {
        const error = validateDescripcion(this.value);
        if (error) showFieldError('descripcion', error);
        else hideFieldError('descripcion');
    });

    editorialField.addEventListener('blur', function() {
        const error = validateEditorial(this.value);
        if (error) showFieldError('editorial', error);
        else hideFieldError('editorial');
    });

    anioField.addEventListener('blur', function() {
        const error = validateAnio(this.value);
        if (error) showFieldError('anio', error);
        else hideFieldError('anio');
    });

    edicionField.addEventListener('blur', function() {
        const error = validateEdicion(this.value);
        if (error) showFieldError('edicion', error);
        else hideFieldError('edicion');
    });

    lugarField.addEventListener('blur', function() {
        const error = validateLugar(this.value);
        if (error) showFieldError('lugar', error);
        else hideFieldError('lugar');
    });

    precioField.addEventListener('blur', function() {
        const error = validatePrecio(this.value);
        if (error) showFieldError('precio', error);
        else hideFieldError('precio');
    });

    nuevoGeneroField.addEventListener('blur', function() {
        const error = validateNuevoGenero(this.value);
        if (error) showFieldError('nuevo-genero', error);
        else hideFieldError('nuevo-genero');
    });

    // Validación al enviar el formulario
    form.addEventListener('submit', function(event) {
        hideAllErrors();

        let hasErrors = false;

        let error = validateNombre(nombreField.value);
        if (error) { showFieldError('nombre', error); hasErrors = true; }

        error = validateAutor(autorField.value);
        if (error) { showFieldError('autor', error); hasErrors = true; }

        error = validateDescripcion(descField.value);
        if (error) { showFieldError('descripcion', error); hasErrors = true; }

        error = validateEditorial(editorialField.value);
        if (error) { showFieldError('editorial', error); hasErrors = true; }

        error = validateAnio(anioField.value);
        if (error) { showFieldError('anio', error); hasErrors = true; }

        error = validateEdicion(edicionField.value);
        if (error) { showFieldError('edicion', error); hasErrors = true; }

        error = validateLugar(lugarField.value);
        if (error) { showFieldError('lugar', error); hasErrors = true; }

        error = validatePrecio(precioField.value);
        if (error) { showFieldError('precio', error); hasErrors = true; }

        error = validateGeneros();
        if (error) { showFieldError('generos', error); hasErrors = true; }

        error = validateNuevoGenero(nuevoGeneroField.value);
        if (error) { showFieldError('nuevo-genero', error); hasErrors = true; }

        if (hasErrors) {
            event.preventDefault();
        }
    });

    // Botones de edición
    editButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const data = JSON.parse(btn.getAttribute('data-book'));

            idField.value = data.id || '';
            nombreField.value = data.nombre || '';
            autorField.value = data.autor || '';
            descField.value = data.descripcion || '';
            editorialField.value = data.editorial || '';
            anioField.value = data.anio_publicacion || '';
            edicionField.value = data.numero_edicion || '';
            lugarField.value = data.lugar_publicacion || '';
            precioField.value = data.precio_bs || '';
            nuevoGeneroField.value = '';
            imagenActual.value = data.imagen || '';
            pdfActual.value = data.pdf_path || '';

            generoCheckboxes.forEach((cb) => {
                cb.checked = false;
            });

            hideAllErrors();
        });
    });
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>

