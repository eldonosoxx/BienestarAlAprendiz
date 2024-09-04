<?php
session_start();
require_once "includes/db_connection.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienestar Al Aprendiz</title>
    <link rel="stylesheet" href="index.css">
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
    <br>

   
    <div class="body__Page">      
            <div class="quiz">
                <div class="title-container">
                    <div class="titulo_color">Bienestar Al Aprendiz</div>
                </div>
                <br><br>

                <h2 id="quiz-title">¡Pon a prueba tu conocimiento sobre nuestro bienestar!</h2>
                <div id="quiz-container">
                    <!-- Pregunta 1 -->
                    <div class="quiz-question" data-question="1">
                        <p>¿Cuál es uno de los servicios ofrecidos para el bienestar académico?</p>
                        <button onclick="checkAnswer('services', 'Tutorías')">Tutorías</button>
                        <button onclick="checkAnswer('services', 'Actividades Recreativas')">Actividades Recreativas</button>
                        <button onclick="checkAnswer('services', 'Asesoría Psicológica')">Asesoría Psicológica</button>
                        <button onclick="checkAnswer('services', 'Transporte')">Transporte</button>
                    </div>
        
                    <!-- Pregunta 2 -->
                    <div class="quiz-question" data-question="2" style="display: none;">
                        <p>¿Qué tipo de apoyo ofrecemos para el bienestar psicológico?</p>
                        <button onclick="checkAnswer('psychological', 'Talleres de Manejo del Estrés')">Talleres de Manejo del Estrés</button>
                        <button onclick="checkAnswer('psychological', 'Tutorías')">Tutorías</button>
                        <button onclick="checkAnswer('psychological', 'Actividades Recreativas')">Actividades Recreativas</button>
                        <button onclick="checkAnswer('psychological', 'Transporte')">Transporte</button>
                    </div>
        
                    <!-- Pregunta 3 -->
                    <div class="quiz-question" data-question="3" style="display: none;">
                        <p>¿Qué tipo de actividades organizamos para fomentar la actividad física?</p>
                        <button onclick="checkAnswer('recreational', 'Asesoría Psicológica')">Asesoría Psicológica</button>
                        <button onclick="checkAnswer('recreational', 'Tutorías')">Tutorías</button>
                        <button onclick="checkAnswer('recreational', 'Transporte')">Transporte</button>
                        <button onclick="checkAnswer('recreational', 'Torneos y Eventos Deportivos')">Torneos y Eventos Deportivos</button>
                    </div>
        
                    <!-- Pregunta 4 -->
                    <div class="quiz-question" data-question="4" style="display: none;">
                        <p>¿Qué recurso proporcionamos para el desarrollo académico?</p>
                        <button onclick="checkAnswer('academic', 'Asesoría Psicológica')">Asesoría Psicológica</button>
                        <button onclick="checkAnswer('academic', 'Libros y Materiales de Estudio')">Libros y Materiales de Estudio</button>
                        <button onclick="checkAnswer('academic', 'Actividades Recreativas')">Actividades Recreativas</button>
                        <button onclick="checkAnswer('academic', 'Transporte')">Transporte</button>
                    </div>
        
                    <!-- Resultado -->
                    <div id="quiz-result" class="quiz-result" style="display: none;"></div>
                    <button id="restart-quiz" class="hidden" onclick="restartQuiz()">Reiniciar Quiz</button>
                </div>
            </div>
        
        <div class="container__article">
            <div class="box__article">
                <img src="img_index/pos1.png" alt="Descripción de la Imagen 1" class="article-image">
                <p class="article-text">Proporciona atención médica básica, incluyendo consultas, primeros auxilios y seguimiento de casos para garantizar el bienestar físico de los aprendices.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos2.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Ofrece apoyo psicológico y emocional mediante asesorías, talleres de manejo del estrés y estrategias de intervención en crisis, promoviendo el bienestar mental.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos3.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Ofrece actividades deportivas y recreativas, organizando torneos y eventos para fomentar la actividad física, la salud y el trabajo en equipo.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos4.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Facilita la participación en eventos artísticos y talleres creativos, promoviendo la integración cultural y el desarrollo personal.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos5.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Brinda recursos y asistencia para el desarrollo académico y personal, incluyendo tutorías, orientación y apoyo logístico para mejorar el rendimiento de los estudiantes.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos6.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Asiste con apoyo social y asesoría en problemas personales y comunitarios, facilitando el acceso a recursos y servicios.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos7.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Organiza eventos para celebrar fechas importantes y logros de los aprendices, promoviendo la integración y el reconocimiento.</p>
            </div>
            <div class="box__article">
                <img src="img_index/pos8.png" alt="Descripción de la Imagen 2" class="article-image">
                <p class="article-text">Ofrece acceso a libros y materiales de estudio, brindando apoyo para la investigación y el estudio académico.</p>
            </div>
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
        let currentQuestion = 1;
        const totalQuestions = 4; // Total de preguntas en el quiz
        let score = 0;

        function checkAnswer(questionType, correctAnswer) {
            const selectedAnswer = event.target.textContent;
            const correctAnswers = {
                'services': 'Tutorías',
                'psychological': 'Talleres de Manejo del Estrés',
                'recreational': 'Torneos y Eventos Deportivos',
                'academic': 'Libros y Materiales de Estudio'
            };

            const resultDiv = document.getElementById('quiz-result');

            if (selectedAnswer === correctAnswers[questionType]) {
                score++;
                resultDiv.innerHTML = "¡Correcto!";
                resultDiv.style.color = "green";
                setTimeout(() => {
                    resultDiv.style.display = 'none';
                    showNextQuestion();
                }, 1500); // Mostrar resultado por 1.5 segundos
            } else {
                resultDiv.innerHTML = "Incorrecto. Inténtalo de nuevo.";
                resultDiv.style.color = "red";
            }

            resultDiv.style.display = 'block';
        }

        function showNextQuestion() {
            const current = document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`);
            current.style.display = 'none';

            currentQuestion++;
            if (currentQuestion > totalQuestions) {
                showFinalResult();
            } else {
                const next = document.querySelector(`.quiz-question[data-question="${currentQuestion}"]`);
                next.style.display = 'block';
            }
        }

        function showFinalResult() {
            document.getElementById('quiz-title').style.display = 'none'; // Ocultar el título
            const questions = document.querySelectorAll('.quiz-question');
            questions.forEach(q => q.style.display = 'none'); // Ocultar las preguntas

            const resultDiv = document.getElementById('quiz-result');
            resultDiv.innerHTML = `¡Felicidades! Has completado el quiz con una puntuación de ${score} de ${totalQuestions}. ¡Gracias por participar!`;
            resultDiv.style.color = "black";
            resultDiv.style.display = 'block';

            document.getElementById('restart-quiz').classList.remove('hidden'); // Mostrar botón de reinicio
        }

        function restartQuiz() {
            score = 0;
            currentQuestion = 1;
            document.getElementById('quiz-title').style.display = 'block'; // Mostrar el título
            document.getElementById('restart-quiz').classList.add('hidden'); // Ocultar botón de reinicio

            const resultDiv = document.getElementById('quiz-result');
            resultDiv.style.display = 'none';

            const questions = document.querySelectorAll('.quiz-question');
            questions.forEach(q => q.style.display = 'none'); // Ocultar preguntas

            const firstQuestion = document.querySelector(`.quiz-question[data-question="1"]`);
            firstQuestion.style.display = 'block'; // Mostrar primera pregunta
        }

    </script>

    <script src="header.js"></script>


</body>
</html>
