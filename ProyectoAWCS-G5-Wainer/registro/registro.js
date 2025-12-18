// Este archivo maneja la validación y envío del formulario de registro
document.getElementById("registerForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const cedula = document.getElementById("cedula").value.trim();
  const nombre = document.getElementById("nombre").value.trim();
  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("correo").value.trim();
  const contrasena = document.getElementById("contrasena").value.trim();
  const confirmar = document.getElementById("confirmar").value.trim();
  const direccion = document.getElementById("direccion").value.trim();
  const genero = document.querySelector('input[name="genero"]:checked');

  if (!cedula || !nombre || !username || !email || !contrasena || !confirmar || !direccion || !genero) {
    return Swal.fire({
      icon: 'error',
      title: 'Campos incompletos',
      text: 'Todos los campos son obligatorios.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  }

  if (!email.includes('@')) {
    return Swal.fire({
      icon: 'error',
      title: 'Correo inválido',
      text: 'Ingrese un correo válido.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  }

  if (contrasena !== confirmar) {
    return Swal.fire({
      icon: 'error',
      title: 'Contraseñas no coinciden',
      text: 'Las contraseñas deben ser iguales.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  }

  // Preparar los datos del formulario para enviar al servidor
  const formData = new FormData();
  formData.append('cedula', cedula);
  formData.append('nombre', nombre);
  formData.append('username', username);
  formData.append('correo', email);
  formData.append('contrasena', contrasena);
  formData.append('confirmar', confirmar);
  formData.append('direccion', direccion);
  formData.append('genero', genero.value.charAt(0).toUpperCase() + genero.value.slice(1));

  // Enviar los datos al servidor usando fetch
  fetch('registroconexion.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Usuario registrado con éxito',
        text: 'Redirigiendo...',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500
      });
      setTimeout(() => {
        window.location.href = "../index.php";
      }, 2500);
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    }
  })
  .catch(error => {
    // Manejar errores de conexión
    console.log('Error de fetch:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error de conexión. Revisa la consola para más detalles.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 5000
    });
  });
});
