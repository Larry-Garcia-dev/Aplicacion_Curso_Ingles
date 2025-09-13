<?php
// php/login_register/login.php (Versión con Redirección desde Backend)

session_start();
require_once '../../config/db.php';

// --- YA NO USAMOS RESPUESTAS JSON ---

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es POST, redirigimos al inicio
    header('Location: ../../index.php');
    exit;
}

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    // Si faltan datos, redirigimos de vuelta al inicio con un mensaje de error
    header('Location: ../../index.php?error=empty_fields');
    exit;
}

try {
    $pdo = conectarDB();

    $sql = "SELECT id, name, password, rol FROM users WHERE phone_number = :phone";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // --- INICIO DE SESIÓN EXITOSO ---
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['loggedin'] = true;

        // --- LÓGICA DE REDIRECCIÓN BASADA EN EL ROL ---
        if ($user['rol'] === 'admin') {
            // Si es admin, lo mandamos al panel de administración
            header('Location: ../admin/index.php');
        } else {
            // Si es cualquier otro rol (ej. 'user'), lo mandamos a la página del curso
            header('Location: ../../curso/index.php');
        }
        exit; // Es crucial salir del script después de una redirección

    } else {
        // --- FALLO EN EL INICIO DE SESIÓN ---
        // Contraseña o usuario incorrecto, redirigimos con un error
        header('Location: ../../index.php?error=invalid_credentials');
        exit;
    }

} catch (PDOException $e) {
    error_log('Error en login: ' . $e->getMessage());
    // Error del servidor, redirigimos con un error genérico
    header('Location: ../../index.php?error=server_error');
    exit;
}
?>