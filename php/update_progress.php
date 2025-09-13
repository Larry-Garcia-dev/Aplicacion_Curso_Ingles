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
        json_response(true, '¡Nivel actualizado con éxito!');
    } else {
        // Si no hay más niveles, informa al usuario
        json_response(false, '¡Felicidades! Has completado todos los niveles.');
    }

} catch (PDOException $e) {
    error_log('Error al actualizar progreso: ' . $e->getMessage());
    json_response(false, 'Ocurrió un error en el servidor.');
}
?>