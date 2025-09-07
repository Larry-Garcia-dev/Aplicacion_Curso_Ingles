<?php
// admin/index.php (versión con PDO)

require_once '../../config/db.php';

// Obtenemos la conexión PDO
$pdo = conectarDB();

// La consulta SQL es la misma
$query = "SELECT id, title, level_order FROM levels ORDER BY level_order ASC";

// Ejecutamos la consulta con PDO
$stmt = $pdo->query($query);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Cursos</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>

<body>
    <header>
        <h1>Panel de Administración</h1>
        <p>Gestiona los niveles, lecciones y ejercicios del curso de inglés.</p>
    </header>

    <main>
        <h2>Niveles del Curso</h2>
        <a href="crear_nivel.php" class="button">Crear Nuevo Nivel</a>

        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Título</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iteramos sobre los resultados con el método fetch() de PDO
                while ($level = $stmt->fetch()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($level['level_order']); ?></td>
                        <td><?php echo htmlspecialchars($level['title']); ?></td>
                        <td>
                            <a href="editar_nivel.php?id=<?php echo $level['id']; ?>">Editar</a>

                            <form action="eliminar_nivel.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este nivel? Esta acción no se puede deshacer.');">
                                <input type="hidden" name="id" value="<?php echo $level['id']; ?>">
                                <button type="submit" style="background-color: #d9534f; color: white; border: none; padding: 8px 12px; cursor: pointer;">Eliminar</button>
                            </form>
                            <a href="lecciones.php?level_id=<?php echo $level['id']; ?>&level_title=<?php echo urlencode($level['title']); ?>" class="button" style="background-color: #5cb85c;">Ver Lecciones</a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </main>

    <?php $pdo = null; // En PDO, la conexión se cierra asignando null al objeto 
    ?>
</body>

</html>