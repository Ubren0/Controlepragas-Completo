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
  elements.agendamentoForm.reset();
  updateServicesList();
  elements.disponibilidadeInfoDiv.style.display = 'none'; // Esconde a disponibilidade inicialmente
});

// --- Pesquisa de Cliente ---
function pesquisarCliente() {
  const termo = elements.pesquisaInput.value.trim();
  elements.resultadoPesquisa.innerHTML = '';

  if (termo.length < 3) {
    elements.resultadoPesquisa.innerHTML = '<p class="text-warning">Digite pelo menos 3 caracteres para buscar.</p>';
    return;
  }

  // Simulação de busca
  setTimeout(() => {
    if (termo.toLowerCase().includes('cliente')) {
      exibirFormularioAgendamento({
        id: 123,
        nome: 'Cliente Teste Automatizado',
        endereco: 'Rua da Simulação, 456 - Bairro Fictício'
      });
    } else {
      elements.resultadoPesquisa.innerHTML = '<p class="text-danger">Cliente não encontrado. Tente outro termo.</p>';
    }
  }, 500);
}

// --- Exibe formulário de agendamento ---
function exibirFormularioAgendamento(cliente) {
  elements.idClienteInput.value = cliente.id;
  elements.nomeClienteSpan.textContent = cliente.nome;
  elements.enderecoClienteSpan.textContent = cliente.endereco;

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
  elements.mensagemSemServicos.style.display =
    elements.servicosUL.children.length === 0 ? 'block' : 'none';
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

// --- Envio do formulário ---
elements.agendamentoForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const clienteId = elements.idClienteInput.value;
  const data = elements.dataInput.value;
  const servicos = [...elements.servicosUL.querySelectorAll('li')].map(item => ({
    value: item.getAttribute('data-value'),
    text: item.textContent.replace('Remover', '').trim()
  }));

  if (!clienteId) {
    alert('Erro: Cliente não selecionado.');
    return;
  }

  if (!data) {
    alert('Por favor, selecione a data do agendamento.');
    elements.dataInput.focus();
    return;
  }

  if (servicos.length === 0) {
    alert('Por favor, adicione pelo menos um serviço.');
    elements.servicoSelect.focus();
    return;
  }

  const agendamento = { clienteId, data, servicos };
  console.log('Dados para agendar:', agendamento);

  const successModal = new bootstrap.Modal(document.getElementById('successModal'));
  successModal.show();

  cancelarAgendamento();
});

// --- Verifica disponibilidade ao escolher data ---
elements.dataInput.addEventListener('change', verificarDisponibilidade);

// --- Verifica disponibilidade ---
function verificarDisponibilidade() {
  console.log('Função verificarDisponibilidade chamada.');

  const clienteId = elements.idClienteInput.value;
  const dataSelecionada = elements.dataInput.value;

  elements.disponibilidadeInfoDiv.style.display = 'block';
  elements.listaAgendamentosNaDataDiv.innerHTML = '<p class="text-muted">Carregando disponibilidade...</p>';
  elements.mensagemDisponivelDiv.style.display = 'none';
  elements.mensagemErroDisponibilidadeDiv.style.display = 'none';
  elements.dataSelecionadaSpan.textContent = dataSelecionada ? new Date(dataSelecionada).toLocaleDateString() : '...';

  if (!clienteId || !dataSelecionada) {
    elements.listaAgendamentosNaDataDiv.innerHTML = '<p class="text-warning">Selecione um cliente e uma data para verificar a disponibilidade.</p>';
    return;
  }

  // --- Simulação (substituir por fetch real) ---
  setTimeout(() => {
    const agendamentosExistentes = [
      { id: 1, servico: 'Dedetização Geral', horario: '09:00' },
      { id: 2, servico: 'Controle de Ratos', horario: '14:30' }
    ];

    elements.listaAgendamentosNaDataDiv.innerHTML = '';

    if (agendamentosExistentes.length > 0) {
      let html = '<h6>Agendamentos existentes nesta data:</h6><ul>';
      agendamentosExistentes.forEach(ag => {
        html += `<li>${ag.horario} - ${ag.servico}</li>`;
      });
      html += '</ul><p class="text-warning mb-0">Considere estes horários ao agendar.</p>';
      elements.listaAgendamentosNaDataDiv.innerHTML = html;
      elements.mensagemDisponivelDiv.style.display = 'none';
    } else {
      elements.listaAgendamentosNaDataDiv.innerHTML = '';
      elements.mensagemDisponivelDiv.style.display = 'block';
    }
  }, 700);

  // Exemplo real (comentado):
  /*
  fetch(`/api/agendamentos/disponibilidade?clienteId=${clienteId}&data=${dataSelecionada}`)
    .then(response => response.json())
    .then(data => {
      // lógica real
    })
    .catch(error => {
      elements.mensagemErroDisponibilidadeDiv.style.display = 'block';
    });
  */
}

// --- Mudança em tipo de serviço (reservado para lógica futura) ---
elements.servicoSelect.addEventListener('change', () => {
  // Lógica extra se desejar
});
