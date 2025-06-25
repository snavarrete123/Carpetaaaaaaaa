<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}
$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $conn->prepare('DELETE FROM proyectos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}
header('Location: index.php');
exit; 