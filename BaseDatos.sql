-- Crear tipo enumerado para los roles de usuario
CREATE TYPE tipo_rol AS ENUM ('administrador', 'docente', 'coordinador', 'alumno');

-- Tabla principal de usuarios del sistema
CREATE TABLE Usuario (
  "id" "SERIAL" PRIMARY KEY, -- Identificador único
  "nombre" "VARCHAR(100)" NOT NULL, -- Nombre del usuario
  "apellido_paterno" "VARCHAR(100)" NOT NULL,
  "apellido_materno" "VARCHAR(100)" NOT NULL,
  "correo" "VARCHAR(100)" UNIQUE NOT NULL, -- Correo único
  "contrasena" "TEXT" NOT NULL, -- Contraseña
  "rol" "tipo_rol" NOT NULL, -- Rol definido con tipo ENUM
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, -- Fecha de creación
  ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Último acceso registrado
);

-- Tabla para los coordinadores, vinculados a un usuario
CREATE TABLE "Coordinador" (
  "id_coordinador" "SERIAL" PRIMARY KEY,
  "usuario_id" "INTEGER" UNIQUE -- Referencia a tabla Usuario
);

-- Tabla para los alumnos, con matrícula, carrera y relación al usuario
CREATE TABLE "Alumno" (
  "id_alumno" "SERIAL" PRIMARY KEY,
  "matricula" "VARCHAR(15)" NOT NULL UNIQUE,
  "usuario_id" "INTEGER" UNIQUE,
  "id_carrera" "INTEGER" NOT NULL -- Carrera que cursa
);

-- Tabla de docentes vinculados a usuario y con grado académico
CREATE TABLE "Docente" (
  "id_docente" "SERIAL" PRIMARY KEY, 
  "usuario_id" "INTEGER" UNIQUE,
  "grado_academico" VARCHAR(80) NOT NULL
);

-- Relación muchos a muchos entre docentes y materias
CREATE TABLE "DocenteMateria" (
  "id_docente" "INTEGER",
  "id_materia" "INTEGER",
  PRIMARY KEY ("id_docente", "id_materia")
);

-- Planteles o campus educativos
CREATE TABLE "Plantel" ( 
  "id_plantel" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL,
  "ubicacion" "VARCHAR(150)" -- Dirección u otra descripción
);

-- Tipo de turno escolar
CREATE TYPE tipo_turno AS ENUM ('Matutino', 'Vespertino', 'Sabatino');

-- Horarios por plantel y turno
CREATE TABLE "PlantelTurno" (
  "id_plantel_turno" SERIAL PRIMARY KEY,
  "id_plantel" INTEGER NOT NULL,
  "hora_descanso" TIME NULL,
  "duracion_bloques" INTEGER NOT NULL,
  "duracion_descanso" INTEGER NOT NULL,
  "dia" dia_semana NOT NULL,
  "hora_inicio" TIME NOT NULL,
  "hora_fin" TIME NOT NULL,
  "turno" tipo_turno NOT NULL,
  CONSTRAINT fk_plantel_turno FOREIGN KEY ("id_plantel") REFERENCES "Plantel" ("id_plantel")
);

-- Carreras disponibles en el sistema
CREATE TABLE "Carrera" (
  "id_carrera" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL
);

-- Relación entre carrera y materia
CREATE TABLE "CarreraMateria" (
  "id_carrera" "INTEGER",
  "id_materia" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_materia", "id_carrera")
);

-- Tipo de periodo escolar
CREATE TYPE tipo_periodo AS ENUM ('A', 'B', 'C');

-- Ciclo escolar con año y periodo
CREATE TABLE "CicloEscolar" (
  "id_ciclo" "SERIAL" PRIMARY KEY,
  "año" "INTEGER" NOT NULL,
  "periodo" "tipo_periodo" NOT NULL
);

-- Nivel académico de materias
CREATE TYPE tipo_nivel AS ENUM ('1°', '2°', '3°', '4°', '5°', '6°', '7°', '8°', '9°', '10°');

-- Materias impartidas con su nivel
CREATE TABLE "Materia" (
  "id_materia" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL,
  "nivel" tipo_nivel NOT NULL
);

-- Relación entre grupo y carrera
CREATE TABLE "CarreraGrupo" (
  "id_carrera" "INTEGER",
  "id_grupo" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_grupo", "id_carrera")
);

-- Grupos de estudiantes, con turno y nivel
CREATE TABLE "Grupo" (
  "id_grupo" "SERIAL" PRIMARY KEY,
  "acronimo" "VARCHAR(15)" NOT NULL,
  "id_ciclo" "INTEGER",
  "turno" tipo_turno NOT NULL,
  "nivel" nivel NOT NULL,
  "id_carrera" "INTEGER" NOT NULL
);

