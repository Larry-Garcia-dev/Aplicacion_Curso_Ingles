<?php
// config/db.php

function conectarDB() {
    // Configuración de la base de datos
    $host = 'localhost';
    $db_name = ' hellrfnj_curso_ingles_db';
    $username = 'root';
    $password = '';

    try {
        // Cadena de conexión (DSN) y creación de la instancia de PDO
        $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password);
        
        // Configurar atributos de PDO para un manejo de errores robusto
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        return $pdo;

    } catch (PDOException $e) {
        // Si hay un error, no mostramos detalles sensibles en producción.
        // Lo ideal sería registrar el error en un archivo de logs.
        error_log('PDO Connection Error: ' . $e->getMessage());
        // Mostramos un mensaje genérico al usuario.
        exit('Hubo un error al conectar con la base de datos.');
    }
}
?>