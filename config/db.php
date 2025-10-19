<?php
// config/db.php
echo "Cargando configuración de la base de datos...\n";
function conectarDB() {
    // Configuración de la base de datos
    $host = '47.87.10.226'; // IP pública de tu servidor
    $port = 3306; // puerto MySQL expuesto en Docker
    $db_name = 'hellrfnj_curso_ingles_db';
    $username = 'hellrfnj_HelloCasabianca';
    $password = 'w0~$OADj6Ptb';

    try {
        // Cadena de conexión (DSN) con puerto explícito
        $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);

        // Configurar atributos de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;

    } catch (PDOException $e) {
        // Registrar el error y mostrar un mensaje genérico
        error_log('PDO Connection Error: ' . $e->getMessage());
        exit('Hubo un error al conectar con la base de datos.');
    }
}
?>
