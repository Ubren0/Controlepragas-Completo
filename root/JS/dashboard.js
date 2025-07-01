/**
 * Dashboard - Interface JavaScript
 * 
 * Gerencia a exibição de dados estatísticos e tabelas dinâmicas do dashboard
 * Carrega informações sobre serviços recentes e próximos agendamentos
 */

document.addEventListener('DOMContentLoaded', function () {
    // Inicializa as seções do dashboard após o carregamento da página
    carregarServicosRecentes();
    carregarProximosAgendamentos();
});

/**
 * Carrega e exibe a tabela de serviços recentes
 * Mostra os últimos serviços realizados com seus status
 */
function carregarServicosRecentes() {
    // Dados de exemplo para demonstração (em produção, viria de uma API)
    const servicosRecentes = [
        { cliente: 'Empresa ABC', servico: 'Dedetização', data: '2024-01-15', status: 'Concluído' },
        { cliente: 'João Silva', servico: 'Controle de Ratos', data: '2024-01-14', status: 'Concluído' },
        { cliente: 'Maria Santos', servico: 'Desinsetização', data: '2024-01-13', status: 'Em Andamento' }
    ];
    
    const tbody = document.getElementById('tabelaRecentes');
    
    if (!tbody) return;
    
    // Verifica se há dados para exibir
    if (servicosRecentes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Nenhum serviço recente.</td></tr>';
        return;
    }
    
    // Gera as linhas da tabela
    let html = '';
    servicosRecentes.forEach(servico => {
        // Define a cor do badge baseado no status
        const badgeClass = servico.status === 'Concluído' ? 'bg-success' : 
                          servico.status === 'Em Andamento' ? 'bg-warning' : 'bg-secondary';
        
        html += `
            <tr>
                <td>${servico.cliente}</td>
                <td>${servico.servico}</td>
                <td>${new Date(servico.data).toLocaleDateString('pt-BR')}</td>
                <td><span class="badge ${badgeClass}">${servico.status}</span></td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

/**
 * Carrega e exibe a tabela de próximos agendamentos
 * Mostra os agendamentos futuros ordenados por data
 */
function carregarProximosAgendamentos() {
    // Dados de exemplo para demonstração (em produção, viria de uma API)
    const agendamentos = [
        { cliente: 'Hotel Central', servico: 'Dedetização Geral', data: '2024-01-18 09:00' },
        { cliente: 'Restaurante Bom Sabor', servico: 'Controle de Pragas', data: '2024-01-19 14:30' },
        { cliente: 'Condomínio Verde', servico: 'Desinsetização', data: '2024-01-20 08:00' }
    ];
    
    const tbody = document.getElementById('tabelaAgendamentos');
    
    if (!tbody) return;
    
    // Verifica se há dados para exibir
    if (agendamentos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Nenhum próximo agendamento.</td></tr>';
        return;
    }
    
    // Gera as linhas da tabela
    let html = '';
    agendamentos.forEach(agendamento => {
        // Formata a data e hora para o padrão brasileiro
        const dataFormatada = new Date(agendamento.data.replace(' ', 'T')).toLocaleString('pt-BR');
        
        html += `
            <tr>
                <td>${agendamento.cliente}</td>
                <td>${agendamento.servico}</td>
                <td>${dataFormatada}</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}