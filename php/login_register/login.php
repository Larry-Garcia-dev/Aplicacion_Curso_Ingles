<?php
// php/login.php (Versión Corregida con el campo 'rol')

session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';

function json_response($success, $message, $data = []) {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido');
}

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    json_response(false, 'El teléfono y la contraseña son obligatorios.');
}

try {
    $pdo = conectarDB();

    // CORRECCIÓN: Seleccionamos la columna 'rol'
    $sql = "SELECT id, name, password, rol FROM users WHERE phone_number = :phone";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        // CORRECCIÓN: Guardamos 'rol' en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_rol'] = $user['rol']; // Usamos 'rol'
        $_SESSION['loggedin'] = true;

        // CORRECCIÓN: Enviamos 'rol' en la respuesta JSON
        json_response(true, '¡Bienvenido de nuevo, ' . htmlspecialchars($user['name']) . '!', [
            'rol' => $user['rol'] // Usamos 'rol'
        ]);
    } else {
        json_response(false, 'El número de teléfono o la contraseña son incorrectos.');
    }

} catch (PDOException $e) {
    error_log('Error en login: ' . $e->getMessage());
    json_response(false, 'Error interno del servidor.');
}
?>