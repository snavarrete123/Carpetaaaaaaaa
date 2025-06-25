<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$rol = $_SESSION['rol'];
$proyectos = $conn->query('SELECT * FROM proyectos ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Proyectos</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #ddd; padding: 0.7em; text-align: left; }
        th { background: #eee; }
        .acciones a { margin-right: 0.5em; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .logout { color: #c00; text-decoration: none; }
        .add-btn { background: #28a745; color: #fff; padding: 0.5em 1em; border: none; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h2>Proyectos</h2>
        <div>
            <span>Usuario: <?= htmlspecialchars($_SESSION['usuario']) ?> (<?= $rol ?>)</span>
            <a href="logout.php" class="logout">Cerrar sesión</a>
            <?php if ($rol === 'admin'): ?>
                <a href="registro.php" class="add-btn" style="background:#007bff;">Registrar usuario</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($rol === 'admin'): ?>
        <a href="nuevo.php" class="add-btn">Añadir Proyecto</a>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Enlace</th>
                <th>Imagen</th>
                <?php if ($rol === 'admin'): ?><th>Acciones</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php while ($p = $proyectos->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['titulo']) ?></td>
                <td><?= nl2br(htmlspecialchars($p['descripcion'])) ?></td>
                <td><?php if ($p['enlace']): ?><a href="<?= htmlspecialchars($p['enlace']) ?>" target="_blank">Ver</a><?php endif; ?></td>
                <td><?php if ($p['imagen']): ?><img src="uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="img" width="60"><?php endif; ?></td>
                <?php if ($rol === 'admin'): ?>
                <td class="acciones">
                    <a href="editar.php?id=<?= $p['id'] ?>">Editar</a>
                    <a href="borrar.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Seguro que deseas borrar este proyecto?')">Borrar</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html> 