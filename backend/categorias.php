<?php
include 'db.php';

$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);

$categorias = array();
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}

echo json_encode($categorias);

$conn->close();
?>
