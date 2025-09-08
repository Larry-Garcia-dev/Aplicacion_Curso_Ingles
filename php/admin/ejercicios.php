<?php
require_once 'auth.php';

// admin/ejercicios.php
require_once '../../config/db.php';

// 1. Validar IDs de la lección y nivel
$lesson_id = filter_input(INPUT_GET, 'lesson_id', FILTER_VALIDATE_INT);
$lesson_title = filter_input(INPUT_GET, 'lesson_title', FILTER_SANITIZE_STRING);

if (!$lesson_id) {
    header('Location: index.php');
    exit;
}

// Para el enlace "volver", necesitamos saber a qué nivel pertenece esta lección
$pdo = conectarDB();
$sql_level = "SELECT level_id FROM lessons WHERE id = :lesson_id";
$stmt_level = $pdo->prepare($sql_level);
$stmt_level->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
$stmt_level->execute();
$parent_level = $stmt_level->fetch();
$level_id = $parent_level['level_id'] ?? null;

// 2. Consultar los ejercicios para esta lección
$sql = "SELECT id, exercise_type, question, correct_answer FROM exercises WHERE lesson_id = :lesson_id ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
$stmt->execute();
$exercises = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicios de <?php echo htmlspecialchars($lesson_title); ?></title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <header>
        <h1>Ejercicios de la lección: <?php echo htmlspecialchars($lesson_title); ?></h1>
        <?php if ($level_id): ?>
            <p><a href="lecciones.php?level_id=<?php echo $level_id; ?>">← Volver a la lista de lecciones</a></p>
        <?php endif; ?>
    </header>

    <main>
        <h2>Gestionar Ejercicios</h2>
        <a href="crear_ejercicio.php?lesson_id=<?php echo $lesson_id; ?>" class="button">Crear Nuevo Ejercicio</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Pregunta / Instrucción</th>
                    <th>Respuesta Correcta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercises as $exercise): ?>
                    <tr>
                        <td><?php echo $exercise['id']; ?></td>
                        <td><?php echo htmlspecialchars($exercise['exercise_type']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['question']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['correct_answer']); ?></td>
                        <td style="display: flex; gap: 10px; align-items: center;">
                            <a href="editar_ejercicio.php?id=<?php echo $exercise['id']; ?>" class="button">Editar</a>

                            <form action="eliminar_ejercicio.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este ejercicio?');">
                                <input type="hidden" name="id" value="<?php echo $exercise['id']; ?>">
                                <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
                                <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 8px 12px; cursor: pointer; font-size: 14px;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($exercises) === 0): ?>
                    <tr>
                        <td colspan="5">Aún no hay ejercicios para esta lección.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <?php $pdo = null; ?>
</body>

</html>