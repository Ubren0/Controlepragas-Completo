// Armazenar referências aos gráficos para destruí-los quando necessário
let servicosChart = null;
let tiposChart = null;

document.addEventListener('DOMContentLoaded', function () {
    // Função para inicializar os gráficos
    function initCharts() {
        // Destruir gráficos anteriores se existirem
        if (servicosChart) {
            servicosChart.destroy();
        }
        if (tiposChart) {
            tiposChart.destroy();
        }
        
        // Configurar o gráfico de serviços
        const ctx = document.getElementById('graficoServicos').getContext('2d');
        servicosChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Dezembro', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio'],
                datasets: [{
                    label: 'Serviços Realizados',
                    data: [65, 59, 80, 81, 56, 75],
                    borderColor: '#2C786C',
                    backgroundColor: 'rgba(44, 120, 108, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Definir dimensões fixas para o container do gráfico de tipos
        const tiposContainer = document.getElementById('graficoTiposServico').parentElement;
        tiposContainer.style.height = '250px'; // Definir altura fixa
        
        // Configurar o gráfico de tipos de serviço
        const ctxPie = document.getElementById('graficoTiposServico').getContext('2d');
        tiposChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Dedetização', 'Desinfecção', 'Outros'],
                datasets: [{
                    data: [42, 28, 30],
                    backgroundColor: [
                        '#2C786C',
                        '#5DBB96',
                        '#1C4E45'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }

    // Inicializar estatísticas
    document.getElementById('clientesCount').textContent = '156';
    document.getElementById('usuariosCount').textContent = '12';
    document.getElementById('servicosCount').textContent = '48';
    
    // Inicializar os gráficos após um pequeno delay para garantir que os containers estejam prontos
    setTimeout(initCharts, 100);
    
    // Controlar redimensionamento da janela com debounce para evitar múltiplas recriações
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(initCharts, 200);
    });
});