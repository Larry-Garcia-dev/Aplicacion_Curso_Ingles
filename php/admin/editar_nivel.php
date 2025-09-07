<?php
// admin/editar_nivel.php

require_once '../../config/db.php';

// 1. Validar el ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    // Si no hay ID o no es válido, redirigimos
    header('Location: index.php');
    exit;
}

// 2. Obtener los datos del nivel de la BD
$pdo = conectarDB();
$sql = "SELECT * FROM levels WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$level = $stmt->fetch();

// Si no se encuentra el nivel, redirigimos
if (!$level) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nivel</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Editar Nivel: <?php echo htmlspecialchars($level['title']); ?></h1>
        <p><a href="index.php">Volver al listado</a></p>
    </header>

    <main>
        <form action="actualizar_nivel.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $level['id']; ?>">

            <p>
                <label for="title">Título del Nivel</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($level['title']); ?>">
            </p>
            <p>
                <label for="level_order">Número de Orden</label>
                <input type="number" id="level_order" name="level_order" required value="<?php echo htmlspecialchars($level['level_order']); ?>">
            </p>
            <p>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($level['description']); ?></textarea>
            </p>
            <button type="submit">Actualizar Nivel</button>
        </form>
    </main>
</body>
</html>
<?php $pdo = null; ?>