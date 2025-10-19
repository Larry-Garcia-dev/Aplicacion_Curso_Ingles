<?php
// php/go_back_progress.php

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

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // 1. Obtener el 'level_order' del nivel actual del usuario
    $sql_current_level = "
        SELECT l.level_order 
        FROM users u
        JOIN levels l ON u.current_level_id = l.id
        WHERE u.id = :user_id
    ";
    $stmt_current = $pdo->prepare($sql_current_level);
    $stmt_current->execute([':user_id' => $user_id]);
    $current_level_order = $stmt_current->fetchColumn();

    if ($current_level_order === false) {
        json_response(false, 'No se pudo encontrar el nivel actual del usuario.');
    }

    // 2. Si el usuario ya está en el primer nivel, no se puede retroceder
    if ((int)$current_level_order <= 1) {
        json_response(false, 'Ya estás en el primer nivel.');
    }

    // 3. Encontrar el ID del nivel anterior basado en el orden
    $prev_level_order = $current_level_order - 1;
    $sql_prev_level = "SELECT id FROM levels WHERE level_order = :prev_order LIMIT 1";
    $stmt_prev = $pdo->prepare($sql_prev_level);
    $stmt_prev->execute([':prev_order' => $prev_level_order]);
    $prev_level_id = $stmt_prev->fetchColumn();

    // 4. Si existe un nivel anterior, actualizar el registro del usuario
    if ($prev_level_id) {
        $sql_update = "UPDATE users SET current_level_id = :prev_level_id WHERE id = :user_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':prev_level_id' => $prev_level_id,
            ':user_id' => $user_id
        ]);
        
        json_response(true, 'Has vuelto al nivel anterior.');
    } else {
        json_response(false, 'No se encontró un nivel anterior.');
    }

} catch (PDOException $e) {
    json_response(false, 'Error en la base de datos: ' . $e->getMessage());
}
?>