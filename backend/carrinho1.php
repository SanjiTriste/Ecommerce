<?php
session_start();

require 'produto.php';
// Inicializa o carrinho se não existir na sessão
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p>Carrinho vazio</p>";
} else {
    echo "<h2>Seu Carrinho de Compras</h2>";
    echo "<div class='produtos-carrinho'>";
    
    foreach ($_SESSION['carrinho'] as $produto_id => $produto) {
        echo "<div class='produto'>";
        echo "<img src='placeholder.jpg' alt='Imagem do Produto'>"; // Substitua pelo caminho correto da imagem
        echo "<h3>{$produto['nome']}</h3>";
        echo "<p>Preço: R$ {$produto['preco']}</p>";
        echo "<p>Quantidade: {$produto['quantidade']}</p>";
        echo "</div>";
    }

    echo "</div>";
}
?>
