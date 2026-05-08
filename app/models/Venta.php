<?php

require_once __DIR__ . '/../../config/database.php';

class Venta
{
    public static function create(int $usuario_id, int $libro_id, float $precio_bs): bool
    {
        $pdo = getPDO();

        $sql = "
            INSERT INTO ventas
                (usuario_id, libro_id, precio_bs)
            VALUES
                (:usuario_id, :libro_id, :precio_bs)
        ";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'usuario_id' => $usuario_id,
            'libro_id'   => $libro_id,
            'precio_bs'  => $precio_bs,
        ]);
    }

    public static function getAllWithDetails(): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                v.fecha_venta,
                l.nombre AS libro_nombre,
                l.autor,
                u.nombre AS usuario_nombre,
                u.apellido,
                v.precio_bs
            FROM ventas v
            JOIN libros l ON v.libro_id = l.id
            JOIN usuarios u ON v.usuario_id = u.id
            ORDER BY v.fecha_venta DESC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getTopSellingBooks(): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                l.nombre,
                l.autor,
                COUNT(v.id) AS ventas,
                SUM(v.precio_bs) AS total_ingresos
            FROM libros l
            LEFT JOIN ventas v ON l.id = v.libro_id
            GROUP BY l.id, l.nombre, l.autor
            ORDER BY ventas DESC, total_ingresos DESC
            LIMIT 10
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getLeastSellingBooks(): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                l.nombre,
                l.autor,
                COUNT(v.id) AS ventas,
                SUM(v.precio_bs) AS total_ingresos
            FROM libros l
            LEFT JOIN ventas v ON l.id = v.libro_id
            GROUP BY l.id, l.nombre, l.autor
            ORDER BY ventas ASC, total_ingresos ASC
            LIMIT 10
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getSalesByMonth(): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                DATE_FORMAT(fecha_venta, '%Y-%m') AS mes,
                COUNT(*) AS total_ventas,
                SUM(precio_bs) AS total_ingresos
            FROM ventas
            GROUP BY DATE_FORMAT(fecha_venta, '%Y-%m')
            ORDER BY mes ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getByUser(int $user_id): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                l.id,
                l.nombre,
                l.autor,
                l.descripcion,
                l.imagen,
                l.pdf_path,
                v.precio_bs,
                v.fecha_venta,
                COUNT(v.id) AS cantidad
            FROM ventas v
            JOIN libros l ON v.libro_id = l.id
            WHERE v.usuario_id = :user_id
            GROUP BY l.id, l.nombre, l.autor, l.descripcion, l.imagen, l.pdf_path, v.precio_bs, v.fecha_venta
            ORDER BY v.fecha_venta DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}