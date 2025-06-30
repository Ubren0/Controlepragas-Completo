// --- Elementos do DOM ---
const elements = {
  secaoBuscar: document.getElementById('secaoBuscarCliente'),
  secaoAgendar: document.getElementById('secaoAgendarServico'),
  pesquisaInput: document.getElementById('pesquisaClienteInput'),
  resultadoPesquisa: document.getElementById('resultadoPesquisaCliente'),
  idClienteInput: document.getElementById('idClienteAgendamento'),
  nomeClienteSpan: document.getElementById('nomeClienteAgendamentoSpan'),
  enderecoClienteSpan: document.getElementById('enderecoClienteAgendamentoSpan'),
  dataInput: document.getElementById('dataAgendamentoInput'),
  servicoSelect: document.getElementById('servicoSelect'),
  servicosUL: document.getElementById('servicosAgendadosUL'),
  mensagemSemServicos: document.getElementById('mensagemSemServicos'),
  agendamentoForm: document.getElementById('agendamentoFormCompleto'),
  disponibilidadeInfoDiv: document.getElementById('disponibilidadeInfo'),
  dataSelecionadaSpan: document.getElementById('dataSelecionadaSpan'),
  listaAgendamentosNaDataDiv: document.getElementById('listaAgendamentosNaData'),
  mensagemDisponivelDiv: document.getElementById('mensagemDisponivel'),
  mensagemErroDisponibilidadeDiv: document.getElementById('mensagemErroDisponibilidade')
};

// --- Inicialização ---
document.addEventListener('DOMContentLoaded', () => {
  // Verificar se elementos essenciais existem antes de usar
  if (elements.agendamentoForm) {
    elements.agendamentoForm.reset();
    elements.agendamentoForm.addEventListener('submit', handleFormSubmit);
  }
  
  updateServicesList();
  
  if (elements.disponibilidadeInfoDiv) {
    elements.disponibilidadeInfoDiv.style.display = 'none';
  }

  // Adicionar listener para verificação de disponibilidade
  if (elements.dataInput) {
    elements.dataInput.addEventListener('change', verificarDisponibilidade);
  }
  
  // Adicionar listener para busca automática
  if (elements.pesquisaInput) {
    elements.pesquisaInput.addEventListener('keyup', buscarClienteAutomatico);
  }
});

// --- Função para lidar com envio do formulário ---
function handleFormSubmit(event) {
  event.preventDefault();

  const clienteId = elements.idClienteInput?.value;
  const data = elements.dataInput?.value;
  const servicos = [...(elements.servicosUL?.querySelectorAll('li') || [])].map(item => 
    item.getAttribute('data-value')
  );

  if (!clienteId) {
    alert('Erro: Cliente não selecionado.');
    return;
  }

  if (!data) {
    alert('Por favor, selecione a data do agendamento.');
    elements.dataInput?.focus();
    return;
  }

  if (servicos.length === 0) {
    alert('Por favor, adicione pelo menos um serviço.');
    elements.servicoSelect?.focus();
    return;
  }

  // Enviar para o backend
  const formData = new FormData();
  formData.append('acao', 'criar_agendamento');
  formData.append('cliente_id', clienteId);
  formData.append('data', data);
  servicos.forEach(servico => {
    formData.append('tipos_servico[]', servico);
  });

  fetch('../PHP/logica_agendamento.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    cancelarAgendamento();
  })
  .catch(error => {
    console.error('Erro ao agendar:', error);
    alert('Erro ao agendar serviço. Tente novamente.');
  });
}

