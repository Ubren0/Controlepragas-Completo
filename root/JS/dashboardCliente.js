document.addEventListener("DOMContentLoaded", function () {
    // A lógica de preenchimento de formulário foi movida para o PHP (editarCliente.php)
    // para usar dados reais do banco de dados.
    // O código abaixo foi removido.
});

// Função para alternar entre as abas
function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
}

// Habilitar edição
function habilitarEdicao() {
    // Habilita os campos do formulário que podem ser editados
    document.getElementById('clienteNome').disabled = false;
    document.getElementById('clienteCnpjCpf').disabled = false;
    document.getElementById('clienteEmail').disabled = false;
    document.getElementById('clienteTelefone').disabled = false;
    document.getElementById('tipoCliente').disabled = false;
    // Adicione outros campos que devem ser editáveis aqui, se existirem no BD

    // Mostra o botão Salvar e Cancelar, e esconde o Editar
    document.getElementById('btnSalvar').style.display = 'inline-block';
    document.getElementById('btnCancelar').style.display = 'inline-block';
    document.querySelector('button[onclick="habilitarEdicao()"]').style.display = 'none';
}

// Cancelar edição e recarregar a página
function cancelarEdicao() {
    // Recarrega a página para descartar as alterações
    window.location.reload();
}
