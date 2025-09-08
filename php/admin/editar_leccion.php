<?php
// admin/editar_leccion.php
require_once '../../config/db.php';

// 1. Validar ID de la lección
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

// 2. Obtener datos de la lección
$pdo = conectarDB();
$sql = "SELECT * FROM lessons WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$lesson = $stmt->fetch();

if (!$lesson) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lección</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Editar Lección: <?php echo htmlspecialchars($lesson['title']); ?></h1>
        <p><a href="lecciones.php?level_id=<?php echo $lesson['level_id']; ?>">← Volver a lecciones</a></p>
    </header>
    <main>
        <form action="actualizar_leccion.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
            <input type="hidden" name="level_id" value="<?php echo $lesson['level_id']; ?>">

            <p>
                <label for="title">Título</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($lesson['title']); ?>">
            </p>
            <p>
                <label for="description">Descripción</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($lesson['description']); ?></textarea>
            </p>
            <p>
                <label for="video_url">URL del Video</label>
                <input type="url" id="video_url" name="video_url" value="<?php echo htmlspecialchars($lesson['video_url']); ?>">
            </p>
             <p>
                <label for="pdf_url">URL del PDF</label>
                <input type="url" id="pdf_url" name="pdf_url" value="<?php echo htmlspecialchars($lesson['pdf_url']); ?>">
            </p>
            <button type="submit">Actualizar Lección</button>
        </form>
    </main>
</body>
</html>