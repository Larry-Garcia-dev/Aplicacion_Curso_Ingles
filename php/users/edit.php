<?php
require_once '../../config/db.php';
$pdo = conectarDB();

// Validar que se recibió un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Buscar el usuario en la DB
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// Si no se encuentra el usuario, redirigir
if (!$user) {
    header("Location: index.php?status=notfound");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Editar Usuario: <?= htmlspecialchars($user['name']) ?></h2>
                <hr>

                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text">Dejar en blanco para no cambiar la contraseña actual.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <input type="number" class="form-control" id="rol" name="rol" value="<?= $user['rol'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="current_level_id" class="form-label">Nivel Actual ID</label>
                            <input type="number" class="form-control" id="current_level_id" name="current_level_id" value="<?= $user['current_level_id'] ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="is_paused" class="form-label">Estado</label>
                        <select class="form-select" id="is_paused" name="is_paused">
                            <option value="0" <?= ($user['is_paused'] == 0) ? 'selected' : '' ?>>Activo</option>
                            <option value="1" <?= ($user['is_paused'] == 1) ? 'selected' : '' ?>>Pausado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="code" class="form-label">Código (opcional)</label>
                        <input type="number" class="form-control" id="code" name="code" value="<?= $user['code'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="pregunta" class="form-label">Pregunta (opcional)</label>
                        <input type="text" class="form-control" id="pregunta" name="pregunta" value="<?= htmlspecialchars($user['pregunta'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="quiz" class="form-label">Quiz ID (opcional)</label>
                        <input type="number" class="form-control" id="quiz" name="quiz" value="<?= $user['quiz'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="audio" class="form-label">Audio (opcional)</label>
                        <input type="text" class="form-control" id="audio" name="audio" value="<?= htmlspecialchars($user['audio'] ?? '') ?>">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>