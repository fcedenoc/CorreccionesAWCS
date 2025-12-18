const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

const userMenu = `
    <li><a href="index.php">Inicio</a></li>
    <li><a href="reportes.php">Reportes</a></li>
    <li><a href="servicios.php">Servicios</a></li>
    <li><a href="denuncias.php">Mi Denuncia</a></li>
    <li><a href="contacto.php">Contacto</a></li>
    <li><a href="login.php" onclick="logout()">Cerrar Sesión</a></li>
    <li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li>
    <li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-off"></i></button></li>
`;

const adminMenu = `
    <li><a href="panelAdmin.php">Panel Admin</a></li>
    <li><a href="php/GestiónUsuarios/gestionUsuarios.php">Gestión Usuarios</a></li>
    <li><a href="login.php" onclick="logout()">Cerrar Sesión</a></li>
    <li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li>
    <li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-on"></i></button></li>
`;

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });
}

attachEventListeners();

const adminToggleBtn = document.getElementById('adminToggle');
if (adminToggleBtn) {
    adminToggleBtn.addEventListener('click', () => {

        if (window.location.pathname.includes('panelAdmin.php') || window.location.pathname.includes('gestionUsuarios.php')) {
            window.location.href = 'index.php';
        } else {
            window.location.href = 'panelAdmin.php';
        }
    });
}

function attachEventListeners() {

    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {

        const newThemeToggle = themeToggle.cloneNode(true);
        themeToggle.parentNode.replaceChild(newThemeToggle, themeToggle);

        newThemeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const icon = newThemeToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-moon');
                icon.classList.toggle('bi-sun');
            }
        });
    }

    const accordions = document.querySelectorAll('.accordion-item');
    accordions.forEach(item => {
        const header = item.querySelector('.accordion-header');
        if (header) {
            header.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        }
    });

    function logout() {
        window.location.href = 'login.php';
    }
