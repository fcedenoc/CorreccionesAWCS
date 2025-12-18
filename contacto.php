<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Mi Comunidad Segura</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">Mi Comunidad Segura</div>
        <button class="menu-toggle"><i class="bi bi-list"></i></button>
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="servicios.php">Servicios</a></li>
            <li><a href="denuncias.php">Mi Denuncia</a></li>
            <li><a href="contacto.php" class="active">Contacto</a></li>
            <li><a href="login.php" onclick="logout()">Cerrar Sesión</a></li>
            <li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li>
            <li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-off"></i></button></li>
        </ul>
    </nav>

    <main id="content">
        <section id="contacto" class="page active" style="display: block;">
            <h2>Contáctanos</h2>

            <form id="contactForm" class="form">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <textarea name="mensaje" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
                <button type="submit">Enviar Mensaje</button>
            </form>

            <div class="faq">
                <h3>Preguntas Frecuentes</h3>
                <div class="accordion">
                    <div class="accordion-item">
                        <button class="accordion-header">¿Cómo creo una denuncia?</button>
                        <div class="accordion-body">Haz clic en "Canal de Denuncias", completa el formulario y adjunta
                            fotos.</div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-header">¿Cómo accedo al mapa de seguridad?</button>
                        <div class="accordion-body">Desde la sección de servicios, selecciona "Mapa de Seguridad".</div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-header">¿Qué hago si olvidé mi contraseña?</button>
                        <div class="accordion-body">En la página de inicio, selecciona "Recuperar acceso".</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 Mi Comunidad Segura — Todos los derechos reservados</p>
    </footer>

    <script src="script.js"></script>

    <script>
        document.getElementById("contactForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("mensajes_guardar.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        title: "Mensaje enviado",
                        text: "Tu mensaje se ha enviado correctamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar"
                    });

                    document.getElementById("contactForm").reset();
                })
                .catch(error => {
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un problema al enviar tu mensaje.",
                        icon: "error",
                        confirmButtonText: "Intentar de nuevo"
                    });
                });
        });
    </script>
</body>

</html>
