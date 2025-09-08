<?php
// php/auth.php (Nuestro "Guardián de Sesión")

// 1. Iniciamos la sesión
// Es crucial para poder acceder a la variable $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Verificamos si el usuario está logueado
// Comprobamos si la variable 'loggedin' existe en la sesión y si es verdadera.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 3. Si no está logueado, lo redirigimos a la página de inicio
    // Usamos '../' para salir de la carpeta /php/ y llegar a la raíz.
    // El 'exit' es importante para detener la ejecución del script inmediatamente.
    header('Location: ../index.php?error=not_logged_in');
    exit;
}

// Si el script llega hasta aquí, significa que el usuario SÍ está logueado
// y se le permite ver el contenido de la página.
?>