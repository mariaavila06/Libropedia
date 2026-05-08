<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        $errors = [];

        // Validar nombre
        $nombre = trim($_POST['nombre'] ?? '');
        if (strlen($nombre) < 2) {
            $errors[] = 'El nombre debe tener al menos 2 caracteres.';
        } elseif (strlen($nombre) > 30) {
            $errors[] = 'El nombre no puede tener más de 30 caracteres.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
            $errors[] = 'El nombre solo puede contener letras.';
        }

        // Validar apellido
        $apellido = trim($_POST['apellido'] ?? '');
        if (strlen($apellido) < 2) {
            $errors[] = 'El apellido debe tener al menos 2 caracteres.';
        } elseif (strlen($apellido) > 30) {
            $errors[] = 'El apellido no puede tener más de 30 caracteres.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido)) {
            $errors[] = 'El apellido solo puede contener letras.';
        }

        // Validar correo
        $correo = trim($_POST['correo'] ?? '');
        if (strlen($correo) < 5) {
            $errors[] = 'El correo debe tener al menos 5 caracteres.';
        } elseif (strlen($correo) > 50) {
            $errors[] = 'El correo no puede tener más de 50 caracteres.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL) || !preg_match('/^[^@\s]+@[^@\s]+\.[a-zA-Z]{2,}$/', $correo)) {
            $errors[] = 'Ingresa un correo válido.';
        }

        // Validar cédula
        $cedula = trim($_POST['cedula'] ?? '');
        if (!preg_match('/^\d+$/', $cedula)) {
            $errors[] = 'La cédula solo puede contener números.';
        } elseif (strlen($cedula) < 7) {
            $errors[] = 'La cédula debe tener al menos 7 dígitos.';
        } elseif (strlen($cedula) > 9) {
            $errors[] = 'La cédula no puede tener más de 9 dígitos.';
        }

        // Validar contraseña
        $contrasena = $_POST['contrasena'] ?? '';
        if (strlen($contrasena) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        } elseif (strlen($contrasena) > 16) {
            $errors[] = 'La contraseña no puede tener más de 16 caracteres.';
        } elseif (!preg_match('/(?=.*[A-Za-z])(?=.*\d)/', $contrasena)) {
            $errors[] = 'La contraseña debe contener al menos una letra y un número.';
        }

        // Validar confirmación de contraseña
        $contrasena_confirmacion = $_POST['contrasena_confirmacion'] ?? '';
        if ($contrasena !== $contrasena_confirmacion) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        // Validar usuario
        $usuario = trim($_POST['usuario'] ?? '');
        if (empty($usuario)) {
            $errors[] = 'El usuario es obligatorio.';
        } else {
            $existing = User::findByUsername($usuario);
            if ($existing) {
                $errors[] = 'El nombre de usuario ya está en uso.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            header('Location: index.php');
            exit;
        }

        $created = User::create([
            'nombre'     => trim($_POST['nombre']),
            'apellido'   => trim($_POST['apellido']),
            'usuario'    => trim($_POST['usuario']),
            'correo'     => trim($_POST['correo']),
            'cedula'     => trim($_POST['cedula']),
            'contrasena' => $_POST['contrasena'],
        ]);

        if ($created) {
            $_SESSION['success'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
        } else {
            $_SESSION['error'] = 'Ocurrió un error al registrar el usuario.';
        }

        header('Location: index.php');
        exit;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        if (empty($_POST['usuario']) || empty($_POST['contrasena'])) {
            $_SESSION['error'] = 'Debes ingresar usuario y contraseña.';
            header('Location: index.php');
            exit;
        }

        if (strlen($_POST['contrasena']) > 15) {
            $_SESSION['error'] = 'La contraseña no puede tener más de 15 caracteres.';
            header('Location: index.php');
            exit;
        }

        if (!preg_match('/(?=.*[A-Za-z])(?=.*\d)/', $_POST['contrasena'])) {
            $_SESSION['error'] = 'La contraseña debe contener al menos una letra y un número.';
            header('Location: index.php');
            exit;
        }

        // Acceso especial para administrador fijo
        if ($_POST['usuario'] === 'Administrador' && $_POST['contrasena'] === '12345678a') {
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = 'Administrador';
            $_SESSION['is_admin'] = true;
            $_SESSION['success'] = 'Inicio de sesión como administrador.';

            header('Location: index.php?action=admin_books');
            exit;
        }

        $user = User::findByUsername($_POST['usuario']);
        if (!$user || !password_verify($_POST['contrasena'], $user['contraseña_hash'])) {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos.';
            header('Location: index.php');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['usuario'];
        $_SESSION['success'] = 'Inicio de sesión exitoso.';

        header('Location: index.php?action=store');
        exit;
    }

    public function logout(): void
    {
        // Limpiar todas las variables de sesión
        $_SESSION = [];

        // Destruir la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

        // Destruir la sesión
        session_destroy();

        // Headers para evitar cache y prevenir navegación atrás
        header('Cache-Control: no-cache, no-store, must-revalidate, private');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Clear-Site-Data: "cache", "cookies", "storage"');

        header('Location: index.php');
        exit;
    }
}

