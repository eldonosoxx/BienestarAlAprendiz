<?php

session_start();

require_once "../includes/db_connection.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 5) {
    header('Location: index.php'); 
    exit();
}



function obtenerCitas($conn) {
    $sql = "SELECT * FROM citas WHERE tipo_cita = 'Deportes' AND activo = 1";
    $result = $conn->query($sql);
    $citas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $citas[] = $row;
        }
    }
    return $citas;
}

$citas = obtenerCitas($conn);


if (isset($_POST['actualizar_estado'])) {
    $id_cita = intval($_POST['id_cita']);
    $nuevo_estado = $_POST['estado_cita'];

    // Validar el nuevo estado
    $estados_validos = ['pendiente', 'confirmada', 'atendida'];
    if (in_array($nuevo_estado, $estados_validos)) {
        // Determinar el valor de 'activo' basado en el nuevo estado
        $activo = ($nuevo_estado == 'atendida') ? 0 : 1;

        // Preparar y ejecutar la actualización
        $stmt = $conn->prepare("UPDATE citas SET estado_cita = ?, activo = ? WHERE id_cita = ?");
        $stmt->bind_param("sii", $nuevo_estado, $activo, $id_cita);

        if ($stmt->execute()) {
            // Redirigir a la misma página para reflejar los cambios
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error al actualizar el estado: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Estado inválido.";
    }

    $conn->close();
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas Deportes</title>
    <link rel="stylesheet" href="mostrar_citas.css">
    <link rel="stylesheet" href="tabla.css">
    <link rel="icon" href="../imagenes/logosimbolo.png">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../imagenes/logosimbolo.png" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">Bienestar</span>
                    <span class="name2">Al Aprendiz</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <ul class="menu-links">
                    <li class="nav-link">
                        <a onclick="citas()">
                            <i class='bx bx-calendar-heart icon' ></i>
                            <span class="text nav-text">Citas</span>
                        </a>
                    </li>

                    <!-- <li class="nav-link">
                        <a onclick="proceso()">
                            <i class='bx bx-calendar-exclamation icon' ></i>
                            <span class="text nav-text">Citas En Proceso</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a onclick="terminadas()">
                            <i class='bx bx-calendar-x icon' ></i>
                            <span class="text nav-text">Citas Terminadas</span>
                        </a>
                    </li>               -->

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="../index.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Volver</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>
        </div>

    </nav>

<section class="home">
        <br><br>

        <div id="citas-inicio">
            <br><br>
            <main class="table" id="customers_table">
                <section class="table__header">
                    <h1  class="text">Formularios Citas</h1>
                    <div class="input-group">
                        <input type="search" placeholder="Search Data...">
                        <img src="img_bienestar/search.png" alt="">
                    </div>
                    <div class="export__file">
                        <label for="export-file" class="export__file-btn" title="Export File"></label>
                        <input type="checkbox" id="export-file">
                        <div class="export__file-options">
                            <label>Export As &nbsp; &#10140;</label>
                            <label for="export-file" id="toPDF">PDF <img src="img_bienestar/pdf.png" alt=""></label>
                            <label for="export-file" id="toJSON">JSON <img src="img_bienestar/json.png" alt=""></label>
                        </div>
                    </div>
                </section>
                <section class="table__body">
                    <table>
                        <thead>
                            <tr>
                                <th> Id <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Primer Nombre <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Segundo Nombre <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Primer Apellido <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Segundo Apellido <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Tipo Documento <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Numero Documento <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Correo <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Telefono <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Numero Ficha <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Asunto <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Tipo Cita <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Fecha <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Hora <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Estado <span class="icon-arrow">&UpArrow;</span></th>
                                <th> Actualizar Estado <span class="icon-arrow">&UpArrow;</span></th>
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
                                    <td><?php echo htmlspecialchars($cita['tipo_cita']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['hora']); ?></td>
                                    <?php

                                        switch(htmlspecialchars($cita['estado_cita'])){
                                            case "pendiente":
                                                $clase = "pendiente";
                                                break;
                                            case "confirmada":
                                                $clase = "confirmada";
                                                break;
                                            case "atendida":
                                                $clase = "atendida";
                                                break;
                                        }
                                    ?>
                                    <td> <p class="status <?= $clase ?>"><?=htmlspecialchars($cita['estado_cita'])?></p> </td>
                                    <td class="estado">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
                                            <select name="estado_cita" class="estado-select">
                                                <option value="pendiente" <?php echo ($cita['estado_cita'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="confirmada" <?php echo ($cita['estado_cita'] == 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                                                <option value="atendida" <?php echo ($cita['estado_cita'] == 'atendida') ? 'selected' : ''; ?>>Atendida</option>
                                            </select>
                                            <input type="submit" name="actualizar_estado" value="Actualizar" class="actualizar-btn">
                                        </form>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>

        <!-- -------------------------------------------- -->
    
        <!-- <div class="ocultar" id="citas-proceso">
            <br><br>
        
        </div> -->

        <!-- ---------------------------- -->

        <!-- <div class="ocultar" id="citas-terminadas"> 
            
            <br><br>
        
        </div> -->

</section>

    <!-- --------------------------------------- -->

<script>
    const body = document.querySelector('body'),
    sidebar = body.querySelector('nav'),
    toggle = body.querySelector(".toggle"),
    modeSwitch = body.querySelector(".toggle-switch"),
    modeText = body.querySelector(".mode-text");


    toggle.addEventListener("click" , () =>{
        sidebar.classList.toggle("close");
    })

    modeSwitch.addEventListener("click" , () =>{
        body.classList.toggle("dark");
        
        if(body.classList.contains("dark")){
            modeText.innerText = "Light mode";
        }else{
            modeText.innerText = "Dark mode";
            
        }
    });

    citasinicio = document.getElementById("citas-inicio")
    // citasproceso = document.getElementById("citas-proceso")
    // citasterminadas = document.getElementById("citas-terminadas")

    

    function citas(){
        // citasproceso.classList.add("ocultar")
        // citasterminadas.classList.add("ocultar")

        
        citasinicio.classList.remove("ocultar")
    }
    // function proceso(){
    //     citasinicio.classList.add("ocultar")
    //     citasterminadas.classList.add("ocultar")

        
    //     citasproceso.classList.remove("ocultar")
    // }
    // function terminadas(){
    //     citasinicio.classList.add("ocultar")
    //     citasproceso.classList.add("ocultar")

        
    //     citasterminadas.classList.remove("ocultar")
    // }


</script>

<script src="tabla.js"></script>


</body>
</html>