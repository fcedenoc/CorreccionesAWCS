const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

const userMenu = `<li><a href="#" data-page="inicio" class="active">Inicio</a></li><li><a href="reportes.php">Reportes</a></li><li><a href="#" data-page="servicios">Servicios</a></li><li><a href="midenuncia.php">Mi Denuncia</a></li><li><a href="#" data-page="contacto">Contacto</a></li><li><a href="login.php">Cerrar Sesión</a></li><li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li><li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-off"></i></button></li>`;

const adminMenu = `<li><a href="panelAdmin.php">Panel Admin</a></li><li><a href="php/GestiónUsuarios/gestionUsuarios.php">Gestión Usuarios</a></li><li><a href="login.php">Cerrar Sesión</a></li><li><button id="themeToggle" class="theme-btn"><i class="bi bi-sun"></i></button></li><li><button id="adminToggle" class="admin-btn"><i class="bi bi-toggle-on"></i></button></li>`;

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('open');
});

attachEventListeners();

const adminToggle = document.getElementById('adminToggle');
adminToggle.addEventListener('click', () => {
    if (window.location.pathname.includes('panelAdmin.php')) {
        window.location.href = 'index.php';
    } else {
        window.location.href = 'panelAdmin.php';
    }
});

function attachEventListeners() {
    const links = document.querySelectorAll('.nav-links a');
    const pages = document.querySelectorAll('.page');
    links.forEach(link => {
        link.addEventListener('click', e => {
            const target = link.dataset.page;
            if (target) {
                e.preventDefault();
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                pages.forEach(page => page.classList.remove('active'));
                document.getElementById(target).classList.add('active');
            }
            // Si no hay data-page, permitir navegación predeterminada
        });
    });

    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        const icon = themeToggle.querySelector('i');
        icon.classList.toggle('bi-moon');
        icon.classList.toggle('bi-sun');
    });

    const adminToggle = document.getElementById('adminToggle');
    adminToggle.addEventListener('click', () => {
        const navLinks = document.querySelector('.nav-links');
        const icon = adminToggle.querySelector('i');
        if (isAdmin) {
            navLinks.innerHTML = userMenu;
            icon.classList.remove('bi-toggle-on');
            icon.classList.add('bi-toggle-off');
            isAdmin = false;
        } else {
            navLinks.innerHTML = adminMenu;
            icon.classList.remove('bi-toggle-off');
            icon.classList.add('bi-toggle-on');
            isAdmin = true;
        }
        attachEventListeners();
    });
}

const accordions = document.querySelectorAll('.accordion-item');

accordions.forEach(item => {
    const header = item.querySelector('.accordion-header');
    header.addEventListener('click', () => {
        item.classList.toggle('active');
    });
});

const form = document.getElementById('contactForm');
form.addEventListener('submit', e => {
    e.preventDefault();
    alert('Gracias por tu mensaje. Te contactaremos pronto.');
    form.reset();
});

function logout() {
    window.location.href = 'assets/login.php';
}
