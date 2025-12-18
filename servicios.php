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
    <title>Servicios - Mi Comunidad Segura</title>
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
            <li><a href="servicios.php" class="active">Servicios</a></li>
            <li><a href="denuncias.php">Mi Denuncia</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="login.php" onclick="logout()">Cerrar Sesión</a></li>
            <li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li>
            <li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-off"></i></button></li>
        </ul>
    </nav>

    <main id="content">
        <section id="servicios" class="page active" style="display: block;">
            <h2>Nuestros Servicios</h2>
            <div class="cards">
                <div class="card">
                    <i class="bi bi-chat-dots icon"></i>
                    <h3>Canal de Denuncias</h3>
                    <p>Envía reportes con evidencia visual y ubicación exacta.</p>
                </div>
                <div class="card">
                    <i class="bi bi-bar-chart icon"></i>
                    <h3>Estadísticas</h3>
                    <p>Accede a datos actualizados sobre seguridad y convivencia.</p>
                </div>
                <div class="card">
                    <i class="bi bi-telephone icon"></i>
                    <h3>Atención Comunitaria</h3>
                    <p>Contacta con las autoridades o representantes locales.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 Mi Comunidad Segura — Todos los derechos reservados</p>
    </footer>

    <script src="script.js"></script>

</body>

</html>