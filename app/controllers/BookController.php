<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Genre.php';
require_once __DIR__ . '/../models/Venta.php';

class BookController
{
    private function setNoCacheHeaders(): void
    {
        header('Cache-Control: no-cache, no-store, must-revalidate, private');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void
    {
        $books = Book::getAllWithGenres();

        require __DIR__ . '/../views/home.php';
    }

    public function store(): void
    {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $selectedGenres = isset($_GET['generos']) && is_array($_GET['generos'])
            ? array_map('intval', $_GET['generos'])
            : [];

        $books = Book::getFiltered($q, $selectedGenres);
        $genres = Genre::getAll();

        require __DIR__ . '/../views/tienda/index1.php';
    }

    public function myBooks(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->setNoCacheHeaders();

        $books = Venta::getByUser($_SESSION['user_id']);

        require __DIR__ . '/../views/my_books.php';
    }
}

