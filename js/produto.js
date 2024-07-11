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
document.addEventListener('DOMContentLoaded', function () {
    // Obtém o ID do produto da URL
    const urlParams = new URLSearchParams(window.location.search);
    const produtoId = urlParams.get('id');

    if (produtoId) {
        fetch(`backend/produto.php?id=${produtoId}`)
            .then(response => response.json())
            .then(data => {
                const produtoContainer = document.getElementById('detalhes-produto');
                
                if (data) {
                    const produtoHtml = `
                        <img src="${data.imagem_url}" alt="${data.nome}">
                        <h2>${data.nome}</h2>
                        <p>Preço: R$ ${data.preco}</p>
                        <p>${data.descricao}</p>
                    `;
                    produtoContainer.innerHTML = produtoHtml;
                } else {
                    produtoContainer.innerHTML = '<p>Produto não encontrado.</p>';
                }
            })
            .catch(error => {
                console.error('Erro ao buscar detalhes do produto:', error);
            });
    } else {
        document.getElementById('detalhes-produto').innerHTML = '<p>ID do produto não fornecido.</p>';
    }
});
