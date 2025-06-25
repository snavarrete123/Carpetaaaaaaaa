<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare('SELECT * FROM proyectos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$proyecto = $res->fetch_assoc();
$stmt->close();
if (!$proyecto) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $enlace = $_POST['enlace'] ?? '';
    $imagen = $proyecto['imagen'];
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
        $stmt = $conn->prepare('UPDATE proyectos SET titulo=?, descripcion=?, imagen=?, enlace=? WHERE id=?');
        $stmt->bind_param('ssssi', $titulo, $descripcion, $imagen, $enlace, $id);
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
    <title>Editar Proyecto</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, textarea, button { width: 100%; padding: 0.7em; margin: 0.5em 0; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Proyecto</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="titulo" value="<?= htmlspecialchars($proyecto['titulo']) ?>" required>
        <textarea name="descripcion" required><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
        <input type="url" name="enlace" value="<?= htmlspecialchars($proyecto['enlace']) ?>">
        <input type="file" name="imagen" accept="image/*">
        <?php if ($proyecto['imagen']): ?><img src="uploads/<?= htmlspecialchars($proyecto['imagen']) ?>" width="80"><?php endif; ?>
        <button type="submit">Guardar Cambios</button>
        <a href="index.php">Volver</a>
    </form>
</div>
</body>
</html> 