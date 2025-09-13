<?php
// admin/guardar_nivel.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once '../../config/db.php';

    // 1. Sanitizar y obtener los datos principales del formulario
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $Traduccion = filter_input(INPUT_POST, 'traduccion', FILTER_SANITIZE_STRING);
    $level_order = filter_input(INPUT_POST, 'level_order', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // 2. Obtener los puntos del resumen del formulario
    $summary_points_raw = isset($_POST['summary']) ? $_POST['summary'] : [];

    // 3. Filtrar los puntos del resumen que no estén vacíos
    $summary_points_filtered = [];
    foreach ($summary_points_raw as $point) {
        // Solo añadimos el punto si el título tiene contenido
        if (!empty(trim($point['title']))) {
            $summary_points_filtered[] = [
                'title' => filter_var(trim($point['title']), FILTER_SANITIZE_STRING),
                'desc' => filter_var(trim($point['desc']), FILTER_SANITIZE_STRING)
            ];
        }
    }

    // 4. Convertir el array de puntos a formato JSON
    // Usamos JSON_UNESCAPED_UNICODE para que los acentos y caracteres especiales se vean bien
    $summary_points_json = json_encode($summary_points_filtered, JSON_UNESCAPED_UNICODE);

    // 5. Validar que los datos esenciales no estén vacíos
    if (!$title || $level_order === false) {
        exit('Error: El título y el número de orden son obligatorios.');
    }

    try {
        $pdo = conectarDB();

        // 6. Preparar la consulta SQL para insertar en la tabla 'levels'
        $sql = "INSERT INTO levels (title, Traduccion, description, summary_points, level_order) 
                VALUES (:title, :Traduccion, :description, :summary_points, :level_order)";

        $stmt = $pdo->prepare($sql);

        // 7. Vincular los valores a los marcadores de posición
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':Traduccion', $Traduccion, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':summary_points', $summary_points_json, PDO::PARAM_STR); // Lo vinculamos como string
        $stmt->bindParam(':level_order', $level_order, PDO::PARAM_INT);

        // 8. Ejecutar la consulta
        $stmt->execute();

        // 9. Redirigir al listado
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            exit('Error: El número de orden (' . htmlspecialchars($level_order) . ') ya existe. Por favor, elige otro.');
        } else {
            error_log('PDO Error al guardar nivel: ' . $e->getMessage());
            exit('Ocurrió un error al guardar el nivel. Inténtalo de nuevo.');
        }
    } finally {
        $pdo = null;
    }
} else {
    header('Location: index.php');
    exit;
}
