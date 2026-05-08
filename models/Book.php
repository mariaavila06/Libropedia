<?php

require_once __DIR__ . '/../../config/database.php';

class Book
{
    public static function getAllWithGenres(): array
    {
        $pdo = getPDO();

        $sql = "
            SELECT 
                b.id,
                b.nombre,
                b.autor,
                b.descripcion,
                b.editorial,
                b.anio_publicacion,
                b.numero_edicion,
                b.lugar_publicacion,
                b.imagen,
                b.precio_bs,
                b.pdf_path,
                GROUP_CONCAT(g.nombre SEPARATOR ', ') AS generos
            FROM libros b
            LEFT JOIN libro_genero lg ON lg.libro_id = b.id
            LEFT JOIN generos g ON g.id = lg.genero_id
            GROUP BY b.id, b.nombre, b.autor, b.descripcion, b.editorial, b.anio_publicacion, b.numero_edicion, b.lugar_publicacion, b.imagen, b.precio_bs, b.pdf_path
            ORDER BY b.nombre ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getFiltered(?string $query, array $genreIds): array
    {
        $pdo = getPDO();

        $where = [];
        $params = [];

        if ($query !== null && $query !== '') {
            $where[] = '(b.nombre LIKE :q OR b.autor LIKE :q)';
            $params[':q'] = '%' . $query . '%';
        }

        if (!empty($genreIds)) {
            $placeholders = [];
            foreach ($genreIds as $index => $id) {
                $key = ':g' . $index;
                $placeholders[] = $key;
                $params[$key] = (int) $id;
            }
            $where[] = 'EXISTS (
                SELECT 1 FROM libro_genero lg2
                WHERE lg2.libro_id = b.id AND lg2.genero_id IN (' . implode(',', $placeholders) . ')
            )';
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'WHERE ' . implode(' AND ', $where);
        }

        $sql = "
            SELECT 
                b.id,
                b.nombre,
                b.autor,
                b.descripcion,
                b.editorial,
                b.anio_publicacion,
                b.numero_edicion,
                b.lugar_publicacion,
                b.imagen,
                b.precio_bs,
                b.pdf_path,
                GROUP_CONCAT(g.nombre SEPARATOR ', ') AS generos
            FROM libros b
            LEFT JOIN libro_genero lg ON lg.libro_id = b.id
            LEFT JOIN generos g ON g.id = lg.genero_id
            $whereSql
            GROUP BY b.id, b.nombre, b.autor, b.descripcion, b.editorial, b.anio_publicacion, b.numero_edicion, b.lugar_publicacion, b.imagen, b.precio_bs, b.pdf_path
            ORDER BY b.nombre ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $pdo = getPDO();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT id, nombre, autor, descripcion, editorial, anio_publicacion, numero_edicion, lugar_publicacion, precio_bs, pdf_path
            FROM libros
            WHERE id IN ($placeholders)
            ORDER BY nombre ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }

    public static function createBook(array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO libros (nombre, autor, descripcion, editorial, anio_publicacion, numero_edicion, lugar_publicacion, imagen, precio_bs, pdf_path)
            VALUES (:nombre, :autor, :descripcion, :editorial, :anio_publicacion, :numero_edicion, :lugar_publicacion, :imagen, :precio_bs, :pdf_path)
        ");

        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':autor' => $data['autor'],
            ':descripcion' => $data['descripcion'],
            ':editorial' => $data['editorial'],
            ':anio_publicacion' => $data['anio_publicacion'],
            ':numero_edicion' => $data['numero_edicion'],
            ':lugar_publicacion' => $data['lugar_publicacion'],
            ':imagen' => $data['imagen'],
            ':precio_bs' => $data['precio_bs'],
            ':pdf_path' => $data['pdf_path'],
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function updateBook(int $id, array $data): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            UPDATE libros
            SET nombre = :nombre,
                autor = :autor,
                descripcion = :descripcion,
                editorial = :editorial,
                anio_publicacion = :anio_publicacion,
                numero_edicion = :numero_edicion,
                lugar_publicacion = :lugar_publicacion,
                imagen = :imagen,
                precio_bs = :precio_bs,
                pdf_path = :pdf_path
            WHERE id = :id
        ");

        $stmt->execute([
            ':nombre' => $data['nombre'],
            ':autor' => $data['autor'],
            ':descripcion' => $data['descripcion'],
            ':editorial' => $data['editorial'],
            ':anio_publicacion' => $data['anio_publicacion'],
            ':numero_edicion' => $data['numero_edicion'],
            ':lugar_publicacion' => $data['lugar_publicacion'],
            ':imagen' => $data['imagen'],
            ':precio_bs' => $data['precio_bs'],
            ':pdf_path' => $data['pdf_path'],
            ':id' => $id,
        ]);
    }

    public static function deleteBook(int $id): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM libros WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public static function setBookGenres(int $bookId, array $genreIds): void
    {
        $pdo = getPDO();
        $pdo->prepare('DELETE FROM libro_genero WHERE libro_id = :id')->execute([':id' => $bookId]);

        if (empty($genreIds)) {
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO libro_genero (libro_id, genero_id) VALUES (:libro_id, :genero_id)');
        foreach ($genreIds as $genreId) {
            $stmt->execute([
                ':libro_id' => $bookId,
                ':genero_id' => $genreId,
            ]);
        }
    }
}

