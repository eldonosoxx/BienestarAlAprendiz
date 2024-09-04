<?php
session_start();

require_once "includes/db_connection.php";

// Inicializar variables para el formulario
$primer_nombre = '';
$segundo_nombre = '';
$primer_apellido = '';
$segundo_apellido = '';
$tipo_documento = '';
$numero_documento = '';
$correo = '';
$telefono = '';
$num_ficha = '';
$tipo_cita = '';
$asunto = '';
$fecha = '';
$hora = '';
$estado_cita = '';

function agregarCita($conn, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $tipo_cita, $asunto, $fecha, $hora, $estado_cita) {
    $stmt = $conn->prepare("INSERT INTO citas (primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, tipo_documento, numero_documento, correo, telefono, num_ficha, tipo_cita, asunto, fecha, hora, estado_cita) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $tipo_cita, $asunto, $fecha, $hora, $estado_cita);
    
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error al ejecutar la consulta: " . $stmt->error;
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cita'])) {
    $primer_nombre = htmlspecialchars($_POST['primer_nombre']);
    $segundo_nombre = htmlspecialchars($_POST['segundo_nombre']);
    $primer_apellido = htmlspecialchars($_POST['primer_apellido']);
    $segundo_apellido = htmlspecialchars($_POST['segundo_apellido']);
    $tipo_documento = htmlspecialchars($_POST['tipo_documento']);
    $numero_documento = htmlspecialchars($_POST['numero_documento']);
    $correo = htmlspecialchars($_POST['correo']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $num_ficha = htmlspecialchars($_POST['num_ficha']);
    $tipo_cita = htmlspecialchars($_POST['tipo_cita']);
    $asunto = htmlspecialchars($_POST['asunto']);
    $fecha = htmlspecialchars($_POST['fecha']);
    $hora = htmlspecialchars($_POST['hora']);
    $estado_cita = "Pendiente"; 
    
    if (!empty($primer_nombre) && !empty($primer_apellido) && !empty($tipo_documento) && !empty($numero_documento) && !empty($correo) && !empty($telefono) && !empty($num_ficha) && !empty($tipo_cita) && !empty($asunto) && !empty($fecha) && !empty($hora)) {
        
        date_default_timezone_set('America/Bogota');

        $fecha_digitada = new DateTime("$fecha $hora");
        $fecha_actual = new DateTime();

        if ($fecha_digitada >= $fecha_actual){
            if (agregarCita($conn, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $tipo_cita, $asunto, $fecha, $hora, $estado_cita)) {
                $primer_nombre = '';
                $segundo_nombre = '';
                $primer_apellido = '';
                $segundo_apellido = '';
                $tipo_documento = '';
                $numero_documento = '';
                $correo = '';
                $telefono = '';
                $num_ficha = '';
                $tipo_cita = '';
                $asunto = '';
                $fecha = '';
                $hora = '';

                $_SESSION['mensaje_evento'] = "La Cita Se Agregó Correctamente";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['error_evento'] = "Hubo un problema al agregar la cita. Inténtalo de nuevo.";
            }
        } else {
            $_SESSION['error_evento'] = "La fecha y hora específicas son menores que la fecha y hora actuales";
        }
    } else {
        $_SESSION['error_evento'] = "Todos los campos son obligatorios.";
    }
}
function obtener_user($conn) {
    // Iniciar la sesión si no está activa
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Verificar si el ID del usuario está en la sesión
    if (empty($_SESSION['id_usuario'])) {
        header("Location: index.php");
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];

    // Preparar la consulta SQL
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta SQL: ' . $conn->error);
    }

    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc(); // Obtiene solo una fila

    // Cerrar la declaración
    $stmt->close();

    return $usuario;
}

$usuario = obtener_user($conn);

////////////////////////

// Verifica si la sesión tiene el correo del usuario
if (!isset($_SESSION['correo'])) {
    die('No se ha definido el correo del usuario');
}

// Obtén el correo del usuario de la sesión
$correoUsuario = $_SESSION['correo'];

// Función para obtener todas las citas de un usuario específico
function obtenerCitas($conn, $correo) {
    $sql = "SELECT activo FROM citas WHERE correo = '$correo'";
    $result = $conn->query($sql);
    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row['activo'];
        }
    }
    return $citas;
}

