<?php
// admin/actualizar_leccion.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../config/db.php';

    // 1. Sanitizar y obtener datos
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $level_id = filter_input(INPUT_POST, 'level_id', FILTER_VALIDATE_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $video_url = filter_input(INPUT_POST, 'video_url', FILTER_VALIDATE_URL);
    $pdf_url = filter_input(INPUT_POST, 'pdf_url', FILTER_VALIDATE_URL);

    // 2. Validar
    if (!$id || !$level_id || !$title) {
        exit('Error: Datos inválidos.');
    }

    try {
        $pdo = conectarDB();
        // 3. Preparar consulta UPDATE
        $sql = "UPDATE lessons SET title = :title, description = :description, video_url = :video_url, pdf_url = :pdf_url WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // 4. Vincular valores
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':video_url', $video_url, PDO::PARAM_STR);
        $stmt->bindParam(':pdf_url', $pdf_url, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 5. Redirigir
        header('Location: lecciones.php?level_id=' . $level_id);
        exit;
    } catch (PDOException $e) {
        error_log('PDO Error al actualizar lección: ' . $e->getMessage());
        exit('Ocurrió un error al actualizar la lección.');
    }
} else {
    header('Location: index.php');
    exit;
}
?>