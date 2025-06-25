<?php
include 'auth.php?admin=1';
include 'db.php';
$id = $_GET['id'];

$conn->query("DELETE FROM proyectos WHERE id=$id");
header("Location: index.php");
?>
  