-- MySQL schema inferred from application code
-- Run: docker exec -i <db_container> mysql -uroot -proot datos < mysql_schema.sql

SET FOREIGN_KEY_CHECKS=0;

-- Users table used by login/registration
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `tbl_user_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `contact_number` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tbl_user_id`),
  UNIQUE KEY `uq_tbl_user_username` (`username`),
  UNIQUE KEY `uq_tbl_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inventory / product list (frequently referenced as `lista7`)
CREATE TABLE IF NOT EXISTS `lista7` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` TEXT,
  `codigos` VARCHAR(150),
  `codigo` VARCHAR(150),
  `precio_compra` DECIMAL(12,2) DEFAULT 0,
  `precio_venta` DECIMAL(12,2) DEFAULT 0,
  `cantidad` DECIMAL(12,3) DEFAULT 0,
  `impuesto` DECIMAL(8,2) DEFAULT 0,
  `destacado` TINYINT(1) DEFAULT 0,
  `imagen` VARCHAR(255),
  `logo` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: MySQL doesn't support CREATE INDEX IF NOT EXISTS; create index directly
CREATE INDEX `idx_lista7_descripcion` ON `lista7`(`descripcion`(100));

-- Customers tables: clientes2 and clientes3
CREATE TABLE IF NOT EXISTS `clientes2` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa` VARCHAR(255),
  `direccion` TEXT,
  `identificacion` VARCHAR(100),
  `telefono` VARCHAR(100),
  `email` VARCHAR(255),
  `ciudad` VARCHAR(150),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `clientes3` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa` VARCHAR(255),
  `direccion` TEXT,
  `identificacion` VARCHAR(100),
  `telefono` VARCHAR(100),
  `email` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Temporary client holder used by POS workflow
CREATE TABLE IF NOT EXISTS `ostemporalclientes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255),
  `nit` VARCHAR(100),
  `direccion` TEXT,
  `email` VARCHAR(255),
  `telefono` VARCHAR(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Generic temporary order lines table
CREATE TABLE IF NOT EXISTS `ostemporal` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  `come` DECIMAL(12,3) DEFAULT 0,
  `celu` VARCHAR(255),
  `num` DECIMAL(12,2) DEFAULT 0,
  `estado` VARCHAR(100),
  `impuesto` DECIMAL(8,2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Temporary quotes / cart
CREATE TABLE IF NOT EXISTS `ostemporal_cotizaciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  `come` DECIMAL(12,3) DEFAULT 0,
  `celu` VARCHAR(255),
  `num` DECIMAL(12,2) DEFAULT 0,
  `estado` VARCHAR(100),
  `impuesto` DECIMAL(8,2) DEFAULT 0,
  `descuento` DECIMAL(8,2) DEFAULT 0,
  `mesa` VARCHAR(50),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tables for mesas / POS
CREATE TABLE IF NOT EXISTS `ostemporal_mesas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ostemporal_mesas2` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Parking / payment temporary table
CREATE TABLE IF NOT EXISTS `ostemporal_parqueadero` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  `num` DECIMAL(12,2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Small tmp table used by AJAX endpoints
CREATE TABLE IF NOT EXISTS `tmp` (
  `id_tmp` INT NOT NULL AUTO_INCREMENT,
  `id_producto` INT DEFAULT NULL,
  `cantidad_tmp` DECIMAL(12,3) DEFAULT 0,
  `precio_tmp` DECIMAL(12,2) DEFAULT 0,
  `session_id` VARCHAR(255),
  PRIMARY KEY (`id_tmp`),
  KEY `fk_tmp_producto` (`id_producto`),
  CONSTRAINT `fk_tmp_lista7` FOREIGN KEY (`id_producto`) REFERENCES `lista7`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Production staging helper
CREATE TABLE IF NOT EXISTS `ostemporalproduccion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` TEXT,
  `num` DECIMAL(12,2) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;
