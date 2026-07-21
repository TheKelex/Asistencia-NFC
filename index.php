<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asistencia</title>
  <link rel="stylesheet" href="style.css" />
  <!-- Iconos simples con Phosphor Icons (CDN, sin tocar otras carpetas) -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>

  <div class="login-wrapper">

    <!-- Lado izquierdo decorativo -->
    <div class="login-deco">
      <div class="deco-content">
        <div class="deco-icon"><i class="ph-fill ph-wifi-high"></i></div>
        <h2>Sistema de Asistencia NFC</h2>
        <p>Registro rapido, seguro y sin papel. Solo acerca tu tarjeta.</p>
      </div>
    </div>

    <!-- Formulario de login -->
    <div class="login-card">
      <div class="card-inner">

        <div class="card-header">
          <h1>Bienvenido</h1>
          <p>Inicia sesion en <strong>Asistencia NFC</strong></p>
        </div>

        <!--
          ZONA DE CONEXION: Cambia action="#" por la URL de tu
          backend/PHP/servidor cuando lo tengas listo.
          El metodo debe ser POST para seguridad.
        -->
        <form id="loginForm" action="./login.php" method="POST">

          <!-- Email -->
          <div class="form-group">
            <label for="username">Usuario</label>
            <div class="input-wrapper">
              <i class="ph ph-user"></i>
              <input
                type="text"
                id="username"
                name="username"
                placeholder="Nombre de Usuario..."
                autocomplete="username"
                required />
            </div>
          </div>

          <!-- Contrasena -->
          <div class="form-group">
            <label for="password">Contrasena</label>
            <div class="input-wrapper">
              <i class="ph ph-lock-simple"></i>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                autocomplete="current-password"
                required />
            </div>
          </div>

          <!-- Boton de login -->
          <button type="submit" id="submitBtn" class="btn-login">
            <span id="btnText">Iniciar sesion</span>
            <span id="btnLoader" class="loader hidden"></span>
          </button>

          <?php if (isset($_GET["error"])): ?>

            <!-- Mensaje de error (oculto por defecto) -->
            <div id="errorMsg" class="error-msg">
              <i class="ph ph-warning-circle"></i>
              <span id="errorText">Credenciales incorrectas. Intenta de nuevo.</span>
            </div>

          <?php endif; ?>

        </form>

        <div class="card-footer">
          <p>No tienes cuenta? <a href="#" id="contactAdmin">Contacta al administrador</a></p>
        </div>

      </div>
    </div>

  </div>

</body>

</html>