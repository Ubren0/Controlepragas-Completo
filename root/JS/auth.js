/**
 * Sistema de autenticação JavaScript
 */

// Verifica se o usuário está logado
function verificarAutenticacao() {
    const usuarioLogado = localStorage.getItem('usuario_logado');
    
    if (!usuarioLogado) {
        // Se não estiver logado, redireciona para login
        alert('Você precisa fazer login para acessar esta página!');
        window.location.href = '../index.php';
        return false;
    }
    
    try {
        const dadosUsuario = JSON.parse(usuarioLogado);
        console.log('Usuário logado:', dadosUsuario.nome);
        return dadosUsuario;
    } catch (e) {
        // Se os dados estiverem corrompidos, limpa e redireciona
        localStorage.removeItem('usuario_logado');
        alert('Dados de login inválidos. Faça login novamente.');
        window.location.href = '../index.php';
        return false;
    }
}

// Função de logout
function logout() {
    localStorage.removeItem('usuario_logado');
    document.cookie = "usuario=; path=/; max-age=0"; // Remove cookie
    alert('Logout realizado com sucesso!');
    window.location.href = '../index.php';
}

// Verificação automática ao carregar páginas
document.addEventListener('DOMContentLoaded', function() {
    // Só verifica autenticação se não estivermos na página de login
    if (!window.location.pathname.includes('index.php') && 
        !window.location.pathname.includes('Sobrenos.php') &&
        !window.location.pathname.includes('primeirocadastro.php')) {
        verificarAutenticacao();
    }
});
