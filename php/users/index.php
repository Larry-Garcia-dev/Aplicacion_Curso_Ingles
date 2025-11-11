<?php
// 1. Incluimos el archivo que define la función
require_once '../../config/db.php';

// 2. Ejecutamos la función para obtener la conexión
$pdo = conectarDB();

// A partir de aquí, todo el código funciona igual...
// Fetch all users
$stmt = $pdo->query("SELECT id, name, phone_number, rol, is_paused FROM users ORDER BY name ASC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Gestión de Usuarios</h1>
            <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Crear Usuario</a>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['phone_number']) ?></td>
                        <td><?= $user['rol'] ?></td>
                        <td>
                            <?php if ($user['is_paused']): ?>
                                <span class="badge bg-warning text-dark">Pausado</span>
                            <?php else: ?>
                                <span class="badge bg-success">Activo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>