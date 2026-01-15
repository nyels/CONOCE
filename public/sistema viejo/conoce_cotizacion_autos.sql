-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2020 a las 01:56:00
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `conoce_cotizacion_autos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aseguradoras`
--

CREATE TABLE `aseguradoras` (
  `id_aseguradora` int(255) NOT NULL,
  `nombre_aseguradora` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_aseguradora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `aseguradoras`
--

INSERT INTO `aseguradoras` (`id_aseguradora`, `nombre_aseguradora`, `foto`, `fecha_alta_aseguradora`, `dio_alta`) VALUES
(1, 'ALLIANZ', 'imagenes_aseguradoras/allianz.jpeg', '2020-06-05 00:43:53', 'ale1'),
(2, 'ANA', 'imagenes_aseguradoras/ana.jpeg', '2020-06-05 00:43:59', 'ale1'),
(3, 'ATLAS', 'imagenes_aseguradoras/atlas.jpeg', '2020-06-05 00:44:10', 'ale1'),
(4, 'BANORTE', 'imagenes_aseguradoras/banorte.jpeg', '2020-06-05 00:44:24', 'ale1'),
(5, 'BERKLEY', 'imagenes_aseguradoras/berkley.jpeg', '2020-06-05 00:44:36', 'ale1'),
(6, 'BX+', 'imagenes_aseguradoras/bx+.jpeg', '2020-06-05 00:44:54', 'ale1'),
(7, 'CHUBB', 'imagenes_aseguradoras/chubb.jpeg', '2020-06-05 00:45:03', 'ale1'),
(8, 'GMX', 'imagenes_aseguradoras/gmx.jpeg', '2020-06-05 00:45:12', 'ale1'),
(9, 'GNP', 'imagenes_aseguradoras/gnp.jpeg', '2020-06-05 00:45:19', 'ale1'),
(10, 'HDI_SEGUROS', 'imagenes_aseguradoras/hdi seguros.jpeg', '2020-06-05 00:45:29', 'ale1'),
(11, 'MAPFRE', 'imagenes_aseguradoras/mapfre.jpeg', '2020-06-05 00:45:39', 'ale1'),
(12, 'ZURICH', 'imagenes_aseguradoras/zurich.jpeg', '2020-06-09 00:52:57', 'ale1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int(255) NOT NULL,
  `apellido_paterno_contacto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_materno_contacto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_contacto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_contacto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono_contacto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `correo_contaco` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_contacto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_contacto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id_contacto`, `apellido_paterno_contacto`, `apellido_materno_contacto`, `nombre_contacto`, `tipo_contacto`, `telefono_contacto`, `correo_contaco`, `fecha_alta_contacto`, `dio_alta_contacto`) VALUES
(1, 'CASTILLO', 'PEREZ', 'MARIO', 'EMPLEADO', '9995595962', '', '2020-06-08 23:51:04', 'autos1'),
(2, 'CASTILLO', 'ZAPATA', 'JUAN', 'SUBAGENTE', '9998564726', '', '2020-06-12 20:53:13', 'autos1'),
(3, 'SACARIAS', 'HERNANDEZ', 'PEDRO', 'SUBAGENTE', '6998541848', '', '2020-06-12 22:55:46', 'autos2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costo_derecho_movimientos`
--

CREATE TABLE `costo_derecho_movimientos` (
  `id_costo_derecho` int(255) NOT NULL,
  `id_tabla_aseguradora_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_aseguradora_mov` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `costo_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_mov` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `costo_derecho_movimientos`
--

INSERT INTO `costo_derecho_movimientos` (`id_costo_derecho`, `id_tabla_aseguradora_mov`, `nombre_aseguradora_mov`, `costo_mov`, `fecha_alta_mov`, `dio_alta_mov`) VALUES
(1, '1', 'ALLIANZ', '350', '2020-06-08 23:55:14', 'ale1'),
(2, '2', 'ANA', '400', '2020-06-08 23:55:19', 'ale1'),
(3, '1', 'ALLIANZ', '200', '2020-06-09 00:53:55', 'ale1'),
(4, '1', 'ALLIANZ', '600', '2020-06-09 01:05:10', 'ale1'),
(5, '12', 'ZURICH', '600', '2020-06-09 02:02:21', 'ale1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costo_derecho_poliza`
--

CREATE TABLE `costo_derecho_poliza` (
  `id_derecho_poliza` int(255) NOT NULL,
  `id_tabla_aseguradora` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `derecho_costo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_costo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_costo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `costo_derecho_poliza`
--

INSERT INTO `costo_derecho_poliza` (`id_derecho_poliza`, `id_tabla_aseguradora`, `derecho_costo`, `fecha_alta_costo`, `dio_alta_costo`) VALUES
(1, '1', '600', '2020-06-08 23:55:14', 'ale1'),
(2, '2', '400', '2020-06-08 23:55:19', 'ale1'),
(3, '12', '600', '2020-06-09 02:02:21', 'ale1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_autos`
--

CREATE TABLE `cotizacion_autos` (
  `Id_contizacion_autos` int(255) NOT NULL,
  `tipo_cotizacion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `hora_solicitada` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `Id_contacto_tabla_autos` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `Id_prospecto_tabla_autos` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `marca_auto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion_auto` varchar(1000) COLLATE utf8_spanish2_ci NOT NULL,
  `modelo_auto` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `uso_de_unidad_auto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_auto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `compania_autos` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_vigencia_autos` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `poliza_renovar_autos` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `prima_ano_autos` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_de_carga` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `paquete_solicitado` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad_aseguradoras` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_envio_cotizacion_renovacion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_envio_poliza_renovacion` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_auto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_auto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `estatus_concretar_cotizacion` int(10) NOT NULL,
  `opcion_concreto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_concreta_autos` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `numero_poliza` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `inicio_vigencia_poliza` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `motivos_no_concretacion` varchar(500) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cotizacion_autos`
--

INSERT INTO `cotizacion_autos` (`Id_contizacion_autos`, `tipo_cotizacion`, `hora_solicitada`, `Id_contacto_tabla_autos`, `Id_prospecto_tabla_autos`, `marca_auto`, `descripcion_auto`, `modelo_auto`, `uso_de_unidad_auto`, `tipo_auto`, `compania_autos`, `fecha_vigencia_autos`, `poliza_renovar_autos`, `prima_ano_autos`, `tipo_de_carga`, `paquete_solicitado`, `cantidad_aseguradoras`, `fecha_envio_cotizacion_renovacion`, `fecha_envio_poliza_renovacion`, `fecha_alta_auto`, `dio_alta_auto`, `estatus_concretar_cotizacion`, `opcion_concreto`, `fecha_concreta_autos`, `numero_poliza`, `inicio_vigencia_poliza`, `motivos_no_concretacion`) VALUES
(1, 'NUEVA', '15:40', '1', '2', 'FORD', '4 PUERTAS STAND', '2015', 'PARTICULAR', 'CAMION', '', '', '', '', 'C MUY PELIGROSA', 'AMPLIA', '2', '', '', '2020-06-12 20:56:26', 'autos1', 0, '', '', '', '', ''),
(2, 'NUEVA', '16:00', '2', '2', 'NISSAN', 'X-TRAIL AUTOMATICA', '2017', 'COMERCIAL', 'AUTO', '', '', '', '', '', 'AMPLIA', '2', '', '', '2020-06-12 22:17:12', 'autos1', 0, '', '', '', '', ''),
(3, 'NUEVA', '16:30', '3', '3', 'SAD', 'DD', '33', 'DD', 'AUTO', '', '', '', '', '', 'AMPLIA', '2', '', '', '2020-06-12 22:57:18', 'autos2', 0, '', '', '', '', ''),
(4, 'RENOVACION', '', '3', '3', 'NISSAN', 'TSURU 4 PUERTAS STAND', '2010', 'PARTICULAR', 'AUTO', 'BX+', '30-06-2020', 'e3e3e3r3r3e3e', '3,333,343', '', 'AMPLIA', '2', '2020-06-12 21:08:15', '2020-06-12 21:09:59', '2020-06-13 02:01:08', 'autos2', 1, '8', '2020-06-12 21:09:16', 't5f6ftyfty', '12-06-2020 ', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estados` int(5) NOT NULL,
  `nombre_estado` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_estado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estados`, `nombre_estado`, `fecha_alta_estado`, `id_usuario`) VALUES
(1, 'AGUASCALIENTES', '0000-00-00 00:00:00', ''),
(2, 'BAJA CALIFORNIA', '0000-00-00 00:00:00', ''),
(3, 'BAJA CALIFORNIA SUR', '0000-00-00 00:00:00', ''),
(4, 'CAMPECHE', '0000-00-00 00:00:00', ''),
(5, 'CHIAPAS', '0000-00-00 00:00:00', ''),
(6, 'CHIHUAHUA', '0000-00-00 00:00:00', ''),
(7, 'COAHUILA', '0000-00-00 00:00:00', ''),
(8, 'COLIMA', '0000-00-00 00:00:00', ''),
(9, 'DISTRITO FEDERAL', '0000-00-00 00:00:00', ''),
(10, 'DURANGO', '0000-00-00 00:00:00', ''),
(11, 'ESTADO DE MÉXICO', '0000-00-00 00:00:00', ''),
(12, 'GUANAJUATO', '0000-00-00 00:00:00', ''),
(13, 'GUERRERO', '0000-00-00 00:00:00', ''),
(14, 'HIDALGO', '0000-00-00 00:00:00', ''),
(15, 'JALISCO', '0000-00-00 00:00:00', ''),
(16, 'MICHOACÁN', '0000-00-00 00:00:00', ''),
(17, 'MORELOS', '0000-00-00 00:00:00', ''),
(18, 'NAYARIT', '0000-00-00 00:00:00', ''),
(19, 'NUEVO LEÓN', '0000-00-00 00:00:00', ''),
(20, 'OAXACA', '0000-00-00 00:00:00', ''),
(21, 'PUEBLA', '0000-00-00 00:00:00', ''),
(22, 'QUERÉTARO', '0000-00-00 00:00:00', ''),
(23, 'QUINTANA ROO', '0000-00-00 00:00:00', ''),
(24, 'SAN LUIS POTOSÍ', '0000-00-00 00:00:00', ''),
(25, 'SINALOA', '0000-00-00 00:00:00', ''),
(26, 'SONORA', '0000-00-00 00:00:00', ''),
(27, 'TABASCO', '0000-00-00 00:00:00', ''),
(28, 'TAMAULIPAS', '0000-00-00 00:00:00', ''),
(29, 'TLAXCALA', '0000-00-00 00:00:00', ''),
(30, 'VERACRUZ', '0000-00-00 00:00:00', ''),
(31, 'YUCATÁN', '0000-00-00 00:00:00', ''),
(32, 'ZACATECAS', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_cotizaciones`
--

CREATE TABLE `opciones_cotizaciones` (
  `id_opciones` int(255) NOT NULL,
  `Id_tabla_contizacion_autos_opciones` int(255) NOT NULL,
  `aseguradora_opciones` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `danos_materiales_opciones` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `importe_factura_danos_materiales_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `deducible_dm_opciones` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `cristales_opciones` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `robo_total_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `importe_factura_robo_total_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `deducible_rt_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `rc_danos_a_terceros_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `deducible_de_rc_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `rc_fallecimiento_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `gastos_medicos_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `accidentes_conductor_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `proteccion_legal_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `asistencia_legal_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `danos_por_la_carga_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `adaptaciones_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion_opciones` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `extension_rc_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `opcion1_nombre_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `opcion1_valor_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `opcion2_nombre_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `opcion2_valor_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `forma_de_pago_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `prima_neta_anual_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `prima_total_anual_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `primer_pago_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `subsecuentes_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `restante_por_pagar_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `derecho_pago_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `recargo_por_cargo_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_opciones` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `opciones_cotizaciones`
--

INSERT INTO `opciones_cotizaciones` (`id_opciones`, `Id_tabla_contizacion_autos_opciones`, `aseguradora_opciones`, `danos_materiales_opciones`, `importe_factura_danos_materiales_opciones`, `deducible_dm_opciones`, `cristales_opciones`, `robo_total_opciones`, `importe_factura_robo_total_opciones`, `deducible_rt_opciones`, `rc_danos_a_terceros_opciones`, `deducible_de_rc_opciones`, `rc_fallecimiento_opciones`, `gastos_medicos_opciones`, `accidentes_conductor_opciones`, `proteccion_legal_opciones`, `asistencia_legal_opciones`, `danos_por_la_carga_opciones`, `adaptaciones_opciones`, `descripcion_opciones`, `extension_rc_opciones`, `opcion1_nombre_opciones`, `opcion1_valor_opciones`, `opcion2_nombre_opciones`, `opcion2_valor_opciones`, `forma_de_pago_opciones`, `prima_neta_anual_opciones`, `prima_total_anual_opciones`, `primer_pago_opciones`, `subsecuentes_opciones`, `restante_por_pagar_opciones`, `derecho_pago_opciones`, `recargo_por_cargo_opciones`, `fecha_alta_opciones`) VALUES
(1, 1, 'ALLIANZ', 'V.FACTURA', '1,524,636', '5', 'AMPARADA', 'V.FACTURA', '1,524,636', '10', '4,182,963', '5', '58,481,851', '7,451,865', '51,748,515', 'AMPARADA', 'AMPARADA', 'EXCLUIDA', '0', '', 'EXCLUIDA', '', '', '', '', 'SEMESTRAL', '434,750.01', '524,545', '7,545', '', '', '600', '3.8744', '2020-06-12 15:56:26'),
(2, 1, 'ANA', 'V.FACTURA', '515,856', '15', 'AMPARADA', 'V.FACTURA', '515,856', '15', '8,484,856', '6', '151,815', '848,452', '623,151', 'AMPARADA', 'AMPARADA', 'EXCLUIDA', '0', '', 'EXCLUIDA', '', '', '', '', 'SEMESTRAL', '645,953.30', '899,631', '5,252', '', '', '600', '3.8744', '2020-06-12 15:56:26'),
(3, 2, 'ALLIANZ', 'V.COMERCIAL', '', '3', 'AMPARADA', 'V.COMERCIAL', '', '10', '52', '5', '5,252', '5,252', '2,525', 'AMPARADA', 'AMPARADA', 'AMPARADA', '525,454', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '36,058.14', '44,144', '445', '', '', '600', '3.8744', '2020-06-12 17:17:12'),
(4, 2, 'ANA', 'V.COMERCIAL', '', '5', 'AMPARADA', 'V.COMERCIAL', '', '15', '52', '2', '5,252', '52,525', '252', 'AMPARADA', 'AMPARADA', 'AMPARADA', '45,454', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '324,704.02', '452,452', '545', '', '', '600', '3.8744', '2020-06-12 17:17:12'),
(5, 3, 'ALLIANZ', 'V.COMERCIAL', '', '3', 'AMPARADA', 'V.COMERCIAL', '', '5', '221', '1', '23,232', '2,323', '2,323', 'AMPARADA', 'AMPARADA', 'AMPARADA', '2,323', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '1,927,665.39', '2,323,423', '34', '', '', '600', '3.8744', '2020-06-12 17:57:18'),
(6, 3, 'ANA', 'V.COMERCIAL', '', '15', 'AMPARADA', 'V.COMERCIAL', '', '20', '212', '2', '2,323', '322,323', '2,323', 'AMPARADA', 'AMPARADA', 'AMPARADA', '2,323', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '167,938.22', '234,234', '3,434', '', '', '600', '3.8744', '2020-06-12 17:57:18'),
(7, 4, 'ALLIANZ', 'V.CONVENIDO', '', '3', 'AMPARADA', 'V.CONVENIDO', '', '10', '2,323', '4', '234,234', '2,344', '23,434', 'AMPARADA', 'AMPARADA', 'AMPARADA', '234,234', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '193,815.80', '234,233', '23,423', '', '', '600', '3.8744', '2020-06-12 21:01:08'),
(8, 4, 'ANA', 'V.COMERCIAL', '', '10', 'AMPARADA', 'V.COMERCIAL', '', '15', '2', '2', '23,432', '234,234', '23,423', 'AMPARADA', 'AMPARADA', 'AMPARADA', '3,223', '', 'AMPARADA', '', '', '', '', 'SEMESTRAL', '167,944.68', '234,243', '234', '', '', '600', '3.8744', '2020-06-12 21:01:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prospectos`
--

CREATE TABLE `prospectos` (
  `id_prospectos` int(255) NOT NULL,
  `nombre_prospecto` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_parteno_prospecto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_materno_prospecto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `nombres_persona_prospecto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_prospecto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo_postal_prospecto` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `colonia_prospecto` varchar(1000) COLLATE utf8_spanish2_ci NOT NULL,
  `estado_prospecto` int(5) NOT NULL,
  `fecha_alta_prospecto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_prospeccto` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `prospectos`
--

INSERT INTO `prospectos` (`id_prospectos`, `nombre_prospecto`, `apellido_parteno_prospecto`, `apellido_materno_prospecto`, `nombres_persona_prospecto`, `tipo_prospecto`, `codigo_postal_prospecto`, `colonia_prospecto`, `estado_prospecto`, `fecha_alta_prospecto`, `dio_alta_prospeccto`) VALUES
(1, '', 'MARIN', 'PEREZ', 'ALICIA', 'FISICA', '97200', 'CHUBURNA', 31, '2020-06-08 23:51:34', 'ale1'),
(2, '', 'CASTILLO', 'ZAPATA', 'JUAN', 'FISICA', '97203', 'CHUBURNA INN', 31, '2020-06-12 20:53:58', 'autos1'),
(3, '', 'PEDOPAO', 'POAPSOP', 'OPOPAOP', 'FISICA', '95849', 'ASDASDASD', 32, '2020-06-12 22:56:04', 'autos2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recargo_fraccionado_movimientos`
--

CREATE TABLE `recargo_fraccionado_movimientos` (
  `id_recargo_mov` int(255) NOT NULL,
  `id_tabla_recargo_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_aseguradora_recargo_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `forma_pago_mov_recargo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad_recargo_mov` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_mov_reacargo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_recargo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `recargo_fraccionado_movimientos`
--

INSERT INTO `recargo_fraccionado_movimientos` (`id_recargo_mov`, `id_tabla_recargo_mov`, `nombre_aseguradora_recargo_mov`, `forma_pago_mov_recargo`, `cantidad_recargo_mov`, `fecha_alta_mov_reacargo`, `dio_alta_recargo`) VALUES
(1, '1', 'ALLIANZ', 'SEMESTRAL', '150', '2020-06-08 23:55:28', 'ale1'),
(2, '2', 'ANA', 'SEMESTRAL', '200', '2020-06-08 23:55:37', 'ale1'),
(3, '1', 'ALLIANZ', 'SEMESTRAL', '15', '2020-06-09 00:01:41', 'ale1'),
(4, '2', 'ANA', 'SEMESTRAL', '20', '2020-06-09 00:01:47', 'ale1'),
(5, '12', 'ZURICH', 'SEMESTRAL', '10', '2020-06-09 00:55:48', 'ale1'),
(6, '1', 'ALLIANZ', 'TRIMESTRAL', '14', '2020-06-09 00:56:24', 'ale1'),
(7, '1', 'ALLIANZ', 'SEMESTRAL', '12', '2020-06-09 00:57:32', 'ale1'),
(8, '1', 'ALLIANZ', 'MENSUAL', '5', '2020-06-09 00:58:03', 'ale1'),
(9, '1', 'ALLIANZ', 'SEMESTRAL', '3.89', '2020-06-09 01:07:22', 'ale1'),
(10, '1', 'ALLIANZ', 'SEMESTRAL', '3.87', '2020-06-09 01:15:07', 'ale1'),
(11, '1', 'ALLIANZ', 'SEMESTRAL', '3.8744', '2020-06-09 01:16:55', 'ale1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recargo_por_cargo_fraccionado`
--

CREATE TABLE `recargo_por_cargo_fraccionado` (
  `id_recargo` int(255) NOT NULL,
  `id_tabla_aseguradoras_recargo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `forma_de_pago` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `cantidad_recargo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta_recargo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dio_alta_recargo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `recargo_por_cargo_fraccionado`
--

INSERT INTO `recargo_por_cargo_fraccionado` (`id_recargo`, `id_tabla_aseguradoras_recargo`, `forma_de_pago`, `cantidad_recargo`, `fecha_alta_recargo`, `dio_alta_recargo`) VALUES
(1, '1', 'SEMESTRAL', '3.8744', '2020-06-08 23:55:28', 'ale1'),
(2, '2', 'SEMESTRAL', '20', '2020-06-08 23:55:37', 'ale1'),
(3, '12', 'SEMESTRAL', '10', '2020-06-09 00:55:48', 'ale1'),
(4, '1', 'TRIMESTRAL', '14', '2020-06-09 00:56:24', 'ale1'),
(5, '1', 'MENSUAL', '5', '2020-06-09 00:58:03', 'ale1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(255) NOT NULL,
  `nombre_persona` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_usuario` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_usuario` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_persona`, `nombre_usuario`, `pass`, `correo`, `tipo_usuario`, `fecha_alta`) VALUES
(1, 'angel ambrosio', 'angel', '123', 'angel@gmail.com', '1', '2020-05-20 02:04:39'),
(2, 'juan salazar', 'ale1', '123', 'verdadquequieres@hotmail.comm', '4', '2020-05-20 05:59:56'),
(3, 'JOSE RAUL MONTAÑO CORTES', 'raul66', 'raul66', 'raul150667@gmail.com', '1', '2020-05-27 19:31:01'),
(4, 'BELEN ARAGON', 'autos1', 'autos1', '', '4', '2020-06-09 01:39:24'),
(5, 'YESSIKA CRUZ', 'autos2', 'autos2', '', '4', '2020-06-09 01:39:24'),
(6, 'PEDRO PABLO LIZARRAGA', 'admin1', 'admin1', '', '2', '2020-06-09 01:40:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aseguradoras`
--
ALTER TABLE `aseguradoras`
  ADD PRIMARY KEY (`id_aseguradora`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Indices de la tabla `costo_derecho_movimientos`
--
ALTER TABLE `costo_derecho_movimientos`
  ADD PRIMARY KEY (`id_costo_derecho`);

--
-- Indices de la tabla `costo_derecho_poliza`
--
ALTER TABLE `costo_derecho_poliza`
  ADD PRIMARY KEY (`id_derecho_poliza`);

--
-- Indices de la tabla `cotizacion_autos`
--
ALTER TABLE `cotizacion_autos`
  ADD PRIMARY KEY (`Id_contizacion_autos`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estados`);

--
-- Indices de la tabla `opciones_cotizaciones`
--
ALTER TABLE `opciones_cotizaciones`
  ADD PRIMARY KEY (`id_opciones`);

--
-- Indices de la tabla `prospectos`
--
ALTER TABLE `prospectos`
  ADD PRIMARY KEY (`id_prospectos`);

--
-- Indices de la tabla `recargo_fraccionado_movimientos`
--
ALTER TABLE `recargo_fraccionado_movimientos`
  ADD PRIMARY KEY (`id_recargo_mov`);

--
-- Indices de la tabla `recargo_por_cargo_fraccionado`
--
ALTER TABLE `recargo_por_cargo_fraccionado`
  ADD PRIMARY KEY (`id_recargo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aseguradoras`
--
ALTER TABLE `aseguradoras`
  MODIFY `id_aseguradora` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `costo_derecho_movimientos`
--
ALTER TABLE `costo_derecho_movimientos`
  MODIFY `id_costo_derecho` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `costo_derecho_poliza`
--
ALTER TABLE `costo_derecho_poliza`
  MODIFY `id_derecho_poliza` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cotizacion_autos`
--
ALTER TABLE `cotizacion_autos`
  MODIFY `Id_contizacion_autos` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estados` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `opciones_cotizaciones`
--
ALTER TABLE `opciones_cotizaciones`
  MODIFY `id_opciones` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `prospectos`
--
ALTER TABLE `prospectos`
  MODIFY `id_prospectos` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recargo_fraccionado_movimientos`
--
ALTER TABLE `recargo_fraccionado_movimientos`
  MODIFY `id_recargo_mov` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `recargo_por_cargo_fraccionado`
--
ALTER TABLE `recargo_por_cargo_fraccionado`
  MODIFY `id_recargo` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
