<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../ConexionBD.php';

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
function listarUsuarios() {
    try {
        $mysqli = abrirConexion();

        $sql = "SELECT id, cedula, nombre_completo, email, username, rol, estado, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
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
function obtenerEstadisticas() {
    try {
        $mysqli = abrirConexion();

        // Total de usuarios
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios");
        $row = $result->fetch_assoc();
        $totalUsuarios = $row['total'];

        // Usuarios activos
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'");
        $row = $result->fetch_assoc();
        $totalActivos = $row['total'];

        // Usuarios bloqueados
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'bloqueado'");
        $row = $result->fetch_assoc();
        $totalBloqueados = $row['total'];

        // Total de administradores
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'admin'");
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
function editarUsuario($input) {
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
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE (email = ? OR username = ?) AND id != ?");
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
        $stmt = $mysqli->prepare("UPDATE usuarios SET nombre_completo = ?, email = ?, username = ?, rol = ? WHERE id = ?");
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
function cambiarEstado($input) {
    try {
        $id = intval($input['id'] ?? 0);
        $estado = trim($input['estado'] ?? '');

        if ($id <= 0 || empty($estado) || !in_array($estado, ['activo', 'bloqueado'])) {
            echo json_encode(['status' => 'error', 'mensaje' => 'Datos inválidos']);
            exit;
        }

        $mysqli = abrirConexion();

        $stmt = $mysqli->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
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
function eliminarUsuario($input) {
    try {
        $id = intval($input['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'mensaje' => 'ID inválido']);
            exit;
        }

        $mysqli = abrirConexion();

        $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE id = ?");
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
