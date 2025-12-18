<?php

$host = "127.0.0.1";
$user = "root";
$password = "Admin123*";

$mysqli = new mysqli($host, $user, $password);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->select_db("BD_PF_III25");

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    direccion TEXT,
    genero VARCHAR(10),
    provincia VARCHAR(100),
    rol VARCHAR(50) DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($mysqli->query($sql) === TRUE) {
    echo "Table usuarios created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();

?>