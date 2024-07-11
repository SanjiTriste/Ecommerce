<?php
include 'db.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
    $_SESSION['usuario'] = $email;
    header('Location: ../index.php');
} else {
    echo "Email ou senha inválidos.";
}

$conn->close();
?>
