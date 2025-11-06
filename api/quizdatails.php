<?php
// ----- CONFIGURACIÓN DE SEGURIDAD -----
// ¡IMPORTANTE! Cambia este token por uno tuyo, largo y seguro.
define('SECRET_TOKEN', 'eternal_secure_token_change_me_key_0987_just_change_it_please_0987');

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

// *** CAMBIO: Validar los nuevos campos de entrada ***
// Asumimos "pregunta" (corrigiendo el "pegunta" de tu ejemplo)
if (empty($input['id']) || !isset($input['pregunta']) || !isset($input['respuesta'])) {
    send_response(400, 'error', 'Datos incompletos. Se requieren "id", "pregunta" y "respuesta".');
}

$idUser = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
$pregunta = filter_var($input['pregunta'], FILTER_UNSAFE_RAW); 
$respuesta = filter_var($input['respuesta'], FILTER_UNSAFE_RAW);


// --- 3. Conexión a la Base de Datos (PDO) ---
// *** CAMBIO: Asegurar charset=latin1 como en tu CREATE TABLE ***
$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=latin1";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    send_response(500, 'error', 'Error interno del servidor (Conexión DB).');
}

// --- 4. Lógica de la API (Adaptada para la tabla Quiz) ---

// *** CAMBIO: Nombres de las columnas (pares) de la nueva tabla ***
$column_pairs = [
    ['p' => 'Pregunta1', 'r' => 'Respuesta1'],
    ['p' => 'Pregunta2', 'r' => 'Respuesta2'],
    ['p' => 'Pregunta3', 'r' => 'Respuesta3'],
    ['p' => 'Pregunta4', 'r' => 'Respuesta4'],
    ['p' => 'Pregunta5', 'r' => 'Respuesta5']
];

try {
    // Paso A: Buscar si el usuario ya existe en la tabla Quiz
    // *** CAMBIO: Usar la tabla 'Quiz' y la columna 'IdUser' ***
    $stmt = $pdo->prepare("SELECT * FROM Quiz WHERE IdUser = ?");
    $stmt->execute([$idUser]);
    $row = $stmt->fetch();

    if ($row) {
        // --- CASO 1: El usuario EXISTE ---
        $target_pregunta_col = null;
        $target_respuesta_col = null;
        
        // Buscar el primer par de columnas donde 'PreguntaX' sea NULL
        foreach ($column_pairs as $pair) {
            if ($row[$pair['p']] === null) { 
                $target_pregunta_col = $pair['p'];
                $target_respuesta_col = $pair['r'];
                break; // Encontramos el primer espacio vacío
            }
        }

        if ($target_pregunta_col) {
            // Encontramos un espacio. Actualizamos (UPDATE).
            // Esto es seguro SÓLO porque los nombres de columnas vienen de nuestro array.
            // *** CAMBIO: Actualizar AMBAS columnas (Pregunta y Respuesta) ***
            $sql = "UPDATE Quiz SET $target_pregunta_col = ?, $target_respuesta_col = ? WHERE IdUser = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$pregunta, $respuesta, $idUser]);
            
            send_response(200, 'success', "Registro actualizado en '$target_pregunta_col' y '$target_respuesta_col' para el usuario $idUser.");
        } else {
            // No hay espacios libres.
            send_response(409, 'error', "Todos los campos (Pregunta 1-5) están llenos para el usuario $idUser.");
        }

    } else {
        // --- CASO 2: El usuario NO EXISTE ---
        // Insertamos un nuevo registro. Los datos van en 'Pregunta1' y 'Respuesta1'.
        // *** CAMBIO: Insertar en IdUser, Pregunta1 y Respuesta1 ***
        $sql = "INSERT INTO Quiz (IdUser, Pregunta1, Respuesta1) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idUser, $pregunta, $respuesta]);
        
        send_response(201, 'success', "Nuevo registro creado para el usuario $idUser en 'Pregunta1' y 'Respuesta1'.");
    }

} catch (PDOException $e) {
    // Capturar cualquier error de la consulta
    send_response(500, 'error', 'Error interno del servidor (Consulta DB).', ['detail' => $e->getMessage()]);
}

?>