// --- Pesquisa de Cliente (versão melhorada) ---
function pesquisarCliente() {
  const termo = elements.pesquisaInput.value.trim();
  elements.resultadoPesquisa.innerHTML = '';

  // Mudança: aceitar 1 caractere
  if (termo.length < 1) {
    elements.resultadoPesquisa.innerHTML = '<p class="text-warning">Digite pelo menos 1 caractere para buscar.</p>';
    return;
  }

  elements.resultadoPesquisa.innerHTML = `
    <div class="alert alert-info">
      <i class="bi bi-search"></i> Buscando "${termo}"...
    </div>`;

  fetch(`../PHP/api.php?acao=buscar_cliente&termo=${encodeURIComponent(termo)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }
      return response.json();
    })
    .then(data => {
        elements.resultadoPesquisa.innerHTML = '';
        
        if (data.sucesso && data.clientes && data.clientes.length > 0) {
            let html = `
              <div class="alert alert-success">
                <strong>✅ ${data.total_encontrados} cliente(s) encontrado(s) para "${data.termo_buscado}":</strong>
              </div>`;
            
            data.clientes.forEach((cliente, index) => {
                const endereco = [cliente.Rua, cliente.Bairro, cliente.Cidade].filter(Boolean).join(', ') || 'Endereço não informado';
                const documento = cliente.CPFCNPJ || 'Não informado';
                const telefone = cliente.Telefone || 'Não informado';
                const isPrioridade = index === 0 && data.total_encontrados > 1;
                
                html += `
                  <div class="card mb-2 ${isPrioridade ? 'border-success' : 'border-primary'}">
                    <div class="card-body p-3">
                      ${isPrioridade ? '<small class="badge bg-success mb-2">Melhor resultado</small>' : ''}
                      <div class="row align-items-center">
                        <div class="col-md-8">
                          <h6 class="card-title mb-1">
                            <strong>${cliente.Nome}</strong>
                            <small class="badge bg-secondary ms-2">ID: ${cliente.ID_Cliente}</small>
                            <small class="badge ${cliente.TipoCliente === 'F' ? 'bg-info' : 'bg-warning'} ms-1">
                              ${cliente.TipoCliente === 'F' ? 'Pessoa Física' : 'Pessoa Jurídica'}
                            </small>
                          </h6>
                          <small class="text-muted d-block">
                            📧 ${cliente.Email || 'Email não informado'} | 
                            📞 ${telefone} | 
                            📄 ${documento}
                          </small>
                          <small class="text-muted">📍 ${endereco}</small>
                        </div>
                        <div class="col-md-4 text-end">
                          <button class="btn btn-primary btn-lg" onclick='exibirFormularioAgendamento(${JSON.stringify(cliente)})'>
                            <i class="bi bi-calendar-plus"></i> Selecionar
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
            });
            elements.resultadoPesquisa.innerHTML = html;
        } else {
            const mensagem = data.mensagem || 'Nenhum cliente encontrado.';
            let html = `
              <div class="alert alert-warning">
                <strong>⚠️ ${mensagem}</strong>
              </div>`;
            
            // Mostrar sugestões se disponível
            if (data.sugestoes && data.sugestoes.length > 0) {
                html += `
                  <div class="card border-info">
                    <div class="card-body">
                      <h6>💡 Sugestões:</h6>
                      <div class="d-flex flex-wrap gap-2">`;
                
                data.sugestoes.forEach(sugestao => {
                    html += `<button class="btn btn-outline-info btn-sm" onclick="elements.pesquisaInput.value='${sugestao}'; pesquisarCliente();">${sugestao}</button>`;
                });
                
                html += `</div></div></div>`;
            }
            
            // Informações para debug e ajuda
            html += `
              <div class="card border-secondary">
                <div class="card-body">
                  <h6>🔍 Como buscar:</h6>
                  <ul class="mb-2">
                    <li><strong>Por nome:</strong> Digite "João" ou "Maria"</li>
                    <li><strong>Por ID:</strong> Digite apenas números (ex: "1", "2")</li>
                    <li><strong>Por documento:</strong> Digite CPF ou CNPJ</li>
                  </ul>
                  
                  ${data.total_sistema ? `<p><small class="text-muted">Total de clientes no sistema: ${data.total_sistema}</small></p>` : ''}
                  
                  ${data.sugestao ? `<div class="alert alert-info"><small>${data.sugestao}</small></div>` : ''}
                  
                  <a href="cadastroCliente.php" class="btn btn-success btn-sm">
                    <i class="bi bi-person-plus"></i> Cadastrar Novo Cliente
                  </a>
                </div>
              </div>
            `;
            
            elements.resultadoPesquisa.innerHTML = html;
            
            // Log debug se disponível
            if (data.debug) {
                console.log('Debug da busca:', data.debug);
            }
        }
    })
    .catch(error => {
        console.error('Erro na busca:', error);
        elements.resultadoPesquisa.innerHTML = `
          <div class="alert alert-danger">
            <strong>❌ Erro de conexão</strong><br>
            Não foi possível conectar ao servidor. Verifique sua conexão e tente novamente.
            <br><small>Erro técnico: ${error.message}</small>
          </div>`;
    });
}

