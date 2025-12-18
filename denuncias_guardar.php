<?php
session_start();
include 'ConexionBD.php';

try {
    $conn = abrirConexion();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

$tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : null;
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
$ubicacion = isset($_POST['ubicacion']) ? trim($_POST['ubicacion']) : null;
$municipalidad = isset($_POST['municipalidad']) ? trim($_POST['municipalidad']) : null;
$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Asumiendo que user_id está almacenado en la sesión

if (!$tipo || !$descripcion || !$ubicacion || !$municipalidad || !$usuario_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos obligatorios']);
    exit;
}

// Manejar subida de archivo
$evidencia_path = null;
if (isset($_FILES['evidencia']) && $_FILES['evidencia']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/' . $usuario_id . '/'; // Directorio específico por usuario
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $file_name = uniqid() . '_' . basename($_FILES['evidencia']['name']);
    $evidencia_path = $upload_dir . $file_name;
    if (!move_uploaded_file($_FILES['evidencia']['tmp_name'], $evidencia_path)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al subir la evidencia']);
        exit;
    }
}

$numero_denuncia = uniqid(); // Generar un número único
$estado = 'Pendiente';

// Analizar ubicacion para obtener lat lng si es posible
$lat = 0.0;
$lng = 0.0;
if (preg_match('/(-?\d+\.\d+), (-?\d+\.\d+)/', $ubicacion, $matches)) {
    $lat = (float)$matches[1];
    $lng = (float)$matches[2];
}

$stmt = $conn->prepare("INSERT INTO denuncias (usuario_id, tipo, descripcion, ubicacion, lat, lng, evidencia_foto, estado, numero_denuncia, municipalidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssddssss", $usuario_id, $tipo, $descripcion, $ubicacion, $lat, $lng, $evidencia_path, $estado, $numero_denuncia, $municipalidad);

if ($stmt->execute()) {
    echo json_encode(['status' => 'OK', 'numero' => $numero_denuncia]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la denuncia']);
}

$stmt->close();
cerrarConexion($conn);
?>