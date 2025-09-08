<?php
// php/registro.php (Versión API para responder a JavaScript)

// Indicamos que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluimos la conexión a la base de datos
require_once '../../config/db.php';

// Función para enviar respuestas JSON y terminar el script
function json_response($success, $message, $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Verificamos que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido');
}

// Obtenemos los datos del POST
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? ''; // El formulario usa 'phone'
$password = $_POST['password'] ?? '';

// --- VALIDACIÓN DEL LADO DEL SERVIDOR (¡MUY IMPORTANTE!) ---
if (empty($name) || mb_strlen(trim($name)) < 2) {
    json_response(false, 'El nombre completo es requerido.');
}
if (!preg_match('/^\+?\d{8,15}$/', $phone)) {
    json_response(false, 'Ingresa un número de teléfono válido.');
}
// Replicamos las reglas de validación de contraseña del JS en el backend
$pass_validations = [
    'length' => mb_strlen($password) >= 8,
    'number' => preg_match('/\d/', $password),
    'special' => preg_match_all('/[^a-zA-Z0-9]/', $password) >= 2,
    'uppercase' => preg_match_all('/[A-Z]/', $password) >= 3,
];
if (in_array(false, $pass_validations)) {
    json_response(false, 'La contraseña no cumple con todos los requisitos de seguridad.');
}

try {
    $pdo = conectarDB();

    // Verificamos si el teléfono ya existe
    $sql_check = "SELECT id FROM users WHERE phone_number = :phone";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':phone' => $phone]);
    if ($stmt_check->fetch()) {
        json_response(false, 'Este número de teléfono ya está registrado.');
    }

    // Encriptamos la contraseña
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Insertamos el nuevo usuario
    $sql = "INSERT INTO users (name, phone_number, password) VALUES (:name, :phone, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':password' => $password_hashed
    ]);

    // Si todo fue bien, enviamos una respuesta exitosa
    json_response(true, '¡Cuenta creada exitosamente! Bienvenido.');

} catch (PDOException $e) {
    error_log('Error en registro: ' . $e->getMessage());
    json_response(false, 'Error interno del servidor. Por favor, intenta más tarde.');
}
?>