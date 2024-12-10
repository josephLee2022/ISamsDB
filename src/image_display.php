<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT image FROM Blogs WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();

    header('Content-Type: image/jpeg'); // Change this if the image type is different
    echo $image;
}

$conn->close();
?>