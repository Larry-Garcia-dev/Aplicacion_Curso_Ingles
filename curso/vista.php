<?php
// La variable $level_data ahora contiene toda la información del nivel actual del usuario.
include '../views/header.php';
?>

<div class="floating-bg">
    <div class="floating-shape shape-circle"></div>
    <div class="floating-shape shape-circle"></div>
    <div class="floating-shape shape-square"></div>
    <div class="floating-shape shape-pencil"></div>
    <div class="floating-shape shape-book"></div>
    <div class="floating-shape shape-apple"></div>
    <div class="floating-shape shape-square"></div>
    <div class="floating-shape shape-pencil"></div>
</div>

<header class="header">
    <nav class="nav container">
        <div>
            <a href="#"><img src="../img/logo.png" alt="Hello Casablanca English" class="logo"></a>
        </div>
        <div class="nav-links">
            <span class="welcome-message">¡Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="../php/logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span> Cerrar Sesión
            </a>
        </div>
    </nav>
</header>
<br>

<?php if (isset($level_data) && !empty($level_data)): // Verificamos que se cargó la información del nivel 
?>

    <section id="hero" class="hero">
        <div class="container hero-content-wrapper">
            <div class="hero-image-container">
                <img src="../img/banners.jpg" alt="Ilustración del curso" class="hero-main-image">
            </div>
            <div class="hero-text-content">
                <h1><?php echo htmlspecialchars($level_data['title']); ?></h1>
                <h3 class="subtitle"><?php echo htmlspecialchars($level_data['Traduccion'] ?? ''); ?></h3>
                <br>
                <p><?php echo htmlspecialchars($level_data['description']); ?></p>
                <div class="hero-buttons">
                    <a href="#videos" class="btn btn-primary btn-lg">Comenzar Lección</a>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Preparamos los datos de las lecciones del nivel actual
    $has_lessons = !empty($level_data['lessons']);
    $first_lesson = $has_lessons ? $level_data['lessons'][0] : null;
    ?>

    <section id="videos" class="section section-muted">
        <div class="container">
            <h2>Video de la Lección: <?php echo htmlspecialchars($level_data['title']); ?></h2>
            <?php if ($has_lessons): ?>
                <div class="video-carousel" data-level-id="<?php echo $level_data['id']; ?>">
                    <!-- <div class="video-controls">
                        <button class="btn btn-outline btn-icon prevVideo">‹</button>
                        <h3 class="video-title"><?php //echo htmlspecialchars($first_lesson['title']); ?></h3>
                        <button class="btn btn-outline btn-icon nextVideo">›</button>
                    </div> -->
                    <div class="video-thumbnail videoContainer" data-video-url="<?php echo htmlspecialchars($first_lesson['video_url']); ?>">
                        <img class="videoThumbnail" src="../img/videos.jpg" alt="Video thumbnail">
                        <button class="play-button">▶</button>
                    </div>
                </div>
            <?php else: ?>
                <p>Aún no hay videos para este nivel.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="exercises" class="section">
        <div class="container">
            <h2>Práctica y Ejercicios Interactivos</h2>
            <?php if ($first_lesson && !empty($first_lesson['exercises'])): ?>
                <div class="exercises-grid">
                    <?php foreach ($first_lesson['exercises'] as $exercise): ?>
                        <div class="exercise-card" id="ex-<?php echo $exercise['id']; ?>">
                            <h3 class="exercise-title"><?php echo htmlspecialchars($exercise['question']); ?></h3>

                            <?php if ($exercise['exercise_type'] === 'listen_and_write' || $exercise['exercise_type'] === 'choose_word'): ?>
                                <button class="audio-btn" onclick="speakText('<?php echo addslashes($exercise['correct_answer']); ?>')">
                                    <span>🔊</span> Escuchar
                                </button>
                            <?php endif; ?>

                            <?php if ($exercise['exercise_type'] === 'choose_word' || $exercise['exercise_type'] === 'complete_sentence'): ?>
                                <div class="exercise-options">
                                    <?php $options = json_decode($exercise['options'] ?? '[]', true) ?: [];
                                    shuffle($options); ?>
                                    <?php foreach ($options as $option): ?>
                                        <button class="option-btn"><?php echo htmlspecialchars($option); ?></button>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <input type="text" class="exercise-input" placeholder="Escribe tu respuesta...">
                            <?php endif; ?>

                            <button class="btn btn-primary verify-btn" style="width: 100%;" data-correct-answer="<?php echo htmlspecialchars($exercise['correct_answer']); ?>">
                                Verificar
                            </button>
                            <div class="exercise-result hidden"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aún no hay ejercicios disponibles para esta lección.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    $summary_points = !empty($level_data['summary_points']) ? json_decode($level_data['summary_points'], true) : [];
    if (!empty($summary_points)):
    ?>
        <section id="summary" class="section section-gradient">
            <div class="container">
                <h2>Recuerda lo Aprendido</h2>
                <div class="summary-grid">
                    <?php foreach ($summary_points as $point): ?>
                        <div class="summary-card">
                            <div class="emoji">🗣️</div>
                            <h3><?php echo htmlspecialchars($point['title'] ?? ''); ?></h3>
                            <p><?php echo htmlspecialchars($point['desc'] ?? ''); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="#" id="next-lesson-btn" class="btn btn-lg" style="background: white; color: var(--primary);">Siguiente lección</a>

            </div>
        </section>
    <?php endif; ?>

<?php else: ?>
    <div class="container" style="padding: 50px; text-align: center;">
        <h1>¡Felicidades!</h1>
        <p>Has completado todos los niveles disponibles en el curso.</p>
    </div>
<?php endif; ?>
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; color: white; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
    <div class="spinner" style="border: 4px solid rgba(255,255,255,.3); border-top-color: white; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite;"></div>
    <p style="margin-top: 20px; font-size: 1.5rem; font-weight: 500;">Actualizando nivel...</p>
</div>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>


<?php include '../views/footer.php'; ?>

<script>
    const levelData = <?php echo isset($level_data) ? json_encode($level_data) : 'null'; ?>;
</script>
<script src="../js/main.js"></script>