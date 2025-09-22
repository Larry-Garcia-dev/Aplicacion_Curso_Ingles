<?php
// php/login_register/send_recovery_code.php

header('Content-Type: application/json');

// Incluir la conexión a la base de datos para verificar si el usuario existe
require_once '../../config/db.php';

function json_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido.');
}

$phone = $_POST['phone'] ?? '';

if (empty($phone)) {
    json_response(false, 'El número de teléfono es requerido.');
}

try {
    $pdo = conectarDB();

    // 1. Verificar si el teléfono existe en la base de datos
    $sql_check = "SELECT id FROM users WHERE phone_number = :phone";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':phone' => $phone]);

    if (!$stmt_check->fetch()) {
        json_response(false, 'Este número de teléfono no está registrado.');
    }

    // 2. Si el usuario existe, llamar al webhook
    $webhook_url = 'https://n8n.magnificapec.com/webhook/e79006dd-93bc-4bfa-9c31-cf6385a802d8';
    
    $data = ['phone' => $phone];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'ignore_errors' => true // Permite capturar la respuesta incluso si hay errores HTTP
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($webhook_url, false, $context);

    // Verificar el código de estado de la respuesta del webhook
    if ($result === FALSE || strpos($http_response_header[0], '200') === false) {
        // Ocurrió un error al contactar el webhook
        error_log("Error al llamar al webhook para el teléfono: " . $phone);
        json_response(false, 'No se pudo enviar el código. Inténtalo más tarde.');
    }

    // Si todo va bien, el webhook se encargó de todo.
    json_response(true, 'Se ha iniciado el proceso de recuperación.');

} catch (PDOException $e) {
    error_log('Error en send_recovery_code: ' . $e->getMessage());
    json_response(false, 'Error interno del servidor.');
}
?>