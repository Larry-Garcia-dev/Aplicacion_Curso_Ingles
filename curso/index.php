<?php
// /curso/index.php (Controlador)

require_once '../config/db.php';

try {
    $pdo = conectarDB();

    // 1. Obtener todos los niveles (asegúrate de incluir la nueva columna)
    $sql_levels = "SELECT * FROM levels ORDER BY level_order ASC";
    $stmt_levels = $pdo->query($sql_levels);
    $course_data = $stmt_levels->fetchAll();

    if (empty($course_data)) {
        throw new Exception("No se encontraron niveles en la base de datos.");
    }

    // 2. Obtener todas las lecciones y ejercicios
    $sql_lessons = "SELECT * FROM lessons ORDER BY id ASC";
    $all_lessons = $pdo->query($sql_lessons)->fetchAll();

    $sql_exercises = "SELECT * FROM exercises ORDER BY id ASC";
    $all_exercises = $pdo->query($sql_exercises)->fetchAll();

    // 3. Organizar lecciones y ejercicios dentro de cada nivel
    foreach ($course_data as &$level) { // Usamos '&' para modificar el array directamente
        $level['lessons'] = [];
        foreach ($all_lessons as $lesson) {
            if ($lesson['level_id'] == $level['id']) {
                $lesson['exercises'] = [];
                foreach ($all_exercises as $exercise) {
                    if ($exercise['lesson_id'] == $lesson['id']) {
                        $lesson['exercises'][] = $exercise;
                    }
                }
                $level['lessons'][] = $lesson;
            }
        }
    }
    unset($level); // Rompemos la referencia

} catch (Exception $e) {
    error_log('Error al cargar datos del curso: ' . $e->getMessage());
    exit('No se pudo cargar el contenido del curso.');
} finally {
    $pdo = null;
}

// 4. Incluir la vista
include 'vista.php';
?>