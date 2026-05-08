<?php

require_once __DIR__ . '/../../config/database.php';

class Genre
{
    public static function getAll(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT id, nombre FROM generos ORDER BY nombre ASC');
        return $stmt->fetchAll();
    }

    public static function create(string $nombre): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO generos (nombre) VALUES (:nombre) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)');
        $stmt->execute([':nombre' => $nombre]);
        return (int) $pdo->lastInsertId() ?: self::getIdByName($nombre);
    }

    public static function getIdByName(string $nombre): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id FROM generos WHERE nombre = :nombre');
        $stmt->execute([':nombre' => $nombre]);
        $result = $stmt->fetch();
        return $result ? (int) $result['id'] : 0;
    }
}

