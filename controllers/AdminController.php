<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Genre.php';
require_once __DIR__ . '/../models/Venta.php';

class AdminController
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

    private function ensureAdmin(): void
    {
        if (empty($_SESSION['is_admin'])) {
            header('Location: index.php');
            exit;
        }
        $this->setNoCacheHeaders();
    }

    public function books(): void
    {
        $this->ensureAdmin();

        $books = Book::getAllWithGenres();
        $genres = Genre::getAll();

        require __DIR__ . '/../views/admin/books.php';
    }

    public function saveBook(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin_books');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $editorial = trim($_POST['editorial'] ?? '');
        $anio_publicacion = (int) ($_POST['anio_publicacion'] ?? 0);
        $numero_edicion = (int) ($_POST['numero_edicion'] ?? 0);
        $lugar_publicacion = trim($_POST['lugar_publicacion'] ?? '');
        $precio = str_replace(',', '.', $_POST['precio_bs'] ?? '0');
        $precio = (float) $precio;
        $generos = isset($_POST['generos']) && is_array($_POST['generos']) ? array_map('intval', $_POST['generos']) : [];

        // Crear nuevo género si se proporcionó
        $nuevo_genero = trim($_POST['nuevo_genero'] ?? '');
        if (!empty($nuevo_genero)) {
            $nuevoGeneroId = Genre::create($nuevo_genero);
            if ($nuevoGeneroId > 0) {
                $generos[] = $nuevoGeneroId;
            }
        }

        $imagePath = $_POST['imagen_actual'] ?? null;
        if (!empty($_FILES['imagen']['tmp_name'])) {
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $fileName = 'libro_' . uniqid() . '.' . $extension;
            $dest = 'assets/img/' . $fileName;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../../' . $dest)) {
                $imagePath = $dest;
            }
        }

        $pdfPath = $_POST['pdf_actual'] ?? null;
        if (!empty($_FILES['pdf']['tmp_name'])) {
            $fileName = 'libro_' . time() . '_' . basename($_FILES['pdf']['name']);
            $dest = 'storage/pdfs/' . $fileName;
            if (move_uploaded_file($_FILES['pdf']['tmp_name'], __DIR__ . '/../../' . $dest)) {
                $pdfPath = $dest;
            }
        }

        if ($id > 0) {
            Book::updateBook($id, [
                'nombre' => $nombre,
                'autor' => $autor,
                'descripcion' => $descripcion,
                'editorial' => $editorial,
                'anio_publicacion' => $anio_publicacion,
                'numero_edicion' => $numero_edicion,
                'lugar_publicacion' => $lugar_publicacion,
                'precio_bs' => $precio,
                'imagen' => $imagePath,
                'pdf_path' => $pdfPath,
            ]);
        } else {
            $id = Book::createBook([
                'nombre' => $nombre,
                'autor' => $autor,
                'descripcion' => $descripcion,
                'editorial' => $editorial,
                'anio_publicacion' => $anio_publicacion,
                'numero_edicion' => $numero_edicion,
                'lugar_publicacion' => $lugar_publicacion,
                'precio_bs' => $precio,
                'imagen' => $imagePath,
                'pdf_path' => $pdfPath,
            ]);
        }

        Book::setBookGenres($id, $generos);

        header('Location: index.php?action=admin_books');
        exit;
    }

    public function deleteBook(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=admin_books');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id > 0) {
            Book::deleteBook($id);
        }

        header('Location: index.php?action=admin_books');
        exit;
    }

    public function reports(): void
    {
        $this->ensureAdmin();

        $ventas = Venta::getAllWithDetails();
        $topSelling = Venta::getTopSellingBooks();
        $salesByMonth = Venta::getSalesByMonth();

        require __DIR__ . '/../views/admin/reports.php';
    }
}

