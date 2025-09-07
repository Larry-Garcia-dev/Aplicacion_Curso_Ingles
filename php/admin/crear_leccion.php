<?php
// admin/crear_leccion.php

// Validamos que el ID del nivel nos llegue correctamente
$level_id = filter_input(INPUT_GET, 'level_id', FILTER_VALIDATE_INT);
if (!$level_id) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Lección</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Crear Nueva Lección</h1>
        <p><a href="lecciones.php?level_id=<?php echo $level_id; ?>">← Volver al listado de lecciones</a></p>
    </header>

    <main>
        <form action="guardar_leccion.php" method="POST">
            <input type="hidden" name="level_id" value="<?php echo $level_id; ?>">

            <p>
                <label for="title">Título de la Lección</label>
                <input type="text" id="title" name="title" required placeholder="Ej: Los Verbos Modales">
            </p>
            <p>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4" placeholder="Ej: En esta lección aprenderás a usar can, could, may, might..."></textarea>
            </p>
            <p>
                <label for="video_url">URL del Video (YouTube, Vimeo, etc.)</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
            </p>
             <p>
                <label for="pdf_url">URL del Material PDF</label>
                <input type="url" id="pdf_url" name="pdf_url" placeholder="https://ejemplo.com/material.pdf">
            </p>
            <button type="submit">Guardar Lección</button>
        </form>
    </main>
</body>
</html>