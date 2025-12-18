<?php
// Archivo de conexión a la base de datos
include 'ConexionBD.php';
// Abrir la conexión
$mysqli = abrirConexion();

// Consulta para contar denuncias por estado
$sql_counts = "SELECT estado, COUNT(*) as cantidad FROM denuncias GROUP BY estado";
$result_counts = $mysqli->query($sql_counts);
$counts = [];
while ($row = $result_counts->fetch_assoc()) {
    $counts[$row['estado']] = $row['cantidad'];
}

// Si no hay datos, poner 0
$pendiente = $counts['Pendiente'] ?? 0;
$en_proceso = $counts['En Proceso'] ?? 0;
$resuelto = $counts['Resuelto'] ?? 0;

// Cerrar la conexión
cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
        }
    </style>
</head>

<body>
    <?php include 'componentes/navbaradmin.html'; ?>
    <div class="container mt-5">
        <main id="content">
            <section id="inicio" class="page active" style="display: block;">
                <h1>Panel de Administración - Inicio</h1>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pendiente</h5>
                                <p class="card-text fs-3"><?php echo $pendiente; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">En Proceso</h5>
                                <p class="card-text fs-3"><?php echo $en_proceso; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Resuelto</h5>
                                <p class="card-text fs-3"><?php echo $resuelto; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark');
                const icon = themeToggle.querySelector('i');
                icon.classList.toggle('bi-moon');
                icon.classList.toggle('bi-sun');
            });
        }

        const adminToggle = document.getElementById('adminToggle');
        if (adminToggle) {
            adminToggle.addEventListener('click', () => {
                if (window.location.pathname.includes('panelAdmin.php')) {
                    window.location.href = 'index.php';
                } else {
                    window.location.href = 'panelAdmin.php';
                }
            });
        }
    </script>
</body>

</html>