<?php
// php/login_register/reset_password.php

header('Content-Type: application/json');
require_once '../../config/db.php';

function json_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido.');
}

// Recibir datos
$phone = $_POST['phone'] ?? '';
$code = $_POST['code'] ?? '';
$password = $_POST['password'] ?? '';

// Validar datos
if (empty($phone) || empty($code) || empty($password)) {
    json_response(false, 'Todos los campos son requeridos.');
}

if (mb_strlen($password) < 8) {
    json_response(false, 'La contraseña debe tener al menos 8 caracteres.');
}

try {
    $pdo = conectarDB();

    // 1. Buscar al usuario por número de teléfono y código
    $sql = "SELECT id FROM users WHERE phone_number = :phone AND code = :code";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':phone' => $phone, ':code' => $code]);
    $user = $stmt->fetch();

    if (!$user) {
        // El código o el teléfono son incorrectos
        json_response(false, 'El código de verificación es incorrecto.');
    }

    // 2. Si el código es correcto, actualizar la contraseña
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
    
    // Se actualiza la contraseña y se limpia el código para que no pueda ser reutilizado
    $sql_update = "UPDATE users SET password = :password, code = NULL WHERE id = :user_id";
    $stmt_update = $pdo->prepare($sql_update);
    
    $stmt_update->execute([
        ':password' => $password_hashed,
        ':user_id' => $user['id']
    ]);

    json_response(true, 'Contraseña actualizada correctamente.');

} catch (PDOException $e) {
    error_log('Error en reset_password: ' . $e->getMessage());
    json_response(false, 'Error interno del servidor. Inténtalo más tarde.');
}
?>