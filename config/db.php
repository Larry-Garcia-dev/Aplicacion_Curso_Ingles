<?php
// config/db.php
// Incluimos el cargador que acabamos de crear en el mismo directorio.
require_once __DIR__ . '/EnvLoader.php';

function conectarDB() {
    
    // El archivo .env debe estar un nivel atrás (en la raíz del proyecto)
    $env_path = __DIR__ . '/../.env';
    $env_loaded = loadEnv($env_path);

    if (!$env_loaded) {
        error_log('Error de configuración: No se encontró el archivo .env. Asegúrate de crearlo en la raíz.');
        exit('Hubo un error al cargar la configuración de la base de datos.');
    }

    // 1. Obtenemos las variables usando el array $_ENV.
    // Usamos operadores ternarios para valores por defecto o para asegurar que existen,
    // pero si el .env está bien configurado, siempre existirán.
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $db_name = $_ENV['DB_NAME'] ?? '';
    $username = $_ENV['DB_USER'] ?? '';
    $password = $_ENV['DB_PASS'] ?? '';

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
        error_log('PDO Connection Error: ' . $e->getMessage());
        exit('Hubo un error al conectar con la base de datos.');
    }
}
?>