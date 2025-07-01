/**
 * Módulo responsável pela comunicação com a API da agenda
 */
const AgendaAPI = {
    /**
     * URL base da API
     */
    baseURL: '../PHP/',

    /**
     * Buscar agendamentos do servidor
     * @returns {Promise} Promise com os dados dos agendamentos
     */
    async buscarAgendamentos() {
        try {
            const response = await fetch(`${this.baseURL}api_agenda_final.php`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (!data.sucesso) {
                throw new Error(data.mensagem || 'Erro desconhecido na API');
            }
            
            return {
                sucesso: true,
                dados: data.agendamentos || [],
                total: data.total || 0,
                debug: data.debug || null
            };
            
        } catch (error) {
            console.error('Erro ao buscar agendamentos:', error);
            return {
                sucesso: false,
                erro: error.message,
                dados: []
            };
        }
    },

    /**
     * Atualizar status de um agendamento
     * @param {number} id - ID do agendamento
     * @param {string} novoStatus - Novo status do agendamento
     * @returns {Promise} Promise com resultado da operação
     */
    async atualizarStatus(id, novoStatus) {
        try {
            const response = await fetch(`${this.baseURL}atualizar_agendamento.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    status: novoStatus
                })
            });

            const data = await response.json();
            
            return {
                sucesso: data.sucesso || false,
                mensagem: data.mensagem || ''
            };
            
        } catch (error) {
            console.error('Erro ao atualizar agendamento:', error);
            return {
                sucesso: false,
                mensagem: 'Erro de conexão: ' + error.message
            };
        }
    },

    /**
     * Buscar estrutura do banco para debug
     * @returns {Promise} Promise com informações de debug
     */
    async buscarDebug() {
        try {
            const response = await fetch(`${this.baseURL}verificar_estrutura_real.php`);
            const text = await response.text();
            
            return {
                sucesso: true,
                dados: text
            };
            
        } catch (error) {
            return {
                sucesso: false,
                erro: error.message
            };
        }
    }
};

// Disponibilizar globalmente
window.AgendaAPI = AgendaAPI;
