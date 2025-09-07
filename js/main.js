
// Global state
let currentVideo = 0;
let selectedAnswers = {};
let userInputs = {};
let showResults = {};
let audioPlaying = null;

const videos = [
    {
        id: "dQw4w9WgXcQ",
        title: "Lección 1: Saludos Básicos",
        thumbnail: "/placeholder.svg?height=400&width=600"
    },
    {
        id: "dQw4w9WgXcQ",
        title: "Lección 2: Números del 1 al 10",
        thumbnail: "/placeholder.svg?height=400&width=600"
    },
    {
        id: "dQw4w9WgXcQ",
        title: "Lección 3: Colores en Inglés",
        thumbnail: "/placeholder.svg?height=400&width=600"
    }
];

// Initialize page
document.addEventListener('DOMContentLoaded', function () {
    initializeIntersectionObserver();
    initializeMouseMovement();
    initializeVideoCarousel();
    initializeExercises();
    initializeSmoothScrolling();
});

// Intersection Observer for scroll animations
function initializeIntersectionObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-lego-build');
            }
        });
    }, { threshold: 0.1 });

    const sections = document.querySelectorAll('.section, .hero');
    sections.forEach(section => observer.observe(section));
}

// Mouse movement effect for floating shapes
function initializeMouseMovement() {
    document.addEventListener('mousemove', (e) => {
        const shapes = document.querySelectorAll('.floating-shape');
        shapes.forEach((shape, index) => {
            const rect = shape.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const deltaX = (e.clientX - centerX) * 0.01;
            const deltaY = (e.clientY - centerY) * 0.01;
            shape.style.transform = `translate(${deltaX}px, ${deltaY}px) rotate(${index * 45}deg)`;
        });
    });
}

// Video carousel functionality
function initializeVideoCarousel() {
    const prevBtn = document.getElementById('prevVideo');
    const nextBtn = document.getElementById('nextVideo');
    const playBtn = document.getElementById('playButton');

    prevBtn.addEventListener('click', () => {
        if (currentVideo > 0) {
            currentVideo--;
            updateVideo();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentVideo < videos.length - 1) {
            currentVideo++;
            updateVideo();
        }
    });

    playBtn.addEventListener('click', () => {
        playVideo();
    });

    updateVideo();
}

function updateVideo() {
    const videoTitle = document.getElementById('videoTitle');
    const videoThumbnail = document.getElementById('videoThumbnail');
    const prevBtn = document.getElementById('prevVideo');
    const nextBtn = document.getElementById('nextVideo');

    videoTitle.textContent = videos[currentVideo].title;
    videoThumbnail.src = videos[currentVideo].thumbnail;

    prevBtn.disabled = currentVideo === 0;
    nextBtn.disabled = currentVideo === videos.length - 1;

    if (prevBtn.disabled) prevBtn.classList.add('disabled');
    else prevBtn.classList.remove('disabled');

    if (nextBtn.disabled) nextBtn.classList.add('disabled');
    else nextBtn.classList.remove('disabled');
}

function playVideo() {
    const videoContainer = document.getElementById('videoContainer');
    const iframe = document.createElement('iframe');
    iframe.className = 'video-iframe';
    iframe.src = `https://www.youtube.com/embed/${videos[currentVideo].id}?autoplay=1`;
    iframe.title = videos[currentVideo].title;
    iframe.frameBorder = '0';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;

    videoContainer.innerHTML = '';
    videoContainer.appendChild(iframe);
}

// Exercise functionality
function initializeExercises() {
    // Initialize option buttons
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const exerciseId = this.closest('.exercise-card').querySelector('.exercise-options').id.replace('options', 'ex');
            const answer = this.dataset.answer;

            // Remove selected class from siblings
            this.parentNode.querySelectorAll('.option-btn').forEach(sibling => {
                sibling.classList.remove('selected');
            });

            // Add selected class to clicked button
            this.classList.add('selected');

            selectedAnswers[exerciseId] = answer;
        });
    });

    // Initialize input fields
    document.getElementById('input3').addEventListener('input', function () {
        userInputs['ex3'] = this.value;
    });

    document.getElementById('input4').addEventListener('input', function () {
        userInputs['ex4'] = this.value;
    });

    // Initialize audio buttons
    document.getElementById('audioBtn1').addEventListener('click', () => playAudio('word1', 'audioBtn1', 'audioText1'));
    document.getElementById('audioBtn4').addEventListener('click', () => playAudio('sentence1', 'audioBtn4', 'audioText4'));
}

function playAudio(audioId, btnId, textId) {
    if (audioPlaying === audioId) return;

    audioPlaying = audioId;
    const btn = document.getElementById(btnId);
    const text = document.getElementById(textId);

    btn.disabled = true;
    btn.classList.add('disabled');
    text.textContent = 'Reproduciendo...';

    // Simulate audio playback
    setTimeout(() => {
        audioPlaying = null;
        btn.disabled = false;
        btn.classList.remove('disabled');
        text.textContent = audioId === 'word1' ? 'Escuchar palabra' : 'Escuchar frase';
    }, 2000);
}

function checkAnswer(exerciseId, correctAnswer) {
    const userAnswer = selectedAnswers[exerciseId] || userInputs[exerciseId];
    const resultDiv = document.getElementById('result' + exerciseId.slice(-1));
    const isCorrect = userAnswer && userAnswer.toLowerCase() === correctAnswer.toLowerCase();

    resultDiv.classList.remove('hidden');
    resultDiv.className = `exercise-result ${isCorrect ? 'correct' : 'incorrect'}`;
    resultDiv.textContent = isCorrect
        ? '¡Correcto! 🎉'
        : `Incorrecto. La respuesta correcta es: ${correctAnswer}`;

    showResults[exerciseId] = true;
}

// Smooth scrolling for navigation links
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