<?php
// admin/crear_nivel.php

// Este archivo es principalmente HTML, pero podemos añadir lógica PHP aquí más adelante si es necesario.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Nivel</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Crear Nuevo Nivel</h1>
        <p><a href="index.php">Volver al listado</a></p>
    </header>

    <main>
        <form action="guardar_nivel.php" method="POST">
            <p>
                <label for="title">Título del Nivel</label>
                <input type="text" id="title" name="title" required placeholder="Ej: Nivel 1: Saludos y Presentaciones">
            </p>
            <p>
                <label for="level_order">Número de Orden</label>
                <input type="number" id="level_order" name="level_order" required placeholder="Ej: 1">
            </p>
            <p>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </p>
            <button type="submit">Guardar Nivel</button>
        </form>
    </main>
</body>
</html>