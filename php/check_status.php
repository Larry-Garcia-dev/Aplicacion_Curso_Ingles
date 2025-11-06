<?php
// php/check_status.php

session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

function json_response($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Error: Usuario no autenticado.');
    exit;
}

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // Consultamos el estado de pausa actual del usuario
    $sql = "SELECT is_paused FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $is_paused = $stmt->fetchColumn();

    if ((int)$is_paused === 0) {
        // ¡El usuario ya no está pausado!
        json_response(true, 'unpaused');
    } else {
        // El usuario sigue pausado
        json_response(false, 'still_paused');
    }

} catch (PDOException $e) {
    json_response(false, 'Error en la base de datos: ' . $e->getMessage());
}
?>