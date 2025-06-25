<?php
include 'auth.php';
include 'db.php';
$es_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
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
        <?php if ($es_admin): ?>
        <a href="add.php" class="btn btn-success">+ Agregar Proyecto</a>
        <a href="logout.php" class="btn btn-danger" style="float:right;">Cerrar sesión</a>
        <?php endif; ?>
    </div>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="project-card" style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.07); margin-bottom:24px; padding:18px; max-width:400px;">
        <a href="<?= $row['url_produccion'] ?>" target="_blank">
          <img src="uploads/<?= $row['imagen'] ?>" alt="Imagen de <?= htmlspecialchars($row['titulo']) ?>" style="width:100%; max-width:320px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.07);">
        </a>
        <h3 style="margin:12px 0 8px 0;">
          <a href="<?= $row['url_produccion'] ?>" target="_blank" style="color:#512da8; text-decoration:none;">
            <?= htmlspecialchars($row['titulo']) ?>
          </a>
        </h3>
        <p style="color:#444; margin-bottom:10px;"> <?= htmlspecialchars($row['descripcion']) ?> </p>
        <div class="project-links" style="margin-bottom:8px;">
          <a href="<?= $row['url_produccion'] ?>" class="btn btn-warning" target="_blank" style="margin-right:8px;">Ver demo</a>
          <a href="<?= $row['url_github'] ?>" class="btn btn-primary" target="_blank">GitHub</a>
        </div>
        <?php if ($es_admin): ?>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-success">Editar</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</a>
        <?php endif; ?>
      </div>
      <hr>
    <?php endwhile; ?>
</div>
</body>
</html>