-- Relación entre planteles y carreras
CREATE TABLE "PlantelCarrera" (
  "id_plantel" "INTEGER",
  "id_carrera" "INTEGER",
  PRIMARY KEY ("id_plantel", "id_carrera")
);

-- Supervisión hecha al docente por parte del coordinador
CREATE TABLE "SupervisionDocente" (
  "id_supervision" "SERIAL" PRIMARY KEY,
  "id_docente" "INTEGER",
  "id_agenda" "INTEGER",
  "observaciones" "TEXT",
  "tema" "TEXT" NOT NULL
);

-- Criterios evaluados en una supervisión docente
CREATE TABLE "SupervisionCriterios" (
  "id_supervision" INTEGER,
  "id_supcriterio" INTEGER,
  estado binary NOT NULL,
  PRIMARY KEY ("id_supervision", "id_supcriterio")
);

-- Evaluación hecha a docente por alumnos
CREATE TABLE "EvaluacionCriterios" (
  "id_evaluacion" INTEGER,
  "id_evacriterio" INTEGER,
  "estado" binary NOT NULL,
  PRIMARY KEY ("id_evaluacion", "id_evacriterio")
);

-- Relación de evaluación docente por parte del alumno
CREATE TABLE "EvaluacionDocente" (
  "id_evaluacion" "SERIAL" PRIMARY KEY,
  "id_alumno" "INTEGER",
  "id_grupo" "INTEGER",
  "id_docente" "INTEGER",
  "id_agenda" "INTEGER"
);

-- Agenda para supervisión
CREATE TABLE "AgendaSupervisor" (
  "id_agenda" "SERIAL" PRIMARY KEY,
  "fecha" "DATE" NOT NULL,
  "id_coordinador" "INTEGER",
  "id_horario" "INTEGER",
  "estado" binary NOT NULL,
  "prioridad" binary NOT NULL
);

-- Tipos de días para horarios
CREATE TYPE tipo_dia AS ENUM ('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');

-- Horarios disponibles para materias o supervisiones
CREATE TABLE "Horario" (
  "id_horario" "SERIAL" PRIMARY KEY,
  "id_ciclo" "INTEGER",
  "dia" "tipo_dia" NOT NULL,
  "hora_inicio" "TIME" NOT NULL,
  "hora_fin" "TIME" NOT NULL
);

-- Relación materia y horario
CREATE TABLE "MateriaHorario" (
  "id_materia" "INTEGER",
  "id_horario" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_materia", "id_horario")
);

-- Inscripción de alumnos en grupos
CREATE TABLE "IncripcionGrupo" (
  "id_grupo" "INTEGER",
  "id_alumno" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_grupo", "id_alumno")
);

-- Coordinadores asignados a una carrera
CREATE TABLE "CoordinadorCarrera" (
  "id_coordinador" "INTEGER",
  "id_carrera" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_coordinador", "id_carrera")
);

-- Tipos de criterios supervisión y evaluación
CREATE TYPE rubro AS ENUM ('Inicio de Clase', 'Desarrollo de Clase', 'Cierre de Clase', 'Desempeño General');

-- Criterios usados en la supervisión docente
CREATE TABLE "CriteriosSupervicion" (
  "id_supcriterio" "SERIAL" PRIMARY KEY,
  "descripcion" "TEXT" NOT NULL,
  "rubro" rubro NOT NULL
);

-- Criterios para la evaluación docente por alumno
CREATE TABLE "CriteriosEvaluacion" (
  "id_evacriterio" SERIAL PRIMARY KEY,
  "descripcion" TEXT NOT NULL,
  "rubro" rubro NOT NULL
);


-- Relaciones (FOREIGN KEYS)

-- Relación alumno → usuario
ALTER TABLE "Alumno"
ADD CONSTRAINT fk_alumno_usuario FOREIGN KEY ("usuario_id") REFERENCES "Usuario" ("id");

-- Relación alumno → carrera
ALTER TABLE "Alumno"
ADD CONSTRAINT fk_alumno_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera");

-- Relación coordinador → usuario
ALTER TABLE "Coordinador"
ADD CONSTRAINT fk_coordinador_usuario FOREIGN KEY ("usuario_id") REFERENCES "Usuario" ("id");

-- CoordinadorCarrera con sus llaves foráneas
ALTER TABLE "CoordinadorCarrera"
ADD CONSTRAINT fk_coor_carrera_coor FOREIGN KEY ("id_coordinador") REFERENCES "Coordinador" ("id_coordinador"),
ADD CONSTRAINT fk_coor_carrera_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera");

-- Relación docente → usuario
ALTER TABLE "Docente"
ADD CONSTRAINT fk_docente_usuario FOREIGN KEY ("usuario_id") REFERENCES "Usuario" ("id");

-- Relación docente-materia
ALTER TABLE "DocenteMateria"
ADD CONSTRAINT fk_docente_materia_docente FOREIGN KEY ("id_docente") REFERENCES "Docente" ("id_docente"),
ADD CONSTRAINT fk_docente_materia_materia FOREIGN KEY ("id_materia") REFERENCES "Materia" ("id_materia");

