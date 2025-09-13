<?php
// config/db.php

function conectarDB() {
    // Configuración de la base de datos
    $host = 'localhost';
    // ¡OJO! He notado un espacio al inicio del nombre de tu base de datos, lo he corregido.
    $db_name = 'hellrfnj_curso_ingles_db'; 
    $username = 'hellrfnj_HelloCasabianca';
    $password = 'w0~$OADj6Ptb';
    $charset = 'utf8mb4';

    try {
        // Cadena de conexión (DSN) y creación de la instancia de PDO
        $dsn = "mysql:host={$host};dbname={$db_name};charset={$charset}";
        $pdo = new PDO($dsn, $username, $password);
        
        // Configurar atributos de PDO para un manejo de errores robusto
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        return $pdo;

    } catch (PDOException $e) {
        // En un entorno real, no mostramos detalles sensibles.
        error_log('PDO Connection Error: ' . $e->getMessage());
        // Lanzamos la excepción para que el bloque de prueba la capture y la muestre.
        throw $e;
    }
}

// --- BLOQUE DE PRUEBA DE CONEXIÓN ---
// Este código solo se ejecuta cuando se accede a este archivo directamente.
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    
    echo "<h1>Probando Conexión a la Base de Datos...</h1>";

    try {
        // Intentamos conectar
        $pdo = conectarDB();
        
        // Si la línea anterior no lanza una excepción, la conexión es exitosa
        echo "<p style='color:green; font-weight:bold;'>¡Conexión exitosa!</p>";
        
        // Opcional: Mostrar información del servidor
        echo "<p>Versión del servidor MySQL: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "</p>";

    } catch (PDOException $e) {
        // Si la función conectarDB() lanza una excepción, la capturamos aquí
        echo "<h2 style='color:red;'>Error de Conexión:</h2>";
        echo "<p><strong>Código de error:</strong> " . $e->getCode() . "</p>";
        echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<hr>";
        echo "<p><strong>Posibles soluciones:</strong></p>";
        echo "<ul>";
        echo "<li>Verifica que el nombre de la base de datos (<strong>{$db_name}</strong>) sea correcto.</li>";
        echo "<li>Asegúrate de que el usuario (<strong>{$username}</strong>) y la contraseña son correctos.</li>";
        echo "<li>Confirma que el servidor de base de datos (<strong>{$host}</strong>) está en ejecución.</li>";
        echo "</ul>";
    } finally {
        // Cerramos la conexión
        $pdo = null;
    }
}
?>