/**
 * Sistema de Autenticação - Login.js
 * 
 * Responsável por gerenciar o processo de autenticação do usuário
 * Inclui validação de credenciais, controle de sessão e redirecionamento
 */

/**
 * Processa e valida as credenciais de login do usuário
 * 
 * @param {Event} event - Evento de submissão do formulário
 */
function verificarLogin(event) {
    // Previne o comportamento padrão de envio do formulário
    event.preventDefault();

    // Coleta os dados inseridos pelo usuário
    const usuario = document.getElementById('usuario').value.trim();
    const senha = document.getElementById('senha').value.trim();
    const lembrarMe = document.getElementById('lembrarMe').checked;

    // Validação básica dos campos obrigatórios
    if (!usuario || !senha) {
        mostrarMensagemErro('Por favor, preencha todos os campos.');
        return;
    }

    // Base de usuários para autenticação (em produção, isso viria do servidor)
    const usuariosPadrao = [
        { login: 'admin', senha: '1234', nome: 'Administrador', nivel: 'Administrador' },
        { login: 'usuario', senha: '123', nome: 'Usuário Comum', nivel: 'Usuário' }
    ];

    // Procura o usuário na base de dados
    const usuarioEncontrado = usuariosPadrao.find(u =>
        u.login.toLowerCase() === usuario.toLowerCase() && u.senha === senha
    );

    if (usuarioEncontrado) {
        // Armazena informações do usuário na sessão local
        localStorage.setItem('usuario_logado', JSON.stringify({
            id: Date.now(),
            login: usuarioEncontrado.login,
            nome: usuarioEncontrado.nome,
            nivel: usuarioEncontrado.nivel
        }));

        // Gerencia a funcionalidade "Lembrar-me"
        if (lembrarMe) {
            // Cookie válido por 7 dias (604800 segundos)
            document.cookie = `usuario=${encodeURIComponent(usuario)}; path=/; max-age=604800`;
        } else {
            // Remove o cookie se "Lembrar-me" não estiver marcado
            document.cookie = "usuario=; path=/; max-age=0";
        }

        // Redireciona para o dashboard após login successful
        window.location.href = '../HTML/dashboard.php';
    } else {
        // Exibe mensagem de erro para credenciais inválidas
        mostrarMensagemErro('Usuário ou senha incorretos!');
    }
}

/**
 * Exibe mensagem de erro na interface
 * 
 * @param {string} mensagem - Texto da mensagem de erro a ser exibida
 */
function mostrarMensagemErro(mensagem) {
    // Remove mensagem de erro anterior, se existir
    const mensagemAnterior = document.querySelector('.mensagem-erro');
    if (mensagemAnterior) {
        mensagemAnterior.remove();
    }

    // Cria elemento para a nova mensagem de erro
    const divMensagem = document.createElement('div');
    divMensagem.className = 'mensagem-erro';
    divMensagem.style.cssText = `
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        text-align: center;
    `;
    divMensagem.textContent = mensagem;

    const form = document.querySelector('form');
    if (form && form.parentNode) {
        form.parentNode.insertBefore(divMensagem, form);
    }

    setTimeout(() => {
        if (divMensagem.parentNode) {
            divMensagem.remove();
        }
    }, 5000);
}

// Função para mostrar/ocultar a senha
function toggleSenha() {
    const senhaInput = document.getElementById('senha');
    const toggleIcon = document.querySelector('.toggle-password');

    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        senhaInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Conectar função de login ao formulário
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', verificarLogin);
    }

    // Mostrar erro se existir na URL
    const urlParams = new URLSearchParams(window.location.search);
    const erro = urlParams.get('erro');

    if (erro) {
        const mensagemDiv = document.getElementById('mensagem');
        if (mensagemDiv) {
            mensagemDiv.textContent = decodeURIComponent(erro);
            mensagemDiv.style.color = 'red';
            mensagemDiv.style.display = 'block';
        }
    }

    // Preencher usuário salvo nos cookies
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'usuario') {
            const usuarioInput = document.getElementById('usuario');
            const lembrarCheckbox = document.getElementById('lembrarMe');
            if (usuarioInput && lembrarCheckbox) {
                usuarioInput.value = decodeURIComponent(value);
                lembrarCheckbox.checked = true;
            }
            break;
        }
    }
});