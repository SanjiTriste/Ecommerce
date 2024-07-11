document.addEventListener('DOMContentLoaded', () => {
    const produtosDestaque = document.getElementById('produtos-destaque');

    // Carregar produtos em destaque
    fetch('backend/produto.php?destaque=true')
        .then(response => response.json())
        .then(data => {
            data.forEach(produto => {
                const div = document.createElement('div');
                div.classList.add('produto');
                div.innerHTML = `
                    <img src="${produto.imagem_url}" alt="${produto.nome}">
                    <h3>${produto.nome}</h3>
                    <p>R$ ${produto.preco}</p>
                    <a href="produto.html?id=${produto.id}">Detalhes</a>
                    <button onclick="adicionarAoCarrinho(${produto.id})">Adicionar ao Carrinho</button>
                `;
                produtosDestaque.appendChild(div);
            });
        });
});

function adicionarAoCarrinho(id) {
    // Adicionar ao carrinho com AJAX
    fetch('backend/add_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produto adicionado ao carrinho!');
        } else {
            alert('Falha ao adicionar produto ao carrinho.');
        }
    });
}
document.addEventListener('DOMContentLoaded', () => {
    const produtosDestaque = document.getElementById('produtos-destaque');

    fetch('backend/produto.php?destaque=true')
        .then(response => response.json())
        .then(data => {
            data.forEach(produto => {
                const div = document.createElement('div');
                div.classList.add('produto');
                div.innerHTML = `
                    <img src="${produto.imagem_url}" alt="${produto.nome}">
                    <h3>${produto.nome}</h3>
                    <p>R$ ${produto.preco}</p>
                    <a href="produto.html?id=${produto.id}">Detalhes</a>
                `;
                produtosDestaque.appendChild(div);
            });
        })
        .catch(error => console.error('Erro ao carregar produtos:', error));
});
