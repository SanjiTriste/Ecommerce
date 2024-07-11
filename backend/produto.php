<?php
include('backend/db.php');

if (isset($_GET['id'])) {
    $produtoId = intval($_GET['id']);
    $query = "SELECT id, nome, preco, imagem_url, descricao FROM produtos WHERE id = $produtoId";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $produto = mysqli_fetch_assoc($result);
        echo json_encode($produto);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}
?>
