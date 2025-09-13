<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello Casabianca</title>
    <link rel="stylesheet" href="css/styles.css">
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
                <span>Hola/Hello</span>
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
                <img src="img/logo_ft.png" alt="" style=" width: 150%; margin-left: -22%;"  >
                <!-- Tab Navigation -->
                <div class="auth-tabs">
                    <button class="tab-button active" data-tab="login">Inicio de Sesión</button>
                    <button class="tab-button" data-tab="registro">Registro</button>
                </div>

                <!-- Login Form -->
                <div class="form-container active" data-form="login">
                    <form class="auth-form" id="login-form" action="php/login_register/login.php" method="POST" >
                        <div class="form-group">
                            <label for="login-phone">Teléfono</label>
                            <input 
                                type="tel" 
                                id="login-phone" 
                                name="phone" 
                                placeholder="+1234567890"
                                required
                            >
                            <div class="error-message" id="login-phone-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="login-password">Contraseña</label>
                            <div class="password-input-container">
                                <input 
                                    type="password" 
                                    id="login-password" 
                                    name="password" 
                                    placeholder="Tu contraseña"
                                    required
                                >
                                <button type="button" class="toggle-password" data-target="login-password">
                                    <span class="toggle-text">Mostrar</span>
                                </button>
                            </div>
                            <div class="error-message" id="login-password-error"></div>
                        </div>

                        <button type="submit" class="submit-button">
                            <span class="button-text">Ingresar</span>
                            <div class="button-spinner" style="display: none;"></div>
                        </button>
                    </form>
                </div>

                <!-- Registration Form -->
                <div class="form-container" data-form="registro">
                    <form class="auth-form" id="registro-form"  action="php/login_register/register.php" method="POST" >
                        <div class="form-group">
                            <label for="registro-name">Nombre completo</label>
                            <input type="text" id="registro-name" name="name" placeholder="Tu nombre completo" required >
                            <div class="error-message" id="registro-name-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="registro-phone">Teléfono</label>
                            <input  type="tel"  id="registro-phone"  name="phone"  placeholder="+1234567890" required >
                            <div class="error-message" id="registro-phone-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="registro-password">Contraseña</label>
                            <div class="password-input-container">
                                <input 
                                    type="password" 
                                    id="registro-password" 
                                    name="password" 
                                    placeholder="Tu contraseña"
                                    required
                                >
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

    <script src="js/app.js"></script>
    <?php //include 'views/footer.php'; ?>
</body>
</html>
