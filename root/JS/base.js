/**
 * Arquivo base.js - Contém funções básicas utilizadas em todo o sistema
 * Inclui funções de UI, navegação e utilitários
 */

// ====================================
// Inicialização do Sistema
// ====================================

/**
 * Configura eventos iniciais dos botões principais
 */
function configurarEventosIniciais() {
    const btnLogout = document.getElementById("btnLogout");
    if (btnLogout) {
        btnLogout.addEventListener("click", logout);
    }

    const btnSubmenu = document.getElementById("btnSubmenu");
    if (btnSubmenu) {
        btnSubmenu.addEventListener("click", toggleSubmenu);
    }
}

// ====================================
// Autenticação e Segurança
// ====================================

/**
 * Realiza o logout do usuário
 */
function logout() {
    // Limpar dados do localStorage
    localStorage.removeItem('usuario_logado');
    document.cookie = "usuario=; path=/; max-age=0"; // Remove cookie
    alert('Logout realizado com sucesso!');
    window.location.href = '../index.php';
}

/**
 * Alterna visibilidade da senha
 */
function toggleSenha() {
    const inputSenha = document.getElementById("senha");
    if (inputSenha) {
        inputSenha.type = inputSenha.type === "password" ? "text" : "password";
    }
}

// ====================================
// UI e Navegação
// ====================================

/**
 * Alterna a exibição do submenu de configurações
 */
function toggleSubmenu() {
    const submenu = document.getElementById('submenu');
    const seta = document.getElementById('seta');
    
    if (submenu && seta) {
        const isExpanded = submenu.getAttribute('aria-expanded') === 'true';
        submenu.setAttribute('aria-expanded', !isExpanded);
        submenu.style.display = isExpanded ? 'none' : 'block';
        seta.textContent = isExpanded ? '▼' : '▲';
    }
}

// ====================================
// Validações
// ====================================

/**
 * Valida se uma string é um CNPJ ou CPF válido
 */
function validarCNPJCPF(cnpjcpf) {
    const apenasNumeros = cnpjcpf.replace(/\D/g, '');
    return apenasNumeros.length === 11 || apenasNumeros.length === 14;
}

/**
 * Verifica se campos obrigatórios estão preenchidos
 */
function validarCamposObrigatorios(campos) {
    for (const campoId of campos) {
        const campo = document.getElementById(campoId);
        if (!campo || campo.value.trim() === '') {
            alert(`O campo ${campoId} é obrigatório.`);
            return false;
        }
    }
    return true;
}

// ====================================
// Manipulação de Tabelas
// ====================================

/**
 * Limpa todas as linhas do corpo de uma tabela
 */
function limparTabela(idTabela) {
    const tbody = document.querySelector(`#${idTabela} tbody`);
    if (tbody) {
        tbody.innerHTML = "";
    }
}

// ====================================
// Utilitários
// ====================================

/**
 * Exibe diálogo de confirmação
 */
function confirmar(mensagem) {
    return confirm(mensagem);
}

/**
 * Recupera um valor do localStorage.
 * @param {string} chave A chave do item.
 * @returns {string|null} O valor armazenado ou null se não for encontrado.
 */
function recuperarDoLocalStorage(chave) {
    return localStorage.getItem(chave);
}

// ====================================
// Verificação de Autorização
// ====================================

/**
 * Verifica se o usuário tem permissão para acessar determinada funcionalidade
 */
function verificarAutorizacao(nivelRequerido) {
    const usuarioLogado = localStorage.getItem('usuario_logado');
    if (!usuarioLogado) {
        window.location.href = 'login.php';
        return false;
    }

    try {
        const usuario = JSON.parse(usuarioLogado);

        if (usuario.nivel === 'Administrador') {
            return true;
        }

        if (Array.isArray(nivelRequerido)) {
            return nivelRequerido.includes(usuario.nivel);
        } else {
            return usuario.nivel === nivelRequerido;
        }
    } catch (e) {
        localStorage.removeItem('usuario_logado');
        window.location.href = 'login.php';
        return false;
    }
}

// ====================================
// Inicialização Geral
// ====================================

document.addEventListener("DOMContentLoaded", () => {
    configurarEventosIniciais();
});
