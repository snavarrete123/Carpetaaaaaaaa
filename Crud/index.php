<?php
include 'auth.php';
include 'db.php';
$result = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi CRUD</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Proyectos</h2>
    <div style="margin-bottom:18px; overflow:auto;">
        <a href="add.php" class="btn btn-success">+ Agregar Proyecto</a>
        <a href="logout.php" class="btn btn-danger" style="float:right;">Cerrar sesión</a>
    </div>
    <?php while($row = $result->fetch_assoc()): ?>
      <div>
        <h3><?= $row['titulo'] ?></h3>
        <p><?= $row['descripcion'] ?></p>
        <img src="uploads/<?= $row['imagen'] ?>" width="150"><br>
        <a href="<?= $row['url_github'] ?>" class="btn btn-primary" target="_blank">GitHub</a>
        <a href="<?= $row['url_produccion'] ?>" class="btn btn-warning" target="_blank">Enlace</a>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-success">Editar</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</a>
      </div>
      <hr>
    <?php endwhile; ?>
</div>
</body>
</html>