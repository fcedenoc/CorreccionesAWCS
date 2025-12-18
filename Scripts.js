let ubicacionActual = null;

// Cargar denuncias
document.addEventListener('DOMContentLoaded', () => {
    cargarListaDenuncias();
});

// formulario de denuncia
document.getElementById('formDenuncia').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('denuncias_guardar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'OK') {
            document.getElementById('numeroDenuncia').textContent = data.numero;
            const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
            modalExito.show();
            limpiarFormulario();
            cargarListaDenuncias(); // Recargar lista si es necesario
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Error al guardar la denuncia.',
                confirmButtonColor: '#667eea'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión.',
            confirmButtonColor: '#667eea'
        });
    });
});

// Obtener ubicación
document.getElementById('btnUbicacion').addEventListener('click', obtenerUbicacion);

// Vista previa de imagen
document.getElementById('evidencia').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('verImagen');
            preview.src = e.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
});


function limpiarFormulario() {
    document.getElementById('formDenuncia').reset();
    document.getElementById('previewContainer').classList.add('d-none');
    document.getElementById('verImagen').src = '';
    ubicacionActual = null;
}

function obtenerUbicacion() {
    if ("geolocation" in navigator) {
        const btnUbicacion = document.getElementById('btnUbicacion');
        btnUbicacion.disabled = true;
        btnUbicacion.textContent = 'Obteniendo...';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                ubicacionActual = `${lat}, ${lon}`;
                document.getElementById('ubicacion').value = ubicacionActual;
                btnUbicacion.disabled = false;
                btnUbicacion.textContent = 'Obtener Ubicación';
            },
            function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo obtener la ubicación. Por favor, inténtelo de nuevo.',
                    confirmButtonColor: '#667eea'
                });
                btnUbicacion.disabled = false;
                btnUbicacion.textContent = 'Obtener Ubicación';
            }
        );
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Su navegador no soporta geolocalización.',
            confirmButtonColor: '#667eea'
        });
    }
}

function cargarListaDenuncias() {
    fetch('denuncias_data.php')
    .then(response => response.json())
    .then(denuncias => {
        const listaDenuncias = document.getElementById('listaDenuncias');
        listaDenuncias.innerHTML = '';

        if (denuncias.length === 0) {
            listaDenuncias.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No hay denuncias registradas</p>
                </div>`;
            return;
        }

        denuncias.forEach(denuncia => {
            const card = document.createElement('div');
            card.className = 'denuncia-card';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">Denuncia #${denuncia.numero_denuncia}</h5>
                        <p class="mb-1"><strong>Tipo:</strong> ${denuncia.tipo}</p>
                        <p class="mb-1"><strong>Fecha:</strong> ${denuncia.fecha_creacion}</p>
                        <p class="mb-2"><strong>Estado:</strong>
                            <span class="badge badge-${denuncia.estado}" style="color: black !important;">
                                ${denuncia.estado.charAt(0).toUpperCase() + denuncia.estado.slice(1)}
                            </span>
                        </p>
                        <p class="mb-0"><small class="text-muted">Ubicación:</strong> ${denuncia.ubicacion}</p>
                    </div>
                    <img src="${denuncia.evidencia_foto}" alt="Evidencia" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                </div>
            `;
            listaDenuncias.appendChild(card);
        });
    })
    .catch(error => {
        console.error('Error cargando denuncias:', error);
    });
}
