<?php

    session_start();  
    
    include('includes/db_connection.php');

    $isLoggedIn = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == true;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="servicios.css">
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

    <h2 class="titulo_color">Cuidamos Tu Bienestar Para Que Tú Puedas Alcanzar Tus Sueños</h2>
    <div class="container_img">
        <div class="imagen_servicios">
            <img class="img_serv" src="img_index/s1.png" alt="imagen">
        </div>
        <div class="imagen_servicios">
            <img class="img_serv" src="img_index/s2.png" alt="imagen">
        </div>
        <div class="imagen_servicios">
            <img class="img_serv" src="img_index/s3.png" alt="imagen">
        </div>
        <div class="imagen_servicios">
            <img class="img_serv" src="img_index/s4.png" alt="imagen">
        </div>
        <div class="imagen_servicios">
            <img class="img_serv" src="img_index/s5.png" alt="imagen">
        </div>
    </div>
    <br>
    <h2 class="titulo_color">Servicios</h2>

    <div class="container">

        <div class="service">
            <div> 
                <h3>Psicología</h3>
                <p>Los servicios de Psicología de Bienestar al Aprendiz en SENA se refieren a los servicios y programas diseñados para apoyar el bienestar emocional y académico de los aprendices. Estos servicios pueden incluir asesoramiento psicológico individual o grupal, talleres para el manejo del estrés, desarrollo de habilidades sociales, y otras intervenciones psicológicas destinadas a mejorar la experiencia y el rendimiento académico de los estudiantes.</p>
            </div>
        </div>

        <div class="service">
            <div>
            <h3>Cultura</h3>
            <p>Bienestar al Aprendiz SENA se compromete no solo con el desarrollo académico, sino también con el bienestar integral de sus estudiantes, ofreciendo una variedad de servicios culturales que enriquecen su experiencia educativa. Estos servicios están diseñados para fomentar el crecimiento personal, la integración social y el desarrollo de habilidades que complementen su formación técnica.</p>
            </div>
        </div>

        <div class="service">
            <div>
            <h3>Prevención de la Enfermedad y Promoción de la Salud</h3>
            <p>En Bienestar al Aprendiz SENA, se promueve la salud y prevención de enfermedades mediante programas de nutrición, actividades físicas, apoyo emocional y educación preventiva. También se facilita el acceso a servicios médicos. Estas iniciativas buscan optimizar el bienestar integral y el rendimiento académico de los estudiantes.</p>
            </div>
        </div>

        <div class="service">
            <div>
            <h3>Desarrollo de Habilidades Blandas</h3>
            <p>En Bienestar al Aprendiz SENA, se impulsa el desarrollo de habilidades blandas esenciales para el crecimiento personal y profesional. Los programas se enfocan en mejorar la comunicación, el trabajo en equipo, la resolución de problemas, la creatividad y la adaptación, fortaleciendo la empleabilidad, el liderazgo positivo y el desarrollo integral de los aprendices.</p>
            </div>
        </div>

        <div class="service">
            <div>
            <h3>Deporte y Actividad Física</h3>
            <p>En Bienestar al Aprendiz SENA, se impulsa el deporte y la actividad física para mejorar la salud integral, el manejo del estrés, la concentración y las habilidades sociales. Los programas deportivos fomentan la integración, el compañerismo y hábitos saludables, apoyando el aprendizaje y crecimiento personal.</p>
            </div>
        </div>

        <div class="service">
            <div>
            <h3>Aprovechamiento del Tiempo Libre y Arte</h3>
            <p>En Bienestar al Aprendiz SENA, se utiliza el arte para promover el desarrollo personal y cultural. Los programas artísticos ofrecen espacios para la creatividad y la expresión, enriqueciendo la experiencia educativa y fomentando la autonomía, el sentido de pertenencia y la valoración de la diversidad cultural. </p>
            </div>
        </div>
    
    </div>

    <section id="events">
            <h2 class="titulo_color2" >Agendar Cita</h2>
            <p>Aquí podrás agendar tu cita.</p> <br>
            <?php
            if ($isLoggedIn) {
                echo '<a href="citas.php" id="loginButton" class="validacion">Formulario Citas</a>';
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
    </section>

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
    
    <script src="validacion.js"></script>
    <script src="header.js"></script>

</body>
</html>
