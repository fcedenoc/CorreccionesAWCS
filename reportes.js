
let chartCategoria, chartEstado, chartMunicipalidad;

async function cargarGraficos(fechaInicio = '', fechaFin = '') {
    const params = new URLSearchParams();
    if(fechaInicio) params.append('fechaInicio', fechaInicio);
    if(fechaFin) params.append('fechaFin', fechaFin);

    const res = await fetch('reportes_data.php?' + params.toString());
    const data = await res.json();

    // Destroy existing charts
    if (chartCategoria) chartCategoria.destroy();
    if (chartEstado) chartEstado.destroy();
    if (chartMunicipalidad) chartMunicipalidad.destroy();

    chartCategoria = new Chart(document.getElementById('chartCategoria'), {
        type: 'bar',
        data: {
            labels: data.categorias.labels,
            datasets: [{
                label: 'Cantidad',
                data: data.categorias.values,
                backgroundColor: 'var(--azul-principal)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    chartEstado = new Chart(document.getElementById('chartEstado'), {
        type: 'pie',
        data: {
            labels: data.estados.labels,
            datasets: [{
                label: 'Cantidad',
                data: data.estados.values,
                backgroundColor: [
                    'var(--amarillo-alerta)',
                    'var(--azul-principal)',
                    'var(--gris-oscuro)'
                ]
            }]
        },
        options: { responsive: true }
    });

    chartMunicipalidad = new Chart(document.getElementById('chartMunicipalidad'), {
        type: 'bar',
        data: {
            labels: data.municipalidades.labels,
            datasets: [{
                label: 'Cantidad',
                data: data.municipalidades.values,
                backgroundColor: 'var(--azul-suave)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
}

cargarGraficos();

document.getElementById('btnFiltrar').addEventListener('click', () => {
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    cargarGraficos(fechaInicio, fechaFin);
});