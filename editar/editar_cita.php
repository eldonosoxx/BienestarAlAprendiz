<?php

require_once "../includes/db_connection.php";

function obtenerCitaPorId($conn, $id_cita) {
    $sql = "SELECT * FROM citas WHERE id_cita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_cita);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$mensaje_cita = '';
$error_cita = '';
$cita = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_cita'])) {
    $id_cita = $_POST['id_cita'];
    $primer_nombre = $_POST['primer_nombre'] ?? '';
    $segundo_nombre = $_POST['segundo_nombre'] ?? '';
    $primer_apellido = $_POST['primer_apellido'] ?? '';
    $segundo_apellido = $_POST['segundo_apellido'] ?? '';
    $tipo_documento = $_POST['tipo_documento'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $num_ficha = $_POST['num_ficha'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $estado_cita = $_POST['estado_cita'] ?? '';

    // Validar la fecha
    if (strtotime($fecha) < strtotime(date('Y-m-d'))) {
        $error_cita = "La fecha de la cita debe ser futura.";
    } else {
        $sql = "UPDATE citas SET 
                    primer_nombre = ?, 
                    segundo_nombre = ?, 
                    primer_apellido = ?, 
                    segundo_apellido = ?, 
                    tipo_documento = ?, 
                    numero_documento = ?, 
                    correo = ?, 
                    telefono = ?, 
                    num_ficha = ?, 
                    asunto = ?, 
                    fecha = ?, 
                    hora = ?, 
                    estado_cita = ? 
                WHERE id_cita = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssssssssssssi', 
            $primer_nombre, 
            $segundo_nombre, 
            $primer_apellido, 
            $segundo_apellido, 
            $tipo_documento, 
            $numero_documento, 
            $correo, 
            $telefono, 
            $num_ficha, 
            $asunto, 
            $fecha, 
            $hora, 
            $estado_cita, 
            $id_cita
        );

        if ($stmt->execute()) {
            $mensaje_cita = "La cita se actualizó correctamente.";
        } else {
            $error_cita = "Hubo un problema al actualizar la cita. Inténtalo de nuevo.";
        }
    }
}

$id_cita = $_POST['id_cita'] ?? '';

if (!empty($id_cita)) {
    $cita = obtenerCitaPorId($conn, $id_cita);

    if (!$cita) {
        exit('Cita no encontrada.');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="editar_cita.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cita</h1>

        <?php if (!empty($mensaje_cita)) : ?>
            <div class="mensaje success"><?php echo $mensaje_cita; ?></div>
        <?php elseif (!empty($error_cita)) : ?>
            <div class="mensaje error"><?php echo $error_cita; ?></div>
        <?php endif; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($cita['id_cita']); ?>">
            <label for="primer_nombre">Primer Nombre:</label>
            <input type="text" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars($cita['primer_nombre']); ?>" required>
            <br><br>
            <label for="segundo_nombre">Segundo Nombre:</label>
            <input type="text" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($cita['segundo_nombre']); ?>">
            <br><br>
            <label for="primer_apellido">Primer Apellido:</label>
            <input type="text" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($cita['primer_apellido']); ?>" required>
            <br><br>
            <label for="segundo_apellido">Segundo Apellido:</label>
            <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($cita['segundo_apellido']); ?>">
            <br><br>
            <label for="tipo_documento">Tipo de Documento:</label>
            <select id="tipo_documento" name="tipo_documento" required>
                <option value="cedula" <?php if ($cita['tipo_documento'] === 'cedula') echo 'selected'; ?>>Cédula</option>
                <option value="tarjeta de identidad" <?php if ($cita['tipo_documento'] === 'tarjeta de identidad') echo 'selected'; ?>>Tarjeta de Identidad</option>
            </select>
            <br><br>
            <label for="numero_documento">Número de Documento:</label>
            <input type="number" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($cita['numero_documento']); ?>" required>
            <br><br>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($cita['correo']); ?>" required>
            <br><br>
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cita['telefono']); ?>" required>
            <br><br>
            <label for="num_ficha">Número de Ficha:</label>
            <input type="number" id="num_ficha" name="num_ficha" value="<?php echo htmlspecialchars($cita['num_ficha']); ?>" required>
            <br><br>
            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" value="<?php echo htmlspecialchars($cita['asunto']); ?>" required>
            <br><br>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($cita['fecha']); ?>" required>
            <br><br>
            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" value="<?php echo htmlspecialchars($cita['hora']); ?>" required>
            <br><br>
            <label for="estado_cita">Estado de la Cita:</label>
            <select id="estado_cita" name="estado_cita" required>
                <option value="pendiente" <?php if ($cita['estado_cita'] === 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="finalizada" <?php if ($cita['estado_cita'] === 'finalizada') echo 'selected'; ?>>Finalizada</option>
                <option value="en proceso" <?php if ($cita['estado_cita'] === 'en proceso') echo 'selected'; ?>>En Proceso</option>
            </select>
            <br><br>
            <input type="submit" name="actualizar_cita" value="Actualizar Cita"> 
            <a href="../admin/admin.php" class="button">Volver Atrás</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