// --- Busca automática conforme digita (melhorada) ---
function buscarClienteAutomatico() {
  const termo = elements.pesquisaInput.value.trim();
  
  // Mudança: aceitar 1 caractere
  if (termo.length < 1) {
    elements.resultadoPesquisa.innerHTML = '';
    return;
  }
  
  // Busca automática após 300ms
  clearTimeout(window.searchTimeout);
  window.searchTimeout = setTimeout(() => {
    pesquisarCliente();
  }, 300);
}

// --- Selecionar cliente da lista de recentes ---
function selecionarClienteRecente(clienteId, clienteNome) {
  // Buscar dados completos do cliente
  fetch(`../PHP/api.php?acao=buscar_cliente&termo=${clienteId}`)
    .then(response => response.json())
    .then(data => {
      if (data.sucesso && data.clientes.length > 0) {
        const cliente = data.clientes[0];
        exibirFormularioAgendamento(cliente);
      } else {
        alert('Erro ao carregar dados do cliente.');
      }
    })
    .catch(error => {
      console.error('Erro ao buscar cliente:', error);
      alert('Erro ao buscar dados do cliente.');
    });
}

// --- Exibe formulário de agendamento ---
function exibirFormularioAgendamento(cliente) {
  elements.idClienteInput.value = cliente.ID_Cliente;
  elements.nomeClienteSpan.textContent = cliente.Nome;
  const endereco = `${cliente.Rua || 'N/A'}, ${cliente.Bairro || 'N/A'} - ${cliente.Cidade || 'N/A'}`;
  elements.enderecoClienteSpan.textContent = endereco;

  elements.secaoBuscar.style.display = 'none';
  elements.secaoAgendar.style.display = 'block';
  elements.dataInput.focus();
}

// --- Adiciona serviço à lista ---
function adicionarServicoAoAgendamento() {
  const option = elements.servicoSelect.options[elements.servicoSelect.selectedIndex];
  const value = option.value;
  const text = option.textContent;

  if (!value) {
    alert('Por favor, selecione um tipo de serviço válido.');
    return;
  }

  const duplicado = [...elements.servicosUL.querySelectorAll('li')]
    .some(item => item.getAttribute('data-value') === value);

  if (duplicado) {
    alert(`O serviço "${text}" já foi adicionado.`);
    elements.servicoSelect.value = '';
    return;
  }

  const listItem = document.createElement('li');
  listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
  listItem.textContent = text;
  listItem.setAttribute('data-value', value);

  const removeButton = document.createElement('button');
  removeButton.className = 'btn btn-sm btn-danger';
  removeButton.textContent = 'Remover';
  removeButton.type = 'button';
  removeButton.onclick = () => {
    elements.servicosUL.removeChild(listItem);
    updateServicesList();
  };

  listItem.appendChild(removeButton);
  elements.servicosUL.appendChild(listItem);
  elements.servicoSelect.value = '';
  updateServicesList();
}

