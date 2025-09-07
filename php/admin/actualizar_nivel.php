<?php
// admin/actualizar_nivel.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../../config/db.php';

    // 1. Sanitizar y obtener los datos (incluyendo el ID)
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $level_order = filter_input(INPUT_POST, 'level_order', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // 2. Validar que los datos son correctos
    if (!$id || !$title || $level_order === false) {
        exit('Error: Datos inválidos.');
    }

    try {
        $pdo = conectarDB();

        // 3. Preparamos la consulta SQL de tipo UPDATE
        $sql = "UPDATE levels SET title = :title, level_order = :level_order, description = :description WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);

        // 4. Vinculamos los valores
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':level_order', $level_order, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // 5. Ejecutamos la consulta
        $stmt->execute();

        // 6. Redirigimos al listado
        header('Location: index.php?status=updated'); // Opcional: enviar un status para mostrar un mensaje
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            exit('Error: El número de orden (' . htmlspecialchars($level_order) . ') ya está en uso por otro nivel.');
        } else {
            error_log('PDO Error al actualizar nivel: ' . $e->getMessage());
            exit('Ocurrió un error al actualizar el nivel.');
        }
    } finally {
        $pdo = null;
    }

} else {
    header('Location: index.php');
    exit;
}
?>