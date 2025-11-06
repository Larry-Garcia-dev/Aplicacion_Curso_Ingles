<?php
// ----- CONFIGURACIÓN DE SEGURIDAD -----
// ¡IMPORTANTE! Cambia este token por uno tuyo, largo y seguro.
define('SECRET_TOKEN', '1234567890_secure_token_change_me_please_1234567890');

// ----- CONFIGURACIÓN DE LA BASE DE DATOS -----
define('DB_HOST', '47.87.10.226');
define('DB_PORT', '3306');
define('DB_NAME', 'hellrfnj_curso_ingles_db');
define('DB_USER', 'hellrfnj_HelloCasabianca');
define('DB_PASS', 'w0~$OADj6Ptb'); // La contraseña que proporcionaste

// --- Cabeceras ---
header('Content-Type: application/json');

/**
 * Función para enviar una respuesta JSON estandarizada y terminar el script.
 * @param int $statusCode - El código de estado HTTP (ej. 200, 400, 401).
 * @param string $status - 'success' o 'error'.
 * @param string $message - Mensaje descriptivo.
 * @param array|null $data - Datos adicionales (opcional).
 */
function send_response($statusCode, $status, $message, $data = null) {
    http_response_code($statusCode);
    $response = ['status' => $status, 'message' => $message];
    if ($data) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// --- 1. Verificación de Seguridad (Token) ---
$token = null;
$headers = getallheaders();

if (isset($headers['Authorization'])) {
    // Extraer el token del 'Bearer <token>'
    if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        $token = $matches[1];
    }
}

if (SECRET_TOKEN !== $token) {
    send_response(401, 'error', 'Acceso no autorizado. Token inválido o ausente.');
}

// --- 2. Verificación del Método y Datos de Entrada ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(405, 'error', 'Método no permitido. Solo se acepta POST.');
}

// Obtener el cuerpo de la solicitud (JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['id']) || !isset($input['texto'])) {
    send_response(400, 'error', 'Datos incompletos. Se requieren "id" y "texto".');
}

$idUser = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
$texto = filter_var($input['texto'], FILTER_SANITIZE_STRING);

// --- 3. Conexión a la Base de Datos (PDO) ---
$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=latin1";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // No mostrar detalles del error en producción
    send_response(500, 'error', 'Error interno del servidor (Conexión DB).');
}

// --- 4. Lógica de la API ---

// Nombres de las columnas (con los espacios)
$ask_columns = ['ask 1', 'ask 2', 'ask 3', 'ask 4', 'ask 5', 'ask 6'];

try {
    // Paso A: Buscar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT * FROM `Calificaciones` WHERE `idUser` = ?");
    $stmt->execute([$idUser]);
    $row = $stmt->fetch();

    if ($row) {
        // --- CASO 1: El usuario EXISTE ---
        $target_column = null;
        
        // Buscar la primera columna 'ask' que sea NULL
        foreach ($ask_columns as $column) {
            // Usamos $row[$column] porque PDO::FETCH_ASSOC nos da un array asociativo
            if ($row[$column] === null) { 
                $target_column = $column;
                break; // Encontramos el primer espacio vacío
            }
        }

        if ($target_column) {
            // Encontramos un espacio. Actualizamos (UPDATE).
            // ¡Importante! No se puede parametrizar un nombre de columna.
            // Esto es seguro SÓLO porque $target_column viene de nuestro array $ask_columns.
            $sql = "UPDATE `Calificaciones` SET `$target_column` = ? WHERE `idUser` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$texto, $idUser]);
            
            send_response(200, 'success', "Registro actualizado en '$target_column' para el usuario $idUser.");
        } else {
            // No hay espacios libres.
            send_response(409, 'error', "Todos los campos (ask 1-6) están llenos para el usuario $idUser.");
        }

    } else {
        // --- CASO 2: El usuario NO EXISTE ---
        // Insertamos un nuevo registro. El texto va en 'ask 1'.
        // Los backticks (`) son cruciales por los espacios en los nombres.
        $sql = "INSERT INTO `Calificaciones` (`idUser`, `ask 1`) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUser, $texto]);
        
        send_response(201, 'success', "Nuevo registro creado para el usuario $idUser en 'ask 1'.");
    }

} catch (PDOException $e) {
    // Capturar cualquier error de la consulta
    send_response(500, 'error', 'Error interno del servidor (Consulta DB).', ['detail' => $e->getMessage()]);
}

?>