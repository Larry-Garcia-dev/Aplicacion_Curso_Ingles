<?php include 'header.php'; ?>

<!-- Floating Background Elements -->
<div class="floating-bg">
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
</div>

<!-- Header -->
<header class="header">
    <nav class="nav container">
        <div>
        <a href=""><img src="../img/logo.png" alt="Hello Casablanca English" class="logo"></a>
            <!-- <img src="../img/logo.png" alt="Hello Casablanca English" class="logo"> -->
        </div>
        <div class="nav-links">
            <a href="#hero">Inicio</a>
            <a href="#videos">Videos</a>
            <a href="#exercises">Ejercicios</a>
            <a href="#summary">Resumen</a>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section id="hero" class="hero">
    <div class="container">
        <h1>Aprende Inglés Nivel A1</h1>
        <p>Descubre el inglés de manera divertida e interactiva con nuestros cursos diseñados especialmente para niños y jóvenes</p>

        <div class="hero-buttons">
            <a href="#videos" class="btn btn-primary btn-lg">Comenzar Ahora</a>
            <a href="#exercises" class="btn btn-outline btn-lg">Descargar curso completo</a>
        </div>

        <div class="hero-badge animate-pulse-glow">
            <span>✓</span>
            <span>100% Gratis</span>
        </div>

        <div class="hero-illustration">
            <div class="hero-circle">🎧</div>
            <div class="hero-emoji">🎵</div>
            <div class="hero-emoji">📚</div>
        </div>
    </div>
</section>

<!-- Video Carousel Section -->
<section id="videos" class="section section-muted">
    <div class="container">
        <h2>Lecciones en Video</h2>

        <div class="video-carousel">
            <div class="video-controls">
                <button class="btn btn-outline btn-icon" id="prevVideo">‹</button>
                <h3 class="video-title" id="videoTitle">Lección 1: Saludos Básicos</h3>
                <button class="btn btn-outline btn-icon" id="nextVideo">›</button>
            </div>

            <div class="video-card">
                <div class="video-thumbnail" id="videoContainer">
                    <img id="videoThumbnail" src="../img/videos.jpg" alt="Video thumbnail">
                    <button class="play-button" id="playButton">▶</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Exercises Section -->
<section id="exercises" class="section">
    <div class="container">
        <h2>Práctica y Ejercicios Interactivos</h2>

        <div class="exercises-grid">
            <!-- Exercise 1: Select the correct word -->
            <div class="exercise-card">
                <h3 class="exercise-title">Selecciona la palabra correcta</h3>
                <button class="audio-btn" id="audioBtn1">
                    <span>🔊</span>
                    <span id="audioText1">Escuchar palabra</span>
                </button>
                <div class="exercise-options grid-2" id="options1">
                    <button class="option-btn" data-answer="Apple">Apple</button>
                    <button class="option-btn" data-answer="Orange">Orange</button>
                    <button class="option-btn" data-answer="Banana">Banana</button>
                    <button class="option-btn" data-answer="Grape">Grape</button>
                </div>
                <button class="btn btn-primary" style="width: 100%;" onclick="checkAnswer('ex1', 'Apple')">Verificar Respuesta</button>
                <div class="exercise-result hidden" id="result1"></div>
            </div>

            <!-- Exercise 2: Complete the sentence -->
            <div class="exercise-card">
                <h3 class="exercise-title">Completa la frase</h3>
                <p class="exercise-question">I _____ a student.</p>
                <div class="exercise-options" id="options2">
                    <button class="option-btn" data-answer="am">am</button>
                    <button class="option-btn" data-answer="is">is</button>
                    <button class="option-btn" data-answer="are">are</button>
                    <button class="option-btn" data-answer="be">be</button>
                    <button class="option-btn" data-answer="was">was</button>
                </div>
                <button class="btn btn-primary" style="width: 100%;" onclick="checkAnswer('ex2', 'am')">Verificar Respuesta</button>
                <div class="exercise-result hidden" id="result2"></div>
            </div>

            <!-- Exercise 3: Translation -->
            <div class="exercise-card">
                <h3 class="exercise-title">Traducción</h3>
                <p class="exercise-question">Traduce al inglés: <strong>Hola</strong></p>
                <input type="text" class="exercise-input" id="input3" placeholder="Escribe tu respuesta aquí...">
                <button class="btn btn-primary" style="width: 100%;" onclick="checkAnswer('ex3', 'hello')">Verificar Respuesta</button>
                <div class="exercise-result hidden" id="result3"></div>
            </div>

            <!-- Exercise 4: Dictation -->
            <div class="exercise-card">
                <h3 class="exercise-title">Dictado de frase</h3>
                <button class="audio-btn" id="audioBtn4">
                    <span>🔊</span>
                    <span id="audioText4">Escuchar frase</span>
                </button>
                <input type="text" class="exercise-input" id="input4" placeholder="Escribe la frase que escuchaste...">
                <button class="btn btn-primary" style="width: 100%;" onclick="checkAnswer('ex4', 'I like apples')">Verificar Respuesta</button>
                <div class="exercise-result hidden" id="result4"></div>
            </div>
        </div>
    </div>
</section>

<!-- Summary Section -->
<section id="summary" class="section section-gradient">
    <div class="container">
        <h2>Recuerda lo Aprendido</h2>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="emoji">🗣️</div>
                <h3>Vocabulario Básico</h3>
                <p>Aprendiste palabras esenciales como saludos, números y colores</p>
            </div>

            <div class="summary-card">
                <div class="emoji">📝</div>
                <h3>Gramática Simple</h3>
                <p>Dominaste el uso del verbo "to be" y estructuras básicas</p>
            </div>

            <div class="summary-card">
                <div class="emoji">🎧</div>
                <h3>Comprensión Auditiva</h3>
                <p>Practicaste escuchar y entender frases simples en inglés</p>
            </div>
        </div>

        <a href="#hero" class="btn btn-lg" style="background: white; color: var(--primary);">Continuar con Nivel A2</a>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="../js/main.js" ></script>