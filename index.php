<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce de Camisas de Times</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .nav-link {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .nav-link img {
            width: 20px;
            height: auto;
            margin-right: 5px;
        }

        .produtos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .produto {
            border: 1px solid #FFD700;
            margin: 10px;
            padding: 10px;
            width: calc(25% - 20px);
            text-align: center;
            box-shadow: 0 2px 4px rgba(255, 215, 0, 0.5);
            transition: transform 0.2s;
            background-color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .produto:hover {
            transform: scale(1.05);
        }

        .produto img {
            width: 100%;
            height: auto;
            max-width: 100%;
            max-height: 150px;
            object-fit: contain;
        }

        .produto h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #FFD700;
        }

        .produto p {
            color: #C0C0C0;
            font-size: 16px;
            margin: 10px 0;
        }

        .produto .preco {
            margin-top: 5px;
            font-size: 1.2rem;
            color: #C0C0C0;
        }

        .produto .detalhes {
            margin-top: 5px;
            font-size: 1rem;
            color: #C0C0C0;
            text-align: center;
            display: block;
        }

        footer {
            background-color: #C0C0C0
            color: #000;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Camisas Rj's</h1>
        <nav>
            <ul class="nav justify-content-around">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><img src="assets/images/icons/casa.png" alt="">
                        <span>Início</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categorias.html"><img src="assets/images/icons/menu.png" alt="">
                        <span>Categorias</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.html"><img src="assets/images/icons/login.png" alt="">
                        <span>Login</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrinho.php"><img src="assets/images/icons/carrinho.png" alt="">
                        <span>Carrinho</span></a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Produtos em Destaque</h2>
        <div class="produtos-container">
            <!-- Listagem de produtos -->
            <?php
            include('backend/db.php');

            $query = "SELECT id, nome, preco, imagem_url FROM produtos";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="produto">';
                    echo '<img src="' . $row['imagem_url'] . '" alt="' . $row['nome'] . '">';
                    echo '<h3>' . $row['nome'] . '</h3>';
                    echo '<p class="preco">R$ ' . $row['preco'] . '</p>';
                    echo '<a class="detalhes" href="produto1.php?id=' . $row['id'] . '">Detalhes</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>
        </div>
    </main>
<footer class="footer">
        <p>&copy; 2024 Camisas Rj's. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
