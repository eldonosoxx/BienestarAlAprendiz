<?php

session_start();  
        
    include('includes/db_connection.php');

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quienes Somos</title>
    <link rel="stylesheet" href="quienes_somos.css">
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

    <div class="titulo-llamativo"><h1>QUIENES SOMOS</h1></div>
<br>
        <div class="imagen-info">
            <img src="img_qs/equipo_bienestar.jpg" alt="Nuestro Equipo">
        </div>

        <div class="texto-principal">
            <h2>Es una estrategia que contribuye a brindar servicios a los aprendices en formación de los programas técnicos y tecnológicos de las modalidades, presencial, virtual y a distancia con el fin de promover acciones que permitan fortalecer sus competencias y habilidades socioemocionales, deportivas, artísticas, de liderazgo, culturales, brindar información sobre la promoción de la salud y prevención de la enfermedad, ofrecer apoyos socioeconómicos para el mejoramiento de su calidad de vida y la satisfacción de culminar su proceso formativo con éxito.</h2>
        </div>
        <div class="titulo-llamativo">
            <h1>¡Conoce a Nuestro Equipo Estelar!</h1> 
        </div>
    
        <div class="container">
        <div class="arrow left">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
        </div>
        <div class="cards-wrapper">
            <div class="card">
                <img src="img_qs/p1.png" alt="Card 1">
                <h3>Beatriz Montealegre</h3>
                <p>Dinamizadora Bienestar</p>
                <button>Telefono</button>
                <div class="phone-number">3104021404</div>
            </div>
            <div class="card">
                <img src="img_qs/p2.png" alt="Card 2">
                <h3>Andrés Fernando Orozco</h3>
                <p>Apoyos De Sostenimiento</p>
                <button>Telefono</button>
                <div class="phone-number">3115309815</div>
            </div>
            <div class="card">
                <img src="img_qs/p3.png" alt="Card 3">
                <h3>Juan Carlos Santos</h3>
                <p>Capellán</p>
                <button>Telefono</button>
                <div class="phone-number">3128705874</div>
            </div>
            <div class="card">
                <img src="img_qs/p4.png" alt="Card 4">
                <h3>Carolina Ayala</h3>
                <p>Enfermera Jefe</p>
                <button>Telefono</button>
                <div class="phone-number">3192261043</div>
            </div>
            <div class="card">
                <img src="img_qs/p5.png" alt="Card 5">
                <h3>Natalia Arias</h3>
                <p>Auxiliar Enfermería</p>
                <button>Telefono</button>
                <div class="phone-number">3206981182</div>
            </div>
            <div class="card">
                <img src="img_qs/p6.png" alt="Card 6">
                <h3>Laura Carmona</h3>
                <p>Psicóloga</p>
                <button>Telefono</button>
                <div class="phone-number">3013176532</div>
            </div>
            <div class="card">
                <img src="img_qs/p7.png" alt="Card 7">
                <h3>Juliana Gutiérrez</h3>
                <p>Trabajadora Social</p>
                <button>Telefono</button>
                <div class="phone-number">3006512777</div>
            </div>
            <div class="card">
                <img src="img_qs/p8.png" alt="Card 8">
                <h3>Juan Carlos Martínez</h3>
                <p>Profesional Deportes</p>
                <button>Telefono</button>
                <div class="phone-number">3115566251</div>
            </div>
            <div class="card">
                <img src="img_qs/p9.png" alt="Card 9">
                <h3>Roger Sail Marín</h3>
                <p>Profesional Cultura</p>
                <button>Telefono</button>
                <div class="phone-number">3154530095</div>
            </div>
        </div>
        <div class="arrow right">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
            </svg>
        </div>
    
        <script src="qs.js"></script>

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

    <script src="header.js"></script>

</body>
</html>
