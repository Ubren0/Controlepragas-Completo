// Aguarda o carregamento completo do DOM antes de executar o código
document.addEventListener("DOMContentLoaded", () => {
    carregarClientes(); // Chama a função para carregar e exibir os clientes na tabela
});

let listaClientes = []; // Array simulado. Substitua futuramente por dados do backend.

function carregarClientes() {
    // Simulação de dados — troque por uma requisição à API se necessário.
    listaClientes = [
        { id: 1, nome: "Cliente A", telefone: "(27) 99999-0000", email: "a@email.com", endereco: "Rua A, 123" },
        { id: 2, nome: "Cliente B", telefone: "(27) 98888-1111", email: "b@email.com", endereco: "Rua B, 456" },
        { id: 3, nome: "Cliente C", telefone: "(27) 97777-2222", email: "c@email.com", endereco: "Rua C, 789" }
    ];

    preencherTabela(listaClientes);
}

function preencherTabela(clientes) {
    const tbody = document.querySelector("#tabelaClientes tbody");
    tbody.innerHTML = ""; // Limpa a tabela antes de preencher

    clientes.forEach(cliente => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${cliente.id}</td>
            <td>${cliente.nome}</td>
            <td>${cliente.telefone}</td>
            <td>${cliente.email}</td>
            <td>${cliente.endereco}</td>
            <td>
                <button onclick="editarCliente(${cliente.id})">✏️ Editar</button>
                <button onclick="excluirCliente(${cliente.id})">🗑️ Excluir</button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

function filtrarClientes() {
    const termo = document.getElementById("searchClientes").value.toLowerCase();
    const filtrados = listaClientes.filter(c =>
        c.nome.toLowerCase().includes(termo)
    );
    preencherTabela(filtrados);
}

function editarCliente(id) {
    // Redireciona para a página de edição com o ID do cliente
    window.location.href = `dashboardCliente.php?id=${id}`;
}

function excluirCliente(id) {
    const confirmar = confirm("Deseja excluir o cliente ID: " + id + "?");
    if (confirmar) {
        listaClientes = listaClientes.filter(c => c.id !== id);
        preencherTabela(listaClientes);
    }
}

