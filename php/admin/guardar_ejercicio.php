<?php
// admin/guardar_ejercicio.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../config/db.php';

    // 1. Sanitizar y obtener datos comunes
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $exercise_type = filter_input(INPUT_POST, 'exercise_type', FILTER_SANITIZE_STRING);
    $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);

    if (!$lesson_id || !$exercise_type || !$question) {
        exit('Error: Faltan datos esenciales.');
    }

    $options = null;
    $correct_answer = '';

    // 2. Procesar datos según el tipo de ejercicio
    if ($exercise_type === 'choose_word' || $exercise_type === 'complete_sentence') {
        // Filtramos y limpiamos el array de opciones
        $options_raw = filter_input(INPUT_POST, 'options', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $options_clean = array_filter(array_map('trim', $options_raw));

        if (empty($options_clean)) {
            exit('Error: Debes proporcionar al menos una opción para este tipo de ejercicio.');
        }

        // La respuesta correcta es la primera opción
        $correct_answer = $options_clean[0];
        // Codificamos el array de opciones a JSON para guardarlo
        $options = json_encode($options_clean);

    } else { // Para 'translate' y 'listen_and_write'
        $correct_answer = filter_input(INPUT_POST, 'correct_answer', FILTER_SANITIZE_STRING);
        if (!$correct_answer) {
            exit('Error: La respuesta correcta es obligatoria para este tipo de ejercicio.');
        }
    }

    try {
        $pdo = conectarDB();
        // 3. Preparar la consulta SQL
        $sql = "INSERT INTO exercises (lesson_id, exercise_type, question, options, correct_answer) VALUES (:lesson_id, :exercise_type, :question, :options, :correct_answer)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Vincular los valores
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->bindParam(':exercise_type', $exercise_type, PDO::PARAM_STR);
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':options', $options, PDO::PARAM_STR);
        $stmt->bindParam(':correct_answer', $correct_answer, PDO::PARAM_STR);
        $stmt->execute();

        // 5. Redirigir
        header('Location: ejercicios.php?lesson_id=' . $lesson_id);
        exit;

    } catch (PDOException $e) {
        error_log('PDO Error al guardar ejercicio: ' . $e->getMessage());
        exit('Ocurrió un error al guardar el ejercicio.');
    }

} else {
    header('Location: index.php');
    exit;
}
?>