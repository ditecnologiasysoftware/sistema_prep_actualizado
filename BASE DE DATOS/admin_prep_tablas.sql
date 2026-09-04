-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-09-2026 a las 12:45:00
-- Versión del servidor: 10.11.18-MariaDB-0+deb12u1
-- Versión de PHP: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `admin_prep`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_candidato`
--

CREATE TABLE `tblc_candidato` (
  `id_candidato` int(10) UNSIGNED NOT NULL,
  `id_proceso_electoral` int(10) UNSIGNED NOT NULL,
  `id_partido_politico` int(10) UNSIGNED DEFAULT 0,
  `nombre` varchar(100) DEFAULT NULL,
  `principal` int(2) DEFAULT NULL,
  `ordenamiento` int(3) NOT NULL DEFAULT 0,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_candidato_partido`
--

CREATE TABLE `tblc_candidato_partido` (
  `id_candidato` int(10) UNSIGNED NOT NULL,
  `id_partido_politico` int(10) UNSIGNED NOT NULL,
  `ordenamiento` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_casilla`
--

CREATE TABLE `tblc_casilla` (
  `id_casilla` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `numero` int(11) DEFAULT NULL,
  `tipo` int(2) DEFAULT NULL COMMENT '1.- basica\n2.- contigua\n3.- extraordinaria\n',
  `id_municipio` int(11) NOT NULL,
  `latitud` varchar(45) DEFAULT NULL,
  `longitud` varchar(45) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `seccion` varchar(11) DEFAULT NULL,
  `id_seccion` int(11) NOT NULL DEFAULT 0,
  `num_contigua` int(50) NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_categoria`
--

CREATE TABLE `tblc_categoria` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_distrito`
--

CREATE TABLE `tblc_distrito` (
  `id_distrito` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `coordenadas` mediumtext DEFAULT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 0,
  `estatus` int(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_estado`
--

CREATE TABLE `tblc_estado` (
  `id_estado` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `clave` varchar(5) NOT NULL,
  `latitud` varchar(15) NOT NULL,
  `longitud` varchar(15) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_etiqueta`
--

CREATE TABLE `tblc_etiqueta` (
  `id_etiqueta` int(11) NOT NULL,
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `etiqueta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_iconos`
--

CREATE TABLE `tblc_iconos` (
  `id_icono` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_municipio`
--

CREATE TABLE `tblc_municipio` (
  `id_municipio` int(11) NOT NULL,
  `id_estado` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `clave` varchar(5) DEFAULT NULL,
  `latitud` varchar(15) NOT NULL,
  `longitud` varchar(15) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_partido_politico`
--

CREATE TABLE `tblc_partido_politico` (
  `id_partido_politico` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `colo` varchar(45) DEFAULT NULL,
  `icono` varchar(45) DEFAULT NULL,
  `ordenamiento` int(4) NOT NULL DEFAULT 1,
  `estatus` int(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_permiso`
--

CREATE TABLE `tblc_permiso` (
  `id_permiso` int(10) UNSIGNED NOT NULL,
  `id_padre` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `archivo` varchar(70) NOT NULL,
  `icono` varchar(45) NOT NULL,
  `ordenamiento` int(4) NOT NULL,
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_proceso_electoral`
--

CREATE TABLE `tblc_proceso_electoral` (
  `id_proceso_electoral` int(10) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `descripcion` mediumtext DEFAULT NULL,
  `id_tipo_eleccion` int(11) NOT NULL DEFAULT 0,
  `id_estado` int(11) NOT NULL DEFAULT 0,
  `id_municipio` int(11) NOT NULL DEFAULT 0,
  `estatus` int(2) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_representante`
--

CREATE TABLE `tblc_representante` (
  `id_representante` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `usuario` varchar(45) DEFAULT NULL COMMENT '	',
  `pass` varchar(180) DEFAULT NULL,
  `estatus` int(2) NOT NULL DEFAULT 1,
  `id_proceso_electoral` int(45) NOT NULL,
  `id_casilla` int(11) DEFAULT NULL,
  `id_municipio` int(11) DEFAULT 0,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_seccion`
--

CREATE TABLE `tblc_seccion` (
  `id_seccion` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `id_municipio` int(11) NOT NULL DEFAULT 0,
  `coordenadas` mediumtext DEFAULT NULL,
  `id_distrito` int(11) NOT NULL,
  `latitud` varchar(45) DEFAULT NULL,
  `longitud` varchar(45) DEFAULT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_tipo_eleccion`
--

CREATE TABLE `tblc_tipo_eleccion` (
  `id_tipo_eleccion` int(10) UNSIGNED NOT NULL COMMENT 'Tabla para indicar el cargo que se esta jugando',
  `nombre` varchar(100) DEFAULT NULL,
  `estatus` int(2) DEFAULT NULL,
  `tipo` int(2) NOT NULL DEFAULT 0 COMMENT '1.- Federal 2.- Estatal 3.- Municipal',
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblc_usuario`
--

CREATE TABLE `tblc_usuario` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `pass` varchar(150) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `eliminar` int(2) NOT NULL,
  `editar` int(2) NOT NULL,
  `estatus` int(2) NOT NULL COMMENT '1.- activo\n2.- inactivo',
  `fecha_registro` datetime NOT NULL,
  `id_estado` int(11) DEFAULT 0,
  `id_municipio` int(11) DEFAULT 0,
  `id_proceso_electoral` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_acta`
--

CREATE TABLE `tbl_acta` (
  `id_acta` int(10) UNSIGNED NOT NULL,
  `id_casilla` int(10) UNSIGNED NOT NULL,
  `archivo` varchar(120) DEFAULT NULL,
  `id_representante` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `votos_nulos` int(11) NOT NULL,
  `id_proceso_electoral` int(11) NOT NULL DEFAULT 0,
  `no_registrados` int(11) NOT NULL DEFAULT 0,
  `total_votos` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_configuracion`
--

CREATE TABLE `tbl_configuracion` (
  `id_configuracion` int(1) UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_termino` date NOT NULL,
  `msj_antes` mediumtext NOT NULL,
  `msj_despues` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_estatus_casilla`
--

CREATE TABLE `tbl_estatus_casilla` (
  `id_estatus_casilla` int(10) UNSIGNED NOT NULL,
  `id_proceso_electoral` int(11) NOT NULL,
  `id_casilla` int(11) NOT NULL,
  `tipo` int(1) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `observaciones` text NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_lista_nominal`
--

CREATE TABLE `tbl_lista_nominal` (
  `id_lista_nominal` int(45) NOT NULL,
  `id_proceso_electoral` int(45) NOT NULL,
  `id_casilla` int(45) NOT NULL DEFAULT 0,
  `folio` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nombre` varchar(180) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `curp` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `seccion` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `estatus_voto` int(1) NOT NULL DEFAULT 0,
  `clave_elector` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_eliminado` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_log`
--

CREATE TABLE `tbl_log` (
  `id_log` int(10) UNSIGNED NOT NULL,
  `id_sesion` int(10) UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `script` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reporte`
--

CREATE TABLE `tbl_reporte` (
  `id_reporte` int(10) UNSIGNED NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `folio` varchar(45) NOT NULL DEFAULT '0',
  `nombre` varchar(100) NOT NULL,
  `tipo_reporte` int(2) NOT NULL COMMENT '1.- denuncia\n2.- observacion',
  `descripcion` mediumtext NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `foto` varchar(150) DEFAULT NULL,
  `latitud` varchar(25) NOT NULL DEFAULT '0',
  `longitud` varchar(25) NOT NULL DEFAULT '0',
  `tipo_registro` int(2) NOT NULL COMMENT '1.- tiempo real\n2.- fuera de tiempo',
  `uuid` varchar(45) DEFAULT NULL,
  `so` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `id_casilla` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reporte_etiqueta`
--

CREATE TABLE `tbl_reporte_etiqueta` (
  `id_reporte` int(10) UNSIGNED NOT NULL,
  `id_etiqueta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_representante_movil`
--

CREATE TABLE `tbl_representante_movil` (
  `id_representante_movil` int(10) UNSIGNED NOT NULL,
  `so` varchar(45) DEFAULT NULL,
  `version` varchar(45) DEFAULT NULL,
  `modelo` varchar(45) DEFAULT NULL,
  `marca` varchar(45) DEFAULT NULL,
  `uuid` varchar(45) DEFAULT NULL,
  `fecha_acceso` datetime DEFAULT NULL,
  `num_accesos` int(11) DEFAULT NULL,
  `estatus` int(2) DEFAULT NULL COMMENT '1.- activo\n2.-inactivo\n3.- desactivado',
  `id_representante` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_resultado`
--

CREATE TABLE `tbl_resultado` (
  `id_candidato` int(10) UNSIGNED NOT NULL,
  `id_partido_politico` int(11) NOT NULL DEFAULT 0,
  `id_casilla` int(10) UNSIGNED NOT NULL,
  `resultado` int(11) DEFAULT NULL,
  `id_representante` int(10) UNSIGNED DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `latitud` varchar(100) DEFAULT NULL,
  `longitud` int(100) DEFAULT NULL,
  `id_usuario` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_sesion`
--

CREATE TABLE `tbl_sesion` (
  `id_sesion` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `fecha_acceso` datetime NOT NULL,
  `so` varchar(45) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `navegador` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario_permiso`
--

CREATE TABLE `tbl_usuario_permiso` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_permiso` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_resultado_elecciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_resultado_elecciones` (
`num_casilla` varchar(109)
,`fecha_p` date
,`estatus_p` int(2)
,`nombre_te` varchar(100)
,`estatus_te` int(2)
,`tipo_te` int(2)
,`nombre_pa` varchar(60)
,`icono_pa` varchar(45)
,`color_pa` varchar(45)
,`estado_c` int(11)
,`municipio_c` int(11)
,`idcandidato_c` int(10) unsigned
,`idp_electoral_c` int(10) unsigned
,`idt_eleccion_c` int(11)
,`idp_politico_c` int(11)
,`nombre_c` varchar(100)
,`principal_c` int(2)
,`resultado` int(11)
,`idrepresentante_r` int(10) unsigned
,`fecha_reg_r` datetime
,`id_casilla` int(10) unsigned
,`nom_casilla` varchar(100)
,`numero` int(11)
,`tipo` int(2)
,`id_municipio` int(11)
,`latitud` varchar(45)
,`longitud` varchar(45)
,`direccion` varchar(200)
,`seccion` varchar(11)
,`num_contigua` int(50)
);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tblc_candidato`
--
ALTER TABLE `tblc_candidato`
  ADD PRIMARY KEY (`id_candidato`),
  ADD KEY `fk_tblc_candidato_tblc_proceso_electoral1_idx` (`id_proceso_electoral`);

--
-- Indices de la tabla `tblc_candidato_partido`
--
ALTER TABLE `tblc_candidato_partido`
  ADD PRIMARY KEY (`id_candidato`,`id_partido_politico`),
  ADD KEY `fk_tblc_candidato_has_tblc_partido_politico_tblc_partido_po_idx` (`id_partido_politico`),
  ADD KEY `fk_tblc_candidato_has_tblc_partido_politico_tblc_candidato1_idx` (`id_candidato`);

--
-- Indices de la tabla `tblc_casilla`
--
ALTER TABLE `tblc_casilla`
  ADD PRIMARY KEY (`id_casilla`),
  ADD KEY `fk_tblc_casilla_tblc_municipio1_idx` (`id_municipio`);

--
-- Indices de la tabla `tblc_categoria`
--
ALTER TABLE `tblc_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `tblc_distrito`
--
ALTER TABLE `tblc_distrito`
  ADD PRIMARY KEY (`id_distrito`),
  ADD KEY `fk_tblc_distrito_tblc_municipio1_idx` (`id_municipio`);

--
-- Indices de la tabla `tblc_estado`
--
ALTER TABLE `tblc_estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `tblc_etiqueta`
--
ALTER TABLE `tblc_etiqueta`
  ADD PRIMARY KEY (`id_etiqueta`,`id_categoria`),
  ADD KEY `fk_tblc_etiqueta_rblc_categoria1_idx` (`id_categoria`);

--
-- Indices de la tabla `tblc_iconos`
--
ALTER TABLE `tblc_iconos`
  ADD PRIMARY KEY (`id_icono`);

--
-- Indices de la tabla `tblc_municipio`
--
ALTER TABLE `tblc_municipio`
  ADD PRIMARY KEY (`id_municipio`,`id_estado`),
  ADD KEY `fk_tblc_municipio_tblc_estado_idx` (`id_estado`);

--
-- Indices de la tabla `tblc_partido_politico`
--
ALTER TABLE `tblc_partido_politico`
  ADD PRIMARY KEY (`id_partido_politico`);

--
-- Indices de la tabla `tblc_permiso`
--
ALTER TABLE `tblc_permiso`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `tblc_proceso_electoral`
--
ALTER TABLE `tblc_proceso_electoral`
  ADD PRIMARY KEY (`id_proceso_electoral`);

--
-- Indices de la tabla `tblc_representante`
--
ALTER TABLE `tblc_representante`
  ADD PRIMARY KEY (`id_representante`);

--
-- Indices de la tabla `tblc_seccion`
--
ALTER TABLE `tblc_seccion`
  ADD PRIMARY KEY (`id_seccion`),
  ADD KEY `fk_tblc_seccion_tblc_distrito1_idx` (`id_distrito`);

--
-- Indices de la tabla `tblc_tipo_eleccion`
--
ALTER TABLE `tblc_tipo_eleccion`
  ADD PRIMARY KEY (`id_tipo_eleccion`);

--
-- Indices de la tabla `tblc_usuario`
--
ALTER TABLE `tblc_usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `tbl_acta`
--
ALTER TABLE `tbl_acta`
  ADD PRIMARY KEY (`id_acta`),
  ADD KEY `fk_tbl_acta_tblc_casilla1_idx` (`id_casilla`);

--
-- Indices de la tabla `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indices de la tabla `tbl_estatus_casilla`
--
ALTER TABLE `tbl_estatus_casilla`
  ADD PRIMARY KEY (`id_estatus_casilla`);

--
-- Indices de la tabla `tbl_lista_nominal`
--
ALTER TABLE `tbl_lista_nominal`
  ADD PRIMARY KEY (`id_lista_nominal`),
  ADD UNIQUE KEY `id_lista_nominal` (`id_lista_nominal`);

--
-- Indices de la tabla `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD PRIMARY KEY (`id_log`,`id_sesion`),
  ADD KEY `fk_tbl_log_tbl_sesion1_idx` (`id_sesion`);

--
-- Indices de la tabla `tbl_reporte`
--
ALTER TABLE `tbl_reporte`
  ADD PRIMARY KEY (`id_reporte`,`id_municipio`),
  ADD KEY `fk_tbl_reporte_tblc_municipio1_idx` (`id_municipio`);

--
-- Indices de la tabla `tbl_reporte_etiqueta`
--
ALTER TABLE `tbl_reporte_etiqueta`
  ADD PRIMARY KEY (`id_reporte`,`id_etiqueta`),
  ADD KEY `fk_tbl_reporte_has_tblc_etiqueta_tblc_etiqueta1_idx` (`id_etiqueta`),
  ADD KEY `fk_tbl_reporte_has_tblc_etiqueta_tbl_reporte1_idx` (`id_reporte`);

--
-- Indices de la tabla `tbl_representante_movil`
--
ALTER TABLE `tbl_representante_movil`
  ADD PRIMARY KEY (`id_representante_movil`),
  ADD KEY `fk_tbl_representante_movil_tblc_representante1_idx` (`id_representante`);

--
-- Indices de la tabla `tbl_resultado`
--
ALTER TABLE `tbl_resultado`
  ADD PRIMARY KEY (`id_candidato`,`id_partido_politico`,`id_casilla`),
  ADD KEY `fk_tbl_resultado_tblc_candidato1_idx` (`id_candidato`),
  ADD KEY `fk_tbl_resultado_tblc_casilla1_idx` (`id_casilla`),
  ADD KEY `id_partido_politico` (`id_partido_politico`);

--
-- Indices de la tabla `tbl_sesion`
--
ALTER TABLE `tbl_sesion`
  ADD PRIMARY KEY (`id_sesion`,`id_usuario`),
  ADD KEY `fk_tbl_sesion_tblc_usuario1_idx` (`id_usuario`);

--
-- Indices de la tabla `tbl_usuario_permiso`
--
ALTER TABLE `tbl_usuario_permiso`
  ADD PRIMARY KEY (`id_usuario`,`id_permiso`),
  ADD KEY `fk_tblc_usuario_has_tblc_permiso_tblc_permiso1_idx` (`id_permiso`),
  ADD KEY `fk_tblc_usuario_has_tblc_permiso_tblc_usuario1_idx` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tblc_candidato`
--
ALTER TABLE `tblc_candidato`
  MODIFY `id_candidato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_casilla`
--
ALTER TABLE `tblc_casilla`
  MODIFY `id_casilla` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_categoria`
--
ALTER TABLE `tblc_categoria`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_distrito`
--
ALTER TABLE `tblc_distrito`
  MODIFY `id_distrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_estado`
--
ALTER TABLE `tblc_estado`
  MODIFY `id_estado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_etiqueta`
--
ALTER TABLE `tblc_etiqueta`
  MODIFY `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_iconos`
--
ALTER TABLE `tblc_iconos`
  MODIFY `id_icono` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_municipio`
--
ALTER TABLE `tblc_municipio`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_partido_politico`
--
ALTER TABLE `tblc_partido_politico`
  MODIFY `id_partido_politico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_permiso`
--
ALTER TABLE `tblc_permiso`
  MODIFY `id_permiso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_proceso_electoral`
--
ALTER TABLE `tblc_proceso_electoral`
  MODIFY `id_proceso_electoral` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_representante`
--
ALTER TABLE `tblc_representante`
  MODIFY `id_representante` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_seccion`
--
ALTER TABLE `tblc_seccion`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblc_tipo_eleccion`
--
ALTER TABLE `tblc_tipo_eleccion`
  MODIFY `id_tipo_eleccion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Tabla para indicar el cargo que se esta jugando';

--
-- AUTO_INCREMENT de la tabla `tblc_usuario`
--
ALTER TABLE `tblc_usuario`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_acta`
--
ALTER TABLE `tbl_acta`
  MODIFY `id_acta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_estatus_casilla`
--
ALTER TABLE `tbl_estatus_casilla`
  MODIFY `id_estatus_casilla` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_lista_nominal`
--
ALTER TABLE `tbl_lista_nominal`
  MODIFY `id_lista_nominal` int(45) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_log`
--
ALTER TABLE `tbl_log`
  MODIFY `id_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_reporte`
--
ALTER TABLE `tbl_reporte`
  MODIFY `id_reporte` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_representante_movil`
--
ALTER TABLE `tbl_representante_movil`
  MODIFY `id_representante_movil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_sesion`
--
ALTER TABLE `tbl_sesion`
  MODIFY `id_sesion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_resultado_elecciones`
--
DROP TABLE IF EXISTS `vw_resultado_elecciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin_prep`@`%` SQL SECURITY DEFINER VIEW `vw_resultado_elecciones`  AS SELECT concat('Casilla: ',`ca`.`nombre`) AS `num_casilla`, `p`.`fecha` AS `fecha_p`, `p`.`estatus` AS `estatus_p`, `te`.`nombre` AS `nombre_te`, `te`.`estatus` AS `estatus_te`, `te`.`tipo` AS `tipo_te`, `pa`.`nombre` AS `nombre_pa`, `pa`.`icono` AS `icono_pa`, `pa`.`colo` AS `color_pa`, `p`.`id_estado` AS `estado_c`, `p`.`id_municipio` AS `municipio_c`, `c`.`id_candidato` AS `idcandidato_c`, `c`.`id_proceso_electoral` AS `idp_electoral_c`, `p`.`id_tipo_eleccion` AS `idt_eleccion_c`, `r`.`id_partido_politico` AS `idp_politico_c`, `c`.`nombre` AS `nombre_c`, `c`.`principal` AS `principal_c`, `r`.`resultado` AS `resultado`, `r`.`id_representante` AS `idrepresentante_r`, `r`.`fecha_registro` AS `fecha_reg_r`, `ca`.`id_casilla` AS `id_casilla`, `ca`.`nombre` AS `nom_casilla`, `ca`.`numero` AS `numero`, `ca`.`tipo` AS `tipo`, `ca`.`id_municipio` AS `id_municipio`, `ca`.`latitud` AS `latitud`, `ca`.`longitud` AS `longitud`, `ca`.`direccion` AS `direccion`, `ca`.`seccion` AS `seccion`, `ca`.`num_contigua` AS `num_contigua` FROM (((((`tbl_resultado` `r` join `tblc_candidato` `c` on(`r`.`id_candidato` = `c`.`id_candidato`)) join `tblc_casilla` `ca` on(`r`.`id_casilla` = `ca`.`id_casilla`)) join `tblc_proceso_electoral` `p` on(`c`.`id_proceso_electoral` = `p`.`id_proceso_electoral`)) join `tblc_tipo_eleccion` `te` on(`p`.`id_tipo_eleccion` = `te`.`id_tipo_eleccion`)) join `tblc_partido_politico` `pa` on(`r`.`id_partido_politico` = `pa`.`id_partido_politico`)) ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tblc_candidato`
--
ALTER TABLE `tblc_candidato`
  ADD CONSTRAINT `fk_tblc_candidato_tblc_proceso_electoral1` FOREIGN KEY (`id_proceso_electoral`) REFERENCES `tblc_proceso_electoral` (`id_proceso_electoral`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tblc_candidato_partido`
--
ALTER TABLE `tblc_candidato_partido`
  ADD CONSTRAINT `fk_tblc_candidato_has_tblc_partido_politico_tblc_candidato1` FOREIGN KEY (`id_candidato`) REFERENCES `tblc_candidato` (`id_candidato`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tblc_candidato_has_tblc_partido_politico_tblc_partido_poli1` FOREIGN KEY (`id_partido_politico`) REFERENCES `tblc_partido_politico` (`id_partido_politico`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tblc_casilla`
--
ALTER TABLE `tblc_casilla`
  ADD CONSTRAINT `fk_tblc_casilla_tblc_municipio1` FOREIGN KEY (`id_municipio`) REFERENCES `tblc_municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tblc_etiqueta`
--
ALTER TABLE `tblc_etiqueta`
  ADD CONSTRAINT `fk_tblc_etiqueta_rblc_categoria1` FOREIGN KEY (`id_categoria`) REFERENCES `tblc_categoria` (`id_categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tblc_municipio`
--
ALTER TABLE `tblc_municipio`
  ADD CONSTRAINT `fk_tblc_municipio_tblc_estado` FOREIGN KEY (`id_estado`) REFERENCES `tblc_estado` (`id_estado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_acta`
--
ALTER TABLE `tbl_acta`
  ADD CONSTRAINT `fk_tbl_acta_tblc_casilla1` FOREIGN KEY (`id_casilla`) REFERENCES `tblc_casilla` (`id_casilla`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD CONSTRAINT `fk_tbl_log_tbl_sesion1` FOREIGN KEY (`id_sesion`) REFERENCES `tbl_sesion` (`id_sesion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_reporte`
--
ALTER TABLE `tbl_reporte`
  ADD CONSTRAINT `fk_tbl_reporte_tblc_municipio1` FOREIGN KEY (`id_municipio`) REFERENCES `tblc_municipio` (`id_municipio`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_reporte_etiqueta`
--
ALTER TABLE `tbl_reporte_etiqueta`
  ADD CONSTRAINT `fk_tbl_reporte_has_tblc_etiqueta_tbl_reporte1` FOREIGN KEY (`id_reporte`) REFERENCES `tbl_reporte` (`id_reporte`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_reporte_has_tblc_etiqueta_tblc_etiqueta1` FOREIGN KEY (`id_etiqueta`) REFERENCES `tblc_etiqueta` (`id_etiqueta`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_representante_movil`
--
ALTER TABLE `tbl_representante_movil`
  ADD CONSTRAINT `fk_tbl_representante_movil_tblc_representante1` FOREIGN KEY (`id_representante`) REFERENCES `tblc_representante` (`id_representante`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_resultado`
--
ALTER TABLE `tbl_resultado`
  ADD CONSTRAINT `fk_tbl_resultado_tblc_candidato1` FOREIGN KEY (`id_candidato`) REFERENCES `tblc_candidato` (`id_candidato`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_resultado_tblc_casilla1` FOREIGN KEY (`id_casilla`) REFERENCES `tblc_casilla` (`id_casilla`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_sesion`
--
ALTER TABLE `tbl_sesion`
  ADD CONSTRAINT `fk_tbl_sesion_tblc_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `tblc_usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tbl_usuario_permiso`
--
ALTER TABLE `tbl_usuario_permiso`
  ADD CONSTRAINT `fk_tblc_usuario_has_tblc_permiso_tblc_permiso1` FOREIGN KEY (`id_permiso`) REFERENCES `tblc_permiso` (`id_permiso`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tblc_usuario_has_tblc_permiso_tblc_usuario1` FOREIGN KEY (`id_usuario`) REFERENCES `tblc_usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
