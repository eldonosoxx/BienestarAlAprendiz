<?php
require_once "../includes/db_connection.php";

// Check if the user ID is provided
if (isset($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);

    // Fetch user details
    $sql_usuario = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
    $result_usuario = $conn->query($sql_usuario);
    $usuario = $result_usuario->fetch_assoc();

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit;
    }
}

// Inicializar variable para el mensaje
$mensaje = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $telefono = $_POST['telefono'];
    $num_ficha = $_POST['num_ficha'];
    $correo = $_POST['correo'];
    $id_rol = $_POST['id_rol'];
    $id_usuario = $_POST['id_usuario'];

    $sql_update = "UPDATE usuario SET 
        primer_nombre = '$primer_nombre',
        segundo_nombre = '$segundo_nombre',
        primer_apellido = '$primer_apellido',
        segundo_apellido = '$segundo_apellido',
        tipo_documento = '$tipo_documento',
        numero_documento = '$numero_documento',
        telefono = '$telefono',
        num_ficha = '$num_ficha',
        correo = '$correo',
        id_rol = '$id_rol'
        WHERE id_usuario = $id_usuario";

    if ($conn->query($sql_update) === TRUE) {
        $mensaje = 'Usuario actualizado con éxito.';
        header('Location: ../admin.php?mensaje=' . urlencode($mensaje));
        exit;
    } else {
        $mensaje = 'Error: ' . $conn->error;
    }
}

// Fetch roles for the dropdown
$sql_roles = "SELECT * FROM rol";
$result_roles = $conn->query($sql_roles);
$roles = $result_roles->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="editar_usuario.css">
</head>
<body>
<div class="container">
    <!-- Mostrar mensaje en la pantalla -->
    <?php if (!empty($mensaje)): ?>
        <div style="color: green; margin-bottom: 20px;">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <h2>Editar Usuario</h2>
    <form action="editar_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
        
        <label for="primer_nombre">Primer Nombre:</label>
        <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars($usuario['primer_nombre']); ?>" required>
        <br><br>

        <label for="segundo_nombre">Segundo Nombre:</label>
        <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($usuario['segundo_nombre']); ?>">
        <br><br>

        <label for="primer_apellido">Primer Apellido:</label>
        <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($usuario['primer_apellido']); ?>" required>
        <br><br>

        <label for="segundo_apellido">Segundo Apellido:</label>
        <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($usuario['segundo_apellido']); ?>">
        <br><br>

        <label for="tipo_documento">Tipo de Documento:</label>
        <select id="tipo_documento" name="tipo_documento" required>
            <option value="cedula" <?php if ($usuario['tipo_documento'] === 'cedula') echo 'selected'; ?>>Cédula</option>
            <option value="tarjeta de identidad" <?php if ($usuario['tipo_documento'] === 'tarjeta de identidad') echo 'selected'; ?>>Tarjeta de Identidad</option>
        </select>
        <br><br>

        <label for="numero_documento">Número Documento:</label>
        <input type="number" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($usuario['numero_documento']); ?>" required>
        <br><br>

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
        <br><br>

        <label for="num_ficha">Número de Ficha:</label>
        <input type="number" id="num_ficha" name="num_ficha" value="<?php echo htmlspecialchars($usuario['num_ficha']); ?>">
        <br><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
        <br><br>

        <label for="id_rol">Rol:</label>
        <select id="id_rol" name="id_rol" required>
            <?php foreach ($roles as $rol): ?>
                <option value="<?php echo htmlspecialchars($rol['id_rol']); ?>" <?php if ($usuario['id_rol'] == $rol['id_rol']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($rol['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" name="actualizar_usuario" value="Actualizar Usuario">
        <a href="../admin/admin.php" class="button">Cancelar</a>
    </form>
</div>
</body>
</html>
