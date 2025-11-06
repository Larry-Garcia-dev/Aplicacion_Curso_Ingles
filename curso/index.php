<?php
// /curso/index.php (Controlador con Progreso de Usuario)

require_once '../php/auth.php'; // Protege la página
require_once '../config/db.php';

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // 1. Obtener el ID del nivel actual Y el estado de pausa del usuario
    $sql_user_data = "SELECT current_level_id, is_paused FROM users WHERE id = :user_id"; // <-- MODIFICADO
    $stmt_user_data = $pdo->prepare($sql_user_data);
    $stmt_user_data->execute([':user_id' => $user_id]);
    $user_data = $stmt_user_data->fetch();

    $current_level_id = $user_data['current_level_id'];
    $is_paused = (bool)$user_data['is_paused']; // <-- NUEVO: Pasamos esto a la vista

    // Fallback: Si no tiene nivel, se le asigna el primero.
    if (!$current_level_id) {
        $sql_first_level = "SELECT id FROM levels WHERE level_order = 1 LIMIT 1";
        $current_level_id = $pdo->query($sql_first_level)->fetchColumn();
        $sql_update_user = "UPDATE users SET current_level_id = :level_id WHERE id = :user_id";
        $stmt_update = $pdo->prepare($sql_update_user);
        $stmt_update->execute([':level_id' => $current_level_id, ':user_id' => $user_id]);
    }

    // 2. Obtener la información COMPLETA de ESE ÚNICO nivel
    // (No cargamos los datos del nivel si el usuario está pausado)
    $level_data = null;
    if (!$is_paused) { // <-- NUEVO: Solo carga el nivel si NO está pausado
        $sql_level = "SELECT * FROM levels WHERE id = :level_id";
        $stmt_level = $pdo->prepare($sql_level);
        $stmt_level->execute([':level_id' => $current_level_id]);
        $level_data = $stmt_level->fetch(PDO::FETCH_ASSOC);

        if ($level_data) {
            // 3. Obtener las lecciones y ejercicios de ESE nivel
            $sql_lessons = "SELECT * FROM lessons WHERE level_id = :level_id ORDER BY id ASC";
            $stmt_lessons = $pdo->prepare($sql_lessons);
            $stmt_lessons->execute([':level_id' => $current_level_id]);
            $level_data['lessons'] = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);

            foreach ($level_data['lessons'] as &$lesson) {
                $sql_exercises = "SELECT * FROM exercises WHERE lesson_id = :lesson_id ORDER BY id ASC";
                $stmt_exercises = $pdo->prepare($sql_exercises);
                $stmt_exercises->execute([':lesson_id' => $lesson['id']]);
                $lesson['exercises'] = $stmt_exercises->fetchAll(PDO::FETCH_ASSOC);
            }
            unset($lesson);
        } else {
            // El usuario completó todos los niveles, $level_data será null
        }
    }

} catch (Exception $e) {
    error_log('Error al cargar datos del curso: ' . $e->getMessage());
    exit('Error al cargar el curso: ' . $e->getMessage());
} finally {
    $pdo = null;
}

// 4. Incluir la vista y pasarle $level_data (el nivel) y $is_paused (el estado)
include 'vista.php';
?>