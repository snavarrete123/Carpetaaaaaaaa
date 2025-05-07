<?php
require 'db.php';

$title = $_POST['title'];
$content = $_POST['content'];
$image_path = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $filename;
    

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = $target_path;
    }
}

$stmt = $conn->prepare("INSERT INTO posts (title, content, image_path) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $image_path);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: posts.php");
exit();
?>
