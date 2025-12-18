<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Estadísticos</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="reportes.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">Mi Comunidad Segura</div>
        <button class="menu-toggle"><i class="bi bi-list"></i></button>
        <ul class="nav-links">
            <li><a href="index.php" data-page="inicio" class="active">Inicio</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="#" data-page="servicios">Servicios</a></li>
            <li><a href="denuncias.php">Mi Denuncia</a></li>
            <li><a href="#" data-page="contacto">Contacto</a></li>
            <li><a href="login.php">Cerrar Sesión</a></li>
            <li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li>
            <li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-off"></i></button></li>
        </ul>
    </nav>

    <main class="container">
        <h1>Reportes Estadísticos de Denuncias</h1>

        <section class="filtros">
            <label>Fecha inicio: <input type="date" id="fechaInicio"></label>
            <label>Fecha fin: <input type="date" id="fechaFin"></label>
            <button id="btnFiltrar">Filtrar</button>
        </section>

        <section class="charts">
            <div class="chart-card">
                <h3>Denuncias por Categoría</h3>
                <canvas id="chartCategoria"></canvas>
            </div>
            <div class="chart-card">
                <h3>Denuncias por Estado</h3>
                <canvas id="chartEstado"></canvas>
            </div>
            <div class="chart-card">
                <h3>Denuncias por Municipalidad</h3>
                <canvas id="chartMunicipalidad"></canvas>
            </div>
        </section>
    </main>

    <script src="reportes.js"></script>
</body>

</html>