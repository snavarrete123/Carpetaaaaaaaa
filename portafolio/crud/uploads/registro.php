<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';
$exito = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'viewer';
    if ($usuario && $password && in_array($rol, ['admin', 'viewer'])) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $usuario, $hash, $rol);
        if ($stmt->execute()) {
            $exito = 'Usuario registrado correctamente';
        } else {
            $error = 'Error: el usuario ya existe o datos inválidos';
        }
        $stmt->close();
    } else {
        $error = 'Completa todos los campos correctamente';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 400px; margin: 40px auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, select, button { width: 100%; padding: 0.7em; margin: 0.5em 0; }
        .error { color: red; }
        .exito { color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Registrar Usuario</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($exito): ?><div class="exito"><?= $exito ?></div><?php endif; ?>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="rol">
            <option value="viewer">Solo Visualizar</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit">Registrar</button>
        <a href="index.php">Volver</a>
    </form>
</div>
</body>
</html> 