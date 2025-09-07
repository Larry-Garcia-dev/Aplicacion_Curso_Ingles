<?php 
// /curso/vista.php (La Vista)
$first_level = $course_data[0] ?? null;
include '../views/header.php'; 
?>

<div class="floating-bg">
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
</div>

<header class="header">
    <nav class="nav container">
        <div><a href="#"><img src="../img/logo.png" alt="Logo" class="logo"></a></div>
        <div class="nav-links">
            <a href="#hero">Inicio</a>
            <?php foreach($course_data as $level): ?>
                <a href="#level-<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['title']); ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>

<?php if($first_level): ?>
<section id="hero" class="hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($first_level['title']); ?></h1>
        <p><?php echo htmlspecialchars($first_level['description']); ?></p>
        <div class="hero-buttons">
            <a href="#level-<?php echo $first_level['id']; ?>" class="btn btn-primary btn-lg">Comenzar Ahora</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php foreach($course_data as $level): ?>
<section id="level-<?php echo $level['id']; ?>" class="section section-muted">
    <div class="container">
        <h2><?php echo htmlspecialchars($level['title']); ?></h2>

        <?php if(!empty($level['lessons'])): 
            $first_lesson = $level['lessons'][0];
        ?>
        <div class="video-carousel" data-level-id="<?php echo $level['id']; ?>">
             <div class="video-controls">
                <button class="btn btn-outline btn-icon prevVideo">‹</button>
                <h3 class="video-title"><?php echo htmlspecialchars($first_lesson['title']); ?></h3>
                <button class="btn btn-outline btn-icon nextVideo">›</button>
            </div>
            <div class="video-card">
                <div class="video-thumbnail videoContainer">
                    <img class="videoThumbnail" src="../img/videos.jpg" alt="Video thumbnail">
                    <button class="play-button">▶</button>
                </div>
            </div>
        </div>

        <h2 style="margin-top: 50px;">Práctica y Ejercicios</h2>
        <div class="exercises-grid">
            <?php foreach($first_lesson['exercises'] as $exercise): ?>
            <div class="exercise-card" id="ex-<?php echo $exercise['id']; ?>">
                <h3 class="exercise-title"><?php echo htmlspecialchars($exercise['question']); ?></h3>
                
                <?php if($exercise['exercise_type'] === 'listen_and_write'): ?>
                    <button class="audio-btn" onclick="speakText('<?php echo addslashes($exercise['correct_answer']); ?>')"><span>🔊</span> Escuchar</button>
                <?php endif; ?>
                
                <?php if ($exercise['exercise_type'] === 'choose_word' || $exercise['exercise_type'] === 'complete_sentence'): ?>
                    <div class="exercise-options">
                        <?php $options = json_decode($exercise['options'], true); shuffle($options); ?>
                        <?php foreach ($options as $option): ?>
                            <button class="option-btn"><?php echo htmlspecialchars($option); ?></button>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <input type="text" class="exercise-input" placeholder="Escribe tu respuesta...">
                <?php endif; ?>
                <button class="btn btn-primary verify-btn" style="width: 100%;" data-correct-answer="<?php echo htmlspecialchars($exercise['correct_answer']); ?>">Verificar</button>
                <div class="exercise-result hidden"></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>Aún no hay lecciones disponibles para este nivel.</p>
        <?php endif; ?>
    </div>
</section>

<?php 
$summary_points = $level['summary_points'] ? json_decode($level['summary_points'], true) : [];
if(!empty($summary_points)): 
?>
<section id="summary-<?php echo $level['id']; ?>" class="section section-gradient">
    <div class="container">
        <h2>Recuerda lo Aprendido en <?php echo htmlspecialchars($level['title']); ?></h2>
        <div class="summary-grid">
            <?php foreach($summary_points as $point): ?>
            <div class="summary-card">
                <div class="emoji">🗣️</div>
                <h3><?php echo htmlspecialchars($point['title']); ?></h3>
                <p><?php echo htmlspecialchars($point['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endforeach; ?> <?php include '../views/footer.php'; ?>
<script>
    const courseData = <?php echo json_encode($course_data); ?>;
</script>
<script src="../js/main.js"></script>