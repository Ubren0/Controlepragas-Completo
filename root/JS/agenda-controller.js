/**
 * Controlador principal da agenda - gerencia a lógica de negócio
 */
const AgendaController = {
    /**
     * Dados dos agendamentos
     */
    agendamentos: [],

    /**
     * Estado atual dos filtros
     */
    filtros: {
        data: '',
        status: ''
    },

    /**
     * Inicializar controlador
     */
    async inicializar() {
        try {
            // Inicializar UI
            AgendaUI.inicializar();
            
            // Configurar data de hoje como padrão
            const hoje = this.obterDataHoje();
            AgendaUI.elementos.filtroData.value = hoje;
            
            // Carregar agendamentos
            await this.carregarAgendamentos();
            
        } catch (error) {
            console.error('Erro na inicialização:', error);
            AgendaUI.atualizarStatus('Erro na inicialização do sistema', 'erro');
        }
    },

    /**
     * Carregar agendamentos do servidor
     */
    async carregarAgendamentos() {
        AgendaUI.atualizarStatus('Buscando dados do servidor...', 'carregando');
        
        try {
            const resultado = await AgendaAPI.buscarAgendamentos();
            
            if (resultado.sucesso) {
                this.agendamentos = resultado.dados;
                AgendaUI.atualizarStatus(`Encontrados ${resultado.total} agendamentos no banco de dados.`, 'sucesso');
                
                if (resultado.total === 0) {
                    AgendaUI.atualizarStatus('Nenhum agendamento encontrado. Use a página "Serviços" para criar novos agendamentos.', 'info');
                }
                
                this.filtrarAgendamentos();
                
            } else {
                AgendaUI.atualizarStatus(`Erro: ${resultado.erro}`, 'erro');
                AgendaUI.exibirMensagemErro(resultado.erro);
                this.agendamentos = [];
            }
            
        } catch (error) {
            console.error('Erro ao carregar agendamentos:', error);
            AgendaUI.atualizarStatus('Erro de conexão com servidor', 'erro');
            AgendaUI.exibirMensagemErro('Erro ao conectar com o servidor: ' + error.message);
        }
    },

    /**
     * Filtrar agendamentos baseado nos filtros atuais
     */
    filtrarAgendamentos() {
        // Atualizar filtros
        this.filtros.data = AgendaUI.elementos.filtroData.value;
        this.filtros.status = AgendaUI.elementos.filtroStatus.value;
        
        // Aplicar filtros
        let agendamentosFiltrados = this.agendamentos;
        
        if (this.filtros.data) {
            agendamentosFiltrados = agendamentosFiltrados.filter(ag => 
                ag.data === this.filtros.data
            );
        }
        
        if (this.filtros.status) {
            agendamentosFiltrados = agendamentosFiltrados.filter(ag => 
                ag.status === this.filtros.status
            );
        }
        
        // Exibir resultados
        AgendaUI.exibirAgendamentos(agendamentosFiltrados);
        this.atualizarEstatisticas();
        
        console.log('Agendamentos filtrados:', agendamentosFiltrados);
    },

    /**
     * Atualizar estatísticas do resumo
     */
    atualizarEstatisticas() {
        const hoje = this.obterDataHoje();
        const agendamentosHoje = this.agendamentos.filter(ag => ag.data === hoje);
        
        const estatisticas = {
            total: this.agendamentos.length,
            pendentes: agendamentosHoje.filter(ag => ag.status === 'Agendado').length,
            concluidos: agendamentosHoje.filter(ag => 
                ag.status === 'Concluído' || ag.status === 'Concluido'
            ).length,
            atrasados: this.agendamentos.filter(ag => 
                ag.data < hoje && ag.status === 'Agendado'
            ).length
        };
        
        AgendaUI.atualizarResumo(estatisticas);
    },

    /**
     * Mostrar todos os agendamentos (limpar filtros)
     */
    mostrarTodos() {
        AgendaUI.elementos.filtroData.value = '';
        AgendaUI.elementos.filtroStatus.value = '';
        this.filtrarAgendamentos();
    },

    /**
     * Recarregar dados do servidor
     */
    async recarregarDados() {
        await this.carregarAgendamentos();
    },

    /**
     * Editar agendamento (placeholder)
     * @param {number} id - ID do agendamento
     */
    editarAgendamento(id) {
        alert(`Funcionalidade de edição em desenvolvimento. ID: ${id}`);
        // TODO: Implementar modal de edição
    },

    /**
     * Cancelar agendamento
     * @param {number} id - ID do agendamento
     */
    async cancelarAgendamento(id) {
        if (!confirm('Tem certeza que deseja cancelar este agendamento?')) {
            return;
        }
        
        try {
            const resultado = await AgendaAPI.atualizarStatus(id, 'Cancelado');
            
            if (resultado.sucesso) {
                // Atualizar localmente
                const agendamento = this.agendamentos.find(ag => ag.id == id);
                if (agendamento) {
                    agendamento.status = 'Cancelado';
                    this.filtrarAgendamentos();
                }
                
                alert('Agendamento cancelado com sucesso!');
                
            } else {
                alert('Erro ao cancelar agendamento: ' + resultado.mensagem);
            }
            
        } catch (error) {
            console.error('Erro ao cancelar agendamento:', error);
            alert('Erro ao cancelar agendamento: ' + error.message);
        }
    },

    /**
     * Exibir debug completo
     */
    async exibirDebug() {
        try {
            const resultado = await AgendaAPI.buscarDebug();
            
            if (resultado.sucesso) {
                AgendaUI.exibirModalDebug(resultado.dados);
            } else {
                alert('Erro ao buscar informações de debug: ' + resultado.erro);
            }
            
        } catch (error) {
            console.error('Erro ao buscar debug:', error);
            alert('Erro ao buscar informações de debug. Verifique o console (F12).');
        }
    },

    /**
     * Obter data de hoje no formato YYYY-MM-DD
     * @returns {string} Data de hoje
     */
    obterDataHoje() {
        const hoje = new Date();
        return hoje.toISOString().split('T')[0];
    }
};

// Disponibilizar globalmente
window.AgendaController = AgendaController;
