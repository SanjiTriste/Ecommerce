<?php
// Inclui o arquivo de configuração do banco de dados
require_once 'backend/db.php';

// Inicia ou resume a sessão
session_start();

// Função para adicionar um item ao carrinho
function adicionarItem($idProduto, $quantidade = 1)
{
  // Valida o ID do produto
  if (!filter_var($idProduto, FILTER_VALIDATE_INT)) {
    return false;
  }

  // Verifica se o produto já está no carrinho
  if (isset($_SESSION['carrinho'][$idProduto])) {
    $_SESSION['carrinho'][$idProduto] += $quantidade;
  } else {
    $_SESSION['carrinho'][$idProduto] = $quantidade;
  }

  return true;
}

// Função para remover um item do carrinho
function removerItem($idProduto)
{
  // Valida o ID do produto
  if (!filter_var($idProduto, FILTER_VALIDATE_INT)) {
    return false;
  }

  // Remove o item do carrinho
  if (isset($_SESSION['carrinho'][$idProduto])) {
    unset($_SESSION['carrinho'][$idProduto]);
    return true;
  }

  return false;
}

// Função para limpar todo o carrinho
function limparCarrinho()
{
  unset($_SESSION['carrinho']);
}

// Processa as ações do usuário
if (isset($_GET['acao'])) {
  $acao = $_GET['acao'];

  switch ($acao) {
    case 'adicionar':
      $idProduto = $_GET['id'];
      $quantidade = isset($_GET['quantidade']) ? (int) $_GET['quantidade'] : 1;

      if (adicionarItem($idProduto, $quantidade)) {
        header('Location: carrinho.php');
        exit;
      } else {
        echo "Erro ao adicionar item ao carrinho.";
      }
      break;

    case 'remover':
      $idProduto = $_GET['id'];

      if (removerItem($idProduto)) {
        header('Location: carrinho.php');
        exit;
      } else {
        echo "Erro ao remover item do carrinho.";
      }
      break;

    case 'limpar':
      limparCarrinho();
      header('Location: carrinho.php');
      exit;
      break;

    default:
      echo "Ação inválida.";
  }
}

// Função para buscar informações de um produto
function buscarProduto($idProduto)
{
  // Valida o ID do produto
  if (!filter_var($idProduto, FILTER_VALIDATE_INT)) {
    return null;
  }

  global $con; // Assume que a variável $con está disponível no escopo global

  $query = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id = ?";
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "i", $idProduto);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id_produto, $nome, $preco, $imagem_url);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($id_produto) {
    return [
      'id' => $id_produto,
      'nome' => $nome,
      'preco' => $preco,
      'imagem_url' => $imagem_url
    ];
  } else {
    return null;
  }
}

// Carrega os produtos do carrinho
$produtosCarrinho = [];
if (isset($_SESSION['carrinho'])) {
  foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {
    $produto = buscarProduto($idProduto);
    if ($produto) {
      $produto['quantidade'] = $quantidade;
      $produtosCarrinho[] = $produto;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrinho de Compras</title>
  <link rel="stylesheet" href="css/main.css">
  <style>
  
    header {
      background-color: #FFD700;
      /* Dourado */
      color: #000;
      /* Preto */
      padding: 20px 0;
      text-align: center;
    }

    .nav-link {
      display: inline-block;
      color: white;
      text-decoration: none;
      margin-right: 20px;
    }

    .nav-link img {
      vertical-align: middle;
      margin-right: 5px;
      max-width: 20px;
      /* Tamanho máximo para os ícones */
    }

    .carrinho-container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .produto {
      display: flex;
      align-items: center;
      justify-content: space-between;
      /* Alinha os itens um ao lado do outro */
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }

    .produto img {
      max-width: 150px;
      /* Aumenta o tamanho da imagem */
      margin-right: 20px;
      /* Adiciona espaço entre a imagem e o texto */
    }

    .produto-info {
      display: flex;
      flex-direction: column;
      /* Coloca o nome e preço em uma coluna */
      margin-right: 20px;
    }

    .nome {
      font-weight: bold;
      color: #FFD700;
      /* Cor dourada */
      margin-bottom: 5px;
      /* Adiciona espaço abaixo do nome */
    }

    .preco {
      font-size: 1.2em;
      color: #C0C0C0;
      /* Cor prateada */
    }

    .acoes {
      display: flex;
      /* Alinha os botões um ao lado do outro */
      align-items: center;
    }

    .acoes button {
      padding: 8px 15px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 3px;
      transition: background-color 0.3s;
      margin-left: 10px;
      /* Espaçamento entre os botões */
    }

    .acoes button:hover {
      background-color: #555;
    }

    .limpar {
      text-align: center;
      margin-top: 20px;
    }

    .limpar button {
      padding: 10px 20px;
      background-color: #d9534f;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 3px;
      transition: background-color 0.3s;
    }

    .limpar button:hover {
      background-color: #c9302c;
    }
    footer {
            background-color: #C0C0C0
            color: #000;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
  </style>
  </style>
</head>

<body>
  <header>
    <h1>Carrinho de Compras</h1>
    <nav>
      <a class="nav-link" href="index.php"><img src="assets/images/icons/casa.png" alt=""> Início</a>
    </nav>
  </header>
  <main>
    <div class="carrinho-container">
      <h2>Seu Carrinho</h2>
      <?php
      // Verifica se há itens no carrinho
      if (!empty($_SESSION['carrinho'])) {
        // Itera sobre os itens no carrinho
        foreach ($produtosCarrinho as $index => $produto) {
          echo '<div class="produto">';
          echo '<img src="' . $produto['imagem_url'] . '" alt="' . $produto['nome'] . '">';
          echo '<div>';
          echo '<div class="nome">' . $produto['nome'] . '</div>';
          echo '<div class="preco">R$ ' . $produto['preco'] . '</div>';
          echo '</div>';
          echo '<div class="acoes">';
          echo '<button onclick="removerItem(' . $produto['id'] . ')">Remover</button>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo '<p>Carrinho vazio.</p>';
      }
      ?>
      <div class="limpar">
        <button onclick="limparCarrinho()">Limpar Carrinho</button>
      </div>
    </div>
    <footer>
      <p>&copy; 2024 Camisas Rj's. Todos os direitos reservados.</p>
    </footer>
  </main>
  <script>
    // Função para remover um item do carrinho
    function removerItem(id) {
      if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
        window.location.href = 'carrinho.php?acao=remover&id=' + id;
      }
    }

    // Função para limpar todo o carrinho
    function limparCarrinho() {
      if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
        window.location.href = 'carrinho.php?acao=limpar';
      }
    }
  </script>
</body>

</html>