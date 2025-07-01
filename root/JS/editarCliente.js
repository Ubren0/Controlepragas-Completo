document.addEventListener("DOMContentLoaded", function () {
    // O preenchimento dos campos agora é feito pelo PHP diretamente no HTML.
    // Este bloco pode ser usado para outras inicializações de JS se necessário.

    const cepInput = document.getElementById('clienteCep');
    if (cepInput) {
        cepInput.addEventListener('blur', function () {
            const cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('clienteRua').value = data.logradouro;
                            document.getElementById('clienteBairro').value = data.bairro;
                            document.getElementById('clienteCidade').value = data.localidade;
                            document.getElementById('clienteEstado').value = data.uf;
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
    }
});

// Habilitar edição
function habilitarEdicao() {
    // Habilita todos os campos do formulário, exceto o ID
    document.getElementById('clienteNome').disabled = false;
    document.getElementById('clienteCnpjCpf').disabled = false;
    document.getElementById('clienteEmail').disabled = false;
    document.getElementById('clienteTelefone').disabled = false;
    document.getElementById('tipoCliente').disabled = false;
    // Habilita campos de endereço
    document.getElementById('clienteCep').disabled = false;
    document.getElementById('clienteRua').disabled = false;
    document.getElementById('clienteBairro').disabled = false;
    document.getElementById('clienteCidade').disabled = false;
    document.getElementById('clienteEstado').disabled = false;

    // Mostra/esconde os botões
    document.getElementById('btnSalvar').style.display = 'inline-block';
    document.getElementById('btnCancelar').style.display = 'inline-block';
    document.querySelector('button[onclick="habilitarEdicao()"]').style.display = 'none';
}

// Cancelar edição e recarregar a página
function cancelarEdicao() {
    // Recarrega a página para restaurar os valores originais
    location.reload();
}
