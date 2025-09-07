<?php

// Configuración de la base de datos
$host = 'localhost'; // O la dirección de tu servidor de base de datos
$db_name = 'nombre_de_tu_bd'; // Reemplaza con el nombre de tu base de datos
$username = 'usuario_de_tu_bd'; // Reemplaza con tu nombre de usuario de la BD
$password = 'tu_contraseña'; // Reemplaza con tu contraseña

try {
    // Cadena de conexión (DSN)
    $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
    
    // Opciones para la conexión
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de obtención por defecto
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Deshabilitar emulación de sentencias
    ];
    
    // Crear una nueva instancia de PDO
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Puedes descomentar la siguiente línea para probar si la conexión es exitosa
    // echo "Conexión a la base de datos exitosa.";

} catch (PDOException $e) {
    // Si hay un error, muestra el mensaje de error de manera segura
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>