


CREATE TABLE `apoyos` (
  `id_apoyos` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_apoyos`)
)


INSERT INTO `apoyos` VALUES (1,'Apoyo Regular','Este apoyo incluye asistencia financiera o recursos continuos para cubrir necesidades básicas durante tu formación, garantizando que puedas concentrarte en tus estudios sin preocupaciones adicionales.','1.png',1),(2,'Apoyo Alimentacion','El SENA ofrece apoyo alimentario esencial, garantizando que los aprendices reciban comidas nutritivas. Esto no solo cubre sus necesidades básicas, sino que también potencia su rendimiento y bienestar, permitiéndoles concentrarse en su formación y alcanzar sus metas.','1.png',1),(3,'Apoyo Transporte','Facilita el traslado a tus centros de formación o prácticas, cubriendo gastos relacionados con el transporte para que puedas asistir regularmente sin inconvenientes.','3.png',0),(4,'Apoyo Monitorias','Ofrece asistencia académica adicional a través de tutorías o mentorías, ayudándote a resolver dudas y mejorar tu desempeño en áreas específicas de tu aprendizaje.','4.png',0),(5,'Apoyo FIC','Dirigido a mejorar tu experiencia educativa con recursos adicionales, como talleres, cursos y materiales complementarios que enriquecen tu formación.','5.png',0);


CREATE TABLE `citas` (
  `id_cita` int NOT NULL AUTO_INCREMENT,
  `primer_nombre` varchar(50) DEFAULT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) DEFAULT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `numero_documento` int DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `num_ficha` int DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `tipo_cita` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `estado_cita` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_cita`)
) 


INSERT INTO `citas` VALUES (1,'Ronald','Stiven','Romero','Donoso','cedula',32222,'ronal@gmail.com','32222',32222,'cita','Psicologica','2024-08-16','10:43:00','confirmada',1),(2,'Ronald','Stiven','Romero','Donoso','cedula',32222,'ronal@gmail.com','32222',32222,'cita','Cultura','2024-08-16','10:43:00','confirmada',1),(8,'Ronald','Stiven','Romero','Donoso','cedula',32222,'ronal@gmail.com','32222',32222,'cita','Psicologica','2024-08-16','10:43:00','confirmada',1),(9,'Ronald','Stiven','Romero','Donoso','tarjeta de identidad',1119886466,'usuario@gmail.com','2',3,'Asesoria','Cultura','2024-09-03','18:18:00','atendida',0),(10,'Ronald','Stiven','Romero','Donoso','tarjeta de identidad',1119886466,'usuario@gmail.com','2',3,'Asesoria','Cultura','2024-09-20','18:19:00','atendida',0),(11,'Ronald','Stiven','Romero','Donoso','tarjeta de identidad',1119886466,'usuario@gmail.com','2',3,'Asesoria','Cultura','2024-09-19','19:22:00','Pendiente',1);


CREATE TABLE `configuracion_formulario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mostrar_apoyo_sostenible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) 


INSERT INTO `configuracion_formulario` VALUES (1,0);


CREATE TABLE `eventos` (
  `id_evento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `fecha_evento` date DEFAULT NULL,
  `hora_evento` time DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_evento`)
) 

INSERT INTO `eventos` VALUES (1,'futbol','aada','2024-09-11','19:44:00','bienestar','pos1.png',1);


CREATE TABLE `formulario_apoyo` (
  `id_formulario_apoyo` int NOT NULL AUTO_INCREMENT,
  `nombre_apoyo` varchar(50) DEFAULT NULL,
  `primer_nombre` varchar(50) DEFAULT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) DEFAULT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `numero_documento` int DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `num_ficha` int DEFAULT NULL,
  `formato_apoyo` varchar(100) DEFAULT NULL,
  `copia_documento` varchar(100) DEFAULT NULL,
  `sisben` varchar(100) DEFAULT NULL,
  `copia_recibo` varchar(100) DEFAULT NULL,
  `soportes` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_formulario_apoyo`)
) 


INSERT INTO `formulario_apoyo` VALUES (1,'Apoyo Alimentación',' Admin','Admin','Admin','Admin','cedula',1119886466,'admin@gmail.com','343434',2,'pos4.png','pos3.png','pos5.png','pos2.png','pos3.png',1),(2,'Apoyo Regular',' Ronald','Stiven','Romero','Stiven','tarjeta de identidad',1119886466,'usuario@gmail.com','2',3,'5.png','6.png','6.png','5.png','5.png',1),(3,'Apoyo Regular',' Ronald','Stiven','Romero','Stiven','tarjeta de identidad',1119886466,'usuario@gmail.com','2',3,'5.png','5.png','5.png','5.png','5.png',0);


-- CREATE TABLE `formulario_evento` (
--   `id_formulario` int NOT NULL AUTO_INCREMENT,
--   `primer_nombre` varchar(50) DEFAULT NULL,
--   `segundo_nombre` varchar(50) DEFAULT NULL,
--   `primer_apellido` varchar(50) DEFAULT NULL,
--   `segundo_apellido` varchar(50) DEFAULT NULL,
--   `correo` varchar(100) DEFAULT NULL,
--   `telefono` varchar(20) DEFAULT NULL,
--   `programa_formacion` varchar(50) DEFAULT NULL,
--   `num_ficha` int DEFAULT NULL,
--   `jornada` varchar(50) DEFAULT NULL,
--   `clasificacion_evento` varchar(50) DEFAULT NULL,
--   PRIMARY KEY (`id_formulario`)
-- ) 


CREATE TABLE `rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) 


INSERT INTO `rol` VALUES (1,'admin'),(2,'usuario'),(3,'Enfermeria'),(4,'Psicologia'),(5,'Deportes'),(6,'Cultura'),(7,'Apoyos'),(8,'Emocional'),(9,'Liderazgo');



CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) NOT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) NOT NULL,
  `tipo_documento` varchar(100) NOT NULL,
  `numero_documento` int NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `num_ficha` int DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_rol` int DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_usuario`)
) 


INSERT INTO `usuario` VALUES (1,'Admin','Admin','Admin','Admin','cedula',1119886466,'3',3,'admin@gmail.com','$2y$10$ZV5ObzfRtbT2K3/AkQjkH.HocCLBDbKvqSeEgS6xJsUGS9hGU1dP6',1,1),(2,'Ronald','Stiven','Romero','Donoso','tarjeta de identidad',1119886466,'2',3,'usuario@gmail.com','$2y$10$59hKK9bk2/XQi9CyjQyKHee/JwQldTzTH0PTxujX9eK2wK71A22FO',2,1),(3,'enfermeria','enfermeria','enfermeria','enfermeria','Cédula',111922,'3222414',3,'enfermeria@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',3,1),(4,'psicologia','psicologia','psicologia','psicologia','Cédula',111922,'3222414',3,'psicologia@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',4,1),(5,'deportes','deportes','deportes','deportes','Cédula',111922,'3222414',3,'deportes@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',5,1),(7,'apoyos','apoyos','apoyos','apoyos','Cédula',111922,'3222414',3,'apoyos@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',7,1),(8,'emocional','emocional','emocional','emocional','Cédula',111922,'3222414',3,'emocional@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',8,1),(9,'liderazgo','liderazgo','liderazgo','liderazgo','Cédula',111922,'3222414',3,'liderazgo@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',9,1),(10,'cultura','cultura','cultura','cultura','Cédula',111922,'3222414',3,'cultura@gmail.com','$2y$10$XQNZSx.aPOF0So7Qrjnv0OOCHv7TH3EjOuXlkIp9wX/m03W1mcr3W',6,1);

