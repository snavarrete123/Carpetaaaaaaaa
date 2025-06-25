<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare('SELECT id, password, rol FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash, $rol);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $rol;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CRUD Portafolio</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .login-container { max-width: 350px; margin: 80px auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, button { width: 100%; padding: 0.7em; margin: 0.5em 0; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Iniciar sesión</h2>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required autofocus>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html> 