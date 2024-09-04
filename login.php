<?php
session_start();
include('includes/db_connection.php');

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Iniciar sesión
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $username = mysqli_real_escape_string($conn, $username);
        
        $busqueda = "SELECT activo FROM usuario WHERE correo = '$username'";
        $resultado = mysqli_query($conn, $busqueda);

        if (mysqli_num_rows($resultado) == 1) {
            $row = mysqli_fetch_assoc($resultado);
            if ($row['activo'] == 0) {
                $error = "<p>Cuenta inhabilitada <br>  Contactar con un administrador</p> ";
            } else {
                $query = "SELECT * FROM usuario WHERE correo = '$username'";
                $result = mysqli_query($conn, $query);
                
                if ($result) {
                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        if (password_verify($password, $row['contraseña'])) {
                            $_SESSION['id_usuario'] = $row['id_usuario'];
                            $_SESSION['id_rol'] = $row['id_rol'];
                            $_SESSION['correo'] = $row['correo'];
                            header("Location: index.php");
                            exit;
                        } else {
                            $error = "Contraseña incorrecta.";
                        }
                    } else {
                        $error = "Usuario no encontrado.";
                    }
                } else {
                    $error = "Error en la consulta: " . mysqli_error($conn);
                }
            }
        } else {
            $error = "Usuario no encontrado.";
        }
        
    } elseif (isset($_POST['register'])) {
        // Registro
        $primer_nombre = $_POST['primer_nombre'];
        $segundo_nombre = $_POST['segundo_nombre'];
        $primer_apellido = $_POST['primer_apellido'];
        $segundo_apellido = $_POST['segundo_apellido'];
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];
        $tipo_documento = $_POST['tipo_documento'];
        $numero_documento = $_POST['numero_documento'];
        $telefono = $_POST['telefono'];
        $num_ficha = $_POST['num_ficha'];
        
        $correo = mysqli_real_escape_string($conn, $correo);
        
        $query = "SELECT * FROM usuario WHERE correo = '$correo'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $error = "El correo ya está registrado.";
        } else {
            $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO usuario (id_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, contraseña, id_rol, tipo_documento, numero_documento, telefono, num_ficha)
                    VALUES (NULL, '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$correo', '$hashed_password', '2', '$tipo_documento', '$numero_documento', '$telefono', '$num_ficha')";
    
            if (mysqli_query($conn, $insert_query)) {
                $success = "¡Registro exitoso! Puedes iniciar sesión <a href='login.php'>aquí</a>.";
            } else {
                $error = "Error al registrar el usuario: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/login.css">
    <link rel="icon" href="imagenes/logosimbolo.png">
</head>
<body>
    <div class="container" id="container">
        <!-- Register Form -->
        <div class="form-container sign-up">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h1>Crear Cuenta</h1>

                <!-- Section 1 -->
                <div class="section" id="section-1">
                    <input type="text" name="primer_nombre" placeholder="Primer Nombre" required>
                    <input type="text" name="segundo_nombre" placeholder="Segundo Nombre">
                    <input type="text" name="primer_apellido" placeholder="Primer Apellido" required>
                    <input type="text" name="segundo_apellido" placeholder="Segundo Apellido">
                    <button type="button" class="next-button" onclick="showSection(2)">Siguiente</button>
                </div>

                <!-- Section 2 -->
                <div class="section" id="section-2" style="display: none;">
                    <label for="tipo_documento">Tipo de Documento:</label>
                    <select id="tipo_documento" name="tipo_documento" required>
                        <option value="" disabled selected>Selecciona el tipo de documento</option>
                        <option value="Cédula">Cédula</option>
                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                    </select>
                    <input type="number" name="numero_documento" placeholder="Número de Documento" required>
                    <input type="tel" name="telefono" placeholder="Teléfono" required>
                    <input type="number" name="num_ficha" placeholder="Número Ficha" required>
                    <button type="button" class="prev-button" onclick="showSection(1)">Anterior</button>
                    <button type="button" class="next-button" onclick="showSection(3)">Siguiente</button>
                </div>

                <!-- Section 3 -->
                <div class="section" id="section-3" style="display: none;">
                    <input type="email" name="correo" placeholder="Correo Electrónico" required>
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                    <button type="button" class="prev-button" onclick="showSection(2)">Anterior</button>
                    <button type="submit" name="register">Registrarse</button>
                </div>

                <?php if ($success) { ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php } ?>
                <?php if ($error && !isset($_POST['register'])) { ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php } ?>
            </form>
        </div>

        <!-- Login Form -->
        <div class="form-container sign-in">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h1>Iniciar Sesión</h1>
                <span>utiliza tu correo y contraseña</span>
                <input type="email" name="username" placeholder="Correo Electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <!-- <a href="#">¿Olvidaste tu contraseña?</a> -->
                <button type="submit" name="login">Iniciar Sesión</button>
                <?php if ($error && !isset($_POST['register'])) { ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php } ?>
            </form>
        </div>

        <!-- Toggle Buttons -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Ingresa tus datos personales para usar todas las funciones del sitio</p>
                    <button class="hidden" id="login">Iniciar Sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, Aprendiz!</h1>
                    <p>Regístrate con tus datos personales para usar todas las funciones del sitio</p>
                    <button class="hidden" id="register">Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/login.js"></script>
</body>
</html>
