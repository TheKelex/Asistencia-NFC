# Asistencia-NFC

## Descripción general

Asistencia-NFC es un proyecto frontend orientado a la gestión de asistencia mediante tecnología NFC. Su propósito es facilitar el registro, seguimiento y control de sesiones de clase desde una interfaz web simple, profesional y responsive.

El sistema está pensado para ser utilizado por instructores que necesitan visualizar el estado de una sesión activa, administrar configuraciones y gestionar la experiencia de uso desde una vista organizada.

---

# Estructura del proyecto

La estructura del repositorio está organizada por módulos y vistas principales:

- Dashboard/ : contiene la interfaz principal del panel de control.
- Ajustes/ : contiene la vista de configuración y perfil del sistema.
- Registrar_Aprendices/ : corresponde al módulo para registrar aprendices.
- Configurar_Sesion/ : incluye la vista para configurar una sesión activa.
- Aparte/bootstrap-5.3.8-dist/ : almacena la biblioteca Bootstrap 5.3.8 utilizada localmente.
- Aparte/Documentacion/ : reúne archivos de documentación técnica y de alcance del proyecto.
- Aparte/Prototipo/ : conserva una versión preliminar o prototipo del sistema.

---

# Explicación de cada archivo

## Dashboard

### Dashboard.html

Contiene la vista principal del dashboard. Aquí se visualizan:

- el estado de la sesión activa,
- métricas de asistencia,
- clases programadas,
- acciones rápidas,
- actividad reciente.

La estructura está organizada mediante un layout de Bootstrap con sidebar, header y contenido principal.

### style.css

Define los estilos visuales propios del dashboard, incluyendo:

- fondo y tipografía,
- navegación lateral,
- encabezado superior,
- tarjetas con efecto glass,
- botones de acción,
- estilos de la actividad reciente.

## Ajustes

### Ajustes.html

Contiene la vista de configuración del sistema. Incluye:

- perfil del instructor,
- formulario de edición básica,
- configuración del sistema,
- panel del lector NFC.

Esta vista está pensada para centralizar ajustes importantes sin salir del flujo principal de la aplicación.

### style.css

Administra los estilos visuales de la vista de ajustes, como:

- diseño de sidebar,
- cards con efecto glass,
- formularios y controles,
- indicador del lector NFC.

## Registrar_Aprendices

### Registrar.html

Representa la vista para registrar aprendices dentro del sistema.

### style.css

Contiene los estilos específicos de esta sección, con enfoque en formularios y componentes de control.

## Configurar_Sesion

### Configurar.html

Incluye la interfaz para preparar una sesión de asistencia antes de iniciar el proceso de registro.

### Sesion_Activa/Sesion.html

Contiene la vista de sesión activa y su flujo de funcionamiento principal.

### style.css

Define los estilos generales de esta área, incluyendo el layout del contenido, botones de acción y elementos interactivos.

### Sesion_Activa/style.css

Gestiona los estilos particulares de la vista de sesión activa.

## index.html

Es la página de inicio del proyecto. En su estado actual sirve como punto de acceso al sistema de login y a la experiencia inicial de usuario.

## login.js

Responsable de la lógica del formulario de inicio de sesión. Maneja:

- alternar la visibilidad de la contraseña,
- validar el envío del formulario,
- mostrar estados de carga y errores,
- redirigir al usuario según la respuesta del flujo de autenticación.

## style.css (raíz)

Contiene los estilos generales del login y de la interfaz inicial. Aquí se definen los estilos base, layout y componentes visuales usados en la página principal.

---

# Flujo de navegación

El flujo general del sistema puede entenderse de la siguiente forma:

Inicio

↓

Login

↓

Dashboard

↓

Registrar Aprendices

↓

Configurar Sesión

↓

Ajustes

Este recorrido permite que el instructor acceda primero a la vista general del sistema y, luego, avance a las secciones de gestión y configuración.

---

# Componentes reutilizados

El proyecto reutiliza varios componentes de forma consistente en sus vistas:

- Sidebar de navegación.
- Header superior.
- Cards con efecto glass.
- Botones principales y secundarios.
- Formularios de entrada.
- Layout responsive basado en Bootstrap.
- Iconografía Material Symbols.

La idea es mantener una experiencia visual uniforme en todas las pantallas del sistema.

---

# Tecnologías utilizadas

- HTML5
- CSS3
- Bootstrap 5.3.8 (instalado localmente)
- JavaScript Vanilla

---

# Convenciones utilizadas

## Organización de carpetas

Las vistas principales están separadas por carpetas con nombres descriptivos, manteniendo la estructura original del proyecto y evitando mezclar lógica de negocio con presentación.

## Organización del CSS

Los estilos se agrupan por vista o módulo, utilizando archivos CSS específicos por carpeta cuando corresponde.

## Organización del HTML

Cada vista mantiene una estructura clara basada en:

- layout general,
- sidebar,
- header,
- contenido principal,
- secciones y componentes visuales.

## Reutilización de componentes

Se prioriza reutilizar bloques de interfaz ya definidos en vez de duplicar estructuras innecesariamente.

## Convenciones de nombres

Los nombres de archivos y carpetas se mantienen en español o en formato descriptivo, alineados con el contexto del sistema.

## Diseño responsive

Las vistas utilizan el sistema de grid de Bootstrap para adaptarse a pantallas móviles, tablets y escritorio sin depender de frameworks adicionales.

## Uso de Bootstrap

El proyecto emplea Bootstrap 5.3.8 instalado localmente para layout, grid, formularios, botones y componentes responsivos.

---

# Recomendaciones para futuros desarrolladores

Para agregar nuevas vistas o módulos al proyecto sin romper la arquitectura existente, se recomienda:

1. Mantener la estructura actual de carpetas.
2. Reutilizar el sidebar y el header cuando sea posible.
3. Añadir estilos en el archivo CSS correspondiente a la vista.
4. Evitar modificar el diseño base de las interfaces existentes.
5. Mantener el enfoque responsive con Bootstrap.
6. Documentar nuevas vistas y componentes a medida que se incorporen.
7. Respetar las rutas y nombres ya definidos para no afectar la navegación.

---

## Restricciones

Este documento describe la estructura actual del proyecto sin modificar el diseño, las rutas, los nombres de archivos ni la organización existente.