-- Evaluación docente → alumno, grupo, docente, agenda
ALTER TABLE "EvaluacionDocente"
ADD CONSTRAINT fk_evaldoc_alumno FOREIGN KEY ("id_alumno") REFERENCES "Alumno" ("id_alumno"),
ADD CONSTRAINT fk_evaldoc_grupo FOREIGN KEY ("id_grupo") REFERENCES "Grupo" ("id_grupo"),
ADD CONSTRAINT fk_evaldoc_docente FOREIGN KEY ("id_docente") REFERENCES "Docente" ("id_docente"),
ADD CONSTRAINT fk_evaldoc_agenda FOREIGN KEY ("id_agenda") REFERENCES "AgendaSupervisor" ("id_agenda");

-- Evaluación criterios → evaluación, criterios
ALTER TABLE "EvaluacionCriterios"
ADD CONSTRAINT fk_evalcriterios_eval FOREIGN KEY ("id_evaluacion") REFERENCES "EvaluacionDocente" ("id_evaluacion"),
ADD CONSTRAINT fk_evalcriterios_criterio FOREIGN KEY ("id_evacriterio") REFERENCES "CriteriosEvaluacion" ("id_evacriterio");

-- Supervisión docente → docente, agenda
ALTER TABLE "SupervisionDocente"
ADD CONSTRAINT fk_supervdoc_docente FOREIGN KEY ("id_docente") REFERENCES "Docente" ("id_docente"),
ADD CONSTRAINT fk_supervdoc_agenda FOREIGN KEY ("id_agenda") REFERENCES "AgendaSupervisor" ("id_agenda");

-- Supervisión criterios → supervisión, criterio
ALTER TABLE "SupervisionCriterios"
ADD CONSTRAINT fk_supervcriterios_superv FOREIGN KEY ("id_supervision") REFERENCES "SupervisionDocente" ("id_supervision"),
ADD CONSTRAINT fk_supervcriterios_criterio FOREIGN KEY ("id_supcriterio") REFERENCES "CriteriosSupervicion" ("id_supcriterio");

-- Grupo → ciclo, carrera
ALTER TABLE "Grupo"
ADD CONSTRAINT fk_grupo_ciclo FOREIGN KEY ("id_ciclo") REFERENCES "CicloEscolar" ("id_ciclo"),
ADD CONSTRAINT fk_grupo_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera");

-- CarreraGrupo relaciones
ALTER TABLE "CarreraGrupo"
ADD CONSTRAINT fk_carrgrup_grupo FOREIGN KEY ("id_grupo") REFERENCES "Grupo" ("id_grupo"),
ADD CONSTRAINT fk_carrgrup_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera");

-- Inscripción de grupo
ALTER TABLE "IncripcionGrupo"
ADD CONSTRAINT fk_inscripcion_grupo FOREIGN KEY ("id_grupo") REFERENCES "Grupo" ("id_grupo"),
ADD CONSTRAINT fk_inscripcion_alumno FOREIGN KEY ("id_alumno") REFERENCES "Alumno" ("id_alumno");

-- Relación plantel ↔ carrera
ALTER TABLE "PlantelCarrera"
ADD CONSTRAINT fk_plantelcarr_plantel FOREIGN KEY ("id_plantel") REFERENCES "Plantel" ("id_plantel"),
ADD CONSTRAINT fk_plantelcarr_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera");

-- Agenda de supervisión → coordinador y horario
ALTER TABLE "AgendaSupervisor"
ADD CONSTRAINT fk_agenda_coord FOREIGN KEY ("id_coordinador") REFERENCES "Coordinador" ("id_coordinador"),
ADD CONSTRAINT fk_agenda_horario FOREIGN KEY ("id_horario") REFERENCES "Horario" ("id_horario");

-- Horario → ciclo
ALTER TABLE "Horario"
ADD CONSTRAINT fk_horario_ciclo FOREIGN KEY ("id_ciclo") REFERENCES "CicloEscolar" ("id_ciclo");

-- Relación materia ↔ horario
ALTER TABLE "MateriaHorario"
ADD CONSTRAINT fk_materiahor_materia FOREIGN KEY ("id_materia") REFERENCES "Materia" ("id_materia"),
ADD CONSTRAINT fk_materiahor_horario FOREIGN KEY ("id_horario") REFERENCES "Horario" ("id_horario");

-- Relación carrera ↔ materia
ALTER TABLE "CarreraMateria"
ADD CONSTRAINT fk_carrmateria_carrera FOREIGN KEY ("id_carrera") REFERENCES "Carrera" ("id_carrera"),
ADD CONSTRAINT fk_carrmateria_materia FOREIGN KEY ("id_materia") REFERENCES "Materia" ("id_materia");