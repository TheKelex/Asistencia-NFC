/* =====================================================
   login.js — Lógica del formulario de login
   
   ⚠️ ZONA DE CONEXIÓN ⚠️
   Busca el comentario "AQUÍ VA TU CONEXIÓN" más abajo
   para saber exactamente dónde poner tu fetch/AJAX/PHP.
===================================================== */

function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eyeIcon');

  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'ph ph-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'ph ph-eye';
  }
}

function handleLogin(event) {
  event.preventDefault(); // Evita que recargue la página

  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const remember = document.getElementById('remember').checked;

  const submitBtn = document.getElementById('submitBtn');
  const btnText   = document.getElementById('btnText');
  const btnLoader = document.getElementById('btnLoader');
  const errorMsg  = document.getElementById('errorMsg');
  const errorText = document.getElementById('errorText');

  // Mostrar estado de carga
  submitBtn.disabled = true;
  btnText.textContent = 'Iniciando sesión...';
  btnLoader.classList.remove('hidden');
  errorMsg.classList.add('hidden');

  // =====================================================
  //  ⚠️ AQUÍ VA TU CONEXIÓN ⚠️
  //
  //  Reemplaza el bloque de simulación de abajo por algo como:
  //
  //  fetch('TU_URL_O_ARCHIVO_PHP', {
  //    method: 'POST',
  //    headers: { 'Content-Type': 'application/json' },
  //    body: JSON.stringify({ email, password, remember })
  //  })
  //  .then(res => res.json())
  //  .then(data => {
  //    if (data.success) {
  //      window.location.href = 'Dashboard/dashboard.html'; // o donde quieras
  //    } else {
  //      showError(data.message || 'Credenciales incorrectas.');
  //    }
  //  })
  //  .catch(() => showError('Error de conexión con el servidor.'));
  //
  //  Cuando lo tengas, borra la "simulación" de abajo.
  // =====================================================

  // --- SIMULACIÓN (borra esto cuando tengas el backend) ---
  setTimeout(() => {
    // Ejemplo: si el email termina en @sena.edu.co, entra
    if (email.endsWith('@sena.edu.co') && password.length >= 6) {
      // Login exitoso → redirige al dashboard
      window.location.href = 'Dashboard/index.html';
    } else {
      showError('Credenciales incorrectas. Intenta de nuevo.');
    }
  }, 1200);
  // --------------------------------------------------------

  function showError(msg) {
    errorText.textContent = msg;
    errorMsg.classList.remove('hidden');
    submitBtn.disabled = false;
    btnText.textContent = 'Iniciar sesión';
    btnLoader.classList.add('hidden');
  }
}
