// Aguarda o carregamento completo do DOM antes de executar o código
document.addEventListener("DOMContentLoaded", () => {
    // Funcionalidade será principalmente controlada pelo PHP
    // Este arquivo mantém apenas funções de UI do lado cliente
});

function filtrarClientes() {
    const termo = document.getElementById("searchClientes").value.toLowerCase();
    const tabela = document.getElementById("tabelaClientes");
    const linhas = tabela.getElementsByTagName("tr");

    // Itera sobre as linhas da tabela (começando de 1 para pular o cabeçalho)
    for (let i = 1; i < linhas.length; i++) {
        const celulas = linhas[i].getElementsByTagName("td");
        let encontrou = false;
        // Itera sobre as células da linha
        for (let j = 0; j < celulas.length; j++) {
            if (celulas[j]) {
                if (celulas[j].innerHTML.toLowerCase().indexOf(termo) > -1) {
                    encontrou = true;
                    break;
                }
            }
        }
        if (encontrou) {
            linhas[i].style.display = "";
        } else {
            linhas[i].style.display = "none";
        }
    }
}

function editarCliente(id) {
    // Redireciona para a página de edição com o ID do cliente
    window.location.href = `editarCliente.php?id=${id}`;
}

function excluirCliente(id, nome) {
    // Usa o nome do cliente na confirmação para mais segurança
    const confirmar = confirm(`Deseja realmente excluir o cliente "${nome}" (ID: ${id})? Esta ação não pode ser desfeita.`);
    if (confirmar) {
        window.location.href = `../PHP/logica_cliente.php?acao=excluir&id=${id}`;
    }
}
                if (celulas[j].innerHTML.toLowerCase().indexOf(termo) > -1) {
                    encontrou = true;
                    break;
                }
            }
        }
        if (encontrou) {
            linhas[i].style.display = "";
        } else {
            linhas[i].style.display = "none";
        }
    }
}

function editarCliente(id) {
    // Redireciona para a página de edição com o ID do cliente
    window.location.href = `editarCliente.php?id=${id}`;
}

function excluirCliente(id, nome) {
    // Usa o nome do cliente na confirmação para mais segurança
    const confirmar = confirm(`Deseja realmente excluir o cliente "${nome}" (ID: ${id})? Esta ação não pode ser desfeita.`);
    if (confirmar) {
        window.location.href = `../PHP/logica_cliente.php?acao=excluir&id=${id}`;
    }
}
    }
}
function excluirCliente(id, nome) {
    // Usa o nome do cliente na confirmação para mais segurança
    const confirmar = confirm(`Deseja realmente excluir o cliente "${nome}" (ID: ${id})? Esta ação não pode ser desfeita.`);
    if (confirmar) {
        window.location.href = `../PHP/logica_cliente.php?acao=excluir&id=${id}`;
    }
}

