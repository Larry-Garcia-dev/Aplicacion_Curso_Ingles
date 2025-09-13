<?php
// php/login_register/login.php (Versión corregida y completa)

session_start();
require_once '../../config/db.php';

// 1. Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es POST, redirigimos al inicio sin hacer nada.
    header('Location: ../../index.php');
    exit;
}

// 2. Recoger los datos del formulario de forma segura
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

// 3. Validar que los campos no estén vacíos
if (empty($phone) || empty($password)) {
    // Si faltan datos, redirigimos de vuelta al inicio con un mensaje de error.
    header('Location: ../../index.php?error=empty_fields');
    exit;
}

try {
    // 4. Conectar a la base de datos
    $pdo = conectarDB();

    // 5. Preparar y ejecutar la consulta para encontrar al usuario por su teléfono
    $sql = "SELECT id, name, password, rol FROM users WHERE phone_number = :phone";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    // 6. Verificar si el usuario existe y si la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // --- INICIO DE SESIÓN EXITOSO ---
        
        // Regenerar el ID de la sesión para mayor seguridad
        session_regenerate_id(true);

        // Guardar los datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['loggedin'] = true;

        // --- LÓGICA DE REDIRECCIÓN BASADA EN EL ROL (NUMÉRICO) ---
        // Asumimos que el rol 1 es para el administrador
        if ($user['rol'] === 1) {
            // Si es admin, lo mandamos al panel de administración
            header('Location: ../admin/index.php');
        } else {
            // Para cualquier otro rol, lo mandamos a la página principal del curso
            header('Location: ../../curso/index.php');
        }
        exit; // Es crucial salir del script después de una redirección

    } else {
        // --- FALLO EN EL INICIO DE SESIÓN ---
        // Contraseña o usuario incorrecto, redirigimos con un error
        header('Location: ../../index.php?error=invalid_credentials');
        exit;
    }

} catch (PDOException $e) {
    // Si hay un error con la base de datos, lo registramos para depuración
    error_log('Error en login: ' . $e->getMessage());
    // Redirigimos al usuario con un error genérico para no exponer detalles
    header('Location: ../../index.php?error=server_error');
    exit;
}
?>