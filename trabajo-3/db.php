<?php
$conn = new mysqli('localhost', 'root', '', 'blog_Seba');
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
