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
    <title>Reportes Estadísticos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="reportes.css">
</head>

<body>
    <?php include 'componentes/navbarusuarios.html'; ?>

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
                <canvas id="chartCategoria" style="background-color: white;"></canvas>
            </div>
            <div class="chart-card">
                <h3>Denuncias por Estado</h3>
                <canvas id="chartEstado" style="background-color: white;"></canvas>
            </div>
            <div class="chart-card">
                <h3>Denuncias por Municipalidad</h3>
                <canvas id="chartMunicipalidad" style="background-color: white;"></canvas>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
<script src="reportes.js"></script>
</body>

</html>