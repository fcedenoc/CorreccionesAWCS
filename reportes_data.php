<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username = "root";
$password = "Admin123*";
$database = "BD_PF_III25";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;

$where = '';
$params = [];
if ($fechaInicio) {
    $where .= " AND fecha_creacion >= ?";
    $params[] = $fechaInicio . " 00:00:00";
}
if ($fechaFin) {
    $where .= " AND fecha_creacion <= ?";
    $params[] = $fechaFin . " 23:59:59";
}

function obtenerDatos($conn, $campo, $where, $params) {
    $sql = "SELECT $campo, COUNT(*) as total FROM denuncias WHERE 1=1 $where GROUP BY $campo";
    $stmt = $conn->prepare($sql);

    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $labels = [];
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row[$campo];
        $values[] = (int)$row['total'];
    }
    $stmt->close();
    return ['labels' => $labels, 'values' => $values];
}

$data = [
    'categorias' => obtenerDatos($conn, 'tipo', $where, $params),
    'estados' => obtenerDatos($conn, 'estado', $where, $params),
    'municipalidades' => obtenerDatos($conn, 'municipalidad', $where, $params)
];

echo json_encode($data);

$conn->close();
?>