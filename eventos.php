<?php
include 'includes/db_connection.php';
session_start();

// Funciones para manejar eventos
function obtenerEventos($conn) {
    $sql = "SELECT * FROM eventos WHERE activo = 1 ORDER BY fecha_evento";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error al obtener los eventos: " . mysqli_error($conn));
    }
    $eventos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $eventos[] = $row;
    }
    return $eventos;
}

function obtenerEventosPorMes($conn, $mes, $anio) {
    $sql = "SELECT * FROM eventos WHERE MONTH(fecha_evento) = ? AND YEAR(fecha_evento) = ? AND activo = 1 ORDER BY fecha_evento";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ii', $mes, $anio);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $eventos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $eventos[] = $row;
        }
        return $eventos;
    } else {
        die("Error al preparar la consulta: " . mysqli_error($conn));
    }
}

function agregarEvento($conn, $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen) {
    $sql = "INSERT INTO eventos (nombre, descripcion, fecha_evento, hora_evento, lugar, imagen, activo) VALUES (?, ?, ?, ?, ?, ?, 1)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssss', $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen);
        return mysqli_stmt_execute($stmt);
    } else {
        die("Error al preparar la consulta: " . mysqli_error($conn));
    }
}

function editarEvento($conn, $id_evento, $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen) {
    $sql = "UPDATE eventos SET nombre=?, descripcion=?, fecha_evento=?, hora_evento=?, lugar=?, imagen=? WHERE id_evento=?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $id_evento);
        return mysqli_stmt_execute($stmt);
    } else {
        die("Error al preparar la consulta: " . mysqli_error($conn));
    }
}

function obtenerEventoPorId($conn, $id_evento) {
    $sql = "SELECT * FROM eventos WHERE id_evento = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'i', $id_evento);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    } else {
        die("Error al preparar la consulta: " . mysqli_error($conn));
    }
}

// Manejo de solicitudes POST para agregar o editar eventos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $fecha_evento = $_POST['fecha_evento'];
            $hora_evento = $_POST['hora_evento'];
            $lugar = $_POST['lugar'];
            $imagen = $_FILES['imagen']['name'];

            if ($imagen) {
                $imagen_path = 'img_eventos/' . basename($imagen);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
                    // Imagen subida exitosamente
                } else {
                    die("Error al subir la imagen.");
                }
            } else {
                $imagen = ''; // En caso de que no se suba una imagen
            }
            
            date_default_timezone_set('America/Bogota');
            $fecha_digitada = new DateTime("$fecha_evento $hora_evento");
            $fecha_actual = new DateTime();

            if ($fecha_digitada >= $fecha_actual) {
                if (agregarEvento($conn, $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen)) {
                    echo "Evento agregado exitosamente.";
                } else {
                    echo "Error al agregar el evento: " . mysqli_error($conn);
                }
            } else {
                echo "La fecha y hora específicas son menores que la fecha y hora actuales.";
            }
            
        } elseif ($_POST['action'] === 'edit') {
            $id_evento = $_POST['id_evento'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $fecha_evento = $_POST['fecha_evento'];
            $hora_evento = $_POST['hora_evento'];
            $lugar = $_POST['lugar'];
            $imagen = $_FILES['imagen']['name'];

            if ($imagen) {
                $imagen_path = 'img_eventos/' . basename($imagen);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_path)) {
                    // Imagen subida exitosamente
                } else {
                    die("Error al subir la imagen.");
                }
            } else {
                // Si no se sube una imagen nueva, mantener la imagen actual
                $evento_actual = obtenerEventoPorId($conn, $id_evento);
                $imagen = $evento_actual['imagen'];
            }

            if (editarEvento($conn, $id_evento, $nombre, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen)) {
                echo "Evento actualizado exitosamente.";
            } else {
                echo "Error al actualizar el evento: " . mysqli_error($conn);
            }
        }
        mysqli_close($conn);
        exit();
    }
}

// Manejo de solicitudes GET para obtener un evento específico
if (isset($_GET['id_evento'])) {
    $id_evento = intval($_GET['id_evento']);
    $evento = obtenerEventoPorId($conn, $id_evento);
    echo json_encode($evento);
    mysqli_close($conn);
    exit();
}

$fecha_actual = new DateTime();
$mes_actual = isset($_GET['mes']) ? intval($_GET['mes']) : $fecha_actual->format('m');
$anio_actual = isset($_GET['anio']) ? intval($_GET['anio']) : $fecha_actual->format('Y');

