<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../ConexionBD.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$usuario = trim($input['usuario'] ?? '');
$contrasenna = $input['contrasenna'] ?? '';

if (empty($usuario) || empty($contrasenna)) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Datos faltantes']);
    exit;
}

try {
    $mysqli = abrirConexion();

    $stmt = $mysqli->prepare("SELECT id, nombre_completo, password_hash FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Usuario no encontrado']);
        $stmt->close();
        cerrarConexion($mysqli);
        exit;
    }

    $stmt->bind_result($id, $nombre, $hash);
    $stmt->fetch();

    if (password_verify($contrasenna, $hash)) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nombre;
        echo json_encode(['status' => 'ok', 'nombre' => $nombre]);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Contraseña incorrecta']);
    }

    $stmt->close();
    cerrarConexion($mysqli);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error de conexión: ' . $e->getMessage()]);
}
?>