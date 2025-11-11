<?php
// php/update_progress.php

session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

function json_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Error: Usuario no autenticado.');
}

// --- NUEVA LÓGICA DE PAUSA ---
// Define los niveles que activarán la pausa (justo ANTES de empezarlos)
// El level_order 4 es el "cuarto nivel".
$pause_levels = [5, 9, 12];
// URL de tu webhook de WhatsApp (reemplaza esto)
$whatsapp_webhook_url = 'https://n8n.magnificapec.com/webhook/33404621-35d4-477d-a562-f5743d0d37f6';

// --- FIN NUEVA LÓGICA ---

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // 1. Obtener el 'level_order' del nivel actual del usuario
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

    // 2. Encontrar el 'level_order' del siguiente nivel
    $next_level_order = $current_level_order + 1;
    $sql_next_level_id = "SELECT id FROM levels WHERE level_order = :next_order LIMIT 1";
    $stmt_next = $pdo->prepare($sql_next_level_id);
    $stmt_next->execute([':next_order' => $next_level_order]);
    $next_level_id = $stmt_next->fetchColumn();

    // --- LÓGICA DE PAUSA MODIFICADA ---
    if ($next_level_id && in_array($next_level_order, $pause_levels)) {
        
        // 3.A. El usuario ha llegado a un checkpoint.
        // Actualizamos su estado a "pausado"
        $sql_pause = "UPDATE users SET is_paused = 1 WHERE id = :user_id";
        $stmt_pause = $pdo->prepare($sql_pause);
        $stmt_pause->execute([':user_id' => $user_id]);

        // 4.A. Llamar al Webhook de WhatsApp con los datos
        $webhook_data = [
            'user_id' => $user_id,
            'user_name' => $_SESSION['user_name'],
            'pausing_at_level' => $next_level_order
        ];
        
        // (Usamos cURL para enviar el webhook)
        $ch = curl_init($whatsapp_webhook_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch); // Enviamos el webhook
        curl_close($ch);

        // 5.A. Enviar respuesta al frontend para que muestre la pantalla de pausa
        json_response(true, 'pause_activated');

    } elseif ($next_level_id) {
        
        // 3.B. Lógica original: El usuario avanza normalmente
        $sql_update = "UPDATE users SET current_level_id = :next_level_id WHERE id = :user_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':next_level_id' => $next_level_id,
            ':user_id' => $user_id
        ]);
        
        // 4.B. Llamar al Webhook de progreso normal (el que ya tenías)
        $webhook_data = [
            'user_id' => $user_id,
            'current_level_id' => $current_level_id,
            'next_level_id' => $next_level_id
        ];
        $webhook_url = 'https://n8n.magnificapec.com/webhook/d790b83d-e01f-4d9c-8b24-cfb23f5fba22';
        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);

        // 5.B. Enviar respuesta normal al frontend
        json_response(true, 'Progreso actualizado.');

    } else {
        // El usuario ya está en el último nivel
        json_response(true, 'Progreso actualizado. El usuario ya ha alcanzado el último nivel.');
    }

} catch (Exception $e) {
    error_log('Error en update_progress: ' . $e->getMessage());
    json_response(false, 'Error en la base de datos: ' . $e->getMessage());
}
?>