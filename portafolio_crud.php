<?php
// Conexión a la base de datos (ajusta los datos si es necesario)
$host = "localhost";
$db = "portafolio_db";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// --- AGREGAR PROYECTO ---
if (isset($_POST['agregar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $url_github = $_POST['url_github'];
    $url_produccion = $_POST['url_produccion'];
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "Crud/uploads/" . $imagen);
    }
    $stmt = $conn->prepare("INSERT INTO proyectos (titulo, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titulo, $descripcion, $url_github, $url_produccion, $imagen);
    $stmt->execute();
    header("Location: portafolio_crud.php");
    exit;
}

// --- ELIMINAR PROYECTO ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM proyectos WHERE id=$id");
    header("Location: portafolio_crud.php");
    exit;
}

// --- EDITAR PROYECTO ---
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $url_github = $_POST['url_github'];
    $url_produccion = $_POST['url_produccion'];
    $img_sql = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "Crud/uploads/" . $imagen);
        $img_sql = ", imagen='$imagen'";
    }
    $sql = "UPDATE proyectos SET titulo='$titulo', descripcion='$descripcion', url_github='$url_github', url_produccion='$url_produccion' $img_sql WHERE id=$id";
    $conn->query($sql);
    header("Location: portafolio_crud.php");
    exit;
}

// --- OBTENER PROYECTOS ---
$proyectos = $conn->query("SELECT * FROM proyectos ORDER BY created_at DESC");

// --- SI SE VA A EDITAR ---
$proyecto_editar = null;
if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM proyectos WHERE id=$id");
    if ($res->num_rows > 0) {
        $proyecto_editar = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portafolio CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 32px 24px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        h2 { text-align: center; color: #333; }
        .galeria { display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; }
        .card { background: #fafafa; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 18px; width: 260px; text-align: center; position: relative; }
        .card img { max-width: 100%; max-height: 140px; border-radius: 8px; margin-bottom: 10px; }
        .card h3 { margin: 10px 0 6px 0; }
        .card p { font-size: 0.97em; color: #444; }
        .card .links { margin: 10px 0; }
        .card .links a { margin: 0 6px; text-decoration: none; color: #0077b6; font-weight: bold; }
        .card .acciones { margin-top: 10px; }
        .card .acciones a, .card .acciones form { display: inline-block; }
        .card .acciones button, .card .acciones a { background: #ff5858; color: #fff; border: none; border-radius: 6px; padding: 6px 12px; margin: 0 2px; cursor: pointer; text-decoration: none; font-size: 0.95em; }
        .card .acciones a.editar { background: #43e97b; color: #fff; }
        form { margin: 30px 0; background: #f7fafc; padding: 18px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        form label { display: block; margin-bottom: 6px; color: #444; font-weight: 500; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #ccc; }
        form button { background: #0077b6; color: #fff; border: none; border-radius: 6px; padding: 10px 18px; font-size: 1em; cursor: pointer; }
        form button:hover { background: #005f87; }
    </style>
</head>
<body>
<div class="container">
    <h2>Portafolio de Proyectos (CRUD en una sola página)</h2>
    <!-- Formulario de agregar o editar -->
    <form method="post" enctype="multipart/form-data">
        <?php if ($proyecto_editar): ?>
            <input type="hidden" name="id" value="<?= $proyecto_editar['id'] ?>">
            <label>Título</label>
            <input type="text" name="titulo" value="<?= htmlspecialchars($proyecto_editar['titulo']) ?>" required>
            <label>Descripción</label>
            <textarea name="descripcion" required><?= htmlspecialchars($proyecto_editar['descripcion']) ?></textarea>
            <label>URL GitHub</label>
            <input type="text" name="url_github" value="<?= htmlspecialchars($proyecto_editar['url_github']) ?>">
            <label>URL Demo</label>
            <input type="text" name="url_produccion" value="<?= htmlspecialchars($proyecto_editar['url_produccion']) ?>">
            <label>Imagen (dejar vacío para no cambiar)</label>
            <input type="file" name="imagen" accept="image/*">
            <button type="submit" name="editar">Actualizar Proyecto</button>
            <a href="portafolio_crud.php">Cancelar</a>
        <?php else: ?>
            <label>Título</label>
            <input type="text" name="titulo" required>
            <label>Descripción</label>
            <textarea name="descripcion" required></textarea>
            <label>URL GitHub</label>
            <input type="text" name="url_github">
            <label>URL Demo</label>
            <input type="text" name="url_produccion">
            <label>Imagen</label>
            <input type="file" name="imagen" accept="image/*">
            <button type="submit" name="agregar">Agregar Proyecto</button>
        <?php endif; ?>
    </form>
    <!-- Galería de proyectos -->
    <div class="galeria">
        <?php while($row = $proyectos->fetch_assoc()): ?>
            <div class="card">
                <?php if (!empty($row['imagen'])): ?>
                    <img src="Crud/uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen del proyecto">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['titulo']) ?></h3>
                <p><?= htmlspecialchars($row['descripcion']) ?></p>
                <div class="links">
                    <?php if (!empty($row['url_produccion'])): ?>
                        <a href="<?= htmlspecialchars($row['url_produccion']) ?>" target="_blank">Ver Demo</a>
                    <?php endif; ?>
                    <?php if (!empty($row['url_github'])): ?>
                        <a href="<?= htmlspecialchars($row['url_github']) ?>" target="_blank">GitHub</a>
                    <?php endif; ?>
                </div>
                <div class="acciones">
                    <a href="?editar=<?= $row['id'] ?>" class="editar">Editar</a>
                    <a href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')">Eliminar</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html> 