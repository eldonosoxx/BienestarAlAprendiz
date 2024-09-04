<?php
session_start();
require_once "includes/db_connection.php";

$isLoggedIn = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == true;

// Función para obtener los datos de los apoyos
function obtenerApoyos($conn, $rol_usuario) {
    if ($rol_usuario == 1) {
        $sql = "SELECT * FROM apoyos ORDER BY id_apoyos";
    } else {
        $sql = "SELECT * FROM apoyos WHERE estado = 1 ORDER BY id_apoyos";
    }
    $result = $conn->query($sql);
    return $result;
}

// Función para habilitar o deshabilitar un apoyo
function actualizarEstadoApoyo($conn, $id_apoyo, $estado) {
    $stmt = $conn->prepare("UPDATE apoyos SET estado = ? WHERE id_apoyos = ?");
    $stmt->bind_param("ii", $estado, $id_apoyo);
    return $stmt->execute();
}

// Función para agregar una solicitud de apoyo
function agregarFormulario($conn, $nombre_apoyo, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $formato_apoyo, $copia_documento, $sisben, $copia_recibo, $soportes) {
    $stmt = $conn->prepare("INSERT INTO formulario_apoyo (nombre_apoyo, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, tipo_documento, numero_documento, correo, telefono, num_ficha, formato_apoyo, copia_documento, sisben, copia_recibo, soportes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssss", $nombre_apoyo, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $formato_apoyo, $copia_documento, $sisben, $copia_recibo, $soportes);
    return $stmt->execute();
}

// Función para actualizar un apoyo
function actualizarApoyo($conn, $id_apoyo, $nombre, $descripcion, $imagen) {
    $stmt = $conn->prepare("UPDATE apoyos SET nombre = ?, descripcion = ?, imagen = ? WHERE id_apoyos = ?");
    $stmt->bind_param("sssi", $nombre, $descripcion, $imagen, $id_apoyo);
    return $stmt->execute();
}

