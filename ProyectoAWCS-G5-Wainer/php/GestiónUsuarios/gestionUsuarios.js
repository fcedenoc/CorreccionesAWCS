let usuarios = [];

document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();

    document.getElementById('searchInput').addEventListener('input', filtrarUsuarios);
    document.getElementById('filterRol').addEventListener('change', filtrarUsuarios);
    document.getElementById('filterEstado').addEventListener('change', filtrarUsuarios);
});

function cargarUsuarios() {
    fetch('gestionUsuarios.php?accion=listar')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                usuarios = data.usuarios;
                cargarTablaUsuarios();
                actualizarEstadisticas();
            } else {
                console.error('Error:', data.mensaje);
                Swal.fire('Error', 'No se pudieron cargar los usuarios', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión', 'error');
        });
}

function cargarTablaUsuarios(usuariosFiltrados = null) {
    const tbody = document.getElementById('usuariosTableBody');
    const noUsersMessage = document.getElementById('noUsersMessage');
    const usuariosAMostrar = usuariosFiltrados || usuarios;

    tbody.innerHTML = '';

    if (usuariosAMostrar.length === 0) {
        noUsersMessage.classList.remove('d-none');
        return;
    } else {
        noUsersMessage.classList.add('d-none');
    }

    usuariosAMostrar.forEach(usuario => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${usuario.id}</td>
            <td>${usuario.cedula}</td>
            <td>${usuario.nombre_completo}</td>
            <td>${usuario.email}</td>
            <td>${usuario.username}</td>
            <td>
                <span class="badge badge-rol-${usuario.rol}">
                    ${getRolLabel(usuario.rol)}
                </span>
            </td>
            <td>
                <span class="badge badge-estado-${usuario.estado}">
                    <i class="fas fa-${usuario.estado === 'activo' ? 'check-circle' : 'ban'}"></i>
                    ${usuario.estado === 'activo' ? 'Activo' : 'Bloqueado'}
                </span>
            </td>
            <td class="text-center">
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-primary" onclick="mostrarCambiarRol(${usuario.id})" 
                        title="Cambiar rol">
                        <i class="fas fa-user-tag"></i>
                    </button>
                    <button class="btn btn-sm ${usuario.estado === 'activo' ? 'btn-warning' : 'btn-success'}" 
                        onclick="toggleEstadoUsuario(${usuario.id})"
                        title="${usuario.estado === 'activo' ? 'Bloquear' : 'Habilitar'}">
                        <i class="fas fa-${usuario.estado === 'activo' ? 'lock' : 'unlock'}"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Función para obtener la etiqueta del rol
function getRolLabel(rol) {
    const labels = {
        'admin': 'Administrador',
        'municipalidad': 'Municipalidad',
        'usuario': 'Usuario'
    };
    return labels[rol] || rol;
}

// Función para mostrar el diálogo de cambio de rol
function mostrarCambiarRol(userId) {
    const usuario = usuarios.find(u => u.id === userId);
    if (!usuario) return;

    Swal.fire({
        title: 'Cambiar Rol de Usuario',
        html: `
            <div class="text-start">
                <p><strong>Usuario:</strong> ${usuario.nombre_completo}</p>
                <p><strong>Rol actual:</strong> ${getRolLabel(usuario.rol)}</p>
                <hr>
                <label for="nuevoRol" class="form-label">Seleccione el nuevo rol:</label>
                <select id="nuevoRol" class="form-select">
                    <option value="usuario" ${usuario.rol === 'usuario' ? 'selected' : ''}>Usuario</option>
                    <option value="municipalidad" ${usuario.rol === 'municipalidad' ? 'selected' : ''}>Municipalidad</option>
                    <option value="admin" ${usuario.rol === 'admin' ? 'selected' : ''}>Administrador</option>
                </select>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Cambiar Rol',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#00559C',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const nuevoRol = document.getElementById('nuevoRol').value;
            if (nuevoRol === usuario.rol) {
                Swal.showValidationMessage('Debe seleccionar un rol diferente al actual');
                return false;
            }
            return nuevoRol;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            cambiarRolUsuario(userId, result.value);
        }
    });
}

// Función para cambiar el rol de un usuario
function cambiarRolUsuario(userId, nuevoRol) {
    const usuario = usuarios.find(u => u.id === userId);
    if (!usuario) return;

    const rolAnterior = usuario.rol;

    fetch('gestionUsuarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            accion: 'editar',
            id: userId,
            nombre_completo: usuario.nombre_completo,
            email: usuario.email,
            username: usuario.username,
            rol: nuevoRol
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            cargarUsuarios();
            Swal.fire({
                icon: 'success',
                title: 'Rol Actualizado',
                html: `
                    <p>El rol de <strong>${usuario.nombre_completo}</strong> ha sido cambiado.</p>
                    <p><small>De: ${getRolLabel(rolAnterior)} → A: ${getRolLabel(nuevoRol)}</small></p>
                `,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error en la operación', 'error');
    });
}

// Función para bloquear/habilitar usuario
function toggleEstadoUsuario(userId) {
    const usuario = usuarios.find(u => u.id === userId);
    if (!usuario) return;

    const accion = usuario.estado === 'activo' ? 'bloquear' : 'habilitar';
    const nuevoEstado = usuario.estado === 'activo' ? 'bloqueado' : 'activo';

    Swal.fire({
        title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} Usuario?`,
        html: `
            <p>¿Está seguro que desea ${accion} al usuario:</p>
            <p><strong>${usuario.nombre_completo}</strong></p>
            <p><small>${usuario.email}</small></p>
            ${accion === 'bloquear' ? '<p class="text-danger mt-2"><i class="fas fa-exclamation-triangle"></i> El usuario no podrá acceder al sistema.</p>' : ''}
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: accion === 'bloquear' ? '#dc3545' : '#28a745',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('gestionUsuarios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'cambiarEstado',
                    id: userId,
                    estado: nuevoEstado
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    cargarUsuarios();
                    Swal.fire({
                        icon: 'success',
                        title: `Usuario ${accion === 'bloquear' ? 'Bloqueado' : 'Habilitado'}`,
                        text: `${usuario.nombre_completo} ha sido ${accion === 'bloquear' ? 'bloqueado' : 'habilitado'} exitosamente.`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error en la operación', 'error');
            });
        }
    });
}

// Función para filtrar usuarios
function filtrarUsuarios() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterRol = document.getElementById('filterRol').value;
    const filterEstado = document.getElementById('filterEstado').value;

    const usuariosFiltrados = usuarios.filter(usuario => {
        const matchSearch =
            usuario.nombre_completo.toLowerCase().includes(searchTerm) ||
            usuario.email.toLowerCase().includes(searchTerm) ||
            usuario.cedula.toLowerCase().includes(searchTerm) ||
            usuario.username.toLowerCase().includes(searchTerm);

        const matchRol = !filterRol || usuario.rol === filterRol;
        const matchEstado = !filterEstado || usuario.estado === filterEstado;

        return matchSearch && matchRol && matchEstado;
    });

    cargarTablaUsuarios(usuariosFiltrados);
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    fetch('gestionUsuarios.php?accion=estadisticas')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                document.getElementById('totalUsuarios').textContent = data.totalUsuarios;
                document.getElementById('totalActivos').textContent = data.totalActivos;
                document.getElementById('totalBloqueados').textContent = data.totalBloqueados;
                document.getElementById('totalAdmins').textContent = data.totalAdmins;
            }
        })
        .catch(error => console.error('Error:', error));
}
