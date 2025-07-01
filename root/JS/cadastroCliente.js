/**
 * Cadastro de Cliente - Interface JavaScript
 * 
 * Gerencia a funcionalidade de auto-preenchimento de endereço via CEP
 * Utiliza a API ViaCEP para buscar informações de endereço automaticamente
 */

document.addEventListener('DOMContentLoaded', function () {
    // Referências dos campos do formulário de endereço
    const cepInput = document.getElementById('cep');
    const ruaInput = document.getElementById('rua');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const estadoInput = document.getElementById('estado');

    /**
     * Limpa todos os campos de endereço
     * Utilizada quando o CEP é inválido ou há erro na consulta
     */
    function limparCamposEndereco() {
        ruaInput.value = '';
        bairroInput.value = '';
        cidadeInput.value = '';
        estadoInput.value = '';
    }

    // Configura o evento de busca automática do CEP
    if (cepInput) {
        cepInput.addEventListener('blur', function () {
            // Remove caracteres não numéricos do CEP
            const cep = this.value.replace(/\D/g, '');

            // Valida se o CEP tem exatamente 8 dígitos
            if (cep.length !== 8) {
                limparCamposEndereco();
                return;
            }

            // Exibe feedback visual durante a busca
            ruaInput.value = 'Buscando...';
            bairroInput.value = 'Buscando...';

            // Consulta a API ViaCEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        // Preenche os campos com os dados encontrados
                        ruaInput.value = data.logradouro;
                        bairroInput.value = data.bairro;
                        cidadeInput.value = data.localidade;
                        estadoInput.value = data.uf;
                    } else {
                        // CEP não encontrado na base da API
                        limparCamposEndereco();
                        alert('CEP não encontrado. Por favor, verifique o número digitado.');
                    }
                })
                .catch(error => {
                    // Trata erros de conexão ou problemas na API
                    console.error('Erro ao buscar CEP:', error);
                    limparCamposEndereco();
                    alert('Não foi possível buscar o CEP. Verifique sua conexão ou tente novamente mais tarde.');
                });
        });
    }
});