// Procesar cambios en el estado de los apoyos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_estado'])) {
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_rol'] == 1) {
            $id_apoyo = $_POST['id_apoyo'];
            $estado = $_POST['estado'];
            if (actualizarEstadoApoyo($conn, $id_apoyo, $estado)) {
                $_SESSION['mensaje_evento'] = "El estado del apoyo ha sido actualizado correctamente.";
            } else {
                $_SESSION['error_evento'] = "Hubo un problema al actualizar el estado del apoyo. Inténtalo de nuevo.";
            }
        } else {
            $_SESSION['error_evento'] = "No tienes permisos para realizar esta acción.";
        }
         // Redirigir para evitar el reenvío del formulario
         header("Location: apoyo_sostenible.php");
         exit();
    } elseif (isset($_POST['enviar_formulario'])) {
        $nombre_apoyo = $_POST['nombre_apoyo'];
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $num_ficha = $_POST['num_ficha'];
    
    // Nombre para la carpeta del usuario
    $nombre_carpeta = $nombre_apoyo . '_' . $primer_nombre . '_' . $primer_apellido;
    $uploadDir = 'apoyo/doc_apoyos/' . $nombre_carpeta . '/';

    // Crear la carpeta si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Subir archivos
    $formato_apoyo = $_FILES['formato_apoyo']['name'] ? $_FILES['formato_apoyo']['name'] : '';
    $copia_documento = $_FILES['copia_documento']['name'] ? $_FILES['copia_documento']['name'] : '';
    $sisben = $_FILES['sisben']['name'] ? $_FILES['sisben']['name'] : '';
    $copia_recibo = $_FILES['copia_recibo']['name'] ? $_FILES['copia_recibo']['name'] : '';
    $soportes = $_FILES['soportes']['name'] ? $_FILES['soportes']['name'] : '';

    // Mover los archivos a la carpeta de destino
    if ($formato_apoyo) {
        move_uploaded_file($_FILES['formato_apoyo']['tmp_name'], $uploadDir . $formato_apoyo);
    }
    if ($copia_documento) {
        move_uploaded_file($_FILES['copia_documento']['tmp_name'], $uploadDir . $copia_documento);
    }
    if ($sisben) {
        move_uploaded_file($_FILES['sisben']['tmp_name'], $uploadDir . $sisben);
    }
    if ($copia_recibo) {
        move_uploaded_file($_FILES['copia_recibo']['tmp_name'], $uploadDir . $copia_recibo);
    }
    if ($soportes) {
        move_uploaded_file($_FILES['soportes']['tmp_name'], $uploadDir . $soportes);
    }

    // Agregar el formulario a la base de datos
    if (agregarFormulario($conn, $nombre_apoyo, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido, $tipo_documento, $numero_documento, $correo, $telefono, $num_ficha, $formato_apoyo, $copia_documento, $sisben, $copia_recibo, $soportes)) {
        $_SESSION['mensaje_evento'] = "Tu solicitud ha sido enviada correctamente.";
    } else {
        $_SESSION['error_evento'] = "Hubo un problema al enviar la solicitud. Inténtalo de nuevo.";
    }

    // Redirigir para evitar el reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();

    } elseif (isset($_POST['actualizar_apoyo'])) {
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_rol'] == 1) {
            $id_apoyo = $_POST['id_apoyo'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            
            // Manejo del archivo
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'apoyo/img_apoyos/';
                
                // Asegúrate de que el directorio existe
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileTmpPath = $_FILES['imagen']['tmp_name'];
                $fileName = $_FILES['imagen']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Definir extensiones permitidas
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExts)) {
                    // Crear un nombre único para evitar colisiones
                    $newFileName = $id_apoyo . '.' . $fileExtension;
                    $destPath = $uploadDir . $newFileName;

                    // Mover archivo del directorio temporal al directorio destino
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $imagen = $newFileName;
                    } else {
                        $_SESSION['error_evento'] = "Hubo un problema al mover el archivo al directorio de destino.";
                        header("Location: apoyo_sostenible.php");
                        exit();
                    }
                } else {
                    $_SESSION['error_evento'] = "Tipo de archivo no permitido. Solo se permiten imágenes.";
                    header("Location: apoyo_sostenible.php");
                    exit();
                }
            }

            if (actualizarApoyo($conn, $id_apoyo, $nombre, $descripcion, $imagen)) {
                $_SESSION['mensaje_evento'] = "El apoyo ha sido actualizado correctamente.";
            } else {
                $_SESSION['error_evento'] = "Hubo un problema al actualizar el apoyo. Inténtalo de nuevo.";
            }
        } else {
            $_SESSION['error_evento'] = "No tienes permisos para realizar esta acción.";
        }

        // Redirigir para evitar el reenvío del formulario
        header("Location: apoyo_sostenible.php");
        exit();
    }
}

// Obtener los datos de los apoyos
$rol_usuario = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 2;
$apoyos = obtenerApoyos($conn, $rol_usuario);


function obtener_user($conn) {
    // Iniciar la sesión si no está activa
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Verificar si el ID del usuario está en la sesión
    if (empty($_SESSION['id_usuario'])) {
        // No hay usuario logueado, devolver null o manejar de otro modo
        return null;
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

// Recuperar información del usuario
$usuario = obtener_user($conn);


// vericiacion si hay citas

function obtenerCitas($conn) {
    $sql = "SELECT correo FROM formulario_apoyo";
    $result = $conn->query($sql);
    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row['correo'];
        }
    }
    return $citas;
}

$citas = obtenerCitas($conn);

//////////////////////////////////////////

