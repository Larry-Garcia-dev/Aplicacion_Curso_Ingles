<?php

// --- Datos de Conexión a la Base de Datos ---
// Es una buena práctica definir las credenciales en variables
// para que el código sea más legible y fácil de mantener.
$host = "47.87.10.226";
$usuario = "hellrfnj_HelloCasabianca";
$contrasena = 'w0~$OADj6Ptb';
$nombre_bd = "hellrfnj_curso_ingles_db";
$puerto = 3306;

// --- Intento de Conexión ---
// Se realiza la conexión a la base de datos usando las variables definidas.
// mysqli_connect(host, usuario, contraseña, nombre_bd, puerto)
$conexion = mysqli_connect($host, $usuario, $contrasena, $nombre_bd, $puerto);

// --- Verificación de la Conexión ---
// Se comprueba si la conexión fue exitosa.
if ($conexion) {
    // Si la conexión es exitosa, se muestra una alerta de JavaScript.
    echo '
    <script>
        alert("Conexión exitosa a la base de datos.");
    </script>
    ';
} else {
    // Si la conexión falla, se muestra un mensaje de error detallado en una alerta
    // y se detiene la ejecución del script.
    $error_msg = "Error de conexión: " . mysqli_connect_error();
    // Usamos json_encode para escapar el mensaje de error de forma segura para JavaScript.
    $escaped_error_msg = json_encode($error_msg);

    die("
    <script>
        alert($escaped_error_msg);
    </script>
    ");
}

?>