// Obtén las citas del usuario
$citas = obtenerCitas($conn, $correoUsuario);

///////////////////

// Función para obtener la cita activa del usuario logueado
function CitaActivaPorCorreo($conn, $correo) {
    $sql = "SELECT * FROM citas WHERE correo = ? AND activo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row;
        }
    }
    $stmt->close();
    return $citas;
}

// Función para obtener la clase CSS en función del estado
function obtenerClaseEstado($estado) {
    switch (htmlspecialchars($estado)) {
        case "pendiente":
            return "pendiente";
        case "confirmada":
            return "confirmada";
        case "atendida":
            return "atendida";
        default:
            return "desconocido";
    }
}

// Obtener el correo del usuario logueado
$correo = $_SESSION['correo'] ?? '';

// Obtener la cita activa del usuario
$citas = CitaActivaPorCorreo($conn, $correo);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link rel="stylesheet" href="citas.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="header.css">
    <link rel="icon" href="imagenes/logosimbolo.png">
</head>
<body>
<header>
        <nav>
            <div class="logo_container">

                <img class="logo-header" src="imagenes/bienestar.png" alt="Logo">
            </div>
            <ul id="abrir_header" >
                <li><a href="index.php">Inicio</a></li>
                <li><a href="eventos.php">Eventos</a></li>
                <li><a href="apoyo_sostenible.php">Apoyo Sostenible</a></li>
                <li><a href="servicios.php">Servicios</a></li>
                <li><a href="quienes_somos.php">Quiénes Somos</a></li>
                <?php
                if(isset($_SESSION['id_usuario'])) {
                    $rol_usuario = $_SESSION['id_rol'];
                    
                    if($rol_usuario == 1) { echo '<li><a href="admin/admin.php">Panel de Administración</a></li>';}
                    
                    if($rol_usuario == 3) { echo '<li><a href="bienestar/enfermeria.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 4) { echo '<li><a href="bienestar/psicologia.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 5) { echo '<li><a href="bienestar/deportes.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 6) { echo '<li><a href="bienestar/cultura.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 7) { echo '<li><a href="bienestar/apoyos.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 8) { echo '<li><a href="bienestar/emocional.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 9) { echo '<li><a href="bienestar/liderazgo.php">Panel Bienestar</a></li>';} 

                    echo '<li><a href="perfil/perfil.php">Perfil</a></li>';

                    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
                } else {
                    echo '<li><a href="login.php">Iniciar Sesión</a></li>';
                }
                ?>
            </ul>
            <div class="burguer_container" onclick="abrirNav()">
                <img class="burguer"  src="iconos/burguer.png" alt="">               
                </div>
        </nav>

    </header>

    <div class="container">
        <h2 class="titulo" >Agendar Cita</h2>

       <!-- notificacion apoyo editado -->
        <div id="notification" class="notification">
            <span id="notification-message"></span>
            <button id="notification-close" class="notification-close">X</button>
        </div>


        <h3 class="titulo2">Formulario Para Agendar Tu Cita</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <section class="form-section">
                <h4>Datos Personales</h4>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="primer_nombre">Primer Nombre:</label>
                    <input type="hidden" id="primer_nombre" name="primer_nombre"   value="<?php echo htmlspecialchars($usuario['primer_nombre'] ?? $primer_nombre); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['primer_nombre'] ?? $primer_nombre); ?>" >
                </div>
                
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="segundo_nombre">Segundo Nombre:</label>
                    <input type="hidden" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($usuario['segundo_nombre'] ?? $segundo_nombre); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['segundo_nombre'] ?? $segundo_nombre); ?>" >
                </div>
                
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Apellido" class="form-icon">
                    <label for="primer_apellido">Primer Apellido:</label>
                    <input type="hidden" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($usuario['primer_apellido'] ?? $primer_apellido); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['primer_apellido'] ?? $primer_apellido); ?>" >
                </div>
                
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Apellido" class="form-icon">
                    <label for="segundo_apellido">Segundo Apellido:</label>
                    <input type="hidden" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_apellido'] ?? $segundo_apellido); ?>" >
                    <input type="text"disabled value="<?php echo htmlspecialchars($usuario['segundo_apellido'] ?? $segundo_apellido); ?>" >
                </div>
            </section>
            
            <section class="form-section">
                <h4>Información de Contacto</h4>
                <div class="form-group">
                    <img src="iconos/correo.png" alt="Correo" class="form-icon">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="hidden" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo'] ?? $correo); ?>" >
                    <input type="email" disabled value="<?php echo htmlspecialchars($usuario['correo'] ?? $correo); ?>" >
                </div>
                
                <div class="form-group">
                    <img src="iconos/telefono.png" alt="Teléfono" class="form-icon">
                    <label for="telefono">Teléfono:</label>
                    <input type="hidden" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? $telefono); ?>" >
                    <input type="tel" disabled value="<?php echo htmlspecialchars($usuario['telefono'] ?? $telefono); ?>" >
                </div>
            </section>
            
            <section class="form-section">
                <h4>Detalles de la Cita</h4>
                <div class="form-group">
                    <img src="iconos/identidad.png" alt="Tipo Documento" class="form-icon">
                    <label for="tipo_documento">Tipo de Documento:</label>
                    <input type="hidden" id="tipo_documento" name="tipo_documento" value="<?php echo htmlspecialchars($usuario['tipo_documento'] ?? $tipo_documento); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['tipo_documento'] ?? $tipo_documento); ?>" >
                </div>
                
                <div class="form-group">
                    <img src="iconos/identidad.png" alt="Número Documento" class="form-icon">
                    <label for="numero_documento">Número de Documento:</label>
                    <input type="hidden" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($usuario['numero_documento'] ?? $numero_documento); ?>" >
                    <input type="number" disabled value="<?php echo htmlspecialchars($usuario['numero_documento'] ?? $numero_documento); ?>" >
                    </div>
                
                <div class="form-group">
                    <img src="iconos/ficha.png" alt="Número Ficha" class="form-icon">
                    <label for="num_ficha">Número De Ficha:</label>
                    <input type="hidden" id="num_ficha" name="num_ficha" value="<?php echo htmlspecialchars($usuario['num_ficha'] ?? $num_ficha); ?>" >
                    <input type="number" disabled value="<?php echo htmlspecialchars($usuario['num_ficha'] ?? $num_ficha); ?>" >
                </div>

                <div class="form-group">
                    <img src="iconos/tipo.png" alt="Tipo Cita" class="form-icon">
                    <label for="tipo_cita">Tipo de Cita:</label>
                    <select id="tipo_cita" name="tipo_cita" required>
                        <option value="Enfermeria">Asesoría Enfermeria</option>
                        <option value="Psicologia">Asesoría Psicológica</option>
                        <option value="Deportes">Asesoría Deportiva</option>  
                        <option value="Cultura">Asesoría Cultural</option>
                        <option value="Apoyos">Apoyo Socioeconómico</option>
                        <option value="Emocional">Asesoría Emocional</option>
                        <option value="Lidezargo">Asesoría Liderazgo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <img src="iconos/asunto.png" alt="Asunto" class="form-icon">
                    <label for="asunto">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" value="<?php echo htmlspecialchars($asunto); ?>" required>
                </div>
                
                <div class="form-group">
                    <img src="iconos/calendario.png" alt="Fecha" class="form-icon">
                    <label for="fecha">Fecha de la Cita:</label>
                    <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
                </div>
                
                <div class="form-group">
                    <img src="iconos/hora.png" alt="Hora" class="form-icon">
                    <label for="hora">Hora de la Cita:</label>
                    <input type="time" id="hora" name="hora" value="<?php echo htmlspecialchars($hora); ?>" required>
                </div>
            </section>

            <?php
                if (isset($_SESSION['id_rol']) && $_SESSION['id_rol']) {
                    if (count($citas) > 0) {
                        // Mostrar la cita activa y su estado
                        foreach ($citas as $cita) {
                            $clase = obtenerClaseEstado($cita['estado_cita']);
                            
                            echo '<div class="cita ' . $clase . '">';
                            echo '<h1 style="text-align: center; font-size: 25px; color: #39A900;"><strong style=" font-size: 30px; color: #00324D;"> ESTADO CITA</strong> <br>' . htmlspecialchars($cita['estado_cita']) . '</h1>';
                            echo '</div>';
                        }
                    } else {
                        // El usuario no tiene ninguna cita activa
                        echo '<input type="submit" name="agregar_cita" value="Agregar Cita" class="btn-agregar">';
                    }
                }
            ?>
            
        </form>

        <br><br><br>

    </div>
    

    <footer>
        <div class="sector-empleo">
            <div class="sector-empleo__text">
                <img loading="lazy" src="img_footer/trabajo-icon.svg" alt="icono-trabajo">
                <h3>Sector Empleo</h3>
            </div>
        </div>
        <div class="entidades">
            <a target="_blank" href="https://www.mintrabajo.gov.co/web/guest/inicio" class="entidades__link--ministerio" ><img loading="lazy" src="img_footer/ministerio-logo-color.svg" alt="ministerio logo" class="entidades__link-img " ></a>
            <a target="_blank" href="https://www.unidadsolidaria.gov.co/"><img loading="lazy" src="img_footer/iOss-logo-color.svg" alt="oss logo" class="entidades__link-img "></a>
            <a target="_blank" href="https://www.serviciodeempleo.gov.co/portada" class="entidades__link" ><img loading="lazy" src="img_footer/iEmpleo-logo-color.svg" alt="empleo logo" class="entidades__link-img "></a>
            <a target="_blank" href="https://www.ssf.gov.co/" class="entidades__link" ><img loading="lazy" src="img_footer/iSuperSubsidio-logo-color.svg" alt="supersubsidio logo" class="entidades__link-img "></a>
            <a target="_blank" href="https://www.colpensiones.gov.co/" class="entidades__link" ><img loading="lazy" src="img_footer/iColpensiones-logo-color.svg" alt="solpensiones logo" class="entidades__link-img "></a>
        </div>
        <div class="gobierno">
            <div class="gobierno__container">
    
                <div class="gobierno__ministerio-container gobierno__ministerio-container--img">
                    <img loading="lazy" src="img_footer/logoGovCol-logo.svg" alt="logogovcol logo" class="gobierno__img">
                </div>
                <div class="gobierno__ministerio-container">
                    <a href="https://petro.presidencia.gov.co" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #c61720;"></span>
                        Presidencia</a>
                    <a href="https://www.minjusticia.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #01630c;"></span>
                        MinJusticia</a>
                    <a href="https://www.mininterior.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #3e6300;"></span>
                        MinInterior</a>
                </div>
                <div class="gobierno__ministerio-container">
                    <a href="https://www.mintic.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #990001;"></span> MinTic</a>
                    <a href="https://www.minsalud.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #410e99;"></span> MinSalud</a>
                    <a href="https://www.mincultura.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #38170c;"></span>
                        MinCultura</a>
                </div>
                <div class="gobierno__ministerio-container">
                    <a href="https://www.minminas.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #151f99;"></span> MinMinas</a>
                    <a href="https://www.mindefensa.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #531400;"></span>
                        MinDefensa</a>
                    <a href="https://www.mineducacion.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #531400;"></span>
                        MinEducación</a>
                </div>
    
                <div class="gobierno__ministerio-container">
                    <a href="https://www.mintrabajo.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #0e3e99;"></span>
                        MinTrabajo</a>
                    <a href="https://www.mintransporte.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #5c8301;"></span>
                        MinTransporte</a>
                    <a href="https://www.urnadecristal.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #2b1399;"></span> Urna de
                        Cristal</a>
                </div>
                <div class="gobierno__ministerio-container">
                    <a href="https://www.minhacienda.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #996201;"></span>
                        MinHacienda</a>
                    <a href="https://www.mincit.gov.co/inicio" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #1e7373;"></span>
                        MinComercio</a>
                    <a href="https://www.minvivienda.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #992900;"></span>
                        MinVivienda</a>
                </div>
                <div class="gobierno__ministerio-container">
                    <a href="https://www.minagricultura.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #3b9901;"></span>
                        MinAgricultura</a>
                    <a href="https://www.vicepresidencia.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #919191;"></span>
                        Vicepresidencia</a>
                    <a href="https://www.minambiente.gov.co/" class="gobierno__link" target="_blank"><span class="gobierno__ministerios-circle" style="background-color: #990001;"></span>
                        MinAmbiente</a>
                </div>
            </div>
    
        </div>
        </div>
        <div class="more-information">
            <div class="more-information__container">
                <div class="more-information__item more-information__item--text">
                    Servicio Nacional de Aprendizaje SENA - Dirección General<br>
                    Calle 57 No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombia<br>
                    Conmutador Nacional (57 1) 5461500 - Extensiones<br>
                    Atención presencial: lunes a viernes 8:00 a.m. a 5:30 p.m.<br>
                    <a style="color:#04324d;" href="http://www.sena.edu.co/es-co/sena/Paginas/directorio.aspx" target="_blank">Resto del país sedes y horarios</a><br>
                    Atención telefónica: lunes a viernes 7:00 a.m. a 7:00 p.m. - <br>sábados 8:00 a.m. a 1:00 p.m.<br>
                    Atención al ciudadano: Bogotá (57 1) 3430111 - Línea gratuita y resto del país 018000 910270<br>
                    Atención al empresario: Bogotá (57 1) 3430101 - Línea gratuita y resto del país 018000 910682<br>
                    <a href="http://sciudadanos.sena.edu.co/SolicitudIndex.aspx" target="_blank" style="color: #04324d">PQRS</a><br>
                    <a href="http://www.sena.edu.co/es-co/ciudadano/Paginas/chat.aspx" target="_blank" style="color: #04324d">Chat en línea</a><br>
                    Correo notificaciones judiciales: servicioalciudadano@sena.edu.co<br>
                    Todos los derechos 2017 SENA - <a href="http://www.sena.edu.co/es-co/Paginas/politicasCondicionesUso.aspx" target="_blank" style="color: #04324d">Políticas de privacidad y condiciones uso Portal Web SENA</a><br>
                    <a href="http://www.sena.edu.co/es-co/transparencia/Documents/proteccion_datos_personales_sena_2016.pdf" target="_blank" style="color: #04324d">Política de Tratamiento para Protección de Datos
                        Personales</a> - <a href="http://compromiso.sena.edu.co/index.php?text=inicio&amp;id=27" target="_blank" style="color:#04324d"><br>Política de seguridad y privacidad de la
                        información</a>
                </div>
                <div class="more-information__icontec">
                    <div class="more-information__item more-information__item more-information__item--img">
                        <img loading="lazy" class="more-information__icontec-img" src="img_footer/normas-iso-logos.svg" alt="Certificación ISO 9001" title="Certificación ISO 9001">
    
                    </div>
                </div>
    
            </div>
        </div>
    
        <div class="gov" id="final">
            <div class="gov__container">
                <a href="https://www.gov.co/" target="_blank">
                    <img loading="lazy" src="img_footer/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
                </a>
    
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var notification = document.getElementById("notification");
            var notificationMessage = document.getElementById("notification-message");
            var notificationClose = document.getElementById("notification-close");
            
            // Mostrar la notificación
            function showNotification(message, type) {
                notificationMessage.textContent = message;
                notification.className = 'notification ' + type; // Establecer el tipo (success, error)
                notification.style.display = 'block';
                
                // Ocultar la notificación después de 5 segundos
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 5000);
            }

            // Cerrar la notificación al hacer clic en el botón
            notificationClose.addEventListener('click', function() {
                notification.style.display = 'none';
            });

            // Ejemplo de uso
            var mensaje = "<?php echo isset($_SESSION['mensaje_evento']) ? $_SESSION['mensaje_evento'] : ''; ?>";
            var errorMensaje = "<?php echo isset($_SESSION['error_evento']) ? $_SESSION['error_evento'] : ''; ?>";
            
            if (mensaje) {
                showNotification(mensaje, 'success');
                <?php unset($_SESSION['mensaje_evento']); ?>
            }

            if (errorMensaje) {
                showNotification(errorMensaje, 'error');
                <?php unset($_SESSION['error_evento']); ?>
            }
        });
    </script>

<script src="header.js"></script>

</body>
</html>
