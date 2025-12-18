<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "Admin123*";
$database = "BD_PF_III25";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$usuario_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$sql = "SELECT numero_denuncia, tipo, descripcion, ubicacion, evidencia_foto, fecha_creacion, estado FROM denuncias WHERE usuario_id = ? ORDER BY fecha_creacion DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$denuncias = [];
while ($row = $result->fetch_assoc()) {
    $denuncias[] = $row;
}

echo json_encode($denuncias);

$stmt->close();
$conn->close();
?>