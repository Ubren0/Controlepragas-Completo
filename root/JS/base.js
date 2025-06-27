/**
 * Arquivo base.js - Contém funções básicas utilizadas em todo o sistema
 * Inclui funções de UI, navegação, armazenamento, validações e utilitários
 */

// ====================================
// Inicialização do Sistema
// ====================================

/**
 * Inicializa a página verificando login e configurando eventos
 */
function inicializarPagina() {
    const estaLogado = localStorage.getItem("sessaoAtiva");
    const paginaAtual = window.location.pathname;
    
    // Verifica se não está logado e não está na página de login
    if (!estaLogado && !paginaAtual.includes("login.php")) {
        window.location.href = "login.php";
        return;
    }
    
    // Verifica se está logado e está tentando acessar login
    if (estaLogado && paginaAtual.includes("login.php")) {
        window.location.href = "dashboard.php";
        return;
    }

    configurarEventosIniciais();
}

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
    localStorage.removeItem("sessaoAtiva");
    sessionStorage.clear();
    window.location.href = "login.php";
}

/**
 * Alterna visibilidade da senha
 */
function toggleSenha() {
    const inputSenha = document.getElementById("senha");
    inputSenha.type = inputSenha.type === "password" ? "text" : "password";
}

// ====================================
// UI e Navegação
// ====================================

/**
 * Alterna a exibição do submenu de configurações
 */
function toggleSubmenu() {
    const submenu = document.getElementById("submenu");
    const seta = document.getElementById("seta");

    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        seta.innerHTML = "▼";
    } else {
        submenu.style.display = "block";
        seta.innerHTML = "▲";
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
// Armazenamento
// ====================================

/**
 * Salva um objeto no localStorage
 */
function salvarNoLocalStorage(chave, objeto) {
    localStorage.setItem(chave, JSON.stringify(objeto));
}

/**
 * Recupera um objeto do localStorage
 */
function recuperarDoLocalStorage(chave, valorPadrao = null) {
    const item = localStorage.getItem(chave);
    return item ? JSON.parse(item) : valorPadrao;
}

/**
 * Gerencia o armazenamento no localStorage
 */
function gerenciarStorage(chave, valor, remover = false) {
    if (remover) {
        localStorage.removeItem(chave);
    } else {
        salvarNoLocalStorage(chave, valor);
    }
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

// ====================================
// Event Listeners
// ====================================

// Inicialização do calendário
$(document).ready(function () {
    $("#calendar").evoCalendar({
        theme: "Royal Navy", // Você pode usar "Royal Navy", "Midnight Blue", "Orange Coral", "Blue Coral"
        calendarEvents: [
            {
                id: "event1", // ID opcional
                name: "Serviço Exemplo", 
                date: "2025-05-20",
                type: "event", 
                description: "Dedetização em Cliente X",
            },
            {
                name: "Revisão Mensal",
                date: "2025-05-25",
                type: "holiday",
                description: "Revisão mensal preventiva em Cliente Y"
            }
        ]
    });
});

// Manipulação do formulário de usuário
document.getElementById("formUsuario").addEventListener("submit", function (e) {
    e.preventDefault();

    // Verificação de campos obrigatórios
    const camposObrigatorios = ["nome", "login", "senha", "confirmarSenha"];
    if (!validarCamposObrigatorios(camposObrigatorios)) return;

    // Verifica se as senhas coincidem
    const senha = document.getElementById("senha").value;
    const confirmarSenha = document.getElementById("confirmarSenha").value;
    if (senha !== confirmarSenha) {
        alert("❌ As senhas não coincidem!");
        return;
    }

    // Cria objeto usuário com os dados do formulário
    const usuario = {
        nome: document.getElementById("nome").value,
        login: document.getElementById("login").value,
        senha, // cuidado: em produção deve-se criptografar
        email: document.getElementById("email").value,
        telefone: document.getElementById("telefone").value,
        cargo: document.getElementById("cargo").value,
    };

    // Armazena o usuário no localStorage (pode ser adaptado para API/BD)
    salvarNoLocalStorage("usuario_" + usuario.login, usuario);

    // Mostra popup de sucesso
    document.getElementById("popup").style.display = "flex";
});

// Chama a inicialização quando o DOM estiver pronto
document.addEventListener("DOMContentLoaded", inicializarPagina);