// --- Atualiza visibilidade da mensagem de lista vazia ---
function updateServicesList() {
  if (elements.mensagemSemServicos && elements.servicosUL) {
    elements.mensagemSemServicos.style.display =
      elements.servicosUL.children.length === 0 ? 'block' : 'none';
  }
}

// --- Cancela agendamento ---
function cancelarAgendamento() {
  elements.agendamentoForm.reset();
  elements.servicosUL.innerHTML = '';
  elements.idClienteInput.value = '';
  elements.nomeClienteSpan.textContent = '';
  elements.enderecoClienteSpan.textContent = '';
  elements.pesquisaInput.value = '';
  elements.resultadoPesquisa.innerHTML = '';
  elements.disponibilidadeInfoDiv.style.display = 'none';

  elements.secaoAgendar.style.display = 'none';
  elements.secaoBuscar.style.display = 'block';

  updateServicesList();
  elements.pesquisaInput.focus();
}

// --- Verifica disponibilidade ao escolher data ---
function verificarDisponibilidade() {
  if (!elements.idClienteInput || !elements.dataInput || !elements.disponibilidadeInfoDiv) {
    return;
  }

  const clienteId = elements.idClienteInput.value;
  const dataSelecionada = elements.dataInput.value;

  elements.disponibilidadeInfoDiv.style.display = 'block';
  
  if (elements.listaAgendamentosNaDataDiv) {
    elements.listaAgendamentosNaDataDiv.innerHTML = '<p class="text-muted">Carregando disponibilidade...</p>';
  }
  
  if (elements.mensagemDisponivelDiv) {
    elements.mensagemDisponivelDiv.style.display = 'none';
  }
  
  if (elements.mensagemErroDisponibilidadeDiv) {
    elements.mensagemErroDisponibilidadeDiv.style.display = 'none';
  }
  
  if (elements.dataSelecionadaSpan) {
    elements.dataSelecionadaSpan.textContent = dataSelecionada ? new Date(dataSelecionada).toLocaleDateString('pt-BR') : '...';
  }

  if (!clienteId || !dataSelecionada) {
    if (elements.listaAgendamentosNaDataDiv) {
      elements.listaAgendamentosNaDataDiv.innerHTML = '<p class="text-warning">Selecione um cliente e uma data para verificar a disponibilidade.</p>';
    }
    return;
  }

  // Buscar agendamentos reais
  fetch(`../PHP/logica_agendamento.php?acao=buscar_agendamentos_data&data=${dataSelecionada}`)
    .then(response => response.json())
    .then(data => {
      if (elements.listaAgendamentosNaDataDiv) {
        elements.listaAgendamentosNaDataDiv.innerHTML = '';
      }

      if (data.sucesso && data.agendamentos.length > 0) {
        let html = '<h6>Agendamentos existentes nesta data:</h6><ul>';
        data.agendamentos.forEach(ag => {
          const horario = new Date(ag.DataHora).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'});
          html += `<li>${horario} - ${ag.cliente_nome} (${ag.tipo_servico_nome})</li>`;
        });
        html += '</ul><p class="text-warning mb-0">Considere estes horários ao agendar.</p>';
        if (elements.listaAgendamentosNaDataDiv) {
          elements.listaAgendamentosNaDataDiv.innerHTML = html;
        }
      } else {
        if (elements.mensagemDisponivelDiv) {
          elements.mensagemDisponivelDiv.style.display = 'block';
        }
      }
    })
    .catch(error => {
      console.error('Erro ao verificar disponibilidade:', error);
      if (elements.mensagemErroDisponibilidadeDiv) {
        elements.mensagemErroDisponibilidadeDiv.style.display = 'block';
      }
    });
}

// --- Mudança em tipo de serviço (reservado para lógica futura) ---
if (elements.servicoSelect) {
  elements.servicoSelect.addEventListener('change', () => {
    // Lógica extra se desejar
  });
}
