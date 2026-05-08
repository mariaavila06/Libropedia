<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += (int) $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>La Libropedia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <?php if (!empty($_SESSION['user_id']) || !empty($_SESSION['is_admin'])): ?>
    <!-- Anti-cache script para prevenir navegación atrás después de logout -->
    <script>
        (function() {
            // Prevenir que el navegador guarde en caché esta página
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, window.location.href);
                window.onpopstate = function() {
                    window.history.pushState(null, null, window.location.href);
                };
            }

            // Verificar si el usuario está autenticado al cargar desde caché
            if (performance && performance.navigation && performance.navigation.type === 2) {
                // La página se cargó desde el botón atrás
                fetch('index.php?action=check_session', { method: 'HEAD', cache: 'no-store' })
                    .then(response => {
                        if (!response.ok) {
                            window.location.href = 'index.php';
                        }
                    })
                    .catch(() => {
                        // Si hay error, redirigir a login
                        window.location.href = 'index.php';
                    });
            }
        })();
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="background-image"></div>
    <header class="site-header">
        <div class="logo">
            <img src="assets/img/logo.png" alt="La Libropedia" class="logo-img">
            <span>La Libropedia</span>
        </div>
        <nav class="main-nav">
            <?php if (!empty($_SESSION['is_admin'])): ?>
                <a href="index.php?action=admin_books" class="nav-link">Libros</a>
                <a href="index.php?action=admin_reports" class="nav-link">Reportes</a>
                <a href="index.php?action=logout" class="btn-primary nav-logout">Cerrar sesión</a>
            <?php elseif (!empty($_SESSION['user_id'])): ?>
                <a href="index.php?action=store" class="nav-link">Tienda</a>
                <a href="index.php?action=my_books" class="nav-link">Mis libros</a>
                <button class="btn-secondary cart-button" type="button" id="btn-cart">
                    Carrito
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </button>
                <a href="index.php?action=logout" class="btn-primary nav-logout">Cerrar sesión</a>
            <?php else: ?>
                <button class="btn-secondary" id="btn-login" type="button">Iniciar sesión</button>
                <button class="btn-primary" id="btn-register" type="button">Registrarse</button>
            <?php endif; ?>
        </nav>
    </header>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
                echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); 
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
                echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); 
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

