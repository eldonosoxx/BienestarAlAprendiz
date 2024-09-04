<?php
session_start();

require_once "../includes/db_connection.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header('Location: index.php'); 
    exit();
}


// Inicializar variable para el mensaje
$mensaje = '';

// Procesar la modificación o desactivación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $sql_update = ''; // Inicializar la variable

    // Verificar si se está desactivando o activando
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'desactivar') {
            $sql_update = "UPDATE usuario SET activo = 0 WHERE id_usuario = $id_usuario";
            $mensaje = "Usuario desactivado con éxito.";
        } elseif ($_POST['accion'] === 'activar') {
            $sql_update = "UPDATE usuario SET activo = 1 WHERE id_usuario = $id_usuario";
            $mensaje = "Usuario activado con éxito.";
        }
    }

    // Ejecutar la consulta de actualización si se ha definido
    if ($sql_update) {
        if ($conn->query($sql_update) === TRUE) {
            $_SESSION['mensaje'] = $mensaje;
            header('Location: admin.php');
            exit;
        } else {
            $mensaje = 'Error: ' . $conn->error;
        }
    }
}

// Obtener todos los usuarios
$sql_usuarios = "SELECT * FROM usuario";
$result_usuarios = $conn->query($sql_usuarios);

////////////////////////

function obtenerEventos($conn) {
    $sql = "SELECT * FROM eventos";
    $result = $conn->query($sql);
    $eventos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
    }
    return $eventos;
}

function obtenerFormulariosApoyo($conn) {
    $sql = "SELECT * FROM formulario_apoyo";
    $result = $conn->query($sql);
    $formularios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $formularios[] = $row;
        }
    }
    return $formularios;
}

function obtenerCitas($conn) {
    $sql = "SELECT * FROM citas";
    $result = $conn->query($sql);
    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row;
        }
    }
    return $citas;
}

$eventos = obtenerEventos($conn);

$formularios_apoyo = obtenerFormulariosApoyo($conn);

$citas = obtenerCitas($conn);

///////////////////////////////////
// Manejar la acción de activar/desactivar eventos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['id_evento'])) {
    $id_evento = intval($_POST['id_evento']);
    $accion = $_POST['accion'];
    
    $nuevo_estado = ($accion === 'activar') ? 1 : 0;
    
    $sql_cambiar_estado_evento = "UPDATE eventos SET activo = $nuevo_estado WHERE id_evento = $id_evento";
    if ($conn->query($sql_cambiar_estado_evento) === TRUE) {
        $mensaje_evento = "El evento se " . ($nuevo_estado ? 'activó' : 'desactivó') . " correctamente.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $error_evento = "Hubo un problema al cambiar el estado del evento. Inténtalo de nuevo.";
    }
}

// Manejar la acción de activar/desactivar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['id_formulario_apoyo'])) {
    $id_formulario_apoyo = intval($_POST['id_formulario_apoyo']);
    $accion = $_POST['accion'];
    
    $nuevo_estado = ($accion === 'activar') ? 1 : 0;
    
    $sql_cambiar_estado_formulario = "UPDATE formulario_apoyo SET activo = $nuevo_estado WHERE id_formulario_apoyo = $id_formulario_apoyo";
    if ($conn->query($sql_cambiar_estado_formulario) === TRUE) {
        $mensaje_formulario = "El formulario se " . ($nuevo_estado ? 'activó' : 'desactivó') . " correctamente.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $error_formulario = "Hubo un problema al cambiar el estado del formulario. Inténtalo de nuevo.";
    }
}

// Manejar la acción de activar/desactivar citas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && isset($_POST['id_cita'])) {
    $id_cita = intval($_POST['id_cita']);
    $accion = $_POST['accion'];
    
    $nuevo_estado = ($accion === 'activar') ? 1 : 0;
    
    $sql_cambiar_estado_cita = "UPDATE citas SET activo = $nuevo_estado WHERE id_cita = $id_cita";
    if ($conn->query($sql_cambiar_estado_cita) === TRUE) {
        $mensaje_cita = "La cita se " . ($nuevo_estado ? 'activó' : 'desactivó') . " correctamente.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $error_cita = "Hubo un problema al cambiar el estado de la cita. Inténtalo de nuevo.";
    }
}

//////////////////////////////////////

