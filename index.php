<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Book.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/controllers/BookController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';

// Enrutador basado en el parámetro "action".
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'add_to_cart':
        $controller = new CartController();
        $controller->add();
        break;

    case 'remove_from_cart':
        $controller = new CartController();
        $controller->remove();
        break;

    case 'downloads':
        $controller = new CartController();
        $controller->downloads();
        break;

    case 'admin_books':
        $controller = new AdminController();
        $controller->books();
        break;

    case 'admin_save_book':
        $controller = new AdminController();
        $controller->saveBook();
        break;

    case 'admin_delete_book':
        $controller = new AdminController();
        $controller->deleteBook();
        break;

    case 'admin_reports':
        $controller = new AdminController();
        $controller->reports();
        break;

    case 'checkout':
        $controller = new CartController();
        $controller->checkout();
        break;

    case 'process_checkout':
        $controller = new CartController();
        $controller->processCheckout();
        break;

    case 'store':
        $controller = new BookController();
        $controller->store();
        break;

    case 'my_books':
        $controller = new BookController();
        $controller->myBooks();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'check_session':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) && empty($_SESSION['is_admin'])) {
            http_response_code(401);
            exit;
        }
        http_response_code(200);
        exit;

    case 'home':
    default:
        $controller = new BookController();
        $controller->index();
        break;
}

