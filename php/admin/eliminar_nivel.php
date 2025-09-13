<?php
// admin/eliminar_nivel.php
require_once 'auth.php'; // Asegura que solo administradores puedan acceder
require_once '../../config/db.php';

// 1. Validar que se recibió un ID y es un número entero
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = $_GET['id'];

    try {
        $pdo = conectarDB();

        // 2. Preparar la consulta SQL para eliminar
        // La tabla 'levels' tiene una clave foránea con 'ON DELETE CASCADE'
        // por lo que al eliminar un nivel, sus puntos de resumen también se borrarán.
        $sql = "DELETE FROM levels WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // 3. Vincular el ID y ejecutar la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 4. Redirigir de vuelta al índice del admin
        header('Location: index.php?status=deleted');
        exit;

    } catch (PDOException $e) {
        // Manejo de errores
        error_log('PDO Error al eliminar nivel: ' . $e->getMessage());
        // Redirigir con un mensaje de error
        header('Location: index.php?status=error');
        exit;
    } finally {
        $pdo = null;
    }
} else {
    // Si no se proporciona un ID válido, redirigir
    header('Location: index.php?status=invalid_id');
    exit;
}