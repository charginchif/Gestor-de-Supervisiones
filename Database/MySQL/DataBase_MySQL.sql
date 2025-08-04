

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone="+00:00";

CREATE TABLE Usuario (
  `id` `SERIAL` PRIMARY KEY, -- Identificador único
  `nombre` `VARCHAR(100)` NOT NULL, -- Nombre del usuario
  `apellido_paterno` `VARCHAR(100)` NOT NULL,
  `apellido_materno` `VARCHAR(100)` NOT NULL,
  `correo` `VARCHAR(100)` UNIQUE NOT NULL, -- Correo único
  `contrasena` `TEXT` NOT NULL, -- Contraseña
  `rol` `tipo_rol` NOT NULL, -- Rol definido con tipo ENUM
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, -- Fecha de creación
  ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Último acceso registrado
) TYPE=MyISAM;

CREATE TABLE `Coordinador` (
  `id_coordinador` `SERIAL` PRIMARY KEY,
  `usuario_id` `INTEGER` UNIQUE -- Referencia a tabla Usuario
) TYPE=MyISAM;

CREATE TABLE `Alumno` (
  `id_alumno` `SERIAL` PRIMARY KEY,
  `matricula` `VARCHAR(15)` NOT NULL UNIQUE,
  `usuario_id` `INTEGER` UNIQUE,
  `id_carrera` `INTEGER` NOT NULL -- Carrera que cursa
) TYPE=MyISAM;

CREATE TABLE `Docente` (
  `id_docente` `SERIAL` PRIMARY KEY, 
  `usuario_id` `INTEGER` UNIQUE,
  `grado_academico` VARCHAR(80) NOT NULL
) TYPE=MyISAM;

CREATE TABLE `DocenteMateria` (
  `id_docente` `INTEGER`,
  `id_materia` `INTEGER`,
  PRIMARY KEY (`id_docente`, `id_materia`)
) TYPE=MyISAM;

CREATE TABLE `Plantel` ( 
  `id_plantel` `SERIAL` PRIMARY KEY,
  `nombre` `VARCHAR(100)` NOT NULL,
  `ubicacion` `VARCHAR(150)` -- Dirección u otra descripción
) TYPE=MyISAM;

