<?php
// Inicia la sesión para acceder a los datos del usuario logueado
session_start();

// Incluye el archivo de conexión a la base de datos
require_once '../config/db.php';

// Define una función para enviar respuestas en formato JSON
header('Content-Type: application/json');

function json_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Verifica que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Error: Usuario no autenticado.');
}

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // 1. Obtener el 'id' y 'level_order' del nivel actual del usuario
    $sql_current_level = "
        SELECT l.id, l.level_order 
        FROM users u
        JOIN levels l ON u.current_level_id = l.id
        WHERE u.id = :user_id
    ";
    $stmt_current = $pdo->prepare($sql_current_level);
    $stmt_current->execute([':user_id' => $user_id]);
    $current_level_info = $stmt_current->fetch(PDO::FETCH_ASSOC);

    if ($current_level_info === false) {
        json_response(false, 'No se pudo encontrar el nivel actual del usuario.');
    }

    $current_level_id = $current_level_info['id'];
    $current_level_order = $current_level_info['level_order'];

    // 2. Encontrar el ID del siguiente nivel basado en el orden
    $next_level_order = $current_level_order + 1;
    $sql_next_level = "SELECT id FROM levels WHERE level_order = :next_order LIMIT 1";
    $stmt_next = $pdo->prepare($sql_next_level);
    $stmt_next->execute([':next_order' => $next_level_order]);
    $next_level_id = $stmt_next->fetchColumn();

    // 3. Si existe un siguiente nivel, actualizar el registro del usuario
    if ($next_level_id) {
        $sql_update = "UPDATE users SET current_level_id = :next_level_id WHERE id = :user_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':next_level_id' => $next_level_id,
            ':user_id' => $user_id
        ]);
        
        // --- NUEVA LÓGICA PARA ENVIAR EL WEBHOOK ---
        
        // Datos a enviar en el JSON
        $webhook_data = [
            'user_id' => $user_id,
            'current_level_id' => $current_level_id,
            'next_level_id' => $next_level_id
        ];

        // URL del webhook
        $webhook_url = 'https://n8n.magnificapec.com/webhook/d790b83d-e01f-4d9c-8b24-cfb23f5fba22';

        // Inicializar cURL
        $ch = curl_init($webhook_url);
        
        // Configurar cURL para enviar JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($webhook_data))
        ]);

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Cerrar cURL
        curl_close($ch);

        // Imprimir en la consola (o log) el resultado del envío
        if ($http_code >= 200 && $http_code < 300) {
            error_log('Webhook enviado correctamente. Respuesta HTTP: ' . $http_code);
            json_response(true, 'Progreso actualizado y webhook enviado correctamente.');
        } else {
            error_log('Error al enviar el webhook. Código HTTP: ' . $http_code);
            json_response(false, 'Progreso actualizado pero falló el envío del webhook.');
        }

    } else {
        json_response(true, 'Progreso actualizado. El usuario ya ha alcanzado el último nivel.');
    }
} catch (PDOException $e) {
    json_response(false, 'Error en la base de datos: ' . $e->getMessage());
}
?>