<?php
// config/EnvLoader.php

function loadEnv($path) {
    if (!file_exists($path)) {
        // Si el archivo no existe, no hacemos nada
        return false;
    }

    // CORRECCIÓN: Usamos las constantes estándar FILE_SKIP_EMPTY_LINES y FILE_IGNORE_NEW_LINES.
    // Esto soluciona el error 'Undefined constant "FILE_IGNORE_EMPTY_LINES"'.
    $lines = file($path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
    
    foreach ($lines as $line) {

        // Ignoramos comentarios (líneas que empiezan con #)
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Separamos nombre y valor en el primer signo de igual
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Eliminar comillas simples o dobles
        $value = trim($value, '"\'');

        // Solo cargamos la variable si no ha sido definida ya por el servidor
        if (!array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    return true;
}
?>