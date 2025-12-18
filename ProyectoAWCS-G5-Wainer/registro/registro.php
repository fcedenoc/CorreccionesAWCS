<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: linear-gradient(#00559C 0%, #4FA3D1 40%, #E6EEF2 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-card {
      width: 100%;
      max-width: 650px;
      background: white;
      border-radius: 20px;
      box-shadow: 0px 4px 20px rgba(0,0,0,0.2);
      padding: 40px;
      text-align: center;
    }

    .btn-register {
      background: #00559C;
      color: #fff;
      border-radius: 10px;
      padding: 10px;
      width: 100%;
      border: none;
      font-size: 1.1rem;
      transition: 0.3s;
    }

    .btn-register:hover {
      background: #003f73;
    }

    .extra-links a {
      color: #00559C;
      text-decoration: none;
    }

    .extra-links a:hover {
      text-decoration: underline;
    }
  </style>

</head>
<body>
  
  <div class="register-card">
    <h3 class="mb-4">Crear Cuenta</h3>
    <form id="registerForm">

      <div class="mb-3 text-start">
        <label class="form-label">Número de Cédula</label>
        <input type="text" class="form-control" id="cedula" placeholder="Ingrese su número de cédula" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre" required>
      </div>

      <div class="mb-3 text-start">
      <label class="form-label">Nombre de usuario</label>
      <input type="text" class="form-control" id="username" placeholder="Ingrese su nombre de usuario" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="correo" placeholder="ejemplo@correo.com" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="contrasena" placeholder="Cree una contraseña" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" class="form-control" id="confirmar" placeholder="Repita su contraseña" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Dirección Exacta</label>
        <input type="text" class="form-control" id="direccion" placeholder="Ingrese su dirección completa" required>
      </div>

      <div class="mb-4 text-start">
        <label class="form-label d-block">Género</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="genero" value="masculino">
          <label class="form-check-label">Masculino</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="genero" value="femenino">
          <label class="form-check-label">Femenino</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="genero" value="otro">
          <label class="form-check-label">Otro</label>
        </div>
      </div>

      <button type="submit" class="btn-register">Registrarse</button>

    </form>

    <div class="extra-links mt-3">
      <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
  </div>

<script src="registro.js"></script>

</body>
</html>

