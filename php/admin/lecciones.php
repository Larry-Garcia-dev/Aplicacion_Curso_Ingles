<?php
// admin/lecciones.php
require_once 'auth.php';

require_once '../../config/db.php';

// 1. Validar el ID del nivel que viene por GET
$level_id = filter_input(INPUT_GET, 'level_id', FILTER_VALIDATE_INT);
$level_title = filter_input(INPUT_GET, 'level_title', FILTER_SANITIZE_STRING);

if (!$level_id) {
    header('Location: index.php');
    exit;
}

// 2. Consultar las lecciones para este nivel
$pdo = conectarDB();
$sql = "SELECT id, title, video_url FROM lessons WHERE level_id = :level_id ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':level_id', $level_id, PDO::PARAM_INT);
$stmt->execute();
$lessons = $stmt->fetchAll(); // Usamos fetchAll para obtener todos los resultados en un array

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecciones de <?php echo htmlspecialchars($level_title); ?></title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <header>
        <h1>Lecciones de: <?php echo htmlspecialchars($level_title); ?></h1>
        <p><a href="index.php">← Volver a la lista de Niveles</a></p>
    </header>

    <main>
        <h2>Gestionar Lecciones</h2>
        <a href="crear_leccion.php?level_id=<?php echo $level_id; ?>" class="button">Crear Nueva Lección</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título de la Lección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lessons as $lesson): ?>
                    <tr>
                        <td><?php echo $lesson['id']; ?></td>
                        <td><?php echo htmlspecialchars_decode($lesson['title']); ?></td>
                        <td style="display: flex; gap: 10px; align-items: center;">
                            <a href="editar_leccion.php?id=<?php echo $lesson['id']; ?>" class="button">Editar</a>

                            <form action="eliminar_leccion.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta lección?');">
                                <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
                                <input type="hidden" name="level_id" value="<?php echo $level_id; ?>">
                                <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 8px 12px; cursor: pointer; font-size: 14px;">Eliminar</button>
                            </form>

                            <a href="ejercicios.php?lesson_id=<?php echo $lesson['id']; ?>&lesson_title=<?php echo urlencode($lesson['title']); ?>" class="button" style="background-color: #5cb85c;">Ver Ejercicios</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($lessons) === 0): ?>
                    <tr>
                        <td colspan="3">Aún no hay lecciones para este nivel. ¡Crea la primera!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <?php $pdo = null; ?>
</body>

</html>