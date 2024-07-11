<?php
include 'db.php';
session_start();

$endereco = $_POST['endereco'];
$usuario = $_SESSION['usuario'];

$sql = "INSERT INTO pedidos (usuario_id, endereco, data, status) VALUES ((SELECT id FROM usuarios WHERE email = '$usuario'), '$endereco', NOW(), 'Pendente')";
if ($conn->query($sql) === TRUE) {
    $pedido_id = $conn->insert_id;
    foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
        $sql = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade) VALUES ('$pedido_id', '$produto_id', '$quantidade')";
        $conn->query($sql);
    }
    unset($_SESSION['carrinho']);
    header("Location: pedido_confirmado.php");
} else {
    echo "Erro ao criar pedido: " . $conn->error;
}

