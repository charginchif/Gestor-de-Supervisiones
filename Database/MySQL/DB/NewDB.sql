-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: srv1102.hstgr.io:3306
-- Generation Time: Aug 25, 2025 at 05:45 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u487588057_syed`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda_supervisor`
--

DROP TABLE IF EXISTS `agenda_supervisor`;
CREATE TABLE `agenda_supervisor` (
  `id_agenda` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_coordinador` int(11) DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL,
  `prioridad` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alumno`
--

DROP TABLE IF EXISTS `alumno`;
CREATE TABLE `alumno` (
  `id_alumno` int(11) NOT NULL,
  `matricula` varchar(15) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carrera`
--

DROP TABLE IF EXISTS `carrera`;
CREATE TABLE `carrera` (
  `id_carrera` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carrera_grupo`
--

DROP TABLE IF EXISTS `carrera_grupo`;
CREATE TABLE `carrera_grupo` (
  `id_carrera` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carrera_materia`
--

DROP TABLE IF EXISTS `carrera_materia`;
CREATE TABLE `carrera_materia` (
  `id_carrera` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_dia`
--

DROP TABLE IF EXISTS `cat_dia`;
CREATE TABLE `cat_dia` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_incorporacion`
--

DROP TABLE IF EXISTS `cat_incorporacion`;
CREATE TABLE `cat_incorporacion` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_modalidad`
--

DROP TABLE IF EXISTS `cat_modalidad`;
CREATE TABLE `cat_modalidad` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_nivel`
--

DROP TABLE IF EXISTS `cat_nivel`;
CREATE TABLE `cat_nivel` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_periodo`
--

DROP TABLE IF EXISTS `cat_periodo`;
CREATE TABLE `cat_periodo` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_rol`
--

DROP TABLE IF EXISTS `cat_rol`;
CREATE TABLE `cat_rol` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_rubro`
--

DROP TABLE IF EXISTS `cat_rubro`;
CREATE TABLE `cat_rubro` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cat_turno`
--

DROP TABLE IF EXISTS `cat_turno`;
CREATE TABLE `cat_turno` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ciclo_escolar`
--

DROP TABLE IF EXISTS `ciclo_escolar`;
CREATE TABLE `ciclo_escolar` (
  `id_ciclo` int(11) NOT NULL,
  `anio` smallint(6) NOT NULL,
  `id_periodo` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coordinador`
--

DROP TABLE IF EXISTS `coordinador`;
CREATE TABLE `coordinador` (
  `id_coordinador` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coordinador_carrera`
--

DROP TABLE IF EXISTS `coordinador_carrera`;
CREATE TABLE `coordinador_carrera` (
  `id_coordinador` int(11) NOT NULL,
  `id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `criterios_evaluacion`
--

DROP TABLE IF EXISTS `criterios_evaluacion`;
CREATE TABLE `criterios_evaluacion` (
  `id_evacriterio` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `id_rubro` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `criterios_supervision`
--

DROP TABLE IF EXISTS `criterios_supervision`;
CREATE TABLE `criterios_supervision` (
  `id_supcriterio` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `id_rubro` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `docente`
--

DROP TABLE IF EXISTS `docente`;
CREATE TABLE `docente` (
  `id_docente` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `grado_academico` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `docente_materia`
--

DROP TABLE IF EXISTS `docente_materia`;
CREATE TABLE `docente_materia` (
  `id_docente` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluacion_criterios`
--

DROP TABLE IF EXISTS `evaluacion_criterios`;
CREATE TABLE `evaluacion_criterios` (
  `id_evaluacion` int(11) NOT NULL,
  `id_evacriterio` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluacion_docente`
--

DROP TABLE IF EXISTS `evaluacion_docente`;
CREATE TABLE `evaluacion_docente` (
  `id_evaluacion` int(11) NOT NULL,
  `id_alumno` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `id_agenda` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `acronimo` varchar(15) NOT NULL,
  `id_ciclo` int(11) DEFAULT NULL,
  `id_turno` tinyint(3) UNSIGNED NOT NULL,
  `id_modalidad` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_nivel` tinyint(3) UNSIGNED NOT NULL,
  `id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `id_ciclo` int(11) DEFAULT NULL,
  `id_dia` tinyint(3) UNSIGNED NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscripcion_grupo`
--

DROP TABLE IF EXISTS `inscripcion_grupo`;
CREATE TABLE `inscripcion_grupo` (
  `id_grupo` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_usuario`
--

DROP TABLE IF EXISTS `login_usuario`;
CREATE TABLE `login_usuario` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `actividad` text NOT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materia`
--

DROP TABLE IF EXISTS `materia`;
CREATE TABLE `materia` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materia_horario`
--

DROP TABLE IF EXISTS `materia_horario`;
CREATE TABLE `materia_horario` (
  `id_materia` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plantel`
--

DROP TABLE IF EXISTS `plantel`;
CREATE TABLE `plantel` (
  `id_plantel` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plantel_carrera`
--

DROP TABLE IF EXISTS `plantel_carrera`;
CREATE TABLE `plantel_carrera` (
  `id_plantel` int(11) NOT NULL,
  `id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plantel_turno`
--

DROP TABLE IF EXISTS `plantel_turno`;
CREATE TABLE `plantel_turno` (
  `id_plantel_turno` int(11) NOT NULL,
  `id_plantel` int(11) NOT NULL,
  `hora_descanso` time DEFAULT NULL,
  `duracion_bloques` int(11) NOT NULL,
  `duracion_descanso` int(11) NOT NULL,
  `id_dia` tinyint(3) UNSIGNED NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `id_turno` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_estudio`
--

DROP TABLE IF EXISTS `plan_estudio`;
CREATE TABLE `plan_estudio` (
  `id_cat_nivel` tinyint(3) UNSIGNED NOT NULL,
  `id_carrera` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `id_modalidad` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervision_criterios`
--

DROP TABLE IF EXISTS `supervision_criterios`;
CREATE TABLE `supervision_criterios` (
  `id_supervision` int(11) NOT NULL,
  `id_supcriterio` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervision_docente`
--

DROP TABLE IF EXISTS `supervision_docente`;
CREATE TABLE `supervision_docente` (
  `id_supervision` int(11) NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `tema` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` text NOT NULL,
  `id_rol` tinyint(3) UNSIGNED NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_carreras`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_carreras`;
CREATE TABLE `vw_admin_carreras` (
`id_carrera` int(11)
,`carrera` varchar(100)
,`coordinador` varchar(302)
,`total_materias` bigint(21)
,`total_planteles` bigint(21)
,`total_modalidades` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_carrera_materia`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_carrera_materia`;
CREATE TABLE `vw_admin_carrera_materia` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_coordinadores`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_coordinadores`;
CREATE TABLE `vw_admin_coordinadores` (
`id_coordinador` int(11)
,`id_usuario` int(11)
,`coordinador` varchar(302)
,`correo` varchar(100)
,`carreras_asignadas` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_criterios_supervision`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_criterios_supervision`;
CREATE TABLE `vw_admin_criterios_supervision` (
`id_supcriterio` int(11)
,`descripcion` text
,`rubro` varchar(40)
,`veces_evaluado` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_horarios`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_horarios`;
CREATE TABLE `vw_admin_horarios` (
`id_horario` int(11)
,`ciclo` varchar(8)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
,`materias_asignadas` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_planteles`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_planteles`;
CREATE TABLE `vw_admin_planteles` (
`id_plantel` int(11)
,`plantel` varchar(100)
,`ubicacion` varchar(150)
,`carreras_ofertadas` bigint(21)
,`modalidades_ofertadas` bigint(21)
,`descansos_configurados` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_plantel_descansos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_plantel_descansos`;
CREATE TABLE `vw_admin_plantel_descansos` (
`id_plantel_turno` int(11)
,`id_plantel` int(11)
,`plantel` varchar(100)
,`dia` varchar(12)
,`turno` varchar(20)
,`hora_inicio` time
,`hora_fin` time
,`hora_descanso` time
,`duracion_bloques` int(11)
,`duracion_descanso` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_plan_estudio`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_plan_estudio`;
CREATE TABLE `vw_admin_plan_estudio` (
`id_carrera` int(11)
,`carrera` varchar(100)
,`id_materia` int(11)
,`materia` varchar(100)
,`id_cat_nivel` tinyint(3) unsigned
,`nivel` varchar(4)
,`nivel_orden` bigint(20) unsigned
,`id_modalidad` tinyint(3) unsigned
,`modalidad` varchar(32)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_supervision_criterios`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_supervision_criterios`;
CREATE TABLE `vw_admin_supervision_criterios` (
`id_supcriterio` int(11)
,`descripcion` text
,`rubro` varchar(40)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_alumno_docentes`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_alumno_docentes`;
CREATE TABLE `vw_alumno_docentes` (
`id_alumno` int(11)
,`id_usuario` int(11)
,`id_grupo` int(11)
,`id_docente` int(11)
,`docente` varchar(302)
,`grado_academico` varchar(80)
,`id_materia` int(11)
,`materia` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_alumno_evaluaciones`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_alumno_evaluaciones`;
CREATE TABLE `vw_alumno_evaluaciones` (
`id_evaluacion` int(11)
,`id_alumno` int(11)
,`id_usuario` int(11)
,`id_grupo` int(11)
,`grupo` varchar(15)
,`id_docente` int(11)
,`docente` varchar(302)
,`total_items` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_alumno_grupo`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_alumno_grupo`;
CREATE TABLE `vw_alumno_grupo` (
`id_alumno` int(11)
,`id_usuario` int(11)
,`id_grupo` int(11)
,`acronimo` varchar(15)
,`anio` smallint(6)
,`periodo` char(1)
,`turno` varchar(20)
,`nivel` varchar(4)
,`id_carrera` int(11)
,`carrera` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_alumno_horario`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_alumno_horario`;
CREATE TABLE `vw_alumno_horario` (
`id_alumno` int(11)
,`id_usuario` int(11)
,`id_grupo` int(11)
,`id_materia` int(11)
,`materia` varchar(100)
,`nivel` varchar(4)
,`modalidad` varchar(32)
,`id_horario` int(11)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_alumno_perfil`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_alumno_perfil`;
CREATE TABLE `vw_alumno_perfil` (
`id_alumno` int(11)
,`id_usuario` int(11)
,`matricula` varchar(15)
,`nombre_completo` varchar(302)
,`correo` varchar(100)
,`id_carrera` int(11)
,`carrera` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_agenda_supervision`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_agenda_supervision`;
CREATE TABLE `vw_coord_agenda_supervision` (
`id_agenda` int(11)
,`fecha` date
,`estado` tinyint(1)
,`prioridad` tinyint(1)
,`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_carreras`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_carreras`;
CREATE TABLE `vw_coord_carreras` (
`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`id_carrera` int(11)
,`carrera` varchar(100)
,`total_materias` bigint(21)
,`total_planteles` bigint(21)
,`total_modalidades` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_criterios_supervision`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_criterios_supervision`;
CREATE TABLE `vw_coord_criterios_supervision` (
`id_supcriterio` int(11)
,`descripcion` text
,`rubro` varchar(40)
,`veces_evaluado` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_docentes`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_docentes`;
CREATE TABLE `vw_coord_docentes` (
`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`id_docente` int(11)
,`docente` varchar(302)
,`correo` varchar(100)
,`grado_academico` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_grupos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_grupos`;
CREATE TABLE `vw_coord_grupos` (
`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`id_grupo` int(11)
,`acronimo` varchar(15)
,`anio` smallint(6)
,`periodo` char(1)
,`turno` varchar(20)
,`nivel` varchar(4)
,`id_carrera` int(11)
,`carrera` varchar(100)
,`total_alumnos` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_horarios`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_horarios`;
CREATE TABLE `vw_coord_horarios` (
`id_horario` int(11)
,`ciclo` varchar(8)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
,`materias_asignadas` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_materias`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_materias`;
CREATE TABLE `vw_coord_materias` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_planteles`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_planteles`;
CREATE TABLE `vw_coord_planteles` (
`id_plantel` int(11)
,`plantel` varchar(100)
,`ubicacion` varchar(150)
,`carreras_ofertadas` bigint(21)
,`modalidades_ofertadas` bigint(21)
,`descansos_configurados` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_plantel_descansos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_plantel_descansos`;
CREATE TABLE `vw_coord_plantel_descansos` (
`id_plantel_turno` int(11)
,`id_plantel` int(11)
,`plantel` varchar(100)
,`dia` varchar(12)
,`turno` varchar(20)
,`hora_inicio` time
,`hora_fin` time
,`hora_descanso` time
,`duracion_bloques` int(11)
,`duracion_descanso` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_plan_estudio`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_plan_estudio`;
CREATE TABLE `vw_coord_plan_estudio` (
`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`id_carrera` int(11)
,`carrera` varchar(100)
,`id_materia` int(11)
,`materia` varchar(100)
,`id_cat_nivel` tinyint(3) unsigned
,`nivel` varchar(4)
,`id_modalidad` tinyint(3) unsigned
,`modalidad` varchar(32)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_coord_supervisiones`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_coord_supervisiones`;
CREATE TABLE `vw_coord_supervisiones` (
`id_supervision` int(11)
,`fecha` date
,`tema` text
,`observaciones` text
,`id_coordinador` int(11)
,`id_usuario_coordinador` int(11)
,`id_docente` int(11)
,`docente` varchar(302)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_docente_evaluaciones`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_docente_evaluaciones`;
CREATE TABLE `vw_docente_evaluaciones` (
`id_evaluacion` int(11)
,`id_docente` int(11)
,`docente` varchar(302)
,`id_grupo` int(11)
,`grupo` varchar(15)
,`id_alumno` int(11)
,`alumno` varchar(302)
,`total_items` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_docente_horario`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_docente_horario`;
CREATE TABLE `vw_docente_horario` (
`id_docente` int(11)
,`id_horario` int(11)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
,`id_materia` int(11)
,`materia` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_docente_materias`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_docente_materias`;
CREATE TABLE `vw_docente_materias` (
`id_docente` int(11)
,`id_materia` int(11)
,`materia` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_docente_perfil`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_docente_perfil`;
CREATE TABLE `vw_docente_perfil` (
`id_docente` int(11)
,`id_usuario` int(11)
,`nombre_completo` varchar(302)
,`correo` varchar(100)
,`grado_academico` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_docente_supervisiones`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_docente_supervisiones`;
CREATE TABLE `vw_docente_supervisiones` (
`id_supervision` int(11)
,`fecha` date
,`tema` text
,`observaciones` text
,`id_docente` int(11)
,`docente` varchar(302)
,`coordinador` varchar(302)
,`dia` varchar(12)
,`hora_inicio` time
,`hora_fin` time
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_carreras` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_carreras`;
CREATE TABLE`vw_admin_carreras`(
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `coordinador` varchar(302) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `total_materias` bigint(21) DEFAULT NULL,
    `total_planteles` bigint(21) DEFAULT NULL,
    `total_modalidades` bigint(21) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_carrera_materia` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_carrera_materia`;
CREATE TABLE`vw_admin_carrera_materia`(

);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_coordinadores` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_coordinadores`;
CREATE TABLE`vw_admin_coordinadores`(
    `id_coordinador` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `coordinador` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `carreras_asignadas` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_criterios_supervision` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_criterios_supervision`;
CREATE TABLE`vw_admin_criterios_supervision`(
    `id_supcriterio` int(11) NOT NULL DEFAULT '0',
    `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `rubro` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
    `veces_evaluado` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_horarios` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_horarios`;
CREATE TABLE`vw_admin_horarios`(
    `id_horario` int(11) NOT NULL DEFAULT '0',
    `ciclo` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `hora_inicio` time NOT NULL,
    `hora_fin` time NOT NULL,
    `materias_asignadas` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_planteles` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_planteles`;
CREATE TABLE`vw_admin_planteles`(
    `id_plantel` int(11) NOT NULL DEFAULT '0',
    `plantel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `ubicacion` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `carreras_ofertadas` bigint(21) NOT NULL DEFAULT '0',
    `modalidades_ofertadas` bigint(21) NOT NULL DEFAULT '0',
    `descansos_configurados` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_plantel_descansos` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_plantel_descansos`;
CREATE TABLE`vw_admin_plantel_descansos`(
    `id_plantel_turno` int(11) NOT NULL DEFAULT '0',
    `id_plantel` int(11) NOT NULL DEFAULT '0',
    `plantel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `turno` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `hora_inicio` time NOT NULL,
    `hora_fin` time NOT NULL,
    `hora_descanso` time DEFAULT NULL,
    `duracion_bloques` int(11) NOT NULL,
    `duracion_descanso` int(11) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_plan_estudio` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_plan_estudio`;
CREATE TABLE`vw_admin_plan_estudio`(
    `id_carrera` int(11) NOT NULL,
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_materia` int(11) NOT NULL,
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_cat_nivel` tinyint(3) unsigned NOT NULL,
    `nivel` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nivel_orden` bigint(20) unsigned NOT NULL DEFAULT '0',
    `id_modalidad` tinyint(3) unsigned NOT NULL,
    `modalidad` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_supervision_criterios` exported as a table
--
DROP TABLE IF EXISTS `vw_admin_supervision_criterios`;
CREATE TABLE`vw_admin_supervision_criterios`(
    `id_supcriterio` int(11) NOT NULL DEFAULT '0',
    `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `rubro` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_alumno_docentes` exported as a table
--
DROP TABLE IF EXISTS `vw_alumno_docentes`;
CREATE TABLE`vw_alumno_docentes`(
    `id_alumno` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `id_grupo` int(11) NOT NULL DEFAULT '0',
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `grado_academico` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_materia` int(11) NOT NULL DEFAULT '0',
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_alumno_evaluaciones` exported as a table
--
DROP TABLE IF EXISTS `vw_alumno_evaluaciones`;
CREATE TABLE`vw_alumno_evaluaciones`(
    `id_evaluacion` int(11) NOT NULL DEFAULT '0',
    `id_alumno` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `id_grupo` int(11) DEFAULT NULL,
    `grupo` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `id_docente` int(11) DEFAULT NULL,
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `total_items` bigint(21) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_alumno_grupo` exported as a table
--
DROP TABLE IF EXISTS `vw_alumno_grupo`;
CREATE TABLE`vw_alumno_grupo`(
    `id_alumno` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `id_grupo` int(11) NOT NULL DEFAULT '0',
    `acronimo` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
    `anio` smallint(6) DEFAULT NULL,
    `periodo` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turno` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nivel` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_alumno_horario` exported as a table
--
DROP TABLE IF EXISTS `vw_alumno_horario`;
CREATE TABLE`vw_alumno_horario`(
    `id_alumno` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `id_grupo` int(11) NOT NULL DEFAULT '0',
    `id_materia` int(11) NOT NULL DEFAULT '0',
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nivel` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `modalidad` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `id_horario` int(11) DEFAULT '0',
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `hora_inicio` time DEFAULT NULL,
    `hora_fin` time DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_alumno_perfil` exported as a table
--
DROP TABLE IF EXISTS `vw_alumno_perfil`;
CREATE TABLE`vw_alumno_perfil`(
    `id_alumno` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `matricula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nombre_completo` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_agenda_supervision` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_agenda_supervision`;
CREATE TABLE`vw_coord_agenda_supervision`(
    `id_agenda` int(11) NOT NULL DEFAULT '0',
    `fecha` date NOT NULL,
    `estado` tinyint(1) NOT NULL,
    `prioridad` tinyint(1) NOT NULL,
    `id_coordinador` int(11) DEFAULT '0',
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `hora_inicio` time DEFAULT NULL,
    `hora_fin` time DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_carreras` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_carreras`;
CREATE TABLE`vw_coord_carreras`(
    `id_coordinador` int(11) NOT NULL,
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_materias` bigint(21) DEFAULT NULL,
    `total_planteles` bigint(21) DEFAULT NULL,
    `total_modalidades` bigint(21) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_criterios_supervision` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_criterios_supervision`;
CREATE TABLE`vw_coord_criterios_supervision`(
    `id_supcriterio` int(11) NOT NULL DEFAULT '0',
    `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `rubro` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
    `veces_evaluado` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_docentes` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_docentes`;
CREATE TABLE`vw_coord_docentes`(
    `id_coordinador` int(11) NOT NULL,
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `grado_academico` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_grupos` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_grupos`;
CREATE TABLE`vw_coord_grupos`(
    `id_coordinador` int(11) NOT NULL,
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `id_grupo` int(11) NOT NULL DEFAULT '0',
    `acronimo` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
    `anio` smallint(6) DEFAULT NULL,
    `periodo` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turno` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nivel` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_alumnos` bigint(21) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_horarios` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_horarios`;
CREATE TABLE`vw_coord_horarios`(
    `id_horario` int(11) NOT NULL DEFAULT '0',
    `ciclo` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `hora_inicio` time NOT NULL,
    `hora_fin` time NOT NULL,
    `materias_asignadas` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_materias` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_materias`;
CREATE TABLE`vw_coord_materias`(

);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_planteles` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_planteles`;
CREATE TABLE`vw_coord_planteles`(
    `id_plantel` int(11) NOT NULL DEFAULT '0',
    `plantel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `ubicacion` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `carreras_ofertadas` bigint(21) NOT NULL DEFAULT '0',
    `modalidades_ofertadas` bigint(21) NOT NULL DEFAULT '0',
    `descansos_configurados` bigint(21) NOT NULL DEFAULT '0'
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_plantel_descansos` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_plantel_descansos`;
CREATE TABLE`vw_coord_plantel_descansos`(
    `id_plantel_turno` int(11) NOT NULL DEFAULT '0',
    `id_plantel` int(11) NOT NULL DEFAULT '0',
    `plantel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `turno` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `hora_inicio` time NOT NULL,
    `hora_fin` time NOT NULL,
    `hora_descanso` time DEFAULT NULL,
    `duracion_bloques` int(11) NOT NULL,
    `duracion_descanso` int(11) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_plan_estudio` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_plan_estudio`;
CREATE TABLE`vw_coord_plan_estudio`(
    `id_coordinador` int(11) NOT NULL,
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `id_carrera` int(11) NOT NULL DEFAULT '0',
    `carrera` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_materia` int(11) NOT NULL DEFAULT '0',
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_cat_nivel` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `nivel` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
    `id_modalidad` tinyint(3) unsigned NOT NULL DEFAULT '0',
    `modalidad` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_coord_supervisiones` exported as a table
--
DROP TABLE IF EXISTS `vw_coord_supervisiones`;
CREATE TABLE`vw_coord_supervisiones`(
    `id_supervision` int(11) NOT NULL DEFAULT '0',
    `fecha` date DEFAULT NULL,
    `tema` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `observaciones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `id_coordinador` int(11) DEFAULT '0',
    `id_usuario_coordinador` int(11) DEFAULT NULL,
    `id_docente` int(11) DEFAULT '0',
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `hora_inicio` time DEFAULT NULL,
    `hora_fin` time DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_docente_evaluaciones` exported as a table
--
DROP TABLE IF EXISTS `vw_docente_evaluaciones`;
CREATE TABLE`vw_docente_evaluaciones`(
    `id_evaluacion` int(11) NOT NULL DEFAULT '0',
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `id_grupo` int(11) DEFAULT NULL,
    `grupo` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `id_alumno` int(11) DEFAULT NULL,
    `alumno` varchar(302) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `total_items` bigint(21) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_docente_horario` exported as a table
--
DROP TABLE IF EXISTS `vw_docente_horario`;
CREATE TABLE`vw_docente_horario`(
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `id_horario` int(11) NOT NULL DEFAULT '0',
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
    `hora_inicio` time NOT NULL,
    `hora_fin` time NOT NULL,
    `id_materia` int(11) NOT NULL DEFAULT '0',
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_docente_materias` exported as a table
--
DROP TABLE IF EXISTS `vw_docente_materias`;
CREATE TABLE`vw_docente_materias`(
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `id_materia` int(11) NOT NULL DEFAULT '0',
    `materia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_docente_perfil` exported as a table
--
DROP TABLE IF EXISTS `vw_docente_perfil`;
CREATE TABLE`vw_docente_perfil`(
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `id_usuario` int(11) NOT NULL DEFAULT '0',
    `nombre_completo` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `grado_academico` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
);

-- --------------------------------------------------------

--
-- Structure for view `vw_docente_supervisiones` exported as a table
--
DROP TABLE IF EXISTS `vw_docente_supervisiones`;
CREATE TABLE`vw_docente_supervisiones`(
    `id_supervision` int(11) NOT NULL DEFAULT '0',
    `fecha` date DEFAULT NULL,
    `tema` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `observaciones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `id_docente` int(11) NOT NULL DEFAULT '0',
    `docente` varchar(302) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `coordinador` varchar(302) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `dia` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `hora_inicio` time DEFAULT NULL,
    `hora_fin` time DEFAULT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda_supervisor`
--
ALTER TABLE `agenda_supervisor`
  ADD PRIMARY KEY (`id_agenda`),
  ADD KEY `fk_agenda_coord` (`id_coordinador`),
  ADD KEY `fk_agenda_horario` (`id_horario`);

--
-- Indexes for table `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_alumno_carrera` (`id_carrera`),
  ADD KEY `idx_alumno_matricula` (`matricula`);

--
-- Indexes for table `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id_carrera`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `carrera_grupo`
--
ALTER TABLE `carrera_grupo`
  ADD PRIMARY KEY (`id_grupo`,`id_carrera`),
  ADD UNIQUE KEY `id_grupo` (`id_grupo`),
  ADD KEY `fk_cg_carrera` (`id_carrera`);

--
-- Indexes for table `carrera_materia`
--
ALTER TABLE `carrera_materia`
  ADD PRIMARY KEY (`id_carrera`,`id_materia`),
  ADD KEY `idx_cm_materia` (`id_materia`);

--
-- Indexes for table `cat_dia`
--
ALTER TABLE `cat_dia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_incorporacion`
--
ALTER TABLE `cat_incorporacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_modalidad`
--
ALTER TABLE `cat_modalidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_nivel`
--
ALTER TABLE `cat_nivel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_periodo`
--
ALTER TABLE `cat_periodo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_rol`
--
ALTER TABLE `cat_rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_rubro`
--
ALTER TABLE `cat_rubro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `cat_turno`
--
ALTER TABLE `cat_turno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `ciclo_escolar`
--
ALTER TABLE `ciclo_escolar`
  ADD PRIMARY KEY (`id_ciclo`),
  ADD UNIQUE KEY `uq_ciclo` (`anio`,`id_periodo`),
  ADD KEY `fk_ciclo_periodo` (`id_periodo`);

--
-- Indexes for table `coordinador`
--
ALTER TABLE `coordinador`
  ADD PRIMARY KEY (`id_coordinador`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `coordinador_carrera`
--
ALTER TABLE `coordinador_carrera`
  ADD PRIMARY KEY (`id_coordinador`,`id_carrera`),
  ADD UNIQUE KEY `id_carrera` (`id_carrera`);

--
-- Indexes for table `criterios_evaluacion`
--
ALTER TABLE `criterios_evaluacion`
  ADD PRIMARY KEY (`id_evacriterio`),
  ADD KEY `fk_ce_rubro` (`id_rubro`);

--
-- Indexes for table `criterios_supervision`
--
ALTER TABLE `criterios_supervision`
  ADD PRIMARY KEY (`id_supcriterio`),
  ADD KEY `fk_cs_rubro` (`id_rubro`);

--
-- Indexes for table `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_docente_usuario` (`usuario_id`);

--
-- Indexes for table `docente_materia`
--
ALTER TABLE `docente_materia`
  ADD PRIMARY KEY (`id_docente`,`id_materia`),
  ADD KEY `fk_dm_materia` (`id_materia`);

--
-- Indexes for table `evaluacion_criterios`
--
ALTER TABLE `evaluacion_criterios`
  ADD PRIMARY KEY (`id_evaluacion`,`id_evacriterio`),
  ADD KEY `fk_ec_criter` (`id_evacriterio`);

--
-- Indexes for table `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `fk_ed_alumno` (`id_alumno`),
  ADD KEY `fk_ed_grupo` (`id_grupo`),
  ADD KEY `fk_ed_docente` (`id_docente`),
  ADD KEY `fk_ed_agenda` (`id_agenda`);

--
-- Indexes for table `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `uq_grupo` (`acronimo`,`id_ciclo`),
  ADD KEY `fk_grupo_turno` (`id_turno`),
  ADD KEY `fk_grupo_nivel` (`id_nivel`),
  ADD KEY `fk_grupo_carrera` (`id_carrera`),
  ADD KEY `idx_grupo_ciclo` (`id_ciclo`),
  ADD KEY `fk_grupo_modalidad` (`id_modalidad`);

--
-- Indexes for table `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `fk_horario_dia` (`id_dia`),
  ADD KEY `idx_horario_ciclo_dia` (`id_ciclo`,`id_dia`);

--
-- Indexes for table `inscripcion_grupo`
--
ALTER TABLE `inscripcion_grupo`
  ADD PRIMARY KEY (`id_grupo`,`id_alumno`),
  ADD UNIQUE KEY `id_alumno` (`id_alumno`);

--
-- Indexes for table `login_usuario`
--
ALTER TABLE `login_usuario`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_login_usuario` (`id_usuario`);

--
-- Indexes for table `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_materia`);

--
-- Indexes for table `materia_horario`
--
ALTER TABLE `materia_horario`
  ADD PRIMARY KEY (`id_materia`,`id_horario`),
  ADD UNIQUE KEY `id_horario` (`id_horario`);

--
-- Indexes for table `plantel`
--
ALTER TABLE `plantel`
  ADD PRIMARY KEY (`id_plantel`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `plantel_carrera`
--
ALTER TABLE `plantel_carrera`
  ADD PRIMARY KEY (`id_plantel`,`id_carrera`),
  ADD KEY `fk_pc_carrera` (`id_carrera`);

--
-- Indexes for table `plantel_turno`
--
ALTER TABLE `plantel_turno`
  ADD PRIMARY KEY (`id_plantel_turno`),
  ADD KEY `fk_pt_dia` (`id_dia`),
  ADD KEY `fk_pt_turno` (`id_turno`),
  ADD KEY `idx_plantel_turno` (`id_plantel`,`id_turno`,`id_dia`);

--
-- Indexes for table `plan_estudio`
--
ALTER TABLE `plan_estudio`
  ADD PRIMARY KEY (`id_carrera`,`id_materia`,`id_cat_nivel`,`id_modalidad`),
  ADD KEY `idx_pe_materia` (`id_materia`),
  ADD KEY `idx_pe_modalidad` (`id_modalidad`),
  ADD KEY `idx_pe_nivel` (`id_cat_nivel`);

--
-- Indexes for table `supervision_criterios`
--
ALTER TABLE `supervision_criterios`
  ADD PRIMARY KEY (`id_supervision`,`id_supcriterio`),
  ADD KEY `fk_sc_criterio` (`id_supcriterio`);

--
-- Indexes for table `supervision_docente`
--
ALTER TABLE `supervision_docente`
  ADD PRIMARY KEY (`id_supervision`),
  ADD KEY `fk_sd_docente` (`id_docente`),
  ADD KEY `fk_sd_agenda` (`id_agenda`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuario_rol` (`id_rol`),
  ADD KEY `idx_usuario_correo` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda_supervisor`
--
ALTER TABLE `agenda_supervisor`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_dia`
--
ALTER TABLE `cat_dia`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_incorporacion`
--
ALTER TABLE `cat_incorporacion`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_modalidad`
--
ALTER TABLE `cat_modalidad`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_nivel`
--
ALTER TABLE `cat_nivel`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_periodo`
--
ALTER TABLE `cat_periodo`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_rol`
--
ALTER TABLE `cat_rol`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_rubro`
--
ALTER TABLE `cat_rubro`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat_turno`
--
ALTER TABLE `cat_turno`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ciclo_escolar`
--
ALTER TABLE `ciclo_escolar`
  MODIFY `id_ciclo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coordinador`
--
ALTER TABLE `coordinador`
  MODIFY `id_coordinador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `criterios_evaluacion`
--
ALTER TABLE `criterios_evaluacion`
  MODIFY `id_evacriterio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `criterios_supervision`
--
ALTER TABLE `criterios_supervision`
  MODIFY `id_supcriterio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `docente`
--
ALTER TABLE `docente`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_usuario`
--
ALTER TABLE `login_usuario`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plantel`
--
ALTER TABLE `plantel`
  MODIFY `id_plantel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plantel_turno`
--
ALTER TABLE `plantel_turno`
  MODIFY `id_plantel_turno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supervision_docente`
--
ALTER TABLE `supervision_docente`
  MODIFY `id_supervision` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda_supervisor`
--
ALTER TABLE `agenda_supervisor`
  ADD CONSTRAINT `fk_agenda_coord` FOREIGN KEY (`id_coordinador`) REFERENCES `coordinador` (`id_coordinador`),
  ADD CONSTRAINT `fk_agenda_horario` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`);

--
-- Constraints for table `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `fk_alumno_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`),
  ADD CONSTRAINT `fk_alumno_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `carrera_grupo`
--
ALTER TABLE `carrera_grupo`
  ADD CONSTRAINT `fk_cg_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`),
  ADD CONSTRAINT `fk_cg_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Constraints for table `carrera_materia`
--
ALTER TABLE `carrera_materia`
  ADD CONSTRAINT `fk_cm_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ciclo_escolar`
--
ALTER TABLE `ciclo_escolar`
  ADD CONSTRAINT `fk_ciclo_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `cat_periodo` (`id`);

--
-- Constraints for table `coordinador`
--
ALTER TABLE `coordinador`
  ADD CONSTRAINT `fk_coordinador_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `coordinador_carrera`
--
ALTER TABLE `coordinador_carrera`
  ADD CONSTRAINT `fk_cc_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`),
  ADD CONSTRAINT `fk_cc_coord` FOREIGN KEY (`id_coordinador`) REFERENCES `coordinador` (`id_coordinador`);

--
-- Constraints for table `criterios_evaluacion`
--
ALTER TABLE `criterios_evaluacion`
  ADD CONSTRAINT `fk_ce_rubro` FOREIGN KEY (`id_rubro`) REFERENCES `cat_rubro` (`id`);

--
-- Constraints for table `criterios_supervision`
--
ALTER TABLE `criterios_supervision`
  ADD CONSTRAINT `fk_cs_rubro` FOREIGN KEY (`id_rubro`) REFERENCES `cat_rubro` (`id`);

--
-- Constraints for table `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `docente_materia`
--
ALTER TABLE `docente_materia`
  ADD CONSTRAINT `fk_dm_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dm_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`);

--
-- Constraints for table `evaluacion_criterios`
--
ALTER TABLE `evaluacion_criterios`
  ADD CONSTRAINT `fk_ec_criter` FOREIGN KEY (`id_evacriterio`) REFERENCES `criterios_evaluacion` (`id_evacriterio`),
  ADD CONSTRAINT `fk_ec_eval` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluacion_docente` (`id_evaluacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluacion_docente`
--
ALTER TABLE `evaluacion_docente`
  ADD CONSTRAINT `fk_ed_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda_supervisor` (`id_agenda`),
  ADD CONSTRAINT `fk_ed_alumno` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`id_alumno`),
  ADD CONSTRAINT `fk_ed_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ed_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Constraints for table `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `fk_grupo_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`),
  ADD CONSTRAINT `fk_grupo_ciclo` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclo_escolar` (`id_ciclo`),
  ADD CONSTRAINT `fk_grupo_modalidad` FOREIGN KEY (`id_modalidad`) REFERENCES `cat_modalidad` (`id`),
  ADD CONSTRAINT `fk_grupo_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `cat_nivel` (`id`),
  ADD CONSTRAINT `fk_grupo_turno` FOREIGN KEY (`id_turno`) REFERENCES `cat_turno` (`id`);

--
-- Constraints for table `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `fk_horario_ciclo` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclo_escolar` (`id_ciclo`),
  ADD CONSTRAINT `fk_horario_dia` FOREIGN KEY (`id_dia`) REFERENCES `cat_dia` (`id`);

--
-- Constraints for table `inscripcion_grupo`
--
ALTER TABLE `inscripcion_grupo`
  ADD CONSTRAINT `fk_ig_alumno` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`id_alumno`),
  ADD CONSTRAINT `fk_ig_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Constraints for table `login_usuario`
--
ALTER TABLE `login_usuario`
  ADD CONSTRAINT `fk_login_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `materia_horario`
--
ALTER TABLE `materia_horario`
  ADD CONSTRAINT `fk_mh_horario` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`),
  ADD CONSTRAINT `fk_mh_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`);

--
-- Constraints for table `plantel_carrera`
--
ALTER TABLE `plantel_carrera`
  ADD CONSTRAINT `fk_pc_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`),
  ADD CONSTRAINT `fk_pc_plantel` FOREIGN KEY (`id_plantel`) REFERENCES `plantel` (`id_plantel`);

--
-- Constraints for table `plantel_turno`
--
ALTER TABLE `plantel_turno`
  ADD CONSTRAINT `fk_pt_dia` FOREIGN KEY (`id_dia`) REFERENCES `cat_dia` (`id`),
  ADD CONSTRAINT `fk_pt_plantel` FOREIGN KEY (`id_plantel`) REFERENCES `plantel` (`id_plantel`),
  ADD CONSTRAINT `fk_pt_turno` FOREIGN KEY (`id_turno`) REFERENCES `cat_turno` (`id`);

--
-- Constraints for table `plan_estudio`
--
ALTER TABLE `plan_estudio`
  ADD CONSTRAINT `fk_pe_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_carrera`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pe_materia` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pe_modalidad` FOREIGN KEY (`id_modalidad`) REFERENCES `cat_modalidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pe_nivel` FOREIGN KEY (`id_cat_nivel`) REFERENCES `cat_nivel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supervision_criterios`
--
ALTER TABLE `supervision_criterios`
  ADD CONSTRAINT `fk_sc_criterio` FOREIGN KEY (`id_supcriterio`) REFERENCES `criterios_supervision` (`id_supcriterio`),
  ADD CONSTRAINT `fk_sc_supervision` FOREIGN KEY (`id_supervision`) REFERENCES `supervision_docente` (`id_supervision`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supervision_docente`
--
ALTER TABLE `supervision_docente`
  ADD CONSTRAINT `fk_sd_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda_supervisor` (`id_agenda`),
  ADD CONSTRAINT `fk_sd_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `cat_rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
