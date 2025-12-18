<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Panel Admin</title>
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
            <section id="contacto" class="page active" style="display: block;">
                <h1>Reportes</h1>
                <p>Aquí irán los reportes.</p>
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
                if (window.location.pathname.includes('panelAdmin.php') || window.location.pathname.includes('admin_reportes.php')) {
                    window.location.href = 'index.php';
                } else {
                    window.location.href = 'panelAdmin.php';
                }
            });
        }
    </script>
</body>

</html>