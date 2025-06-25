<?php
session_start();
include 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['observador'])) {
    // Modo observador
    $_SESSION['user'] = 'observador';
    $_SESSION['role'] = 'observador';
    header("Location: index.php");
    exit();
  }
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $username;
    $_SESSION['role'] = $user['role'];
    header("Location: index.php");
    exit();
  } else {
    $error = "Credenciales incorrectas.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" id="loginForm">
      <input type="text" name="username" placeholder="Usuario" id="usernameField" required><br>
      <input type="password" name="password" placeholder="Contraseña" id="passwordField" required><br>
      <button type="submit">Iniciar Sesión</button>
      <button type="submit" name="observador" value="1" style="margin-left:10px; background:#888; color:#fff;" onclick="return iniciarComoObservador(event)">Iniciar como Observador</button>
    </form>
    <script>
      function iniciarComoObservador(e) {
        document.getElementById('usernameField').removeAttribute('required');
        document.getElementById('passwordField').removeAttribute('required');
        return true;
      }
    </script>
  </div>
</body>
</html> 