import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {

    // ── Doughnut – Resumo Financeiro ──────────────────────────
    const elResumo = document.getElementById('graficoResumo');
    if (elResumo) {
        const vencida  = parseFloat(elResumo.dataset.vencida) || 0;
        const pendente = parseFloat(elResumo.dataset.pendente) || 0;
        const paga     = parseFloat(elResumo.dataset.paga) || 0;

        new Chart(elResumo, {
            type: 'doughnut',
            data: {
                labels: ['Vencidas', 'Pendentes', 'Pagas (este mês)'],
                datasets: [{
                    data: [vencida, pendente, paga],
                    backgroundColor: ['#dc3545', '#ffc107', '#198754'],
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' R$ ' + ctx.parsed.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw(chart) {
                    const { ctx, chartArea: { width, height, left, top } } = chart;
                    ctx.save();
                    ctx.font = 'bold 13px sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.textAlign = 'center';
                    ctx.fillText('Total em aberto', left + width / 2, top + height / 2 - 10);
                    ctx.font = 'bold 16px sans-serif';
                    ctx.fillStyle = '#000000';
                    ctx.fillText(
                        'R$ ' + (vencida + pendente).toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
                        left + width / 2,
                        top + height / 2 + 12
                    );
                    ctx.restore();
                }
            }]
        });
    }

    // ── Barras – Últimos 6 meses ──────────────────────────────
    const elFaturas = document.getElementById('graficoFaturas');
    if (elFaturas) {
        const labels    = JSON.parse(elFaturas.dataset.labels    || '[]');
        const pagas     = JSON.parse(elFaturas.dataset.pagas     || '[]');
        const pendentes = JSON.parse(elFaturas.dataset.pendentes || '[]');
        const vencidas  = JSON.parse(elFaturas.dataset.vencidas  || '[]');

        new Chart(elFaturas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Pagas',     data: pagas,     backgroundColor: '#198754' },
                    { label: 'Pendentes', data: pendentes, backgroundColor: '#ffc107' },
                    { label: 'Vencidas',  data: vencidas,  backgroundColor: '#dc3545' },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' R$ ' + ctx.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: val => 'R$ ' + val.toLocaleString('pt-BR')
                        }
                    }
                }
            }
        });
    }

});