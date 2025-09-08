<?php
// php/logout.php

// 1. Iniciar la sesión para poder acceder a ella.
session_start();

// 2. Vaciar todas las variables de sesión.
$_SESSION = array();

// 3. Destruir la sesión completamente.
session_destroy();

// 4. Redirigir al usuario a la página de inicio de sesión (index.php en la raíz).
header('Location: ../index.php?status=logout_success');
exit;
?>