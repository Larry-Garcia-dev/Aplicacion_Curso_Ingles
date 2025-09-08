<?php
// admin/crear_nivel.php
require_once 'auth.php';

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

            <fieldset>
                <legend>Puntos del Resumen del Nivel</legend>
                <p>
                    <label>Punto 1 (Título / Descripción)</label>
                    <input type="text" name="summary[0][title]" placeholder="Título 1 (ej: Vocabulario)">
                    <input type="text" name="summary[0][desc]" placeholder="Descripción 1">
                </p>
                <p>
                    <label>Punto 2 (Título / Descripción)</label>
                    <input type="text" name="summary[1][title]" placeholder="Título 2 (ej: Gramática)">
                    <input type="text" name="summary[1][desc]" placeholder="Descripción 2">
                </p>
                <p>
                    <label>Punto 3 (Título / Descripción)</label>
                    <input type="text" name="summary[2][title]" placeholder="Título 3 (ej: Comprensión)">
                    <input type="text" name="summary[2][desc]" placeholder="Descripción 3">
                </p>
            </fieldset>

            <button type="submit">Guardar Nivel</button>
        </form>
    </main>
</body>

</html>