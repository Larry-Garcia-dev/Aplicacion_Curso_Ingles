<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello Casabianca</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/recovery.css">
    <!-- <link rel="stylesheet" href="../css/main.css"> -->
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

</head>

<body>
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container" aria-live="polite"></div>

    <main class="main-container">
        <!-- Hero Section (70%) -->
        <section class="hero">
            <!-- Live Preview Badge -->
            <div class="live-badge">
                <div class="live-dot"></div>
                <span>Hola/Hello V1</span>
            </div>

            <!-- Decorative Elements -->
            <div class="decorative-elements">
                <div class="blob blob-1"></div>
                <div class="blob blob-2"></div>
                <div class="ring ring-1"></div>
                <div class="ring ring-2"></div>
                <div class="square square-1"></div>
                <div class="square square-2"></div>
            </div>

            <!-- Hero Content -->
            <div class="hero-content">
                <h1 class="hero-title">Hello Casabianca</h1>
                <p class="hero-description">
                    Bienvenido a nuestra plataforma de aprendizaje de inglés.
                    Descubre una nueva forma de dominar el idioma con tecnología avanzada.
                </p>
            </div>
        </section>

        <!-- Auth Section (30%) -->
        <section class="auth">

            <div class="auth-container">
                <img src="img/logo_ft.png" alt="" style=" width: 150%; margin-left: -22%;">
                <!-- Tab Navigation -->
                <div class="auth-tabs">
                    <button class="tab-button active" data-tab="login">Inicio de Sesión</button>
                    <button class="tab-button" data-tab="registro">Registro</button>
                </div>

                <!-- Login Form -->
                <div class="form-container active" data-form="login">
                    <form class="auth-form" id="#" action="php/login_register/login.php" method="POST">
                        <!-- <div class="form-group">
                            <label for="login-phone">Teléfono</label>
                            <input type="tel" id="login-phone" name="phone" placeholder="+1234567890" required>
                            <div class="error-message" id="login-phone-error"></div>
                        </div> -->
                         <div class="form-group">
                            <label for="registro-phone">Teléfono</label>
                            <input type="tel" id="registro-phone" name="phone" value="57" required pattern="^57\d+$" title="El teléfono debe comenzar con 57 y solo puede contener números.">
                            <div class="error-message" id="registro-phone-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="login-password">Contraseña</label>
                            <div class="password-input-container">
                                <input type="password" id="login-password" name="password" placeholder="Tu contraseña" required>
                                <button type="button" class="toggle-password" data-target="login-password">
                                    <span class="toggle-text">Mostrar</span>
                                </button>
                            </div>
                            <div class="error-message" id="login-password-error"></div>
                        </div>
                        <div style="text-align: right; margin-top: -1rem; margin-bottom: 1.5rem;">
                            <a href="#" id="open-recovery-modal" style="font-size: 0.875rem; color: var(--primary); text-decoration: none;">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="submit-button">
                            <span class="button-text">Ingresar</span>
                            <div class="button-spinner" style="display: none;"></div>
                        </button>
                    </form>
                </div>

                <!-- Registration Form -->
                <div class="form-container" data-form="registro">
                    <form class="auth-form" id="registro-form" action="php/login_register/register.php" method="POST">
                        <div class="form-group">
                            <label for="registro-name">Nombre completo</label>
                            <input type="text" id="registro-name" name="name" placeholder="Tu nombre completo" required>
                            <div class="error-message" id="registro-name-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="registro-phone">Teléfono</label>
                            <input type="tel" id="registro-phone" name="phone" value="57" required pattern="^57\d+$" title="El teléfono debe comenzar con 57 y solo puede contener números.">
                            <div class="error-message" id="registro-phone-error"></div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const phoneInput = document.getElementById('registro-phone');

                                // Función para posicionar el cursor después del prefijo
                                const setCursorPosition = () => {
                                    // Mueve el cursor al final del prefijo si está al principio
                                    if (phoneInput.selectionStart < 2) {
                                        phoneInput.setSelectionRange(2, 2);
                                    }
                                };

                                // Evento al hacer clic o focus para mover el cursor
                                phoneInput.addEventListener('focus', setCursorPosition);
                                phoneInput.addEventListener('click', setCursorPosition);

                                // Evento al presionar una tecla
                                phoneInput.addEventListener('keydown', (e) => {
                                    // Si el cursor está al inicio y se presiona "Backspace" o "Delete", se previene la acción
                                    if ((e.key === 'Backspace' || e.key === 'Delete') && phoneInput.selectionStart <= 2) {
                                        e.preventDefault();
                                    }
                                });

                                // Evento de entrada para asegurar que el valor siempre comience con "57"
                                // Esto es útil si el usuario intenta pegar texto que no incluye el prefijo.
                                phoneInput.addEventListener('input', () => {
                                    if (!phoneInput.value.startsWith('57')) {
                                        phoneInput.value = '57';
                                    }
                                });
                            });
                        </script>

                        <div class="form-group">
                            <label for="registro-password">Contraseña</label>
                            <div class="password-input-container">
                                <input
                                    type="password"
                                    id="registro-password"
                                    name="password"
                                    placeholder="Tu contraseña"
                                    required>
                                <button type="button" class="toggle-password" data-target="registro-password">
                                    <span class="toggle-text">Mostrar</span>
                                </button>
                            </div>
                            <button type="button" class="suggest-password" data-target="registro-password">
                                Sugerir contraseña
                            </button>
                            <div class="error-message" id="registro-password-error"></div>
                        </div>

                        <!-- Password Validation Rules -->
                        <div class="password-rules" aria-live="polite">
                            <div class="rule" data-rule="length">
                                <span class="rule-icon">✗</span>
                                <span class="rule-text">≥8 caracteres</span>
                            </div>
                            <div class="rule" data-rule="number">
                                <span class="rule-icon">✗</span>
                                <span class="rule-text">≥1 número</span>
                            </div>
                            <div class="rule" data-rule="special">
                                <span class="rule-icon">✗</span>
                                <span class="rule-text">≥2 especiales</span>
                            </div>
                            <div class="rule" data-rule="uppercase">
                                <span class="rule-icon">✗</span>
                                <span class="rule-text">≥3 mayúsculas</span>
                            </div>
                        </div>

                        <button type="submit" class="submit-button">
                            <span class="button-text">Crear cuenta</span>
                            <div class="button-spinner" style="display: none;"></div>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <div id="recoveryModal" class="recovery-modal">
        <div class="recovery-modal-content">
            <span class="recovery-close-btn">&times;</span>

            <div id="recovery-step-1" class="recovery-step active">
                <h2>Recuperar Contraseña</h2>
                <form id="recovery-form-1" class="auth-form">
                    <div class="form-group">
                        <label for="recovery-phone">Tu número de teléfono</label>
                        <input type="tel" id="recovery-phone" name="phone" placeholder="+1234567890" required>
                        <div class="error-message" id="recovery-phone-error"></div>
                    </div>
                    <button type="submit" class="submit-button">
                        <span class="button-text">Enviar Código</span>
                        <div class="button-spinner" style="display: none;"></div>
                    </button>
                </form>
            </div>

            <div id="recovery-loading-step" class="recovery-step">
                <div class="loader-container">
                    <div class="loader"></div>
                    <p class="loader-text">Enviando código a tu WhatsApp...</p>
                </div>
            </div>

            <div id="recovery-step-2" class="recovery-step">
                <h2>Verificar y Restablecer</h2>
                <form id="recovery-form-2" class="auth-form">
                    <div class="form-group">
                        <label for="recovery-code">Código de Verificación</label>
                        <input type="text" id="recovery-code" name="code" placeholder="Código recibido" required>
                        <div class="error-message" id="recovery-code-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="recovery-password-1">Nueva Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="recovery-password-1" name="password" placeholder="Mínimo 8 caracteres" required>
                            <button type="button" class="toggle-password-recovery" data-target="recovery-password-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="recovery-password-1-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="recovery-password-2">Confirmar Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="recovery-password-2" name="password_confirm" placeholder="Repite la contraseña" required>
                            <button type="button" class="toggle-password-recovery" data-target="recovery-password-2">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="recovery-password-2-error"></div>
                    </div>
                    <button type="submit" class="submit-button">
                        <span class="button-text">Cambiar Contraseña</span>
                        <div class="button-spinner" style="display: none;"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/recovery.js"></script>

    <script src="js/app.js"></script>
    <?php include 'views/footer.php'; ?>
</body>

</html>