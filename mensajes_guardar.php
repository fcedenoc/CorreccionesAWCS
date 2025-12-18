<?php
$servername = "localhost";
$username = "root";
$password = "Admin123*";
$database = "BD_PF_III25";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo "Error de conexión a la base de datos";
    exit;
}

$nombre  = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
$correo  = isset($_POST['correo']) ? trim($_POST['correo']) : null;
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : null;

if (!$nombre || !$correo || !$mensaje) {
    http_response_code(400); 
    echo "Faltan datos obligatorios";
    exit;
}

$stmt = $conn->prepare("INSERT INTO mensajes (nombre, correo, mensaje) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $correo, $mensaje);

if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Error al guardar el mensaje";
}

$stmt->close();
$conn->close();
?>
