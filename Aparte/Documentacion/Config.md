### Configuracion Repositorio Remoto

Comandos basicos para inicializar el repositorio y dejarlo linkeado:

git init  
git commit -m "first commit"  
git branch -M main  
git remote add origin https://github.com/TheKelex/Asistencia-NFC.git  
git pull -u origin main

### Prueba del formulario de configuración

Para probar la vista dinámica de configuración en XAMPP, abrir la siguiente URL:

http://localhost/NFC/Configurar_Sesion/Configurar.php

La página debe mostrar los select de fichas y competencias cargados desde la base de datos sistema_asistencia_nfc.