// Función para obtener el estado actual del formulario
function obtenerEstadoFormulario($conn) {
    $sql = "SELECT mostrar_apoyo_sostenible FROM configuracion_formulario WHERE id = 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (bool) $row['mostrar_apoyo_sostenible']; // Convertir a booleano
    } else {
        // Si no hay registros, asumir que el formulario está oculto por defecto
        return false;
    }
}

// Actualizar la configuración del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_apoyo_sostenible'])) {
    $estado_actual = obtenerEstadoFormulario($conn);
    $nuevo_estado = !$estado_actual; // Cambiar el estado actual

    // Actualizar la base de datos
    $sql_update = "UPDATE configuracion_formulario SET mostrar_apoyo_sostenible = " . ($nuevo_estado ? "1" : "0") . " WHERE id = 1";
    if ($conn->query($sql_update) === TRUE) {
        // Éxito al actualizar, redirigir de vuelta a admin.php
        header("Location: admin.php");
        exit();
    } else {
        echo "Error al actualizar la configuración: " . $conn->error;
    }
}

// Obtener el estado actual del formulario
$mostrar_apoyo_sostenible = obtenerEstadoFormulario($conn);

$conn->close();

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="../imagenes/logosimbolo.png">


</head>
<body>
    <div class="dashboard">
    <nav class="sidebar">
    <h2>Panel de Administración</h2>    
    <ul>
        <li><a href="#perfil">Usuarios</a></li>
        <li><a href="#eventos">Eventos</a></li>
        <li><a href="#formularios">Formularios de Apoyo</a></li>
        <li><a href="#citas">Citas</a></li>
        <button class="volver-btn" onclick="location.href='../index.php';">Volver</button>
        </ul>
