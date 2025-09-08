<?php
// admin/editar_ejercicio.php
require_once 'auth.php';

require_once '../../config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo = conectarDB();
$sql = "SELECT * FROM exercises WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$exercise = $stmt->fetch();

if (!$exercise) {
    header('Location: index.php');
    exit;
}

// Decodificamos las opciones si existen
$options = $exercise['options'] ? json_decode($exercise['options'], true) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ejercicio</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        .campo-opciones { display: none; }
    </style>
</head>
<body>
    <header>
        <h1>Editar Ejercicio</h1>
        <p><a href="ejercicios.php?lesson_id=<?php echo $exercise['lesson_id']; ?>">← Volver</a></p>
    </header>

    <main>
        <form action="actualizar_ejercicio.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $exercise['id']; ?>">
            <input type="hidden" name="lesson_id" value="<?php echo $exercise['lesson_id']; ?>">

            <p>
                <label for="exercise_type">Tipo de Ejercicio</label>
                <select id="exercise_type" name="exercise_type" required onchange="toggleFields()">
                    <option value="choose_word" <?php echo $exercise['exercise_type'] === 'choose_word' ? 'selected' : ''; ?>>Escoger la palabra correcta</option>
                    <option value="complete_sentence" <?php echo $exercise['exercise_type'] === 'complete_sentence' ? 'selected' : ''; ?>>Completar la frase</option>
                    <option value="translate" <?php echo $exercise['exercise_type'] === 'translate' ? 'selected' : ''; ?>>Traducir</option>
                    <option value="listen_and_write" <?php echo $exercise['exercise_type'] === 'listen_and_write' ? 'selected' : ''; ?>>Escuchar y escribir</option>
                </select>
            </p>

            <p>
                <label for="question">Pregunta o Instrucción</label>
                <input type="text" id="question" name="question" required value="<?php echo htmlspecialchars($exercise['question']); ?>">
            </p>

            <div id="options-container" class="campo-opciones">
                <label>Opciones de Respuesta (la primera es la correcta)</label>
                <input type="text" name="options[]" placeholder="Opción 1 (Correcta)" value="<?php echo htmlspecialchars($options[0] ?? ''); ?>">
                <input type="text" name="options[]" placeholder="Opción 2" value="<?php echo htmlspecialchars($options[1] ?? ''); ?>">
                <input type="text" name="options[]" placeholder="Opción 3" value="<?php echo htmlspecialchars($options[2] ?? ''); ?>">
                <input type="text" name="options[]" placeholder="Opción 4" value="<?php echo htmlspecialchars($options[3] ?? ''); ?>">
            </div>

            <p>
                <label for="correct_answer">Respuesta Correcta</label>
                <input type="text" id="correct_answer" name="correct_answer" value="<?php echo htmlspecialchars($exercise['correct_answer']); ?>">
            </p>
            
            <button type="submit">Actualizar Ejercicio</button>
        </form>
    </main>

    <script>
        function toggleFields() {
            const exerciseType = document.getElementById('exercise_type').value;
            const optionsContainer = document.getElementById('options-container');
            const correctAnswerInput = document.getElementById('correct_answer');

            if (exerciseType === 'choose_word' || exerciseType === 'complete_sentence') {
                optionsContainer.style.display = 'block';
                correctAnswerInput.parentElement.style.display = 'none';
            } else {
                optionsContainer.style.display = 'none';
                correctAnswerInput.parentElement.style.display = 'block';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</body>
</html>