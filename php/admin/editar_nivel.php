<?php
// admin/editar_nivel.php
require_once 'auth.php';

require_once '../../config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo = conectarDB();
$sql = "SELECT * FROM levels WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$level = $stmt->fetch();

if (!$level) {
    header('Location: index.php');
    exit;
}
$summary_points = $level['summary_points'] ? json_decode($level['summary_points'], true) : [];
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
        <h1>Editar Nivel: <?php echo htmlspecialchars_decode($level['title']); ?></h1>
        <p><a href="index.php">Volver al listado</a></p>
    </header>

    <main>
        <form action="actualizar_nivel.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $level['id']; ?>">

            <p>
                <label for="title">Título del Nivel</label>
                <!-- <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars_decode($level['title']); ?>"> -->
                <textarea rows="3" name="title" id="title"><?php echo htmlspecialchars_decode($level['title']); ?></textarea>
            </p>
            <p>
                <label for="title">Traduccion</label>
                <!-- <input type="text" id="title" name="Traduccion" required value="<?php echo htmlspecialchars_decode($level['Traduccion']); ?>"> -->
                <textarea rows="3" name="Traduccion" id=""><?php echo htmlspecialchars_decode($level['Traduccion']); ?></textarea>
            </p>
            <p>
                <label for="level_order">Número de Orden</label>
                <input type="number" id="level_order" name="level_order" required value="<?php echo htmlspecialchars_decode($level['level_order']); ?>">
            </p>
            <p>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars_decode($level['description']); ?></textarea>
            </p>

            <fieldset>
                <legend>Puntos del Resumen del Nivel</legend>
                <p>
                    <label>Punto 1</label>
                    <input type="text" name="summary[0][title]" placeholder="Título 1" value="<?php echo htmlspecialchars_decode($summary_points[0]['title'] ?? ''); ?>">
                    <textarea rows="3" name="summary[0][desc]" placeholder="Descripción 1"><?php echo htmlspecialchars_decode($summary_points[0]['desc'] ?? ''); ?></textarea>
                </p>
                <p>
                    <label>Punto 2</label>
                    <input type="text" name="summary[1][title]" placeholder="Título 2" value="<?php echo htmlspecialchars_decode($summary_points[1]['title'] ?? ''); ?>">
                    <textarea rows="3" name="summary[1][desc]" placeholder="Descripción 2"><?php echo htmlspecialchars_decode($summary_points[1]['desc'] ?? ''); ?></textarea>
                </p>
                <p>
                    <label>Punto 3</label>
                    <input type="text" name="summary[2][title]" placeholder="Título 3" value="<?php echo htmlspecialchars_decode($summary_points[2]['title'] ?? ''); ?>">
                    <textarea rows="3" name="summary[2][desc]" placeholder="Descripción 3"><?php echo htmlspecialchars_decode($summary_points[2]['desc'] ?? ''); ?></textarea>
                </p>
            </fieldset>

            <button type="submit">Actualizar Nivel</button>
        </form>
    </main>
</body>

</html>