/**
 * Sistema de Agenda - Interface JavaScript
 * 
 * Gerencia a visualização, filtros e operações dos agendamentos
 * Controla a interface do usuário para gestão da agenda de serviços
 */

// Dados de exemplo dos agendamentos (em produção viria de uma API)
let agendamentos = [
    {
        id: 1,
        cliente: 'João Silva',
        servico: 'Dedetização',
        data: '2024-01-18',
        hora: '09:00',
        status: 'Agendado',
        endereco: 'Rua A, 123 - Centro'
    },
    {
        id: 2,
        cliente: 'Maria Santos',
        servico: 'Controle de Ratos',
        data: '2024-01-18',
        hora: '14:30',
        status: 'Em Andamento',
        endereco: 'Rua B, 456 - Vila Nova'
    }
];

/**
 * Obtém a data atual no formato YYYY-MM-DD
 * 
 * @return {string} - Data atual formatada para campos de data HTML
 */
function getTodayDate() {
    const hoje = new Date();
    return hoje.toISOString().split('T')[0];
}

/**
 * Carrega e exibe os agendamentos aplicando filtros selecionados
 * Função principal que coordena a exibição dos dados na interface
 */
function carregarAgendamentos() {
    console.log('Carregando agendamentos...');
    
    // Coleta os valores dos campos de filtro
    const dataFiltro = document.getElementById('filtroData').value;
    const statusFiltro = document.getElementById('filtroStatus').value;
    
    let agendamentosFiltrados = agendamentos;
    
    // Aplica filtro por data se especificado
    if (dataFiltro) {
        agendamentosFiltrados = agendamentosFiltrados.filter(ag => ag.data === dataFiltro);
    }
    
    // Aplica filtro por status se especificado
    if (statusFiltro) {
        agendamentosFiltrados = agendamentosFiltrados.filter(ag => ag.status === statusFiltro);
    }
    
    // Atualiza a interface com os dados filtrados
    exibirAgendamentos(agendamentosFiltrados);
    atualizarResumo();
}

/**
 * Renderiza a lista de agendamentos na interface
 * 
 * @param {Array} lista - Array de objetos de agendamento para exibir
 */
function exibirAgendamentos(lista) {
    const container = document.getElementById('listaAgendamentos');
    
    if (!container) {
        console.error('Container de agendamentos não encontrado');
        return;
    }
    
    // Exibe mensagem quando não há agendamentos
    if (lista.length === 0) {
        container.innerHTML = '<div class="text-center"><p>Nenhum agendamento encontrado.</p></div>';
        return;
    }
    
    // Gera o HTML para cada agendamento
    let html = '';
    lista.forEach(agendamento => {
        const statusBadge = getStatusBadge(agendamento.status);
        
        html += '<div class="evento-item">';
        html += '<div class="row align-items-center">';
        html += '<div class="col-md-8">';
        html += '<h6><strong>' + agendamento.cliente + '</strong> - ' + agendamento.servico + '</h6>';
        html += '<small class="text-muted">' + agendamento.data + ' ' + agendamento.hora + ' | ' + agendamento.endereco + '</small>';
        html += '</div>';
        html += '<div class="col-md-2 text-center">';
        html += '<span class="badge ' + statusBadge + '">' + agendamento.status + '</span>';
        html += '</div>';
        html += '<div class="col-md-2 text-end">';
        html += '<button class="btn btn-sm btn-outline-primary" onclick="editarAgendamento(' + agendamento.id + ')">✏️</button> ';
        html += '<button class="btn btn-sm btn-outline-danger" onclick="cancelarAgendamento(' + agendamento.id + ')">❌</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
    });
    
    container.innerHTML = html;
}

function getStatusBadge(status) {
    switch(status) {
        case 'Agendado': return 'bg-primary';
        case 'Em Andamento': return 'bg-warning text-dark';
        case 'Concluído': return 'bg-success';
        case 'Cancelado': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

function atualizarResumo() {
    const hoje = getTodayDate();
    const agendamentosHoje = agendamentos.filter(ag => ag.data === hoje);
    
    document.getElementById('totalAgendamentos').textContent = agendamentos.length;
    document.getElementById('agendamentosPendentes').textContent = agendamentosHoje.filter(ag => ag.status === 'Agendado').length;
    document.getElementById('agendamentosConcluidos').textContent = agendamentosHoje.filter(ag => ag.status === 'Concluído').length;
    document.getElementById('agendamentosAtrasados').textContent = agendamentos.filter(ag => ag.data < hoje && ag.status === 'Agendado').length;
}

function editarAgendamento(id) {
    alert('Editar agendamento ID: ' + id);
}

function cancelarAgendamento(id) {
    if (confirm('Cancelar este agendamento?')) {
        const agendamento = agendamentos.find(ag => ag.id === id);
        if (agendamento) {
            agendamento.status = 'Cancelado';
            carregarAgendamentos();
        }
    }
}

function mostrarTodos() {
    document.getElementById('filtroData').value = '';
    document.getElementById('filtroStatus').value = '';
    carregarAgendamentos();
}

document.addEventListener('DOMContentLoaded', function() {
    const hoje = getTodayDate();
    document.getElementById('filtroData').value = hoje;
    
    setTimeout(() => {
        carregarAgendamentos();
    }, 100);
});
