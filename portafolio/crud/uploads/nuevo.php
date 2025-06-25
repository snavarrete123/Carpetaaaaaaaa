<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $enlace = $_POST['enlace'] ?? '';
    $imagen = '';
    if (!empty($_FILES['imagen']['name'])) {
        $img_name = basename($_FILES['imagen']['name']);
        $target = 'uploads/' . $img_name;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target)) {
            $imagen = $img_name;
        } else {
            $error = 'Error al subir la imagen';
        }
    }
    if (!$error) {
        $stmt = $conn->prepare('INSERT INTO proyectos (titulo, descripcion, imagen, enlace) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $titulo, $descripcion, $imagen, $enlace);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Proyecto</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, textarea, button { width: 100%; padding: 0.7em; margin: 0.5em 0; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Añadir Proyecto</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="url" name="enlace" placeholder="Enlace (opcional)">
        <input type="file" name="imagen" accept="image/*">
        <button type="submit">Guardar</button>
        <a href="index.php">Volver</a>
    </form>
</div>
</body>
</html> 