<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="main-container">
    <div class="container">
      <h2 class="mb-4">Posts del Blog</h2>
      <div class="text-center mb-4">
        <a href="create.php" class="btn btn-primary">Crear nuevo post</a>
        <a href="index.html" class="btn btn-secondary">Inicio</a>
      </div>
      <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
        while($row = $result->fetch_assoc()):
        ?>
          <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card post-card">
              <?php if ($row['image_path']): ?>
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                     class="post-image" 
                     alt="Imagen del post">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <p class="post-meta">Publicado: <?php echo $row['created_at']; ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</body>
</html>
