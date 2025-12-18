<?php
// Archivo de conexión a la base de datos
include 'ConexionBD.php';
// Abrir la conexión
$mysqli = abrirConexion();

// Consulta para obtener todas las denuncias
$sql_denuncias = "SELECT * FROM denuncias ORDER BY id DESC";
$result_denuncias = $mysqli->query($sql_denuncias);
$denuncias = [];
while ($row = $result_denuncias->fetch_assoc()) {
    $denuncias[] = $row;
}

// Cerrar la conexión
cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Denuncias - Panel Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
            <section id="denuncia" class="page active" style="display: block;">
                <h2>Listado de Denuncias</h2>
                <table id="denunciasTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($denuncias as $denuncia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($denuncia['id']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['numero_denuncia']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['ubicacion']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['estado']); ?></td>
                            <td><?php echo htmlspecialchars($denuncia['fecha_creacion']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#denunciasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });
        });

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
                if (window.location.pathname.includes('panelAdmin.php') || window.location.pathname.includes('admin_denuncias.php')) {
                    window.location.href = 'index.php';
                } else {
                    window.location.href = 'panelAdmin.php';
                }
            });
        }
    </script>
</body>
</html>
