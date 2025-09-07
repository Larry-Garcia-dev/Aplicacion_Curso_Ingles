<?php
// admin/eliminar_nivel.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../../config/db.php';

    // 1. Sanitizar y validar el ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        // Si no hay ID o no es válido, no hacemos nada y redirigimos
        header('Location: index.php?status=error');
        exit;
    }

    try {
        $pdo = conectarDB();

        // 2. Preparamos la consulta SQL de tipo DELETE
        // ¡IMPORTANTE! Al tener ON DELETE CASCADE en la base de datos,
        // al eliminar un nivel, también se eliminarán todas las lecciones
        // y ejercicios asociados a él.
        $sql = "DELETE FROM levels WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);

        // 3. Vinculamos el valor del ID
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // 4. Ejecutamos la consulta
        $stmt->execute();

        // 5. Redirigimos al listado con un mensaje de éxito
        header('Location: index.php?status=deleted');
        exit;

    } catch (PDOException $e) {
        error_log('PDO Error al eliminar nivel: ' . $e->getMessage());
        exit('Ocurrió un error al intentar eliminar el nivel.');
    } finally {
        $pdo = null;
    }

} else {
    // Si se intenta acceder por otro método que no sea POST, redirigimos
    header('Location: index.php');
    exit;
}
?>