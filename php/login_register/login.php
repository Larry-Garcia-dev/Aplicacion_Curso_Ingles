<?php
// php/login_register/login.php (Corrección final de tipo de dato)

session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    header('Location: ../../index.php?error=empty_fields');
    exit;
}

try {
    $pdo = conectarDB();

    $sql = "SELECT id, name, password, rol FROM users WHERE phone_number = :phone";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['loggedin'] = true;

        // --- LÓGICA DE REDIRECCIÓN CORREGIDA ---
        // Convertimos el 'rol' a un entero (int) antes de la comparación estricta.
        if ((int)$user['rol'] === 1) {
            // Si es admin, lo mandamos al panel de administración
            header('Location: ../admin/index.php');
        } else {
            // Para cualquier otro rol, lo mandamos a la página principal del curso
            header('Location: ../../curso/index.php');
        }
        exit;

    } else {
        header('Location: ../../index.php?error=invalid_credentials');
        exit;
    }

} catch (PDOException $e) {
    error_log('Error en login: ' . $e->getMessage());
    header('Location: ../../index.php?error=server_error');
    exit;
}
?>