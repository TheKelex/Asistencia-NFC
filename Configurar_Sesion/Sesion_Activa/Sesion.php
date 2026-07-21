<?php
session_start();
require_once '../../conexion.php';

$sesionStmt = $conexion->prepare(
    'SELECT id_sesion, id_ficha, id_competencia, fecha_inicio, tolerancia_minutos, estado FROM sesion_asistencia WHERE estado = ? ORDER BY id_sesion DESC LIMIT 1'
);
$estadoActivo = 'ACTIVA';
$sesionStmt->bind_param('s', $estadoActivo);
$sesionStmt->execute();
$sesionResult = $sesionStmt->get_result();
$sesion = $sesionResult->fetch_assoc();
$sesionStmt->close();

if (!$sesion) {
    header('Location: ../../Dashboard/Dashboard.html');
    exit;
}

$fichaStmt = $conexion->prepare('SELECT id_ficha, nombre_programa, jornada FROM ficha WHERE id_ficha = ? LIMIT 1');
$fichaStmt->bind_param('i', $sesion['id_ficha']);
$fichaStmt->execute();
$fichaResult = $fichaStmt->get_result();
$ficha = $fichaResult->fetch_assoc();
$fichaStmt->close();

$competenciaStmt = $conexion->prepare('SELECT id_competencia, nombre FROM competencia WHERE id_competencia = ? LIMIT 1');
$competenciaStmt->bind_param('i', $sesion['id_competencia']);
$competenciaStmt->execute();
$competenciaResult = $competenciaStmt->get_result();
$competencia = $competenciaResult->fetch_assoc();
$competenciaStmt->close();

$aprendicesStmt = $conexion->prepare(
    'SELECT id_aprendiz, nombre, numero_identidad, serial_nfc FROM aprendiz WHERE id_ficha = ? ORDER BY nombre ASC'
);
$aprendicesStmt->bind_param('i', $sesion['id_ficha']);
$aprendicesStmt->execute();
$aprendicesResult = $aprendicesStmt->get_result();

$aprendices = [];
while ($aprendiz = $aprendicesResult->fetch_assoc()) {
    $aprendices[] = [
        'id_aprendiz' => (int) $aprendiz['id_aprendiz'],
        'nombre' => $aprendiz['nombre'],
        'numero_identidad' => $aprendiz['numero_identidad'],
        'serial_nfc' => $aprendiz['serial_nfc']
    ];
}
$aprendicesStmt->close();

$registradosStmt = $conexion->prepare('SELECT id_aprendiz FROM asistencia WHERE id_sesion = ?');
$registradosStmt->bind_param('i', $sesion['id_sesion']);
$registradosStmt->execute();
$registradosResult = $registradosStmt->get_result();

$registrados = [];
while ($registro = $registradosResult->fetch_assoc()) {
    $registrados[] = (int) $registro['id_aprendiz'];
}
$registradosStmt->close();

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia</title>
    <link rel="stylesheet" href="./style.css">
    </link>
    <link rel="stylesheet" href="../../Aparte/bootstrap-5.3.8-dist/css/bootstrap.css">
    </link> <!--Bootstrap CSS-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!--Google Icons-->

</head>

