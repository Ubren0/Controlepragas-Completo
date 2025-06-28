/**
 * Gerenciamento de login e autenticação
 */

// Carrega usuário salvo ao iniciar
window.onload = function () {
    const usuarioSalvo = recuperarDoLocalStorage("usuario");
    if (usuarioSalvo) {
        document.getElementById("usuario").value = usuarioSalvo;
        document.getElementById("lembrarMe").checked = true;
    }
};

// Função de login
function fazerLogin() {
    const usuario = document.getElementById("usuario").value;
    const senha = document.getElementById("senha").value;
    const lembrarMe = document.getElementById("lembrarMe").checked;

    // Validação básica
    if (!usuario || !senha) {
        alert("Por favor, preencha todos os campos!");
        return;
    }

    // Verifica credenciais fixas
    if (usuario === "Admin" && senha === "1234") {
        // Salva dados da sessão
        localStorage.setItem("sessaoAtiva", "true");
        localStorage.setItem("usuarioAtual", usuario);
        
        if (lembrarMe) {
            localStorage.setItem("usuario", usuario);
        } else {
            localStorage.removeItem("usuario");
        }

        window.location.href = "/HTML/dashboard.php";
    } else {
        alert("Usuário ou senha incorretos!");
    }
}

