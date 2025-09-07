<?php
// admin/guardar_leccion.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../../config/db.php';

    // 1. Sanitizar y obtener los datos
    $level_id = filter_input(INPUT_POST, 'level_id', FILTER_VALIDATE_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $video_url = filter_input(INPUT_POST, 'video_url', FILTER_VALIDATE_URL);
    $pdf_url = filter_input(INPUT_POST, 'pdf_url', FILTER_VALIDATE_URL);

    // 2. Validar datos
    if (!$level_id || !$title) {
        exit('Error: El título y el ID del nivel son obligatorios.');
    }

    try {
        $pdo = conectarDB();

        // 3. Preparar la consulta SQL
        $sql = "INSERT INTO lessons (level_id, title, description, video_url, pdf_url) VALUES (:level_id, :title, :description, :video_url, :pdf_url)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Vincular los valores
        $stmt->bindParam(':level_id', $level_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':video_url', $video_url, PDO::PARAM_STR);
        $stmt->bindParam(':pdf_url', $pdf_url, PDO::PARAM_STR);

        // 5. Ejecutar
        $stmt->execute();

        // 6. Redirigir de vuelta a la lista de lecciones de ese nivel
        header('Location: lecciones.php?level_id=' . $level_id);
        exit;

    } catch (PDOException $e) {
        error_log('PDO Error al guardar lección: ' . $e->getMessage());
        exit('Ocurrió un error al guardar la lección.');
    } finally {
        $pdo = null;
    }

} else {
    header('Location: index.php');
    exit;
}
?>