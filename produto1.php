<?php
// Inclui o arquivo de configuração do banco de dados
require_once 'backend/db.php';

// Função para buscar informações de um produto pelo ID
function buscarProduto($idProduto)
{
  global $con;

  // Valida o ID do produto
  if (!filter_var($idProduto, FILTER_VALIDATE_INT)) {
    return false;
  }

  try {
    $query = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nome, $preco, $imagem_url);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($id) {
      return [
        'id' => $id,
        'nome' => $nome,
        'preco' => $preco,
        'imagem_url' => $imagem_url
      ];
    } else {
      return null; // Produto não encontrado
    }
  } catch (Exception $e) {
    // Log de erros ou tratamento específico de exceções
    return null;
  }
}

// Função para adicionar um item ao carrinho
function adicionarAoCarrinho($idProduto)
{
  // Inicia ou resume a sessão
  session_start();

  // Busca informações do produto
  $produto = buscarProduto($idProduto);

  // Verifica se o produto foi encontrado
  if (!$produto) {
    return false; // Produto não encontrado
  }

  // Adiciona o produto ao carrinho
  if (!isset($_SESSION['carrinho'][$idProduto])) {
    $_SESSION['carrinho'][$idProduto] = [
      'id' => $produto['id'],
      'nome' => $produto['nome'],
      'preco' => $produto['preco'],
      'quantidade' => 1
    ];
  } else {
    $_SESSION['carrinho'][$idProduto]['quantidade']++;
  }

  return true;
}

// Processa a adição ao carrinho se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['adicionarAoCarrinho']) && isset($_POST['idProduto'])) {
    $idProduto = $_POST['idProduto'];

    if (adicionarAoCarrinho($idProduto)) {
      header('Location: carrinho.php');
      exit;
    } else {
      $erro = "Erro ao adicionar item ao carrinho: Produto não encontrado.";
    }
  }
}

// Verifica se foi fornecido um ID de produto válido na query string
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
  $idProduto = $_GET['id'];

  $produto = buscarProduto($idProduto);

  if (!$produto) {
    die("Produto não encontrado.");
  }
} else {
  die("ID de produto inválido.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes do Produto - <?php echo htmlspecialchars($produto['nome']); ?></title>
  <link rel="stylesheet" href="css/main.css">
  <style>
    <style>

    /* Estilos da barra de navegação */
    nav {
      padding: 0.1rem 0;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .navbar {
      display: flex;
      justify-content: flex-end;
      /* Alinha os itens à direita */
      background-color: #FFD700;
      /* Dourado */
      color: #c0c0c0;
      /* Prata para o texto */
      padding: 10px;
    }

    .navbar a {
      color: black;
      /* Prata */
      text-decoration: none;
      margin-left: 20px;
      /* Espaçamento entre os links */
    }

    .produto-container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      /* Centraliza o conteúdo */
    }

    .produto-img {
      max-width: 100%;
      height: auto;
      display: block;
      /* Para garantir que a imagem não tenha espaços extras */
      margin: 0 auto;
      /* Centraliza a imagem */
    }

    .produto-nome {
      font-size: 1.5em;
      font-weight: bold;
      margin: 10px 0;
    }

    .produto-preco {
      font-size: 1.2em;
      color: #333;
      margin-bottom: 20px;
    }

    .botao-adicionar {
      padding: 10px 20px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 3px;
      transition: background-color 0.3s;
    }

    .botao-adicionar:hover {
      background-color: #555;
    }

    .mensagem-erro {
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }

    footer {
      background-color: #C0C0C0 color: #000;
      text-align: center;
      padding: 10px 0;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="navbar">
    <a href="index.php">Página Inicial</a>
    <a href="carrinho.php">Carrinho</a>
  </div>
  <div class="produto-container">
    <img class="produto-img" src="<?php echo htmlspecialchars($produto['imagem_url']); ?>"
      alt="<?php echo htmlspecialchars($produto['nome']); ?>">
    <div class="produto-nome"><?php echo htmlspecialchars($produto['nome']); ?></div>
    <div class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $produto['id']); ?>">
      <input type="hidden" name="idProduto" value="<?php echo $produto['id']; ?>">
      <button type="submit" name="adicionarAoCarrinho" class="botao-adicionar">Adicionar ao Carrinho</button>
    </form>
    <?php if (isset($erro)): ?>
      <div class="mensagem-erro"><?php echo $erro; ?></div>
    <?php endif; ?>
  </div>
  <footer class="footer">
    <p>&copy; 2024 Camisas Rj's. Todos os direitos reservados.</p>
  </footer>
</body>

</html>