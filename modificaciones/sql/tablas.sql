drop database app_inta;
create database app_inta;
use app_inta;

create table `agencia`(
	`id` int(11) auto_increment primary key,
	`nombre` varchar(100),
	`id_localidad` int(11),
	`direccion` varchar(255),
	`telefono` varchar(255),
	`email` varchar(255)
);

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL auto_increment,
  `id_entidad` int(11) NOT NULL,
  `tipo_entidad` enum('agencia','audiencia','actividad') NOT NULL,
  `path` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `bm_usuario` (
  `id` int(11) NOT NULL auto_increment,
  `id_agencia` int(11) NOT NULL,
  `activo` tinyint default NULL,
  `username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) collate utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) collate utf8_unicode_ci NOT NULL,
  `email` varchar(150) collate utf8_unicode_ci NOT NULL,
  `ultimo_acceso` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
);

CREATE TABLE `objetivo` (
  `id` int(11) NOT NULL auto_increment,
  `id_agencia` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  `resultado_esperado` text NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `problema` (
  `id` int(11) NOT NULL auto_increment,
  -- `id_agencia` int(11) NOT NULL, -- esto no es necesario porque tanto la audiencia como el objetivo apuntan a la agencia
  `id_objetivo` int(11) NOT NULL,
  `id_audiencia` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  `importancia_economica` decimal(4,2) NOT NULL,
  `impacto_ambiental` decimal(4,2) NOT NULL,
  `importancia_social` decimal(4,2) NOT NULL,
  `familias_implicadas` decimal(4,2) NOT NULL,
  `valor_agregado_potencial` decimal(4,2) NOT NULL,
  `impacto_desarrollo` decimal(4,2) NOT NULL,
  `prioridad` tinyint NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `indicador_objetivo` (
  `id` int(11) NOT NULL auto_increment,
  `id_objetivo` int(11) NOT NULL,
  `id_indicador` int(11) NOT NULL,
  `id_medio_verificacion` int(11) NOT NULL,
  `adecuado` tinyint NOT NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE `indicador` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  `tipo_indicador` enum('resultado','actividad'),
  PRIMARY KEY  (`id`)
);

CREATE TABLE `medio_verificacion` (
  `id` int(11) NOT NULL auto_increment,
  `id_indicador` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE `proyecto` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE `tipo_audiencia` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `audiencia` (
  `id` int(11) NOT NULL auto_increment,
  `id_tipo_audiencia` int(11) NOT NULL,
  `id_agencia` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `actividad` (
  `id` int(11) NOT NULL auto_increment,
  `id_objetivo` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  `ano` varchar(4) collate utf8_unicode_ci NOT NULL,
  `porcentaje_cumplimiento` decimal(5,2) NOT NULL,
  `porcentaje_tiempo` decimal(5,2) NOT NULL,
  `presupuesto_estimado` decimal(11,2) NOT NULL,
  `mes_enero` tinyint NOT NULL,
  `mes_febrero` tinyint NOT NULL,
  `mes_marzo` tinyint NOT NULL,
  `mes_abril` tinyint NOT NULL,
  `mes_mayo` tinyint NOT NULL,
  `mes_junio` tinyint NOT NULL,
  `mes_julio` tinyint NOT NULL,
  `mes_agosto` tinyint NOT NULL,
  `mes_septiembre` tinyint NOT NULL,
  `mes_octubre` tinyint NOT NULL,
  `mes_noviembre` tinyint NOT NULL,
  `mes_diciembre` tinyint NOT NULL,
  `observaciones` text NOT NULL,
  `fecha_realizado` date,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `usuario_actividad`(
  `id` int(11) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `aspecto_objetivo` (
  `id` int(11) NOT NULL auto_increment,
  `id_objetivo` int(11) NOT NULL,
  `id_tipo_aspecto` int(11) NOT NULL,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `tipo_aspecto` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) comment="esto es positivo/negativo";


CREATE TABLE `estrategia_actividad` (
  `id` int(11) NOT NULL auto_increment,
  `id_actividad` int(11) NOT NULL,
  `id_estrategia` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `estrategia` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
);




