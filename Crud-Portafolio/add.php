<?php
$admin = 1;
include 'auth.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $url_github = $_POST['url_github'];
    $url_produccion = $_POST['url_produccion'];
    $imagen = '';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $imagen);
    }

    $stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titulo, $descripcion, $url_github, $url_produccion, $imagen);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Proyecto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Agregar Proyecto</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Título</label>
        <input type="text" name="titulo" required>
        <label>Descripción</label>
        <input type="text" name="descripcion" required>
        <label>URL GitHub</label>
        <input type="text" name="url_github">
        <label>URL Producción</label>
        <input type="text" name="url_produccion">
        <label>Imagen</label>
        <label class="custom-file-upload">
            <input type="file" name="imagen" accept="image/*">
            Seleccionar imagen
        </label>
        <div style="margin-top:16px;">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>