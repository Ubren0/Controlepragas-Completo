/**
 * Gerenciamento de usuários usando JavaScript
 */

// Dados de usuários salvos no localStorage
let usuarios = [];

// Carrega usuários quando a página é iniciada
document.addEventListener('DOMContentLoaded', function() {
    carregarUsuarios();
    exibirUsuarios();
});

// Carrega usuários do localStorage ou cria dados padrão
function carregarUsuarios() {
    const usuariosSalvos = localStorage.getItem('usuarios_sistema');
    
    if (usuariosSalvos) {
        usuarios = JSON.parse(usuariosSalvos);
    } else {
        // Usuários padrão se não existirem
        usuarios = [
            {
                id: 1,
                nome: 'Administrador do Sistema',
                login: 'admin',
                cargo: 'Administrador'
            },
            {
                id: 2,
                nome: 'Usuário Comum',
                login: 'usuario',
                cargo: 'Usuário'
            }
        ];
        salvarUsuarios();
    }
}

// Salva usuários no localStorage
function salvarUsuarios() {
    localStorage.setItem('usuarios_sistema', JSON.stringify(usuarios));
}

// Exibe usuários na tabela
function exibirUsuarios() {
    const tbody = document.getElementById('tabelaUsuarios');
    const contador = document.getElementById('usuariosCount');
    
    contador.textContent = usuarios.length;
    
    if (usuarios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Nenhum usuário cadastrado.</td></tr>';
        return;
    }
    
    let html = '';
    usuarios.forEach(usuario => {
        html += `
            <tr>
                <td>${usuario.nome}</td>
                <td>${usuario.login}</td>
                <td>${usuario.cargo}</td>
                <td>
                    <button class="btn-acao-editar" onclick="editarUsuario(${usuario.id})">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                    <button class="btn-acao-excluir" onclick="excluirUsuario(${usuario.id}, '${usuario.nome}')">
                        <i class="bi bi-trash-fill"></i> Excluir
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Adiciona novo usuário
function adicionarUsuario(nome, login, cargo) {
    // Verifica se o login já existe
    const loginExiste = usuarios.find(u => u.login.toLowerCase() === login.toLowerCase());
    if (loginExiste) {
        mostrarMensagem('Erro: O login já existe!', 'erro');
        return false;
    }
    
    const novoId = usuarios.length > 0 ? Math.max(...usuarios.map(u => u.id)) + 1 : 1;
    
    const novoUsuario = {
        id: novoId,
        nome: nome,
        login: login,
        cargo: cargo
    };
    
    usuarios.push(novoUsuario);
    salvarUsuarios();
    exibirUsuarios();
    mostrarMensagem('Usuário cadastrado com sucesso!', 'sucesso');
    return true;
}

// Edita usuário existente
function editarUsuario(id) {
    const usuario = usuarios.find(u => u.id === id);
    if (!usuario) {
        mostrarMensagem('Usuário não encontrado!', 'erro');
        return;
    }
    
    // Redireciona para página de edição (seria implementada posteriormente)
    mostrarMensagem('Funcionalidade de edição em desenvolvimento.', 'info');
}

// Exclui usuário
function excluirUsuario(id, nome) {
    if (!confirm(`Tem certeza que deseja excluir o usuário "${nome}"? Esta ação não pode ser desfeita.`)) {
        return;
    }
    
    const index = usuarios.findIndex(u => u.id === id);
    if (index !== -1) {
        usuarios.splice(index, 1);
        salvarUsuarios();
        exibirUsuarios();
        mostrarMensagem('Usuário excluído com sucesso!', 'sucesso');
    } else {
        mostrarMensagem('Usuário não encontrado!', 'erro');
    }
}

// Exibe mensagens para o usuário
function mostrarMensagem(texto, tipo) {
    const divMensagens = document.getElementById('mensagens');
    const classe = tipo === 'sucesso' ? 'alert-success' : 
                   tipo === 'erro' ? 'alert-danger' : 
                   'alert-info';
    
    divMensagens.innerHTML = `<div class="alert ${classe}">${texto}</div>`;
    
    // Remove a mensagem após 5 segundos
    setTimeout(() => {
        divMensagens.innerHTML = '';
    }, 5000);
}

// Função global para ser usada por outras páginas
window.adicionarUsuario = adicionarUsuario;
