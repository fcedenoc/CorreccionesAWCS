CREATE DATABASE BD_PF_III25;

USE BD_PF_III25;

-- ============================
-- TABLA: usuarios
-- ============================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre_completo VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    direccion TEXT NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    provincia VARCHAR(50) NOT NULL,
    rol ENUM('usuario', 'municipalidad', 'admin') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM usuarios;


UPDATE usuarios
SET rol = 'admin'
WHERE email = 'franc20@gmail.com';


SELECT COUNT(*) FROM usuarios WHERE rol = 'admin';

CREATE TABLE denuncias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('Denuncia Ambiental', 'Infraestructura', 'Servicios Públicos', 'Seguridad', 'Otro') NOT NULL,
    descripcion TEXT NOT NULL,
    ubicacion VARCHAR(255),
    lat DECIMAL(10 , 7 ) NOT NULL,
    lng DECIMAL(10 , 7 ) NOT NULL,
    evidencia_foto VARCHAR(255),
    estado ENUM('Pendiente', 'En Proceso', 'Resuelto') DEFAULT 'Pendiente',
    numero_denuncia VARCHAR(50) UNIQUE NOT NULL,
    municipalidad VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id)
        REFERENCES usuarios (id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

Select * from denuncias;

-- ============================
-- TABLA: mensajes (NUEVA)
-- ============================
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(150) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,

    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);