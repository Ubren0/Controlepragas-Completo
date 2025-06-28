document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const idCliente = urlParams.get("id");

    if (idCliente) {
        // Aqui você deve implementar uma requisição para buscar os dados reais do cliente
        // Exemplo: fetch() para API ou preenchimento via PHP inserido na página
        // Por enquanto, os campos ficam vazios aguardando dados

        // Se for usar PHP, pode injetar os dados direto aqui, por exemplo:
        // document.getElementById("clienteId").value = <?= json_encode($cliente['ID_Cliente']) ?>;
        // document.getElementById("clienteNome").value = <?= json_encode($cliente['Nome']) ?>;
        // etc.

        // Por enquanto, vamos limpar os campos
        [
            "clienteId", "clienteNome", "clienteCnpjCpf", "clienteTipoContrato", "clienteDataInicio",
            "clienteRua", "clienteBairro", "clienteCidade", "clienteEstado", "clienteCep",
            "administradoraNome", "administradoraContato", "supervisorNome", "supervisorContato",
            "clienteFaturamento", "clienteFormaPagamento"
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = "";
            }
        });
    }
});

// Função para alternar entre as abas
function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
}

// Habilitar edição
function habilitarEdicao() {
    document.querySelectorAll("input, select").forEach(input => {
        if (input.id !== 'clienteId') {
            input.disabled = false;
        }
    });
    document.getElementById("btnSalvar").style.display = "inline-block";
    document.getElementById("btnCancelar").style.display = "inline-block";
}

// Cancelar edição e recarregar a página
function cancelarEdicao() {
    location.reload();
}
