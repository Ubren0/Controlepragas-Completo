/**
 * Módulo responsável pela interface da agenda
 */
const AgendaUI = {
    /**
     * Elementos DOM
     */
    elementos: {
        statusCarregamento: null,
        listaAgendamentos: null,
        filtroData: null,
        filtroStatus: null,
        totalAgendamentos: null,
        agendamentosPendentes: null,
        agendamentosConcluidos: null,
        agendamentosAtrasados: null
    },

    /**
     * Inicializar elementos DOM
     */
    inicializar() {
        this.elementos.statusCarregamento = document.getElementById('statusCarregamento');
        this.elementos.listaAgendamentos = document.getElementById('listaAgendamentos');
        this.elementos.filtroData = document.getElementById('filtroData');
        this.elementos.filtroStatus = document.getElementById('filtroStatus');
        this.elementos.totalAgendamentos = document.getElementById('totalAgendamentos');
        this.elementos.agendamentosPendentes = document.getElementById('agendamentosPendentes');
        this.elementos.agendamentosConcluidos = document.getElementById('agendamentosConcluidos');
        this.elementos.agendamentosAtrasados = document.getElementById('agendamentosAtrasados');
    },

    /**
     * Atualizar status de carregamento
     * @param {string} mensagem - Mensagem a ser exibida
     * @param {string} tipo - Tipo da mensagem (sucesso, erro, info)
     */
    atualizarStatus(mensagem, tipo = 'info') {
        if (!this.elementos.statusCarregamento) return;
        
        const icones = {
            sucesso: '✅',
            erro: '❌',
            info: 'ℹ️',
            carregando: '⏳'
        };
        
        this.elementos.statusCarregamento.innerHTML = `${icones[tipo] || ''} ${mensagem}`;
    },

    /**
     * Exibir lista de agendamentos
     * @param {Array} agendamentos - Lista de agendamentos
     */
    exibirAgendamentos(agendamentos) {
        if (!this.elementos.listaAgendamentos) return;

        if (!agendamentos || agendamentos.length === 0) {
            this.exibirMensagemVazia();
            return;
        }

        const html = agendamentos.map(agendamento => this.criarItemAgendamento(agendamento)).join('');
        this.elementos.listaAgendamentos.innerHTML = html;
    },

    /**
     * Criar HTML para um item de agendamento
     * @param {Object} agendamento - Dados do agendamento
     * @returns {string} HTML do item
     */
    criarItemAgendamento(agendamento) {
        const statusBadge = this.obterClasseBadge(agendamento.status);
        const statusClass = this.obterClasseStatus(agendamento.status);
        const dataFormatada = this.formatarDataHora(agendamento.data, agendamento.hora);

        return `
            <div class="evento-item ${statusClass}" data-id="${agendamento.id}">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-1">
                            <strong>${this.escaparHTML(agendamento.cliente || 'Cliente não informado')}</strong> - 
                            ${this.escaparHTML(agendamento.servico || 'Serviço não informado')}
                        </h6>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> ${dataFormatada} | 
                            <i class="bi bi-geo-alt"></i> ${this.escaparHTML(agendamento.endereco || 'Endereço não informado')}
                        </small>
                    </div>
                    <div class="col-md-2 text-center">
                        <span class="badge ${statusBadge}">${this.escaparHTML(agendamento.status || 'Sem status')}</span>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="AgendaController.editarAgendamento(${agendamento.id})" 
                                title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger ms-1" 
                                onclick="AgendaController.cancelarAgendamento(${agendamento.id})" 
                                title="Cancelar">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Exibir mensagem quando não há agendamentos
     */
    exibirMensagemVazia() {
        this.elementos.listaAgendamentos.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3">Nenhum agendamento encontrado</h5>
                <p>Não há agendamentos para os filtros selecionados.</p>
                <button class="btn btn-primary" onclick="window.location.href='servico.php'">
                    <i class="bi bi-plus-circle"></i> Criar Novo Agendamento
                </button>
            </div>
        `;
    },

    /**
     * Exibir mensagem de erro
     * @param {string} mensagem - Mensagem de erro
     */
    exibirMensagemErro(mensagem) {
        this.elementos.listaAgendamentos.innerHTML = `
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                <h5 class="mt-2">Problema ao carregar agendamentos</h5>
                <p>${this.escaparHTML(mensagem)}</p>
                <div class="mt-3">
                    <h6>🔧 Soluções possíveis:</h6>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="../PHP/configurar_sistema.php" class="btn btn-outline-primary btn-sm">
                            Configurar Sistema
                        </a>
                        <a href="../PHP/testar_dados.php" class="btn btn-outline-info btn-sm">
                            Testar Dados
                        </a>
                        <a href="servico.php" class="btn btn-outline-success btn-sm">
                            Criar Agendamento
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="AgendaController.recarregarDados()">
                            Tentar Novamente
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Atualizar resumo estatístico
     * @param {Object} estatisticas - Dados estatísticos
     */
    atualizarResumo(estatisticas) {
        if (this.elementos.totalAgendamentos) {
            this.elementos.totalAgendamentos.textContent = estatisticas.total || 0;
        }
        if (this.elementos.agendamentosPendentes) {
            this.elementos.agendamentosPendentes.textContent = estatisticas.pendentes || 0;
        }
        if (this.elementos.agendamentosConcluidos) {
            this.elementos.agendamentosConcluidos.textContent = estatisticas.concluidos || 0;
        }
        if (this.elementos.agendamentosAtrasados) {
            this.elementos.agendamentosAtrasados.textContent = estatisticas.atrasados || 0;
        }
    },

    /**
     * Obter classe CSS para o status do agendamento
     * @param {string} status - Status do agendamento
     * @returns {string} Classe CSS
     */
    obterClasseStatus(status) {
        switch(status?.toLowerCase()) {
            case 'concluído':
            case 'concluido':
                return 'evento-concluido';
            case 'cancelado':
                return 'evento-urgente';
            default:
                return '';
        }
    },

    /**
     * Obter classe CSS para o badge do status
     * @param {string} status - Status do agendamento
     * @returns {string} Classe CSS do badge
     */
    obterClasseBadge(status) {
        switch(status?.toLowerCase()) {
            case 'agendado':
                return 'bg-primary';
            case 'em andamento':
                return 'bg-warning text-dark';
            case 'concluído':
            case 'concluido':
                return 'bg-success';
            case 'cancelado':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    },

    /**
     * Formatar data e hora para exibição
     * @param {string} data - Data no formato YYYY-MM-DD
     * @param {string} hora - Hora no formato HH:MM:SS
     * @returns {string} Data formatada
     */
    formatarDataHora(data, hora) {
        if (!data) return 'Data não informada';
        
        try {
            const dataHora = new Date(`${data}T${hora || '00:00:00'}`);
            return dataHora.toLocaleString('pt-BR');
        } catch (error) {
            return `${data} ${hora || ''}`;
        }
    },

    /**
     * Escapar HTML para prevenir XSS
     * @param {string} texto - Texto a ser escapado
     * @returns {string} Texto escapado
     */
    escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    },

    /**
     * Exibir modal de debug
     * @param {Object} dados - Dados de debug
     */
    exibirModalDebug(dados) {
        const modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.style.background = 'rgba(0,0,0,0.5)';
        
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">🔍 Debug da API de Agenda</h5>
                        <button type="button" class="btn-close" onclick="this.closest('.modal').remove()"></button>
                    </div>
                    <div class="modal-body">
                        <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-size: 12px; overflow-x: auto; max-height: 400px;">${JSON.stringify(dados, null, 2)}</pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').remove()">Fechar</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Remover modal ao clicar fora
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
};

// Disponibilizar globalmente
window.AgendaUI = AgendaUI;
