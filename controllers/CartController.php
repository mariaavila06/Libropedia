<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Venta.php';

class CartController
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

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=store');
            exit;
        }

        $bookId = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;
        if ($bookId <= 0) {
            header('Location: index.php?action=store');
            exit;
        }

        if (!isset($_SESSION['cart'][$bookId])) {
            $_SESSION['cart'][$bookId] = 0;
        }
        $_SESSION['cart'][$bookId] += 1;

        header('Location: index.php?action=store');
        exit;
    }

    public function remove(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=store');
            exit;
        }

        $bookId = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;
        if ($bookId <= 0) {
            header('Location: index.php?action=store');
            exit;
        }

        if (isset($_SESSION['cart'][$bookId])) {
            unset($_SESSION['cart'][$bookId]);
        }

        header('Location: index.php?action=store');
        exit;
    }

    public function checkout(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $bookIds = array_keys($cart);
        $items = Book::findByIds($bookIds);

        $totalBs = 0.0;
        foreach ($items as &$item) {
            $qty = $cart[$item['id']] ?? 1;
            $item['cantidad'] = $qty;
            $item['subtotal_bs'] = $qty * (float) $item['precio_bs'];
            $totalBs += $item['subtotal_bs'];
        }
        unset($item);

        $subtotalBs = $totalBs;
        $ivaBs = $subtotalBs > 0 ? $subtotalBs * 0.16 : 0.0;
        $totalConIvaBs = $subtotalBs + $ivaBs;

        $rateUsd = null;
        $totalUsd = null;

        require __DIR__ . '/../views/checkout.php';
    }

    public function downloads(): void
    {
        $this->setNoCacheHeaders();

        $items = $_SESSION['purchased_items'] ?? [];
        $bookIds = array_column($items, 'id');
        $books = Book::findByIds($bookIds);

        // Combinar con cantidades
        $downloads = [];
        foreach ($books as $book) {
            $item = array_filter($items, fn($i) => $i['id'] == $book['id']);
            $item = reset($item);
            $downloads[] = array_merge($book, ['cantidad' => $item['cantidad'] ?? 1]);
        }

        require __DIR__ . '/../views/downloads.php';
    }

    public function processCheckout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=checkout');
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->setNoCacheHeaders();

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: index.php?action=checkout');
            exit;
        }

        $bookIds = array_keys($cart);
        $items = Book::findByIds($bookIds);

        foreach ($items as &$item) {
            $qty = $cart[$item['id']] ?? 1;
            $item['cantidad'] = $qty;
            $item['subtotal_bs'] = $qty * (float) $item['precio_bs'];
            for ($i = 0; $i < $qty; $i++) {
                Venta::create($_SESSION['user_id'], $item['id'], $item['precio_bs']);
            }
        }
        unset($item);

        // Vaciar el carrito
        $_SESSION['cart'] = [];
        $_SESSION['purchased_items'] = $items;

        $totalBs = 0.0;
        foreach ($items as $item) {
            $totalBs += $item['subtotal_bs'];
        }

        $subtotalBs = $totalBs;
        $ivaBs = $subtotalBs > 0 ? $subtotalBs * 0.16 : 0.0;
        $totalConIvaBs = $subtotalBs + $ivaBs;

        $rateUsd = null;
        $totalUsd = null;

        // Guardar datos de la factura para PDF
        $_SESSION['last_invoice'] = [
            'id' => date('Ymd') . '-' . rand(1000, 9999),
            'fecha' => date('Y-m-d H:i:s'),
            'items' => $items,
            'subtotalBs' => $subtotalBs,
            'ivaBs' => $ivaBs,
            'totalConIvaBs' => $totalConIvaBs,
            'metodo_pago' => $_POST['metodo_pago'] ?? 'No especificado'
        ];

        require __DIR__ . '/../views/invoice.php';
    }
}

