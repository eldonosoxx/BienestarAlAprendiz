<?php
session_start();
require_once "../includes/db_connection.php";

$id_usuario = $_SESSION['id_usuario'];

// Verifica si el usuario está logueado
if (!isset($id_usuario)) {
    die("No está autorizado");
}

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $primer_nombre = mysqli_real_escape_string($conn, $_POST['primer_nombre']);
    $segundo_nombre = mysqli_real_escape_string($conn, $_POST['segundo_nombre']);
    $primer_apellido = mysqli_real_escape_string($conn, $_POST['primer_apellido']);
    $segundo_apellido = mysqli_real_escape_string($conn, $_POST['segundo_apellido']);
    $tipo_documento = mysqli_real_escape_string($conn, $_POST['tipo_documento']);
    $numero_documento = mysqli_real_escape_string($conn, $_POST['numero_documento']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $num_ficha = mysqli_real_escape_string($conn, $_POST['num_ficha']);

    // Actualizar los datos del usuario en la base de datos
    $update_sql = "UPDATE usuario SET 
                    primer_nombre='$primer_nombre',
                    segundo_nombre='$segundo_nombre',
                    primer_apellido='$primer_apellido',
                    segundo_apellido='$segundo_apellido',
                    tipo_documento='$tipo_documento',
                    numero_documento='$numero_documento',
                    telefono='$telefono',
                    correo='$correo',
                    num_ficha='$num_ficha'
                    WHERE id_usuario='$id_usuario'";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Datos actualizados correctamente.";
        $message_class = "success";
    } else {
        $message = "Error al actualizar los datos: " . mysqli_error($conn);
        $message_class = "error";
    }
}

// Preparar y ejecutar la consulta para mostrar los datos del usuario
$sql = "SELECT * FROM usuario WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);
mysqli_close($conn); // Cierra la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="../header.css">
    <link rel="icon" href="../imagenes/logosimbolo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
        <nav>
            <div class="logo_container">
                
                <img class="logo-header" src="../imagenes/bienestar.png" alt="Logo">
            </div>
            <ul id="abrir_header" >
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../eventos.php">Eventos</a></li>
                <li><a href="../apoyo_sostenible.php">Apoyo Sostenible</a></li>
                <li><a href="../servicios.php">Servicios</a></li>
                <li><a href="../quienes_somos.php">Quiénes Somos</a></li>
                <?php
                if(isset($_SESSION['id_usuario'])) {
                    $rol_usuario = $_SESSION['id_rol'];
                    
                    if($rol_usuario == 1) { echo '<li><a href="../admin/admin.php">Panel de Administración</a></li>';}
                    
                    if($rol_usuario == 3) { echo '<li><a href="../bienestar/enfermeria.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 4) { echo '<li><a href="../bienestar/psicologia.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 5) { echo '<li><a href="../bienestar/deportes.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 6) { echo '<li><a href="../bienestar/cultura.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 7) { echo '<li><a href="../bienestar/apoyos.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 8) { echo '<li><a href="../bienestar/emocional.php">Panel Bienestar</a></li>';} 

                    if($rol_usuario == 9) { echo '<li><a href="../bienestar/liderazgo.php">Panel Bienestar</a></li>';} 
                    
                    echo '<li><a href="../perfil/perfil.php">Perfil</a></li>';

                    echo '<li><a href="../logout.php">Cerrar Sesión</a></li>';
                } else {
                    echo '<li><a href="../login.php">Iniciar Sesión</a></li>';
                }
                ?>
                
                
                
            </ul>
            <div class="burguer_container" onclick="abrirNav()">
                <img class="burguer"  src="../iconos/burguer.png" alt="">               
                </div>
            
        </nav>

    </header>

<div class="profile-container">
    <div class="profile-headers">
        <h1>Perfil de Usuario</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($user["primer_nombre"] . $user["primer_apellido"]); ?>!</p>
        <i class="fas fa-edit edit-icon" onclick="toggleEditForm()"></i>
    </div>

    <?php if (isset($message)): ?>
        <div id="message" class="message <?php echo $message_class; ?> fade-out"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="profile-details">
        <div class="profile-detail">
            <h2>Información Personal</h2>
            <p><strong>Primer Nombre:</strong> <?php echo htmlspecialchars($user["primer_nombre"]); ?></p>
            <p><strong>Segundo Nombre:</strong> <?php echo htmlspecialchars($user["segundo_nombre"]); ?></p>
            <p><strong>Primer Apellido:</strong> <?php echo htmlspecialchars($user["primer_apellido"]); ?></p>
            <p><strong>Segundo Apellido:</strong> <?php echo htmlspecialchars($user["segundo_apellido"]); ?></p>
        </div>

        <div class="profile-detail">
            <h2>Documento</h2>
            <p><strong>Tipo de Documento:</strong> <?php echo htmlspecialchars($user["tipo_documento"]); ?></p>
            <p><strong>Número de Documento:</strong> <?php echo htmlspecialchars($user["numero_documento"]); ?></p>
        </div>

        <div class="profile-detail">
            <h2>Contacto</h2>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user["telefono"]); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($user["correo"]); ?></p>
        </div>

        <?php if ($id_usuario == 2): ?>
        <div class="profile-detail">
            <h2>Información Adicional</h2>
            <p><strong>Número de Ficha:</strong> <?php echo htmlspecialchars($user["num_ficha"]); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div id="edit-form-container" class="form-container">
        <h2>Editar Perfil</h2>
        <form id="profile-form" action="perfil.php" method="post">
            <div class="form-group">
                <label for="primer_nombre">Primer Nombre</label>
                <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars($user['primer_nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="segundo_nombre">Segundo Nombre</label>
                <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($user['segundo_nombre']); ?>">
            </div>
            <div class="form-group">
                <label for="primer_apellido">Primer Apellido</label>
                <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($user['primer_apellido']); ?>" required>
            </div>
            <div class="form-group">
                <label for="segundo_apellido">Segundo Apellido</label>
                <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($user['segundo_apellido']); ?>">
            </div>
            <div class="form-group">
                <label for="tipo_documento">Tipo de Documento</label>
                <input type="text" id="tipo_documento" name="tipo_documento" value="<?php echo htmlspecialchars($user['tipo_documento']); ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_documento">Número de Documento</label>
                <input type="text" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($user['numero_documento']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="num_ficha">Número de Ficha</label>
                <input type="text" id="num_ficha" name="num_ficha" value="<?php echo htmlspecialchars($user['num_ficha']); ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="Actualizar Información">
            </div>
        </form>
    </div>
</div>

<script src="perfil.js"></script>
<script src="../header.js"></script>

</body>
</html>
