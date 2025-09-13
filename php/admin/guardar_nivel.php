<?php
// admin/guardar_nivel.php

// 1. Verificamos que se está accediendo a través del método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 2. Incluimos el archivo de conexión a la base de datos
    require_once '../../config/db.php';

    // 3. Sanitizamos y obtenemos los datos del formulario
    // filter_input es una forma segura de obtener datos de entrada
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $Traduccion = filter_input(INPUT_POST, 'traduccion', FILTER_SANITIZE_STRING);
    $level_order = filter_input(INPUT_POST, 'level_order', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // 4. Validamos que los datos esenciales no estén vacíos
    if (!$title || $level_order === false) {
        // Si faltan datos, podrías redirigir con un mensaje de error.
        // Por ahora, simplemente terminamos el script.
        exit('Error: El título y el número de orden son obligatorios.');
    }

    try {
        // 5. Obtenemos la conexión PDO
        $pdo = conectarDB();

        // 6. Preparamos la consulta SQL para evitar inyección SQL
        // Usamos marcadores de posición con nombre (ej: :title) para mayor claridad
        $sql = "INSERT INTO levels (title,Traduccion, level_order, description) VALUES (:title,:Traduccion, :level_order, :description)";

        // Preparamos la sentencia
        $stmt = $pdo->prepare($sql);

        // 7. Vinculamos los valores a los marcadores de posición
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':Traduccion', $Traduccion, PDO::PARAM_STR);
        $stmt->bindParam(':level_order', $level_order, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        // 8. Ejecutamos la consulta
        $stmt->execute();

        // 9. Redirigimos al usuario al listado de niveles
        // Esto previene que se reenvíe el formulario si el usuario recarga la página
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        // Manejo de errores
        // Verificamos si el error es por una clave única duplicada (level_order)
        if ($e->getCode() == 23000) {
            exit('Error: El número de orden (' . htmlspecialchars($level_order) . ') ya existe. Por favor, elige otro.');
        } else {
            // Para cualquier otro error de la base de datos
            error_log('PDO Error al guardar nivel: ' . $e->getMessage());
            exit('Ocurrió un error al guardar el nivel. Inténtalo de nuevo.');
        }
    } finally {
        // Cerramos la conexión
        $pdo = null;
    }
} else {
    // Si alguien intenta acceder a este archivo directamente, lo redirigimos
    header('Location: index.php');
    exit;
}
