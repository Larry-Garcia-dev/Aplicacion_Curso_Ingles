<?php
// Asume que $course_data viene del controlador
// Tomamos el primer nivel para el hero
$first_level = $course_data[0] ?? null;
include '../views/header.php';
?>

<!-- Floating Background Elements -->
 <div class="floating-bg">
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
</div> 
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

<!-- Header -->

<header class="header">
    <nav class="nav container">
        <div>
            <a href="#"><img src="../img/logo.png" alt="Hello Casablanca English" class="logo"></a>
        </div>
        <div class="nav-links">
            <a href="#hero">Inicio</a>
            <?php if (!empty($course_data)): ?>
                <?php foreach ($course_data as $level): ?>
                    <a href="#level-<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['title']); ?></a>
                <?php endforeach; ?>
            <?php endif; ?>

            <a href="../php/logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span> Cerrar Sesión
            </a>
        </div>
    </nav>

</header>
<br>
<!-- Hero Section -->
<section id="hero" class="hero">
    <div class="container hero-content-wrapper">
        <div class="hero-image-container">
            <img src="../img/banners.jpg" alt="Ilustración del curso" class="hero-main-image">
        </div>

        <div class="hero-text-content">
            <?php if ($first_level): ?>
                <h1><?php echo htmlspecialchars($first_level['title']); ?></h1>
                <h3 class="subtitle"><?php echo htmlspecialchars($first_level['Traduccion']); ?></h3>
                <br>
                <p><?php echo htmlspecialchars($first_level['description']); ?></p>
            <?php else: ?>
                <h1>Próximamente</h1>
                <p>Estamos preparando el contenido del curso.</p>
            <?php endif; ?>

            <div class="hero-buttons">
                <?php if ($first_level): ?>
                    <a href="#level-<?php echo $first_level['id']; ?>" class="btn btn-primary btn-lg">Comenzar Ahora</a>
                <?php endif; ?>
                <!-- <a href="#exercises" class="btn btn-outline btn-lg">Descargar curso completo</a> -->
            </div>

            <!-- <div class="hero-badge animate-pulse-glow">
                <span>✓</span>
                <span>100% Gratis</span>
            </div> -->
        </div>
    </div>
</section>

<!-- Video Carousel Section (dinámico por nivel) -->
<section id="videos" class="section section-muted">
    <div class="container">
        <h2> Video <?php echo htmlspecialchars($first_level['title']); ?> </h2>

        <?php if (!empty($course_data)): ?>
            <?php foreach ($course_data as $level): ?>
                <?php
                $has_lessons = !empty($level['lessons']);
                $first_lesson = $has_lessons ? $level['lessons'][0] : null;
                ?>
                <div id="level-<?php echo $level['id']; ?>" class="video-carousel" data-level-id="<?php echo $level['id']; ?>" style="margin-top: 32px;">
                    <div class="video-controls">
                        <!-- <button class="btn btn-outline btn-icon prevVideo">‹</button> -->
                        <!-- <h3 class="video-title">
                            <?php //echo $first_lesson ? htmlspecialchars($first_lesson['title']) : 'Lección aún no disponible'; ?>
                        </h3> -->
                        <!-- <button class="btn btn-outline btn-icon nextVideo">›</button> -->
                    </div>

                    <div class="video-thumbnail videoContainer" data-video-url="<?php echo htmlspecialchars($first_lesson['video_url']); ?>">
                        <img class="videoThumbnail" src="../img/videos.jpg" alt="Video thumbnail">
                        <button class="play-button">▶</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay niveles disponibles aún.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Interactive Exercises Section (dinámico por nivel: primera lección) -->
<section id="exercises" class="section">
    <div class="container">
        <h2>Práctica y Ejercicios Interactivos</h2>
        <?php if (!empty($course_data)): ?>
            <?php foreach ($course_data as $level): ?>
                <?php
                $has_lessons = !empty($level['lessons']);
                $first_lesson = $has_lessons ? $level['lessons'][0] : null;
                ?>

                <div class="level-exercises" data-level-id="<?php echo $level['id']; ?>" style="margin-top: 32px;">
                    <!-- <h3><?php echo htmlspecialchars($level['title']); ?></h3> -->

                    <?php if ($first_lesson && !empty($first_lesson['exercises'])): ?>
                        <div class="exercises-grid">
                            <?php foreach ($first_lesson['exercises'] as $exercise): ?>
                                <div class="exercise-card" id="ex-<?php echo $exercise['id']; ?>">
                                    
                                    <h3 class="exercise-title">
                                        <?php echo htmlspecialchars($exercise['question']); ?>
                                    </h3>

                                    <?php if ($exercise['exercise_type'] === 'listen_and_write'): ?>
                                       
                                        <button class="audio-btn" onclick="speakText('<?php echo addslashes($exercise['correct_answer']); ?>')">
                                            <span>🔊</span> Escuchar
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($exercise['exercise_type'] === 'choose_word' || $exercise['exercise_type'] === 'complete_sentence'): ?>
                                        <div class="exercise-options">
                                            <?php
                                            $options = json_decode($exercise['options'] ?? '[]', true) ?: [];
                                            shuffle($options);
                                            foreach ($options as $option):
                                            ?>
                                                <button class="option-btn"><?php echo htmlspecialchars($option); ?></button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <input type="text" class="exercise-input" placeholder="Escribe tu respuesta...">
                                    <?php endif; ?>

                                    <button
                                        class="btn btn-primary verify-btn"
                                        style="width: 100%;"
                                        data-correct-answer="<?php echo htmlspecialchars($exercise['correct_answer']); ?>">
                                        Verificar
                                    </button>
                                    <div class="exercise-result hidden"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Aún no hay ejercicios disponibles para este nivel.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay niveles para mostrar ejercicios.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Summary Section (dinámico por nivel) -->
<section id="summary" class="section section-gradient">
    <div class="container">
        <h2>Recuerda lo Aprendido</h2>

        <?php if (!empty($course_data)): ?>
            <?php foreach ($course_data as $level): ?>
                <?php
                $summary_points = !empty($level['summary_points'])
                    ? (is_array($level['summary_points']) ? $level['summary_points'] : json_decode($level['summary_points'], true))
                    : [];
                ?>
                <?php if (!empty($summary_points)): ?>
                    <div class="level-summary" data-level-id="<?php echo $level['id']; ?>" style="margin-top: 32px;">
                        <!-- <h3><?php echo htmlspecialchars($level['title']); ?></h3> -->
                        <div class="summary-grid">
                            <?php foreach ($summary_points as $point): ?>
                                <div class="summary-card">
                                    <div class="emoji">🗣️</div>
                                    <h3><?php echo htmlspecialchars($point['title'] ?? ''); ?></h3>
                                    <p><?php echo htmlspecialchars($point['desc'] ?? ''); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="level-summary" data-level-id="<?php echo $level['id']; ?>" style="margin-top: 32px;">
                        <h3><?php echo htmlspecialchars($level['title']); ?></h3>
                        <p>Aún no hay resumen para este nivel.</p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay niveles para mostrar resumen.</p>
        <?php endif; ?>

        <a href="#hero" class="btn btn-lg" style="background: white; color: var(--primary);">Siguiente lección</a>
    </div>
</section>

<?php include '../views/footer.php'; ?>

<!-- Pasamos los datos al JS para el carrusel y ejercicios -->
<script>
    const courseData = <?php echo json_encode($course_data); ?>;
</script>
<script src="../js/main.js"></script>