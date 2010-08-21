drop database app_bigmotorcicle;
create database app_bigmotorcicle;
use app_bigmotorcicle;



-- tabla de usuarios administradores
drop table if exists `bm_administrador`;
CREATE TABLE `bm_administrador` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) collate utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) collate utf8_unicode_ci NOT NULL,
  `ultimo_acceso` datetime default NULL,
  `activo` varchar(1) collate utf8_unicode_ci default NULL,
  `privilegios` varchar(20) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
insert into bm_administrador(username, password, nombre, apellido, ultimo_acceso, activo, privilegios)
values
('admin', 'nimda', 'sdfsdf', 'sdfsdf', now(), '1', '777');

-- tabla de importaciones
drop table if exists `bm_importacion`;
CREATE TABLE `bm_importacion` (
  `id` int(11) NOT NULL auto_increment,
  `archivo` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- tabla de imagenes importadas en transaccion de importación
DROP TABLE IF EXISTS `bm_imagen_importacion`;
CREATE TABLE `bm_imagen_importacion` (
  `id` int(11) NOT NULL auto_increment,
  `id_importacion` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `importar` varchar(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `bm_imagen_importacion`
    ADD CONSTRAINT `fk_imagen_importacion`
        FOREIGN KEY
             (`id_importacion`)
        REFERENCES
            `bm_importacion` (`id`) ON DELETE CASCADE;

DROP TABLE IF EXISTS `bm_producto`;
CREATE TABLE `bm_producto` (
  `id` int(11) NOT NULL auto_increment,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `rubro` varchar(255) NOT NULL,
  `stock` decimal(20,2) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `bm_producto_importacion`;
CREATE TABLE `bm_producto_importacion` (
  `id` int(11) NOT NULL auto_increment,
  `id_importacion` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `rubro` varchar(255) NOT NULL,
  `stock` decimal(20,2) default NULL,
  `precio` decimal(20,2) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  `importar` varchar(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `bm_producto_importacion`
    ADD CONSTRAINT `fk_producto_importacion`
        FOREIGN KEY
             (`id_importacion`)
        REFERENCES
            `bm_importacion` (`id`) ON DELETE CASCADE;

CREATE TABLE `bm_producto_backup` (
  `id` int(11) NOT NULL auto_increment,
  `id_backup` int(11) not null,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `rubro` varchar(255) NOT NULL,
  `stock` decimal(20,2) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bm_backup` (
  `id` int(11) NOT NULL auto_increment,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bm_usuario` (
  `id` int(11) NOT NULL auto_increment,
  `activo` varchar(1) collate utf8_unicode_ci default NULL,
  `username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) collate utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) collate utf8_unicode_ci NOT NULL,
  `email` varchar(150) collate utf8_unicode_ci NOT NULL,
  `telefono_cod_area` varchar(10) collate utf8_unicode_ci NOT NULL,
  `telefono_numero` varchar(20) collate utf8_unicode_ci NOT NULL,
  `domicilio` varchar(100) collate utf8_unicode_ci NOT NULL,
  `localidad` varchar(100) collate utf8_unicode_ci NOT NULL,
  `provincia` varchar(100) collate utf8_unicode_ci NOT NULL,
  `pais` varchar(100) collate utf8_unicode_ci NOT NULL,
  `moto` varchar(100) collate utf8_unicode_ci NOT NULL,
  `moto_marca` varchar(100) collate utf8_unicode_ci NOT NULL,
  `moto_modelo` varchar(100) collate utf8_unicode_ci NOT NULL,
  `moto_anio` varchar(10) collate utf8_unicode_ci NOT NULL,
  `marca_favorita1` varchar(100) collate utf8_unicode_ci NOT NULL,
  `marca_favorita2` varchar(100) collate utf8_unicode_ci NOT NULL,
  `marca_favorita3` varchar(100) collate utf8_unicode_ci NOT NULL,
  `newsletter` int(1) collate utf8_unicode_ci NOT NULL,
  `ultimo_acceso` timestamp not null default current_timestamp,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `bm_ciudad` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `bm_ciudad_codigo_postal` (
  `id` int(11) NOT NULL auto_increment,
  `id_ciudad` int(11) NOT NULL,
  `codigo_postal` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `bm_ciudad_codigo_postal`
    ADD CONSTRAINT `fk_ciudad_codigo_postal`
        FOREIGN KEY
             (`id_ciudad`)
        REFERENCES
            `bm_ciudad` (`id`) ON DELETE CASCADE;
CREATE TABLE `bm_metodo_envio` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(300) NOT NULL,
  `activo` int(1) NOT NULL default 1,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `bm_forma_pago` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(300) NOT NULL,
  `activo` int(1) NOT NULL default 1,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `bm_venta` (
  `id` int(11) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL,
  `ciudad` varchar(300) NOT NULL,
  `codigo_postal` varchar(300) NOT NULL,
  `metodo_envio` varchar(300) NOT NULL,
  `forma_pago` varchar(300) NOT NULL,
  `estado` enum('pendiente','proceso','finalizada','cancelada') NOT NULL default 'pendiente',
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `bm_venta`
    ADD CONSTRAINT `fk_venta_to_usuario`
        FOREIGN KEY
             (`id_usuario`)
        REFERENCES
            `bm_usuario` (`id`) ON DELETE CASCADE;

CREATE TABLE `bm_detalle_venta` (
  `id` int(11) NOT NULL auto_increment,
  `id_venta` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `rubro` varchar(255) NOT NULL,
  `cantidad` decimal(20,2) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `bm_detalle_venta`
    ADD CONSTRAINT `fk_detalle_venta`
        FOREIGN KEY
             (`id_venta`)
        REFERENCES
            `bm_venta` (`id`) ON DELETE CASCADE;

CREATE TABLE `bm_config` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);

  
alter table bm_venta
change estado `estado` enum('pendiente','proceso','finalizada','cancelada','guardado') NOT NULL default 'pendiente';





CREATE TABLE `bm_novedad` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(300) NOT NULL,
  `descripcion` text NOT NULL,
  `desarrollo` text NOT NULL,
  `activo` int(1) NOT NULL default 1,
  `fecha` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bm_marca_imagen` (
  `id` int(11) NOT NULL auto_increment,
  `marca` varchar(300) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


alter table bm_config
  change `valor` `valor` varchar(2000) NOT NULL;

CREATE TABLE `bm_testimonio` (
  `id` int(11) NOT NULL auto_increment,
  `descripcion` text NOT NULL,
  `relator` varchar(50) NOT NULL,
  `localidad` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;










hacer una clase de cual derive Db_Model_Abstract donde se puedan hacer consultas de vista de manera rapida, ej:
$vista = new Db_Model_View(
	array('bm_producto_importacion','pi','campo1','campo2','i.id'=>'id_importacion'),
	array('bm_importacion','i','id','archivo'),
);
$lv = $vista->search();
var_dump($lv[0]);=>
Object(Core_Model_View(_data=>array('pi_campo1'=>'valor','pi_campo2'=>'valor2','i_archivo')))