if (isset($_SESSION['correo'])){

    // Obtén el correo del usuario de la sesión
    $correoUsuario = $_SESSION['correo'];
    
    // Función para obtener el estado de todas las citas de apoyo de un usuario
    function obtenerEstadosApoyo($conn, $correo) {
        $sql = "SELECT activo FROM formulario_apoyo WHERE correo = '$correo'";
        $result = $conn->query($sql);
        $estados = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estados[] = $row['activo'];
            }
        }
        return $estados;
    }    
    
    // Obtén los estados de las citas de apoyo del usuario
    $estadosApoyo = obtenerEstadosApoyo($conn, $correoUsuario);
    
    // Verifica si el usuario está logueado
    $isLoggedIn = isset($_SESSION['correo']); 

}    

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apoyos Sostenibles</title>
    <link rel="stylesheet" href="apoyo/apoyo.css">
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
    
    <!-- notificacion apoyo editado -->
    <div id="notification" class="notification">
        <span id="notification-message"></span>
        <button id="notification-close" class="notification-close">X</button>
    </div>

    <h2 class="titulo_color">Apoyos Sostenibles</h2>

    <div class="container">
        <p class="descripcion">¡Descubre cómo transformar tu futuro con el respaldo que mereces! <br> En el SENA, te ofrecemos apoyos sostenibles diseñados para potenciar tu aprendizaje y bienestar. Desde recursos educativos hasta asesoría personalizada, estamos comprometidos con tu éxito. <br> ¡Explora nuestras opciones y da el primer paso hacia un futuro brillante y lleno de posibilidades! <br> ¡Tu camino hacia el crecimiento comienza aquí!</p>

        <div class="cards-container">
            <?php if ($apoyos->num_rows > 0) : ?>
                <?php while ($row = $apoyos->fetch_assoc()) : ?>
                    <div class="card">
                        <img src="apoyo/img_apoyos/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($row['descripcion']); ?></p> <br>

                        <?php
                            if ($isLoggedIn) {
                                // Verifica si hay alguna cita activa
                                if (in_array('1', $estadosApoyo)) {
                                    // El usuario ya tiene una cita activa
                                    echo '<h1 style="text-align: center; font-size: 20px; color: #39A900"> <strong style=" font-size: 25px; color: #00324D"> ESTADO APOYO</strong> <br> Ya tienes un apoyo en proceso</h1>';
                                } elseif (in_array('0', $estadosApoyo)) {
                                    // El usuario tiene citas desactivas (puede solicitar otra)
                                    echo '<button class="btn-open-form" data-apoyo-id="' . htmlspecialchars($row['id_apoyos']) . '" data-apoyo-nombre="' . htmlspecialchars($row['nombre']) . '">Solicitar Apoyo</button>';
                                } else {
                                    // El usuario no tiene citas
                                    echo '<button class="btn-open-form" data-apoyo-id="' . htmlspecialchars($row['id_apoyos']) . '" data-apoyo-nombre="' . htmlspecialchars($row['nombre']) . '">Solicitar Apoyo</button>';
                                }
                            } else {
                                echo '<a href="#" id="loginButton" class="validacion" onclick="showLoginAlert()">Formulario Citas</a>';
                                echo '
                                <div id="loginVentana" class="ventana">
                                    <div class="ventana-content">
                                        <span class="close" onclick="hideLoginAlert()">&times;</span>
                                        <h1 class="texto-validacion">Por favor, inicia sesión para acceder.</h1>
                                    </div>
                                </div>
                                ';
                            }
                        ?>

                    
                        <?php
                        if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == true){
                            echo '';
                        }
                        ?>
                        <?php if ($rol_usuario == 1) : ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display: inline;">
                                <input type="hidden" name="id_apoyo" value="<?php echo htmlspecialchars($row['id_apoyos']); ?>">
                                <input type="hidden" name="estado" value="<?php echo $row['estado'] == 1 ? 0 : 1; ?>">
                                <button type="submit" name="actualizar_estado" class="btn-admin">
                                    <?php echo $row['estado'] == 1 ? 'Deshabilitar' : 'Habilitar'; ?>
                                </button>
                            </form>
                            <button class="btn-edit" data-apoyo-id="<?php echo htmlspecialchars($row['id_apoyos']); ?>" data-apoyo-nombre="<?php echo htmlspecialchars($row['nombre']); ?>" data-apoyo-descripcion="<?php echo htmlspecialchars($row['descripcion']); ?>" data-apoyo-imagen="<?php echo htmlspecialchars($row['imagen']); ?>">Editar</button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="no-apoyos">
                    <img src="imagenes/sin_apoyos.png" alt="No hay apoyos disponibles">
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal para solicitar apoyo -->
    <div id="form-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="modal-title">Formulario de Solicitud</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_apoyo" id="id_formulario_apoyo" value="">
                <input type="hidden" name="nombre_apoyo" id="nombre_apoyo" value="">

                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="primer_nombre">Primer Nombre:</label>
                    <input type="hidden" id="primer_nombre" name="primer_nombre" value=" <?php echo htmlspecialchars($usuario['primer_nombre']); ?>" >
                    <input type="text" disabled value=" <?php echo htmlspecialchars($usuario['primer_nombre']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="segundo_nombre">Segundo Nombre:</label>
                    <input type="hidden" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="primer_apellido">Primer Apellido:</label>
                    <input type="hidden" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="segundo_apellido">Segundo Nombre:</label>
                    <input type="hidden" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/identidad.png" alt="Tipo Documento" class="form-icon">
                    <label for="tipo_documento">Tipo de Documento:</label>
                    <input type="hidden" id="tipo_documento" name="tipo_documento" value="<?php echo htmlspecialchars($usuario['tipo_documento']); ?>" >
                    <input type="text" disabled value="<?php echo htmlspecialchars($usuario['tipo_documento']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="numero_documento">Numero De Documento:</label>
                    <input type="hidden" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($usuario['numero_documento']); ?>" >
                    <input type="number" disabled value="<?php echo htmlspecialchars($usuario['numero_documento']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="correo">Correo:</label>
                    <input type="hidden" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" >
                    <input type="email" disabled value="<?php echo htmlspecialchars($usuario['correo']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="telefono">Telefono:</label>
                    <input type="hidden" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" >
                    <input type="tel" disabled value="<?php echo htmlspecialchars($usuario['telefono']); ?>" >
                </div>
                <div class="form-group">
                    <img src="iconos/usuario.png" alt="Nombre" class="form-icon">
                    <label for="num_ficha">Numero De Ficha:</label>
                    <input type="hidden" id="num_ficha" name="num_ficha" value="<?php echo htmlspecialchars($usuario['num_ficha']); ?>" >
                    <input type="number" disabled value="<?php echo htmlspecialchars($usuario['num_ficha']); ?>" >
                </div>
                <label for="formato_apoyo">Formato Apoyo:</label>
                <input type="file" required id="formato_apoyo" name="formato_apoyo" accept="image/*">
                
                <label for="copia_documento">Fotocopia Documento:</label>
                <input type="file" required id="copia_documento" name="copia_documento" accept="image/*">
                
                <label for="sisben">Fotocopia Sisben:</label>
                <input type="file" required  id="sisben" name="sisben" accept="image/*">
                
                <label for="copia_recibo">Fotocopia Recibo Público:</label>
                <input type="file" required  id="copia_recibo" name="copia_recibo" accept="image/*">
                
                <label for="soportes">Soportes:</label>
                <input type="file" required  id="soportes" name="soportes" accept="image/*">

                <button type="submit" name="enviar_formulario">Enviar Solicitud</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar apoyo -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close-button-edit">&times;</span>
            <h2>Editar Apoyo</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_apoyo" id="edit_id_apoyo" value="">
                <label for="edit_nombre">Nombre:</label>
                <input type="text" id="edit_nombre" name="nombre" required>
                <label for="edit_descripcion">Descripción:</label>
                <textarea id="edit_descripcion" name="descripcion" required></textarea>
                <label for="edit_imagen">Imagen:</label>
                <input type="file" id="edit_imagen" name="imagen" accept="image/*">
                <div id="image-preview-container">
                    <img id="image-preview" src="" alt="Imagen Actual" style="max-width: 100px;"/>
                </div>
                <button type="submit" name="actualizar_apoyo">Actualizar Apoyo</button>
            </form>
        </div>
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

    <script src="apoyo/apoyo.js"></script>
    <script src="validacion.js"></script>
    <script src="header.js"></script>

</body>
</html>
