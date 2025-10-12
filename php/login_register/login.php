<?php
// php/login_register/login.php

// OJO: Quitamos session_start() de aquí.
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
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
        
        // --- INICIO DE LA MODIFICACIÓN PARA SESIÓN PERSISTENTE ---

        // 1. Definimos la duración de la cookie (30 días en segundos)
        $cookie_lifetime = 60 * 60 * 24 * 30;
        
        // 2. Establecemos los parámetros de la cookie ANTES de iniciar la sesión
        session_set_cookie_params($cookie_lifetime);
        
        // 3. Ahora sí, iniciamos la sesión
        session_start();
        session_regenerate_id(true); // Por seguridad

        // --- FIN DE LA MODIFICACIÓN ---

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['loggedin'] = true;

        // Lógica de redirección
        if ((int)$user['rol'] === 1) {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../../curso/index.php');
        }
        exit;

    } else {
        header('Location: ../../index.php?error=invalid_credentials');
        exit;
    }

} catch (PDOException $e) {
    error_log('Error en login: ' . $e->getMessage());
    header('Location: ../../index.php?error=server_error');
    exit;
}
?>