<?php
// admin/actualizar_nivel.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../config/db.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $level_order = filter_input(INPUT_POST, 'level_order', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    $summary_raw = $_POST['summary'] ?? [];
    $summary_points_clean = [];
    foreach ($summary_raw as $point) {
        if (!empty(trim($point['title'])) && !empty(trim($point['desc']))) {
            $summary_points_clean[] = [
                'title' => filter_var(trim($point['title']), FILTER_SANITIZE_STRING),
                'desc' => filter_var(trim($point['desc']), FILTER_SANITIZE_STRING)
            ];
        }
    }
    $summary_json = !empty($summary_points_clean) ? json_encode($summary_points_clean) : null;

    if (!$id || !$title || $level_order === false) {
        exit('Error: Datos inválidos.');
    }

    try {
        $pdo = conectarDB();
        $sql = "UPDATE levels SET title = :title, level_order = :level_order, description = :description, summary_points = :summary_points WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':level_order', $level_order, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':summary_points', $summary_json, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: index.php?status=updated');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            exit('Error: El número de orden (' . htmlspecialchars($level_order) . ') ya está en uso.');
        } else {
            error_log('PDO Error al actualizar nivel: ' . $e->getMessage());
            exit('Ocurrió un error al actualizar el nivel.');
        }
    }
} else {
    header('Location: index.php');
    exit;
}
?>