CREATE TABLE `PlantelTurno` (
  `id_plantel_turno` SERIAL PRIMARY KEY,
  `id_plantel` INTEGER NOT NULL,
  `hora_descanso` TIME NULL,
  `duracion_bloques` INTEGER NOT NULL,
  `duracion_descanso` INTEGER NOT NULL,
  `dia` dia_semana NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fin` TIME NOT NULL,
  `turno` tipo_turno NOT NULL
) TYPE=MyISAM;

CREATE TABLE `Carrera` (
  `id_carrera` `SERIAL` PRIMARY KEY,
  `nombre` `VARCHAR(100)` NOT NULL
) TYPE=MyISAM;

CREATE TABLE `CarreraMateria` (
  `id_carrera` `INTEGER`,
  `id_materia` `INTEGER` UNIQUE,
  PRIMARY KEY (`id_materia`, `id_carrera`)
) TYPE=MyISAM;

CREATE TABLE `CicloEscolar` (
  `id_ciclo` `SERIAL` PRIMARY KEY,
  `año` `INTEGER` NOT NULL,
  `periodo` `tipo_periodo` NOT NULL
) TYPE=MyISAM;

CREATE TABLE `Materia` (
  `id_materia` `SERIAL` PRIMARY KEY,
  `nombre` `VARCHAR(100)` NOT NULL,
  `nivel` tipo_nivel NOT NULL
) TYPE=MyISAM;

CREATE TABLE `CarreraGrupo` (
  `id_carrera` `INTEGER`,
  `id_grupo` `INTEGER` UNIQUE,
  PRIMARY KEY (`id_grupo`, `id_carrera`)
) TYPE=MyISAM;

CREATE TABLE `Grupo` (
  `id_grupo` `SERIAL` PRIMARY KEY,
  `acronimo` `VARCHAR(15)` NOT NULL,
  `id_ciclo` `INTEGER`,
  `turno` tipo_turno NOT NULL,
  `nivel` nivel NOT NULL,
  `id_carrera` `INTEGER` NOT NULL
) TYPE=MyISAM;

CREATE TABLE `PlantelCarrera` (
  `id_plantel` `INTEGER`,
  `id_carrera` `INTEGER`,
  PRIMARY KEY (`id_plantel`, `id_carrera`)
) TYPE=MyISAM;

CREATE TABLE `SupervisionDocente` (
  `id_supervision` `SERIAL` PRIMARY KEY,
  `id_docente` `INTEGER`,
  `id_agenda` `INTEGER`,
  `observaciones` `TEXT`,
  `tema` `TEXT` NOT NULL
) TYPE=MyISAM;

CREATE TABLE `SupervisionCriterios` (
  `id_supervision` INTEGER,
  `id_supcriterio` INTEGER,
  estado binary NOT NULL,
  PRIMARY KEY (`id_supervision`, `id_supcriterio`)
) TYPE=MyISAM;

CREATE TABLE `EvaluacionCriterios` (
  `id_evaluacion` INTEGER,
  `id_evacriterio` INTEGER,
  `estado` binary NOT NULL,
  PRIMARY KEY (`id_evaluacion`, `id_evacriterio`)
) TYPE=MyISAM;

CREATE TABLE `EvaluacionDocente` (
  `id_evaluacion` `SERIAL` PRIMARY KEY,
  `id_alumno` `INTEGER`,
  `id_grupo` `INTEGER`,
  `id_docente` `INTEGER`,
  `id_agenda` `INTEGER`
) TYPE=MyISAM;

CREATE TABLE `AgendaSupervisor` (
  `id_agenda` `SERIAL` PRIMARY KEY,
  `fecha` `DATE` NOT NULL,
  `id_coordinador` `INTEGER`,
  `id_horario` `INTEGER`,
  `estado` binary NOT NULL,
  `prioridad` binary NOT NULL
) TYPE=MyISAM;

CREATE TABLE `Horario` (
  `id_horario` `SERIAL` PRIMARY KEY,
  `id_ciclo` `INTEGER`,
  `dia` `tipo_dia` NOT NULL,
  `hora_inicio` `TIME` NOT NULL,
  `hora_fin` `TIME` NOT NULL
) TYPE=MyISAM;

CREATE TABLE `MateriaHorario` (
  `id_materia` `INTEGER`,
  `id_horario` `INTEGER` UNIQUE,
  PRIMARY KEY (`id_materia`, `id_horario`)
) TYPE=MyISAM;

CREATE TABLE `IncripcionGrupo` (
  `id_grupo` `INTEGER`,
  `id_alumno` `INTEGER` UNIQUE,
  PRIMARY KEY (`id_grupo`, `id_alumno`)
) TYPE=MyISAM;

CREATE TABLE `CoordinadorCarrera` (
  `id_coordinador` `INTEGER`,
  `id_carrera` `INTEGER` UNIQUE,
  PRIMARY KEY (`id_coordinador`, `id_carrera`)
) TYPE=MyISAM;

CREATE TABLE `CriteriosSupervicion` (
  `id_supcriterio` `SERIAL` PRIMARY KEY,
  `descripcion` `TEXT` NOT NULL,
  `rubro` rubro NOT NULL
) TYPE=MyISAM;

CREATE TABLE `CriteriosEvaluacion` (
  `id_evacriterio` SERIAL PRIMARY KEY,
  `descripcion` TEXT NOT NULL,
  `rubro` rubro NOT NULL
) TYPE=MyISAM;

CREATE TABLE login_usuario (
  id_log SERIAL PRIMARY KEY,
  id_usuario INTEGER  REFERENCES Usuario(id_usuario),
  actividad TEXT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)



-- Relaciones (FOREIGN KEYS)

-- Relación alumno → usuario
ALTER TABLE `Alumno`

-- Relación alumno → carrera
ALTER TABLE `Alumno`

-- Relación coordinador → usuario
ALTER TABLE `Coordinador`

-- CoordinadorCarrera con sus llaves foráneas
ALTER TABLE `CoordinadorCarrera`

-- Relación docente → usuario
ALTER TABLE `Docente`

-- Relación docente-materia
ALTER TABLE `DocenteMateria`

-- Evaluación docente → alumno, grupo, docente, agenda
ALTER TABLE `EvaluacionDocente`

-- Evaluación criterios → evaluación, criterios
ALTER TABLE `EvaluacionCriterios`

-- Supervisión docente → docente, agenda
ALTER TABLE `SupervisionDocente`

-- Supervisión criterios → supervisión, criterio
ALTER TABLE `SupervisionCriterios`

-- Grupo → ciclo, carrera
ALTER TABLE `Grupo`

-- CarreraGrupo relaciones
ALTER TABLE `CarreraGrupo`

-- Inscripción de grupo
ALTER TABLE `IncripcionGrupo`

-- Relación plantel ↔ carrera
ALTER TABLE `PlantelCarrera`

-- Agenda de supervisión → coordinador y horario
ALTER TABLE `AgendaSupervisor`

-- Horario → ciclo
ALTER TABLE `Horario`

-- Relación materia ↔ horario
ALTER TABLE `MateriaHorario`

-- Relación carrera ↔ materia
ALTER TABLE `CarreraMateria`
