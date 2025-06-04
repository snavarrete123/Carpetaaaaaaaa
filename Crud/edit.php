<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'];
$proyecto = $conn->query("SELECT * FROM proyectos WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $url_github = $_POST['url_github'];
  $url_produccion = $_POST['url_produccion'];

  if ($_FILES['imagen']['name']) {
    $imagen = $_FILES['imagen']['name'];
    move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/$imagen");
    $img_sql = ", imagen='$imagen'";
  } else {
    $img_sql = "";
  }

  $sql = "UPDATE proyectos SET titulo='$titulo', descripcion='$descripcion', url_github='$url_github', url_produccion='$url_produccion' $img_sql WHERE id=$id";
  $conn->query($sql);
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Editar Proyecto</h2>
    <?php if (!empty($proyecto['imagen'])): ?>
      <div class="img-preview">
        <img src="uploads/<?= htmlspecialchars($proyecto['imagen']) ?>" alt="Imagen actual del proyecto">
      </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <label for="titulo">Nombre del proyecto</label>
      <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($proyecto['titulo']) ?>" required placeholder="Título del proyecto">

      <label for="descripcion">Descripción</label>
      <textarea id="descripcion" name="descripcion" placeholder="Descripción"><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>

      <label for="url_github">URL de GitHub</label>
      <input type="url" id="url_github" name="url_github" value="<?= htmlspecialchars($proyecto['url_github']) ?>" placeholder="URL de GitHub">

      <label for="url_produccion">URL del Proyecto</label>
      <input type="url" id="url_produccion" name="url_produccion" value="<?= htmlspecialchars($proyecto['url_produccion']) ?>" placeholder="URL de Producción">

      <label for="imagen">Imagen del proyecto</label>
      <input type="file" id="imagen" name="imagen" accept="image/*">

      <button type="submit">Actualizar</button>
    </form>
    <a class="btn btn-secondary" href="index.php">&larr; Volver al listado</a>
  </div>
</body>
</html>