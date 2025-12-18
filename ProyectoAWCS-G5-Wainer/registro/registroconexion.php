<?php
// Configuración inicial para mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de conexión a la base de datos
require_once '../ConexionBD.php';

// Establecer el tipo de respuesta como JSON
header('Content-Type: application/json');

// Verificar que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y limpiar los datos enviados desde el formulario
$cedula = trim($_POST['cedula'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$genero = $_POST['genero'] ?? '';
$provincia = 'San José'; // Valor por defecto, ya que no está en el formulario

// Verificar que todos los campos obligatorios estén llenos
if (empty($cedula) || empty($nombre) || empty($username) || empty($correo) || empty($contrasena) || empty($confirmar) || empty($direccion) || empty($genero)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

// Validar que el correo electrónico tenga un formato correcto
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Ingrese un correo válido.']);
    exit;
}

// Verificar que las contraseñas coincidan
if ($contrasena !== $confirmar) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas deben ser iguales.']);
    exit;
}

try {
    // Abrir conexión a la base de datos
    $mysqli = abrirConexion();

    // Verificar si el nombre de usuario o correo ya existen en la base de datos
    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario o correo ya está registrado.']);
        $stmt->close();
        cerrarConexion($mysqli);
        exit;
    }
    $stmt->close();

    // Encriptar la contraseña para mayor seguridad
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO usuarios (cedula, nombre_completo, email, username, password_hash, direccion, genero, provincia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $cedula, $nombre, $correo, $username, $hashed_password, $direccion, $genero, $provincia);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);
    }
    $stmt->close();

    // Cerrar la conexión a la base de datos
    cerrarConexion($mysqli);
} catch (Exception $e) {
    // Manejar errores de conexión o ejecución
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>