<?php
// admin/crear_ejercicio.php
$lesson_id = filter_input(INPUT_GET, 'lesson_id', FILTER_VALIDATE_INT);
if (!$lesson_id) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Ejercicio</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        .campo-opciones { display: none; }
    </style>
</head>
<body>
    <header>
        <h1>Crear Nuevo Ejercicio</h1>
        <p><a href="ejercicios.php?lesson_id=<?php echo $lesson_id; ?>">← Volver a ejercicios</a></p>
    </header>

    <main>
        <form action="guardar_ejercicio.php" method="POST">
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">

            <p>
                <label for="exercise_type">Tipo de Ejercicio</label>
                <select id="exercise_type" name="exercise_type" required onchange="toggleFields()">
                    <option value="">-- Selecciona un tipo --</option>
                    <option value="choose_word">Escoger la palabra correcta</option>
                    <option value="complete_sentence">Completar la frase</option>
                    <option value="translate">Traducir</option>
                    <option value="listen_and_write">Escuchar y escribir</option>
                </select>
            </p>

            <p>
                <label for="question">Pregunta o Instrucción</label>
                <input type="text" id="question" name="question" required placeholder="Ej: Escribe la frase que escuches">
            </p>

            <div id="options-container" class="campo-opciones">
                <label>Opciones de Respuesta</label>
                <input type="text" name="options[]" placeholder="Opción 1 (Respuesta Correcta)">
                <input type="text" name="options[]" placeholder="Opción 2">
                <input type="text" name="options[]" placeholder="Opción 3">
                <input type="text" name="options[]" placeholder="Opción 4">
            </div>

            <p>
                <label for="correct_answer">Respuesta Correcta</label>
                <input type="text" id="correct_answer" name="correct_answer" placeholder="Ej: The cat is on the table">
            </p>
            
            <button type="submit">Guardar Ejercicio</button>
        </form>
    </main>

    <script>
        function toggleFields() {
            const exerciseType = document.getElementById('exercise_type').value;
            const optionsContainer = document.getElementById('options-container');
            const correctAnswerInput = document.getElementById('correct_answer');
            const optionInputs = optionsContainer.querySelectorAll('input');

            if (exerciseType === 'choose_word' || exerciseType === 'complete_sentence') {
                optionsContainer.style.display = 'block';
                correctAnswerInput.parentElement.style.display = 'none';
                correctAnswerInput.required = false; // No es requerido
                optionInputs[0].required = true; // La primera opción es requerida
            } else {
                optionsContainer.style.display = 'none';
                correctAnswerInput.parentElement.style.display = 'block';
                correctAnswerInput.required = true; // Es requerido
                optionInputs[0].required = false; // La primera opción ya no es requerida
            }
        }
        
        // Llamamos a la función al cargar la página
        toggleFields();
    </script>
</body>
</html>