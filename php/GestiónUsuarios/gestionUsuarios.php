<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../ConexionBD.php';

$isAjax = isset($_GET['accion']) || isset($_POST['accion']);

if (!$isAjax) {
    echo <<<'HTML'
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="user-management-page">
    <div class="container">
        <div class="management-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-users-cog me-2"></i>Gestión de Usuarios</h3>
                <a href="../../index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>

            <!-- Barra de búsqueda y filtros -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Buscar por nombre, email o cédula...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterRol">
                        <option value="">Todos los roles</option>
                        <option value="admin">Admin</option>
                        <option value="municipalidad">Municipalidad</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterEstado">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activos</option>
                        <option value="bloqueado">Bloqueados</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTableBody">

                    </tbody>
                </table>
            </div>

            <div id="noUsersMessage" class="text-center text-muted p-4 d-none">
                <i class="fas fa-user-slash fa-3x mb-3"></i>
                <p>No se encontraron usuarios</p>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h5 id="totalUsuarios">0</h5>
                            <p>Total Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h5 id="totalActivos">0</h5>
                            <p>Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <div class="stat-content">
                            <h5 id="totalBloqueados">0</h5>
                            <p>Bloqueados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-content">
                            <h5 id="totalAdmins">0</h5>
                            <p>Administradores</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
    </script>
</body>

</html>
HTML;
    exit;
}

// API part
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener acción solicitada
    $accion = $_GET['accion'] ?? '';

    if ($accion === 'listar') {
        listarUsuarios();
    } else if ($accion === 'estadisticas') {
        obtenerEstadisticas();
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $accion = $input['accion'] ?? '';

    if ($accion === 'editar') {
        editarUsuario($input);
    } else if ($accion === 'eliminar') {
        eliminarUsuario($input);
    } else if ($accion === 'cambiarEstado') {
        cambiarEstado($input);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Acción no válida']);
    }
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
}

// Función para listar todos los usuarios
function listarUsuarios()
{
    try {
        $mysqli = abrirConexion();

        $sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $result = $mysqli->query($sql);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $mysqli->error);
        }

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        cerrarConexion($mysqli);
        echo json_encode(['status' => 'ok', 'usuarios' => $usuarios]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}

// Función para obtener estadísticas
function obtenerEstadisticas()
{
    try {
        $mysqli = abrirConexion();

        // Total de usuarios
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuario");
        $row = $result->fetch_assoc();
        $totalUsuarios = $row['total'];

        // Usuarios activos
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuario WHERE estado = 'activo'");
        $row = $result->fetch_assoc();
        $totalActivos = $row['total'];

        // Usuarios bloqueados
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuario WHERE estado = 'bloqueado'");
        $row = $result->fetch_assoc();
        $totalBloqueados = $row['total'];

        // Total de administradores
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuario WHERE rol = 'admin'");
        $row = $result->fetch_assoc();
        $totalAdmins = $row['total'];

        cerrarConexion($mysqli);
        echo json_encode([
            'status' => 'ok',
            'totalUsuarios' => $totalUsuarios,
            'totalActivos' => $totalActivos,
            'totalBloqueados' => $totalBloqueados,
            'totalAdmins' => $totalAdmins
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}

// Función para editar usuario
function editarUsuario($input)
{
    try {
        $id = intval($input['id'] ?? 0);
        $nombre = trim($input['nombre_completo'] ?? '');
        $email = trim($input['email'] ?? '');
        $username = trim($input['username'] ?? '');
        $rol = trim($input['rol'] ?? '');

        if ($id <= 0 || empty($nombre) || empty($email) || empty($username) || empty($rol)) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
            exit;
        }

        $mysqli = abrirConexion();

        // Verificar que el email y username no estén duplicados (excepto para el usuario actual)
        $stmt = $mysqli->prepare("SELECT id FROM usuario WHERE (email = ? OR username = ?) AND id != ?");
        $stmt->bind_param("ssi", $email, $username, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['status' => 'error', 'mensaje' => 'El email o username ya están en uso']);
            $stmt->close();
            cerrarConexion($mysqli);
            exit;
        }
        $stmt->close();

        // Actualizar usuario
        $stmt = $mysqli->prepare("UPDATE usuario SET nombre_completo = ?, email = ?, username = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $email, $username, $rol, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'ok', 'mensaje' => 'Usuario actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar: ' . $stmt->error]);
        }

        $stmt->close();
        cerrarConexion($mysqli);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}

// Función para cambiar estado del usuario
function cambiarEstado($input)
{
    try {
        $id = intval($input['id'] ?? 0);
        $estado = trim($input['estado'] ?? '');

        if ($id <= 0 || empty($estado) || !in_array($estado, ['activo', 'bloqueado'])) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Datos inválidos']);
            exit;
        }

        $mysqli = abrirConexion();

        $stmt = $mysqli->prepare("UPDATE usuario SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'ok', 'mensaje' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar estado']);
        }

        $stmt->close();
        cerrarConexion($mysqli);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}

// Función para eliminar usuario
function eliminarUsuario($input)
{
    try {
        $id = intval($input['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'mensaje' => 'ID inválido']);
            exit;
        }

        $mysqli = abrirConexion();

        $stmt = $mysqli->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'ok', 'mensaje' => 'Usuario eliminado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'mensaje' => 'Usuario no encontrado']);
            }
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al eliminar']);
        }

        $stmt->close();
        cerrarConexion($mysqli);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}
?>