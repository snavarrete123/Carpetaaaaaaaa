<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
if (isset(
  $_GET['admin']) && $_GET['admin'] == 1 && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
  header("Location: index.php");
  exit;
}
?>