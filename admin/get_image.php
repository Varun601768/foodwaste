<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'demo');
$result = $conn->query("SELECT image_path FROM background_images");

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_path'];
}

echo json_encode($images);
?>
