<?php
include 'db.php';
session_start();

$nome = $_POST['nome'];
$senha = $_POST['senha'];
$email = $_SESSION['usuario'];

$sql = "UPDATE usuarios SET nome = '$nome'";
if (!empty($senha)) {
    $sql .= ", senha = '$senha'";
}
$sql .= " WHERE email = '$email'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'error' => $conn->error));
}

$conn->close();
?>
