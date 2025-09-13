<?php
// /curso/index.php (Controlador con Progreso de Usuario)

require_once '../php/auth.php'; // Protege la página para que solo usuarios logueados entren
require_once '../config/db.php';

try {
    $pdo = conectarDB();
    $user_id = $_SESSION['user_id'];

    // 1. Obtener el ID del nivel actual del usuario desde la tabla 'users'
    $sql_user_level = "SELECT current_level_id FROM users WHERE id = :user_id";
    $stmt_user_level = $pdo->prepare($sql_user_level);
    $stmt_user_level->execute([':user_id' => $user_id]);
    $current_level_id = $stmt_user_level->fetchColumn();

    // Fallback: Si por alguna razón el usuario no tiene un nivel, se le asigna el primero.
    if (!$current_level_id) {
        $sql_first_level = "SELECT id FROM levels WHERE level_order = 1 LIMIT 1";
        $current_level_id = $pdo->query($sql_first_level)->fetchColumn();
        $sql_update_user = "UPDATE users SET current_level_id = :level_id WHERE id = :user_id";
        $stmt_update = $pdo->prepare($sql_update_user);
        $stmt_update->execute([':level_id' => $current_level_id, ':user_id' => $user_id]);
    }

    // 2. Obtener la información COMPLETA de ESE ÚNICO nivel
    $sql_level = "SELECT * FROM levels WHERE id = :level_id";
    $stmt_level = $pdo->prepare($sql_level);
    $stmt_level->execute([':level_id' => $current_level_id]);
    $level_data = $stmt_level->fetch(PDO::FETCH_ASSOC);

    if (!$level_data) {
        throw new Exception("Contenido no encontrado. Es posible que hayas completado todos los niveles.");
    }

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

} catch (Exception $e) {
    // --- CÓDIGO CORREGIDO ---
    // Se eliminó la comilla simple extra antes del punto de concatenación
    error_log('Error al cargar datos del curso: ' . $e->getMessage());
    exit('Error al cargar el curso: ' . $e->getMessage());

}  finally {

    $pdo = null;
}

// 4. Incluir la vista y pasarle la variable $level_data (que contiene un solo nivel)
include 'vista.php';
?>