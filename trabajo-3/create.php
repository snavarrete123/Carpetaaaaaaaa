<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Crear Nuevo Post</h2>
  <form action="save_post.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Título</label>
      <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="content" class="form-label">Contenido</label>
      <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-3">
      <label for="image" class="form-label">Imagen (opcional)</label>
      <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.html" class="btn btn-secondary">Inicio</a>
  </form>
</div>
</body>
</html>