</nav>


        <div class="main-content">
            <h1>Bienvenido al Panel de Administración</h1>

            <!-- Sección: Perfil -->
            <div id="perfil" class="card section">
                <h2>Modificar Usuarios Registrados</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_usuarios->num_rows > 0): ?>
                                <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $usuario['id_usuario']; ?></td>
                                        <td><?php echo htmlspecialchars(trim($usuario['primer_nombre'] . ' ' . $usuario['primer_apellido'])); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                        <td class="acciones">
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                <input type="hidden" name="accion" value="<?php echo $usuario['activo'] ? 'desactivar' : 'activar'; ?>">
                                                <input type="submit" name="submit" value="<?php echo $usuario['activo'] ? 'Desactivar' : 'Activar'; ?>" class="activar-btn accion-btn">
                                            </form>
                                            <form action="../editar/editar_usuario.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                <button type="submit" name="editar_usuario" class="btn-icon editar-btn">
                                                <img src="../iconos/editar_user.png" alt="Editar">
                                                </button>
                                                </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5">No hay usuarios registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
                <br>

                <?php if (!empty($_SESSION['mensaje'])): ?>
                    <div id="mensaje" style="color: green; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                    </div>
                    <script>
                        // Ocultar el mensaje después de 3 segundos
                        setTimeout(function() {
                            document.getElementById('mensaje').style.display = 'none';
                            // Limpiar el mensaje de la sesión
                            <?php unset($_SESSION['mensaje']); ?>
                        }, 3000);
                    </script>
                <?php endif; ?>

        
            </div>


            <!-- Sección: Eventos -->
            <div id="eventos" class="card section">
                <h2>Lista de Eventos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha del Evento</th>
                            <th>Hora del Evento</th>
                            <th>Lugar</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventos as $evento) : ?>
                            <tr>
                                <td><?php echo $evento['id_evento']; ?></td>
                                <td><?php echo $evento['nombre']; ?></td>
                                <td><?php echo $evento['descripcion']; ?></td>
                                <td><?php echo $evento['fecha_evento']; ?></td>
                                <td><?php echo isset($evento['hora_evento']) ? $evento['hora_evento'] : ''; ?></td>
                                <td><?php echo isset($evento['lugar']) ? $evento['lugar'] : ''; ?></td>
                                <td><?php echo $evento['imagen']; ?></td>
                                <td class="acciones">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_evento" value="<?php echo $evento['id_evento']; ?>">
                                    <input type="hidden" name="accion" value="<?php echo $evento['activo'] ? 'desactivar' : 'activar'; ?>">
                                    <input type="submit" name="submit" value="<?php echo $evento['activo'] ? 'Desactivar' : 'Activar'; ?>" class="activar-btn accion-btn">
                                </form>
                                    <!-- <form action="../editar/editar_evento.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_evento" value="<?php echo $evento['id_evento']; ?>">
                                        <button type="submit" name="editar_evento" class="btn-icon editar-btn">
                                            <img src="../iconos/editar.png" alt="Editar">
                                        </button>
                                        
                                    </form> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sección: Formularios -->
            <div id="formularios" class="card section">
                <h2>Lista de Formularios de Apoyo</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Primer Apellido</th>
                            <th>Segundo Apellido</th>
                            <th>Tipo Documento</th>
                            <th>Numero Documento</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th class="accions" >Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formularios_apoyo as $formulario) : ?>
                            <tr>
                                <td><?php echo $formulario['id_formulario_apoyo']; ?></td>
                                <td><?php echo $formulario['nombre_apoyo']; ?></td>
                                <td><?php echo $formulario['primer_nombre']; ?></td>
                                <td><?php echo $formulario['segundo_nombre']; ?></td>
                                <td><?php echo $formulario['primer_apellido']; ?></td>
                                <td><?php echo $formulario['segundo_apellido']; ?></td>
                                <td><?php echo $formulario['tipo_documento']; ?></td>
                                <td><?php echo $formulario['numero_documento']; ?></td>
                                <td><?php echo $formulario['correo']; ?></td>
                                <td><?php echo $formulario['telefono']; ?></td>
                                <td class="acciones">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_formulario_apoyo" value="<?php echo $formulario['id_formulario_apoyo']; ?>">
                                    <input type="hidden" name="accion" value="<?php echo $formulario['activo'] ? 'desactivar' : 'activar'; ?>">
                                    <input type="submit" name="submit" value="<?php echo $formulario['activo'] ? 'Desactivar' : 'Activar'; ?>" class="activar-btn accion-btn">
                                </form>
                                
                                    <form action="../editar/editar_formulario_apoyo.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_formulario_apoyo" value="<?php echo $formulario['id_formulario_apoyo']; ?>">
                                        <button type="submit" name="editar_formulario" class="btn-icon editar-btn">
                                            <img src="../iconos/editar.png" alt="Editar">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sección: Citas -->
            <div id="citas" class="card2 section">
                <h2>Lista de Citas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Primer Nombre</th>
                            <th>Segundo Nombre</th>
                            <th>Primer Apellido</th>
                            <th>Segundo Apellido</th>
                            <th>Tipo Documento</th>
                            <th>Num Documento</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Num Ficha</th>
                            <th>Asunto</th>
                            <th>Fecha Cita</th>
                            <th>Hora Cita</th>
                            <th>Estado Cita</th>
                            <th class="accion2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cita['id_cita']); ?></td>
                                <td><?php echo htmlspecialchars($cita['primer_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['segundo_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cita['primer_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($cita['segundo_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($cita['tipo_documento']); ?></td>
                                <td><?php echo htmlspecialchars($cita['numero_documento']); ?></td>
                                <td><?php echo htmlspecialchars($cita['correo']); ?></td>
                                <td><?php echo htmlspecialchars($cita['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($cita['num_ficha']); ?></td>
                                <td><?php echo htmlspecialchars($cita['asunto']); ?></td>
                                <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado_cita']); ?></td>
                                <td class="acciones">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
                                    <input type="hidden" name="accion" value="<?php echo $cita['activo'] ? 'desactivar' : 'activar'; ?>">
                                    <input type="submit" name="submit" value="<?php echo $cita['activo'] ? 'Desactivar' : 'Activar'; ?>" class="activar-btn accion-btn">
                                </form>
                                    <form action="../editar/editar_cita.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($cita['id_cita']); ?>">
                                        <button type="submit" name="editar_cita" class="btn-icon editar-btn">
                                            <img src="../iconos/editar.png" alt="Editar">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    const links = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.section');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));

            sections.forEach(section => {
                section.style.display = 'none';
            });
            target.style.display = 'block';

            links.forEach(link => {
                link.classList.remove('active');
            });
            link.classList.add('active');
        });
    });

    // Mostrar la primera sección por defecto
    sections.forEach(section => {
        section.style.display = 'none';
    });
    sections[0].style.display = 'block';
</script>

</body>
</html>
