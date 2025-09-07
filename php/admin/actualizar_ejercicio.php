<?php
// admin/actualizar_ejercicio.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../config/db.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);
    $exercise_type = filter_input(INPUT_POST, 'exercise_type', FILTER_SANITIZE_STRING);
    $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);

    if (!$id || !$lesson_id || !$exercise_type || !$question) {
        exit('Error: Faltan datos esenciales.');
    }

    $options = null;
    $correct_answer = '';

    if ($exercise_type === 'choose_word' || $exercise_type === 'complete_sentence') {
        $options_raw = filter_input(INPUT_POST, 'options', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $options_clean = array_filter(array_map('trim', $options_raw));
        $correct_answer = $options_clean[0] ?? '';
        $options = json_encode($options_clean);
    } else {
        $correct_answer = filter_input(INPUT_POST, 'correct_answer', FILTER_SANITIZE_STRING);
    }

    try {
        $pdo = conectarDB();
        $sql = "UPDATE exercises SET exercise_type = :exercise_type, question = :question, options = :options, correct_answer = :correct_answer WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':exercise_type', $exercise_type, PDO::PARAM_STR);
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':options', $options, PDO::PARAM_STR);
        $stmt->bindParam(':correct_answer', $correct_answer, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ejercicios.php?lesson_id=' . $lesson_id);
        exit;
    } catch (PDOException $e) {
        error_log('PDO Error al actualizar ejercicio: ' . $e->getMessage());
        exit('Ocurrió un error al actualizar el ejercicio.');
    }
} else {
    header('Location: index.php');
    exit;
}
?>