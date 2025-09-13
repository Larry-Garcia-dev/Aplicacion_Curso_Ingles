// js/main.js (Versión Unificada con Animaciones y Funcionalidad Dinámica)

// --- FUNCIONES DE AYUDA GLOBALES ---

/**
 * Extrae el ID de un video de YouTube desde su URL.
 */
function getYouTubeID(url) {
    if (!url) return null;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

/**
 * Lee texto en voz alta usando la API del navegador.
 */
function speakText(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'en-US';
        utterance.rate = 0.4;
        window.speechSynthesis.speak(utterance);
    } else {
        alert("Tu navegador no soporta la síntesis de voz.");
    }
}

/**
 * Actualiza la interfaz de un carrusel de video con los datos de una lección.
 */
function updateCarouselUI(carouselElement, lesson) {
    const videoTitle = carouselElement.querySelector('.video-title');
    const videoContainer = carouselElement.querySelector('.videoContainer');
    
    videoTitle.textContent = lesson.title;

    const thumbnail = document.createElement('img');
    thumbnail.className = 'videoThumbnail';
    const videoId = getYouTubeID(lesson.video_url || '');
    thumbnail.src = videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '../img/videos.jpg';
    thumbnail.alt = 'Video thumbnail';

    const playBtn = document.createElement('button');
    playBtn.className = 'play-button';
    playBtn.innerHTML = '▶';

    videoContainer.innerHTML = '';
    videoContainer.append(thumbnail, playBtn);
    videoContainer.dataset.videoUrl = lesson.video_url || '';
}


// --- INICIALIZACIÓN DE ANIMACIONES Y EFECTOS ---

/**
 * Activa animaciones en las secciones cuando aparecen en pantalla.
 */
function initializeIntersectionObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in'); // Usaremos una animación más sutil
            }
        });
    }, { threshold: 0.1 });

    const sections = document.querySelectorAll('.section, .hero');
    sections.forEach(section => observer.observe(section));
}

/**
 * Mueve las formas flotantes del fondo según la posición del mouse.
 */
function initializeMouseMovement() {
    document.addEventListener('mousemove', (e) => {
        const shapes = document.querySelectorAll('.floating-shape');
        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.005;
            const deltaX = (e.clientX - window.innerWidth / 2) * speed;
            const deltaY = (e.clientY - window.innerHeight / 2) * speed;
            shape.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
        });
    });
}

/**
 * Habilita el desplazamiento suave para los enlaces de anclaje (#).
 */
function initializeSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}


// --- LÓGICA PRINCIPAL DE LA APLICACIÓN ---

document.addEventListener('DOMContentLoaded', function () {
    // 1. Inicializar todos los efectos visuales y animaciones
    initializeIntersectionObserver();
    initializeMouseMovement();
    initializeSmoothScrolling();

    // 2. Configurar todos los eventos interactivos para el contenido dinámico
    document.addEventListener('click', function(event) {
        
        // Clic en el contenedor de video para reproducir
        const videoContainer = event.target.closest('.videoContainer');
        if (videoContainer) {
            const videoUrl = videoContainer.dataset.videoUrl;
            if (!videoUrl) {
                alert('No hay una URL de video definida para esta lección.');
                return;
            }
            const videoId = getYouTubeID(videoUrl);
            if (videoId) {
                const iframe = document.createElement('iframe');
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&mute=1`;
                iframe.frameBorder = '0';
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;
                videoContainer.innerHTML = '';
                videoContainer.appendChild(iframe);
            } else {
                alert('La URL del video no es válida o no es de YouTube.');
            }
        }
        
        // Clic en un botón de opción de ejercicio
        const optionButton = event.target.closest('.option-btn');
        if (optionButton) {
            optionButton.parentElement.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('selected'));
            optionButton.classList.add('selected');
        }

        // Clic en el botón de verificar ejercicio
        const verifyButton = event.target.closest('.verify-btn');
        if (verifyButton) {
            const card = verifyButton.closest('.exercise-card');
            const correctAnswer = verifyButton.dataset.correctAnswer.toLowerCase().trim();
            const resultDiv = card.querySelector('.exercise-result');
            let userAnswer = '';
            const input = card.querySelector('.exercise-input');
            const selectedOption = card.querySelector('.option-btn.selected');

            if (input) userAnswer = input.value.toLowerCase().trim();
            else if (selectedOption) userAnswer = selectedOption.textContent.toLowerCase().trim();
            else {
                alert('Por favor, selecciona una opción o escribe una respuesta.');
                return;
            }

            resultDiv.classList.remove('hidden');
            if (userAnswer === correctAnswer) {
                resultDiv.textContent = '¡Correcto! 🎉';
                resultDiv.style.color = 'green';
            } else {
                resultDiv.textContent = 'Intenta de nuevo.';
                resultDiv.style.color = 'red';
            }
        }

        // Clic en los botones de navegación del carrusel
        const prevBtn = event.target.closest('.prevVideo');
        const nextBtn = event.target.closest('.nextVideo');
        if (prevBtn || nextBtn) {
            const carousel = (prevBtn || nextBtn).closest('.video-carousel');
            const levelId = carousel.dataset.levelId;
            const levelData = courseData.find(level => level.id == levelId);
            if (!levelData || !levelData.lessons.length) return;

            const currentTitle = carousel.querySelector('.video-title').textContent;
            let currentLessonIndex = levelData.lessons.findIndex(l => l.title === currentTitle);
            if (currentLessonIndex === -1) currentLessonIndex = 0;

            if (nextBtn) currentLessonIndex = (currentLessonIndex + 1) % levelData.lessons.length;
            if (prevBtn) currentLessonIndex = (currentLessonIndex - 1 + levelData.lessons.length) % levelData.lessons.length;
            
            updateCarouselUI(carousel, levelData.lessons[currentLessonIndex]);
        }
    });
});

// ... (todo el código existente de main.js) ...

// --- LÓGICA PARA EL BOTÓN "SIGUIENTE LECCIÓN" ---
document.addEventListener('DOMContentLoaded', function () {
    // ... (resto del código dentro de DOMContentLoaded si existe) ...
    
    const nextLessonButton = document.getElementById('next-lesson-btn');
    const loadingOverlay = document.getElementById('loading-overlay');

    if (nextLessonButton) {
        nextLessonButton.addEventListener('click', async (event) => {
            // Prevenimos el comportamiento por defecto del enlace
            event.preventDefault(); 

            // 1. Mostramos la pantalla de carga
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }

            try {
                // 2. Llamamos al script PHP para actualizar el nivel
                const response = await fetch('../php/update_progress.php', {
                    method: 'POST'
                });

                const result = await response.json();

                // 3. Esperamos 3 segundos para que el usuario vea la animación
                setTimeout(() => {
                    if (result.success) {
                        // 4. Si todo fue bien, recargamos la página para mostrar el nuevo nivel
                        location.reload();
                    } else {
                        // Si hay un error (ej. ya no hay más niveles), lo mostramos
                        if (loadingOverlay) {
                            loadingOverlay.style.display = 'none';
                        }
                        alert(result.message || 'No se pudo pasar al siguiente nivel.');
                        // Si ya no hay más niveles, el usuario verá la página de felicitaciones al recargar.
                        location.reload(); 
                    }
                }, 3000); // 3000 milisegundos = 3 segundos

            } catch (error) {
                console.error('Error al actualizar el progreso:', error);
                // En caso de un error de red, ocultamos la carga y mostramos un mensaje
                 setTimeout(() => {
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                    alert('Ocurrió un error de red. Por favor, inténtalo de nuevo.');
                }, 3000);
            }
        });
    }
});