<body>

    <!--================= Inicio menu offcanvas (es el mismo que el sticky pero para celulares) =================-->
    <div class="offcanvas offcanvas-start w-50" tabindex="-1" id="menuMovil">

        <div class="offcanvas-header">

            <div class="d-flex gap-2 align-items-center">

                <span class="material-symbols-outlined logo-nfc p-2 text-white rounded-3">
                    nfc
                </span>

                <div>
                    <h5 class="m-0" style="color:#226D00;">
                        Asistencia
                    </h5>

                    <small>Sistema NFC</small>

                </div>

            </div>

            <button class="btn-close" data-bs-dismiss="offcanvas">
            </button>

        </div>

        <div class="offcanvas-body d-flex flex-column gap-3">

            <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2">
                    dashboard
                </span>

                Dashboard

            </a>

            <a class="d-flex gap-2 btn-accion-activo align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2" style="color:#226D00;">
                    event_seat
                </span>

                Sesión

            </a>

            <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2">
                    history
                </span>

                Historial

            </a>

            <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2">
                    analytics
                </span>

                Reportes

            </a>

            <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2">
                    group
                </span>

                Aprendices

            </a>

            <a class="d-flex gap-2 btn-accion mt-auto align-items-center py-3 rounded-3 text-decoration-none text-black"
                href="#">

                <span class="material-symbols-outlined px-2">
                    settings
                </span>

                Ajustes

            </a>

        </div>

    </div>
    <!--================= Fin menu offcanvas (es el mismo que el sticky pero para celulares) =================-->





    <!--Row padre para la division del menu sticky-->
    <div class="row w-100 g-0">

        <!--================= Inicio menu sticky =================-->
        <div class="col-lg-2 d-none d-lg-flex p-4 menu-sticky shadow flex-column">

            <!--Nombre sistema / logo-->
            <div class="d-flex gap-2 mb-5 align-items-center">

                <span class="material-symbols-outlined logo-nfc p-2 text-white rounded-3">
                    nfc
                </span>

                <!--Evita el espaciado entre los textos-->
                <div class="p-0">
                    <h4 class="p-0 m-0" style="color: #226D00;">Asistencia</h4>
                    <p class="p-0 m-0" style="font-size: 0.8rem;">Sistema NFC</p>
                </div>

            </div>

            <!--Bloque opciones-->
            <div class="d-flex flex-column flex-grow-1 gap-3">

                <!--Bloque opcion (copy + paste)-->
                <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2">
                        dashboard
                    </span>

                    <p class="my-auto">Dashboard</p>

                </a>

                <a class="d-flex gap-2 btn-accion-activo align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2" style="color: #226D00;">
                        event_seat
                    </span>

                    <p class="my-auto">Sesion</p>

                </a>

                <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2">
                        history
                    </span>

                    <p class="my-auto">Historial</p>

                </a>

                <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2">
                        analytics
                    </span>

                    <p class="my-auto">Reportes</p>

                </a>

                <a class="d-flex gap-2 btn-accion align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2">
                        group
                    </span>

                    <p class="my-auto">Aprendices</p>

                </a>

                <!--Ajustes-->
                <a class="d-flex gap-2 btn-accion mt-auto align-items-center py-3 rounded-3 text-decoration-none text-black"
                    href="#">

                    <span class="material-symbols-outlined px-2" style="color: gray;">
                        settings
                    </span>

                    <p class="my-auto" style="color: gray;">Ajustes</p>

                </a>

            </div>

        </div>
        <!--================= Fin menu sticky =================-->





        <!--================= Inicio del contenido =================-->

        <div class="col-12 col-lg-10 p-4 d-flex flex-column contenido container-fluid">

            <!--Boton para abrir el menu offcanvas (solo se muestra en celulares)-->
            <div class="d-lg-none mb-4">

                <button class="btn text-white d-flex align-items-center gap-2" data-bs-toggle="offcanvas"
                    data-bs-target="#menuMovil" style="background-color: #226D00;">

                    <span class="material-symbols-outlined">
                        menu
                    </span>

                    Menú

                </button>

            </div>

            <!--Row para las cards informativa-->
            <div class="row d-flex gap-3">

                <!--Card (copy + paste)-->
                <div class="d-flex mx-auto align-items-center pb-3 gap-3 mb-3 col-auto">

                    <span class="material-symbols-outlined p-2 rounded-3 icono-resumen" style="color: green;">
                        person_check
                    </span>

                    <div>
                        <small class="text-secondary d-block">Presentes</small>
                        <span class="fw-semibold"></span><span class="fw-semibold">24</span>
                    </div>

                </div>

                <div class="d-flex mx-auto align-items-center pb-3 gap-3 mb-3 col-auto">

                    <span class="material-symbols-outlined p-2 rounded-3 icono-resumen" style="color: red;">
                        person_off
                    </span>

                    <div>
                        <small class="text-secondary d-block">Ausentes</small>
                        <span class="fw-semibold"></span><span class="fw-semibold">5</span>
                    </div>

                </div>

            </div>

            <!--Row para la division de 3 (info central)-->
            <div class="row flex-grow-1 overflow-hidden">

                <!--Div para los detalles de la sesion-->
                <div class="col-lg-4 col-12 p-2">
                    <div class="d-flex flex-column gap-3 h-100 bg-white rounded-3 p-3 text-center text-lg-start">

                        <!--Div nombre del apartado-->
                        <div class="d-flex align-items-center gap-3 justify-content-center justify-content-lg-start">

                            <span class="material-symbols-outlined" style="color: #226D00;">
                                info
                            </span>

                            <h5 class="fw-bold mb-0">Detalles</h5>

                        </div>

                        <div>
                            <small class="text-secondary d-block">Ficha Seleccionada</small>
                            <span class="fw-semibold"><?php echo htmlspecialchars($ficha['id_ficha'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>

                        <div>
                            <small class="text-secondary d-block">Competencia</small>
                            <span class="fw-semibold"><?php echo htmlspecialchars($competencia['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>

                        <div>
                            <small class="text-secondary d-block">Instructor</small>
                            <span class="fw-semibold"><?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Instructor', ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>

                        <div class="mt-auto">

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <form action="">
                                            <!--Encabezado del modal-->
                                            <div class="modal-header">
                                                <!--Titulo del modal-->
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Registro Manual</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>


                                            <!--Inicio contenido del modal-->
                                            <div class="modal-body d-flex flex-column">

                                                <div class="d-flex align-items-center gap-1 mb-1">

                                                    <span class="material-symbols-outlined text-secondary">
                                                        person_search
                                                    </span>
                                                    <label class="text-secondary">Buscar Aprendiz</label>

                                                </div>

                                                <input type="number" class="rounded-3 p-2 border" required placeholder="Ingrese el ID del aprendiz...">

                                            </div>
                                            <!--Fin contenido del modal-->


                                            <!--Footer del modal (botones)-->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-custom"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit"
                                                    class="btn d-flex align-items-center gap-2 btn-custom">

                                                    <span class="material-symbols-outlined">
                                                        save
                                                    </span>

                                                    Guardar Asistencia
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3">

                                <!-- Button trigger modal -->
                                <button type="button"
                                    class="btn mt-3 fw-bold d-flex mt-auto gap-2 align-items-center justify-content-center btn-outline-custom w-100"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal">

                                    <span class="material-symbols-outlined">
                                        keyboard
                                    </span>

                                    Registro Manual
                                </button>

                                <button id="btnTerminarSesion"
                                    class="btn fw-bold d-flex gap-2 align-items-center justify-content-center btn-danger w-100"
                                    type="button">

                                    <span class="material-symbols-outlined">
                                        stop_circle
                                    </span>

                                    Terminar Sesion
                                </button>

                            </div>

                        </div>

                    </div>
                </div>

                <!--Div decorativo NFC-->
                <div class="col-lg-4 col-12 p-2">
                    <button id="btnNFC" class="btn bg-white rounded-3 p-3 d-flex flex-column h-100 align-items-center justify-content-center w-100 border-0">

                        <span id="iconoNFC" class="material-symbols-outlined nfc-scan-off mb-3">
                            contactless_off
                        </span>

                        <h4 id="tituloNFC" class="fw-bold">Activar lector NFC</h4>

                        <p id="estadoNFC" class="text-secondary mb-0">
                            Lector NFC desactivado
                        </p>

                    </button>
                </div>

                <!--Div que muestra los registros de las personas-->
                <div class="col-lg-4 col-12 p-2">
                    <div class="bg-white rounded-3 p-3 d-flex flex-column h-100 text-center text-lg-start">

                        <!--Div nombre del apartado-->
                        <div
                            class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2 mb-3">

                            <span class="material-symbols-outlined" style="color: #226D00;">
                                history_toggle_off
                            </span>

                            <h5 class="fw-bold mb-0">Registros</h5>

                        </div>

                        <div id="listaRegistros" class="overflow-auto flex-grow-1 d-flex flex-column gap-2" style="max-height: calc(100vh - 200px);">

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>





    <!--================= Inicio zona scirpts =================-->

    <!--Scripts bootstrap-->
    <script src="../../Aparte/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>

    <script>
        const btnNFC = document.getElementById("btnNFC");
        const btnTerminarSesion = document.getElementById("btnTerminarSesion");
        const icono = document.getElementById("iconoNFC");
        const titulo = document.getElementById("tituloNFC");
        const estado = document.getElementById("estadoNFC");
        const listaRegistros = document.getElementById("listaRegistros");

        const aprendices = <?php echo json_encode($aprendices, JSON_UNESCAPED_UNICODE); ?>;
        const registrados = <?php echo json_encode($registrados, JSON_UNESCAPED_UNICODE); ?>;
        const sesionId = <?php echo (int) $sesion['id_sesion']; ?>;
        const sessionData = <?php echo json_encode($sesion, JSON_UNESCAPED_UNICODE); ?>;

        let activo = false;
        let ndef = null;
        let lectorActivo = false;
        let registros = [];

        function actualizarContadores() {
            const presentes = registros.length;
            const ausentes = Math.max(0, aprendices.length - presentes);
            document.querySelectorAll('.fw-semibold')[0].textContent = presentes;
            document.querySelectorAll('.fw-semibold')[1].textContent = ausentes;
        }

        function renderRegistros() {
            if (registros.length === 0) {
                listaRegistros.innerHTML = '<div class="text-secondary">Aún no hay registros.</div>';
                return;
            }

            listaRegistros.innerHTML = registros.map((registro) => `
                <div class="d-flex gap-3 align-items-center">
                    <span class="material-symbols-outlined icono-aprendiz">person</span>
                    <div>
                        <span class="fw-semibold">${registro.nombre}</span>
                        <small class="text-secondary d-block">ID: ${registro.numero_identidad}</small>
                    </div>
                </div>
            `).join('');
        }

        function mostrarMensaje(texto) {
            estado.textContent = texto;
        }

        function normalizarSerial(valor) {
            if (!valor) {
                return '';
            }

            const texto = String(valor).trim();

            if (!texto) {
                return '';
            }

            const sinSeparadores = texto
                .toLowerCase()
                .replace(/[^a-f0-9]/g, '');

            if (sinSeparadores) {
                return sinSeparadores;
            }

            return texto.toLowerCase();
        }

        btnNFC.addEventListener("click", () => {
            activo = !activo;

            if (activo) {
                icono.textContent = "contactless";
                icono.classList.remove("nfc-scan-off");
                icono.classList.add("nfc-scan");
                titulo.textContent = "Acercar la Tarjeta del Aprendiz";
                estado.textContent = "Lector NFC Funcionando";
                activarLector();
            } else {
                icono.textContent = "contactless_off";
                icono.classList.remove("nfc-scan");
                icono.classList.add("nfc-scan-off");
                titulo.textContent = "Activar lector NFC";
                estado.textContent = "Lector NFC desactivado";
                desactivarLector();
            }
        });

        async function activarLector() {
            if (!("NDEFReader" in window)) {
                estado.textContent = "Este dispositivo no soporta NFC.";
                return;
            }

            try {
                ndef = new NDEFReader();
                await ndef.scan();
                lectorActivo = true;
                ndef.onreading = (event) => {
                    if (!lectorActivo) return;
                    const uid = event.serialNumber;
                    mostrarMensaje('Tarjeta detectada');
                    registrar(uid);
                };
                ndef.onreadingerror = () => {
                    if (!lectorActivo) return;
                    mostrarMensaje('No fue posible leer la tarjeta.');
                };
            } catch (error) {
                console.error(error);
                estado.textContent = 'Error al activar el lector.';
            }
        }

        function desactivarLector() {
            lectorActivo = false;
        }

        function registrar(uid) {
            const serialBuscado = normalizarSerial(uid);
            const aprendiz = aprendices.find((item) => normalizarSerial(item.serial_nfc) === serialBuscado);

            if (!aprendiz) {
                mostrarMensaje('Tarjeta no registrada');
                return;
            }

            if (registrados.includes(aprendiz.id_aprendiz)) {
                mostrarMensaje('El estudiante ya registró asistencia.');
                return;
            }

            fetch('../../registrar_asistencia.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_sesion: sesionId, id_aprendiz: aprendiz.id_aprendiz })
            })
            .then((respuesta) => respuesta.json())
            .then((data) => {
                if (data.success) {
                    const registro = {
                        nombre: data.aprendiz.nombre,
                        numero_identidad: data.aprendiz.numero_identidad
                    };
                    registros.push(registro);
                    registrados.push(aprendiz.id_aprendiz);
                    renderRegistros();
                    actualizarContadores();
                    mostrarMensaje('Asistencia registrada');
                } else {
                    mostrarMensaje(data.mensaje || 'No se pudo registrar la asistencia.');
                }
            })
            .catch(() => {
                mostrarMensaje('No se pudo completar el registro.');
            });
        }

        btnTerminarSesion.addEventListener('click', () => {
            fetch('../../terminar_sesion.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_sesion: sesionId })
            })
            .then((respuesta) => respuesta.json())
            .then((data) => {
                if (data.success) {
                    desactivarLector();
                    mostrarMensaje('Sesión finalizada correctamente.');
                    setTimeout(() => {
                        window.location.href = '../../Dashboard/Dashboard.html';
                    }, 1000);
                } else {
                    mostrarMensaje(data.mensaje || 'No se pudo cerrar la sesión.');
                }
            })
            .catch(() => {
                mostrarMensaje('No se pudo cerrar la sesión.');
            });
        });

        if (registrados.length > 0) {
            const registrosIniciales = aprendices.filter((aprendiz) => registrados.includes(aprendiz.id_aprendiz));
            registros = registrosIniciales.map((aprendiz) => ({
                nombre: aprendiz.nombre,
                numero_identidad: aprendiz.numero_identidad
            }));
            renderRegistros();
            actualizarContadores();
        } else {
            renderRegistros();
            actualizarContadores();
        }
    </script>

</body>

</html>