// Verificar si los parámetros son válidos
if ($mes_actual < 1 || $mes_actual > 12) {
    $mes_actual = $fecha_actual->format('m');
}
if ($anio_actual < 1900 || $anio_actual > $fecha_actual->format('Y')) {
    $anio_actual = $fecha_actual->format('Y');
}

// Obtener eventos para el mes y año seleccionados
$eventos = obtenerEventosPorMes($conn, $mes_actual, $anio_actual);

$es_admin = isset($_SESSION['id_usuario']) && $_SESSION['id_rol'] == 1;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos</title>
    <link rel="stylesheet" href="eventos.css">
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="imagenes/logosimbolo.png">
    <link rel="stylesheet" href="header.css">
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
<br><br>

<div class="container">
    <h1 class="titulo" >Calendario de Eventos</h1> 
    <?php if ($es_admin): ?>
        <button id="openAddEventModal" class="btn-volver">Agregar Evento</button>
    <?php endif; ?> <br><br> 
    <div class="calendar-nav">
        <button onclick="changeMonth(-1)">Anterior</button>
        <h2><?php echo $fecha_actual->format('l d F Y'); ?></h2>
        <button onclick="changeMonth(1)">Siguiente</button>
    </div>

    <div class="calendar">
    <?php
    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    foreach ($dias as $dia): ?>
        <div class="header"><?php echo $dia; ?></div>
    <?php endforeach; ?>
    
    <?php
    // Código para mostrar días y eventos
    $primer_dia_del_mes = new DateTime("$anio_actual-$mes_actual-01");
    $ultimo_dia_del_mes = new DateTime($primer_dia_del_mes->format('Y-m-t'));
    $j = 0;   
    for ($i = 0; $i < $primer_dia_del_mes->format('N') - 1; $i++) {
        echo '<div class="day empty"></div>';
    }
    for ($dia = 1; $dia <= $ultimo_dia_del_mes->format('d'); $dia++):
        $fecha_evento = sprintf('%04d-%02d-%02d', $anio_actual, $mes_actual, $dia);
        $eventos_del_dia = array_filter($eventos, function($evento) use ($fecha_evento) {
            return $evento['fecha_evento'] === $fecha_evento;
        });
        
        if(count($eventos_del_dia) >= 1){
            $j= $j + 1;
        }
    ?>
        <div class="day">
            <?php echo $dia; ?>
            <?php foreach ($eventos_del_dia as $evento): ?>
                
                <div class="event"  onclick="showEventDetails(<?php echo count($eventos_del_dia) >= 1 ? $evento['id_evento'] : '' ?>)"><?php echo $evento['nombre']; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endfor; ?>
    </div>
</div>

<!-- Modal para agregar evento -->
<div id="addEventModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Agregar Evento</h2>
        <form id="addEventForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
            <label for="fecha_evento">Fecha:</label>
            <input type="date" id="fecha_evento" name="fecha_evento" required>
            <label for="hora_evento">Hora:</label>
            <input type="time" id="hora_evento" name="hora_evento" required>
            <label for="lugar">Lugar:</label>
            <input type="text" id="lugar" name="lugar" required>
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen">
            <button type="submit">Guardar</button>
        </form>
    </div>
</div>

<!-- Modal para editar evento -->
<div id="editEventModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Editar Evento</h2>
        <form id="editEventForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_id_evento" name="id_evento">
            <label for="edit_nombre">Nombre:</label>
            <input type="text" id="edit_nombre" name="nombre" required>
            <label for="edit_descripcion">Descripción:</label>
            <textarea id="edit_descripcion" name="descripcion" required></textarea>
            <label for="edit_fecha_evento">Fecha:</label>
            <input type="date" id="edit_fecha_evento" name="fecha_evento" required>
            <label for="edit_hora_evento">Hora:</label>
            <input type="time" id="edit_hora_evento" name="hora_evento" required>
            <label for="edit_lugar">Lugar:</label>
            <input type="text" id="edit_lugar" name="lugar" required>
            <label for="edit_imagen">Imagen:</label>
            <input type="file" id="edit_imagen" name="imagen">
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</div>

<!-- Modal para ver detalles del evento -->
<div id="eventDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="eventDetailsContent"></div>
        <?php if ($es_admin): ?>
            <button id="editEventButton" style="display: none;">Editar Evento</button>
        <?php endif; ?>
    </div>
</div>

<script src="eventos.js"></script>
<script src="header.js"></script>

</body>
</html>
