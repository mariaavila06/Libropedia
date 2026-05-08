<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    public static function findByUsername(string $username): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
        $stmt->execute(['usuario' => $username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function create(array $data): bool
    {
        $pdo = getPDO();

        $sql = "
            INSERT INTO usuarios
                (nombre, apellido, usuario, correo, cedula, contraseña_hash)
            VALUES
                (:nombre, :apellido, :usuario, :correo, :cedula, :contrasena)
        ";

        $stmt = $pdo->prepare($sql);
        $hashed = password_hash($data['contrasena'], PASSWORD_BCRYPT);

        return $stmt->execute([
            'nombre'     => $data['nombre'],
            'apellido'   => $data['apellido'],
            'usuario'    => $data['usuario'],
            'correo'     => $data['correo'],
            'cedula'     => $data['cedula'],
            'contrasena' => $hashed,
        ]);
    }
}

