-- DATA HUB | Esquema inicial de Catastro Comercial
-- MySQL 8.0+ / InnoDB / utf8mb4
-- Mantiene las 63 columnas originales del CATAGO26.
-- Las columnas originales aceptan NULL. NIS, CICLO y SECTOR son numericos;
-- los demas codigos se conservan como texto cuando pueden tener ceros a la izquierda.
-- NIS NO es PK ni UNIQUE: puede repetirse por periodo.

CREATE DATABASE IF NOT EXISTS data_hub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE data_hub;

CREATE TABLE IF NOT EXISTS usuarios (
    ficha VARCHAR(20) NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(120) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN','USUARIO') NOT NULL DEFAULT 'USUARIO',
    estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL,
    PRIMARY KEY (ficha),
    UNIQUE KEY uq_usuarios_usuario (usuario),
    KEY idx_usuarios_estado_rol (estado, rol)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS importaciones (
    importacion_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    fuente VARCHAR(60) NOT NULL,
    nombre_archivo VARCHAR(255) NULL,
    periodo DATE NOT NULL COMMENT 'Primer dia del mes al que corresponde el archivo',
    fecha_importacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuario_ficha VARCHAR(20) NULL,
    filas_archivo BIGINT UNSIGNED NULL,
    filas_importadas BIGINT UNSIGNED NULL,
    estado ENUM('PENDIENTE','PROCESANDO','COMPLETADA','ERROR','REEMPLAZADA') NOT NULL DEFAULT 'PENDIENTE',
    observaciones TEXT NULL,
    PRIMARY KEY (importacion_id),
    KEY idx_importacion_fuente_periodo (fuente, periodo),
    KEY idx_importacion_fecha (fecha_importacion),
    KEY idx_importacion_usuario (usuario_ficha),
    CONSTRAINT fk_importacion_usuario
      FOREIGN KEY (usuario_ficha) REFERENCES usuarios(ficha)
      ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS catastro (
    catastro_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    importacion_id BIGINT UNSIGNED NOT NULL,
    periodo DATE NOT NULL COMMENT 'Periodo comercial del Catastro, no fecha de carga',
    `nis` BIGINT UNSIGNED NULL,
    `nombre` VARCHAR(180) NULL,
    `tip_docu` VARCHAR(10) NULL,
    `documento` VARCHAR(20) NULL,
    `correo` VARCHAR(180) NULL,
    `cus` VARCHAR(30) NULL,
    `calle` VARCHAR(180) NULL,
    `numero` VARCHAR(30) NULL,
    `duplicador` VARCHAR(40) NULL,
    `cgv` VARCHAR(20) NULL,
    `mza` VARCHAR(30) NULL,
    `lote` VARCHAR(30) NULL,
    `urbaniza` VARCHAR(220) NULL,
    `distrito` VARCHAR(20) NULL,
    `referencia` VARCHAR(220) NULL,
    `ruta` VARCHAR(20) NULL,
    `itin` VARCHAR(20) NULL,
    `aol` VARCHAR(20) NULL,
    `est_sum` VARCHAR(20) NULL,
    `actifact` VARCHAR(30) NULL,
    `tarifa` VARCHAR(20) NULL,
    `ntarifa` VARCHAR(30) NULL,
    `cua` VARCHAR(30) NULL,
    `num_apa` VARCHAR(30) NULL,
    `f_inst` DATE NULL,
    `micro` VARCHAR(20) NULL,
    `diametro` DECIMAL(10,2) NULL,
    `dia_conex` DECIMAL(10,2) NULL,
    `f_alta` DATE NULL,
    `f_alta_con` DATE NULL,
    `cod_abas` VARCHAR(30) NULL,
    `ciclo` INT UNSIGNED NULL,
    `nia` VARCHAR(30) NULL,
    `grupo_fami` VARCHAR(20) NULL,
    `codlotep` VARCHAR(40) NULL,
    `sector` INT UNSIGNED NULL,
    `desasociad` VARCHAR(30) NULL,
    `gr_concept` VARCHAR(30) NULL,
    `uniuso` VARCHAR(20) NULL,
    `soc` TINYINT NULL,
    `dom` TINYINT NULL,
    `com` TINYINT NULL,
    `ind` TINYINT NULL,
    `est` TINYINT NULL,
    `promedio` DECIMAL(14,3) NULL,
    `csmo_fac` DECIMAL(14,3) NULL,
    `porc_porcentaje` DECIMAL(14,3) NULL,
    `impo_ad` DECIMAL(14,2) NULL,
    `deuda` DECIMAL(14,2) NULL,
    `tipofact` VARCHAR(30) NULL,
    `asigna` VARCHAR(20) NULL,
    `marcamed` VARCHAR(50) NULL,
    `hrprom` DECIMAL(14,3) NULL,
    `sisfoh` VARCHAR(40) NULL,
    `f_ini_sisf` DATE NULL,
    `f_fin_sisf` DATE NULL,
    `lect_act` INT NULL,
    `inci` VARCHAR(40) NULL,
    `f_lect` DATE NULL,
    `xcoord` DECIMAL(16,6) NULL,
    `ycoord` DECIMAL(16,6) NULL,
    `antena` VARCHAR(20) NULL,
    `zona_peligrosa` VARCHAR(20) NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (catastro_id),
    KEY idx_catastro_nis_periodo (nis, periodo),
    KEY idx_catastro_periodo (periodo),
    KEY idx_catastro_importacion (importacion_id),
    KEY idx_catastro_cua (cua),
    KEY idx_catastro_sector (sector),
    KEY idx_catastro_distrito (distrito),
    CONSTRAINT fk_catastro_importacion
      FOREIGN KEY (importacion_id) REFERENCES importaciones(importacion_id)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Vista de compatibilidad con los 19 campos que actualmente consume SIAM-C.
-- No reemplaza la tabla completa: el resto de las 63 columnas permanece disponible.
CREATE OR REPLACE VIEW vw_catastro_siam AS
SELECT
    catastro_id,
    importacion_id,
    periodo,
    nis,
    nombre,
    calle,
    numero,
    mza,
    lote,
    urbaniza,
    distrito,
    est_sum,
    ntarifa,
    cua,
    dia_conex,
    ciclo,
    sector,
    uniuso,
    xcoord,
    ycoord,
    antena,
    diametro
FROM catastro;

-- Consultas de control:
-- SELECT periodo, COUNT(*) AS registros FROM catastro GROUP BY periodo ORDER BY periodo;
-- SELECT nis, COUNT(*) AS repeticiones FROM catastro GROUP BY nis HAVING COUNT(*) > 1;
-- SELECT * FROM vw_catastro_siam WHERE nis = '1234567';
