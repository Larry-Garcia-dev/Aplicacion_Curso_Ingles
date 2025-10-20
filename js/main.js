// js/main.js (Versión mejorada y unificada)

document.addEventListener('DOMContentLoaded', function () {

    // --- CONFIGURACIÓN INICIAL ---
    const nextLessonButton = document.getElementById('next-lesson-btn');
    const prevLessonButton = document.getElementById('prev-lesson-btn');
    const loadingOverlay = document.getElementById('loading-overlay');
    const exercisesGrid = document.querySelector('.exercises-grid');

    // Conjunto para almacenar los IDs de los ejercicios completados correctamente
    const completedExercises = new Set();
    // Obtenemos el número total de ejercicios en la página
    const totalExercises = exercisesGrid ? exercisesGrid.children.length : 0;

    // Hacemos que la página siempre inicie desde arriba
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    window.scrollTo(0, 0);

    // --- LÓGICA DE EJERCICIOS ---

    /**
     * Revisa si todos los ejercicios han sido completados y activa el botón para avanzar.
     */
    function checkCompletion() {
        // Comprueba si el número de ejercicios únicos completados es igual al total
        if (nextLessonButton && completedExercises.size >= totalExercises && totalExercises > 0) {
            nextLessonButton.classList.remove('disabled');
            nextLessonButton.style.pointerEvents = 'auto';
            nextLessonButton.style.opacity = '1';
            nextLessonButton.textContent = '¡Lección Completada! Siguiente →';

            // Opcional: Muestra una alerta o un mensaje de felicitación
            // alert('¡Felicidades! Has completado todos los ejercicios. Ya puedes avanzar.');

            // Mueve la vista suavemente hacia el botón para que el usuario lo note
            nextLessonButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    /**
     * Procesa la verificación de la respuesta de un ejercicio.
     * @param {HTMLElement} verifyButton - El botón "Verificar" que fue presionado.
     */
    function verifyExercise(verifyButton) {
        const card = verifyButton.closest('.exercise-card');
        const correctAnswer = verifyButton.dataset.correctAnswer.toLowerCase().trim();
        const resultDiv = card.querySelector('.exercise-result');
        let userAnswer = '';

        const input = card.querySelector('.exercise-input');
        const selectedOption = card.querySelector('.option-btn.selected');

        if (input) {
            userAnswer = input.value.toLowerCase().trim();
        } else if (selectedOption) {
            userAnswer = selectedOption.textContent.toLowerCase().trim();
        } else {
            alert('Por favor, selecciona o escribe una respuesta antes de verificar.');
            return;
        }

        resultDiv.classList.remove('hidden');
        if (userAnswer === correctAnswer) {
            resultDiv.textContent = '¡Correcto! 🎉';
            resultDiv.className = 'exercise-result correct'; // Clase para estilo verde

            // Agrega el ID del ejercicio al conjunto de completados
            completedExercises.add(card.id);

            // Desactiva los botones y el input para evitar cambios
            verifyButton.disabled = true;
            if (input) input.disabled = true;
            card.querySelectorAll('.option-btn').forEach(btn => btn.disabled = true);


            // Revisa si ya se completaron todos los ejercicios
            checkCompletion();
        } else {
            resultDiv.textContent = 'Incorrecto. ¡Inténtalo de nuevo!';
            resultDiv.className = 'exercise-result incorrect'; // Clase para estilo rojo

            // Animación de vibración para la tarjeta incorrecta
            card.classList.add('shake-error');
            setTimeout(() => card.classList.remove('shake-error'), 500);
        }
    }

    // --- MANEJO DE EVENTOS ---

    // Listener central para todos los clics en la página
    document.body.addEventListener('click', function (event) {
        const target = event.target;

        // Clic en el botón de verificar ejercicio
        const verifyButton = target.closest('.verify-btn');
        if (verifyButton) {
            verifyExercise(verifyButton);
            return; // Detiene la ejecución para no interferir con otros listeners
        }

        // Clic en un botón de opción de ejercicio
        const optionButton = target.closest('.option-btn');
        if (optionButton) {
            // Permite que solo un botón por ejercicio esté seleccionado
            optionButton.parentElement.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('selected'));
            optionButton.classList.add('selected');
            return;
        }

        // Clic en el contenedor de video para reproducir
        const videoContainer = target.closest('.videoContainer[data-video-url]');
        if (videoContainer && !videoContainer.querySelector('iframe')) {
            playYouTubeVideo(videoContainer);
            return;
        }
    });

    // --- LÓGICA DE NAVEGACIÓN ENTRE NIVELES ---

    /**
     * Maneja la llamada a la API para cambiar de nivel (avanzar o retroceder).
     * @param {string} url - La URL del script PHP a llamar.
     * @param {string} errorMessage - Mensaje de error a mostrar si falla.
     */
    async function updateLevel(url, errorMessage) {
        if (loadingOverlay) loadingOverlay.style.display = 'flex';

        try {
            const response = await fetch(url, { method: 'POST' });
            const result = await response.json();

            // Esperamos 2 segundos para que la transición sea suave
            setTimeout(() => {
                if (result.success) {
                    location.reload(); // Recarga la página para mostrar el nuevo nivel
                } else {
                    if (loadingOverlay) loadingOverlay.style.display = 'none';
                    alert(result.message || errorMessage);
                    if (result.message === 'Ya estás en el primer nivel.') {
                        // No hace nada si ya está en el primer nivel.
                    } else {
                         location.reload();
                    }
                }
            }, 2000);

        } catch (error) {
            console.error('Error en la navegación de nivel:', error);
            setTimeout(() => {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                alert('Ocurrió un error de red. Por favor, inténtalo de nuevo.');
            }, 2000);
        }
    }

    // Evento para el botón "Siguiente Lección"
    if (nextLessonButton) {
        nextLessonButton.addEventListener('click', (event) => {
            event.preventDefault();
            // Evita el avance si el botón sigue deshabilitado
            if (nextLessonButton.classList.contains('disabled')) {
                alert('Debes completar correctamente todos los ejercicios para poder avanzar.');
                return;
            }
            updateLevel('../php/update_progress.php', 'No se pudo pasar al siguiente nivel.');
        });
    }

    // Evento para el botón "Lección Anterior"
    if (prevLessonButton) {
        prevLessonButton.addEventListener('click', (event) => {
            event.preventDefault();
            updateLevel('../php/go_back_progress.php', 'No se pudo retroceder al nivel anterior.');
        });
    }

});


// --- FUNCIONES AUXILIARES ---

/**
 * Extrae el ID de un video de YouTube desde su URL.
 * @param {string} url - La URL del video de YouTube.
 * @returns {string|null} - El ID del video o null si no se encuentra.
 */
function getYouTubeID(url) {
    if (!url) return null;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

/**
 * Carga y reproduce un video de YouTube en el contenedor especificado.
 * @param {HTMLElement} container - El div que contiene la miniatura del video.
 */
function playYouTubeVideo(container) {
    const videoUrl = container.dataset.videoUrl;
    const videoId = getYouTubeID(videoUrl);

    if (videoId) {
        const iframe = document.createElement('iframe');
        iframe.setAttribute('src', `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&mute=0`);
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        iframe.setAttribute('allowfullscreen', '');
        container.innerHTML = ''; // Limpia el contenedor (quita miniatura y botón play)
        container.appendChild(iframe);
    } else {
        alert('La URL del video no es válida o no es de YouTube.');
    }
}

/**
 * Lee un texto en voz alta usando la API de Síntesis de Voz del navegador.
 * @param {string} text - El texto a ser leído.
 */
function speakText(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel(); // Detiene cualquier locución anterior
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-US'; // Asegura que se use la voz en inglés
        utterance.rate = 0.4; // Reduce la velocidad para mayor claridad
        window.speechSynthesis.speak(utterance);
    } else {
        alert("Tu navegador no soporta la funcionalidad de audio.");
    }
}