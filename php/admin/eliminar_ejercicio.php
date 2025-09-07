<?php
// admin/eliminar_ejercicio.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../config/db.php';

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $lesson_id = filter_input(INPUT_POST, 'lesson_id', FILTER_VALIDATE_INT);

    if (!$id || !$lesson_id) {
        header('Location: index.php');
        exit;
    }

    try {
        $pdo = conectarDB();
        $sql = "DELETE FROM exercises WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ejercicios.php?lesson_id=' . $lesson_id);
        exit;
    } catch (PDOException $e) {
        error_log('PDO Error al eliminar ejercicio: ' . $e->getMessage());
        exit('Ocurrió un error al eliminar el ejercicio.');
    }
} else {
    header('Location: index.php');
    exit;
}
?>