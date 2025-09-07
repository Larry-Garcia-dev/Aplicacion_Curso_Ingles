<?php
// admin/eliminar_leccion.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/db.php';

    // 1. Sanitizar y validar IDs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $level_id = filter_input(INPUT_POST, 'level_id', FILTER_VALIDATE_INT);

    if (!$id || !$level_id) {
        header('Location: index.php');
        exit;
    }

    try {
        $pdo = conectarDB();
        // 2. Preparar consulta DELETE
        $sql = "DELETE FROM lessons WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 3. Redirigir a la lista de lecciones del nivel correspondiente
        header('Location: lecciones.php?level_id=' . $level_id);
        exit;
    } catch (PDOException $e) {
        error_log('PDO Error al eliminar lección: ' . $e->getMessage());
        exit('Ocurrió un error al eliminar la lección.');
    }
} else {
    header('Location: index.php');
    exit;
}
?>