<?php
include 'db.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if ($conn->query($sql) === TRUE) {
    header('Location: ../login.php');
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
