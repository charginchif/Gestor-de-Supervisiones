CREATE TABLE Usuario (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena TEXT NOT NULL,
    rol VARCHAR(20) NOT NULL CHECK (
        rol IN ('administrador', 'docente', 'coordinador', 'alumno')
    ),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "Coordinador" (
  "id_coordinador" "SERIAL" PRIMARY KEY,
  "usuario_id" "INTEGER" UNIQUE
);

CREATE TABLE "Alumno" (
  "id_alumno" "SERIAL" PRIMARY KEY,
  "matricula" "VARCHAR(15)" NOT NULL UNIQUE,
  "usuario_id" "INTEGER" UNIQUE
  "id_carrera" "INTEGER" NOT NULL
);

CREATE TABLE "Docente" (
  "id_docente" "SERIAL" PRIMARY KEY, 
  "usuario_id" "INTEGER" UNIQUE
  "grado_academico" VARCHAR(80) NOT NULL,
);

CREATE TABLE "DocenteMateria" (
  "id_docente" "INTEGER",
  "id_materia" "INTEGER",
  PRIMARY KEY ("id_docente", "id_materia")
);

CREATE TABLE "Plantel" ( 
  "id_plantel" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL,
  "ubicacion" "VARCHAR(150)"
);

CREATE TYPE tipo_turno AS ENUM ('Matutino', 'Vespertino', 'Sabatino');
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

CREATE TABLE "Carrera" (
  "id_carrera" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL
);

CREATE TABLE "CarreraMateria" (
  "id_carrera" "INTEGER",
  "id_materia" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_materia", "id_carrera")
);

CREATE TABLE "CicloEscolar" (
  "id_ciclo" "SERIAL" PRIMARY KEY,
  "año" "INTEGER" NOT NULL,
  periodo VARCHAR(1) NOT NULL CHECK (
      periodo IN ('A', 'B', 'C')
  )
);

CREATE TYPE nivel AS ENUM ('1°', '2°', '3°', '4°', '5°', '6°', '7°', '8°', '9°', '10°');
CREATE TABLE "Materia" (
  "id_materia" "SERIAL" PRIMARY KEY,
  "nombre" "VARCHAR(100)" NOT NULL
  "nivel" nivel NOT NULL
);

CREATE TABLE "CarreraGrupo" (
  "id_carrera" "INTEGER",
  "id_grupo" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_grupo", "id_carrera")
);

CREATE TABLE "Grupo" (
  "id_grupo" "SERIAL" PRIMARY KEY,
  "acronimo" "VARCHAR(15)" NOT NULL,
  "id_ciclo" "INTEGER",
  "turno" tipo_turno NOT NULL,
  "nivel" nivel NOT NULL,
  "id_carrera" "INTEGER" NOT NULL
);

CREATE TABLE "PlantelCarrera" (
  "id_plantel" "INTEGER",
  "id_carrera" "INTEGER",
  PRIMARY KEY ("id_plantel", "id_carrera")
);

CREATE TABLE "SupervisionDocente" (
  "id_supervision" "SERIAL" PRIMARY KEY,
  "id_docente" "INTEGER",
  "id_agenda" "INTEGER",
  "observaciones" "TEXT",
  "tema" "TEXT" NOT NULL
);

CREATE TABLE "SupervisionCriterios" (
  "id_supervision" INTEGER,
  "id_supcriterio" INTEGER,
  estado binary NOT NULL,
  PRIMARY KEY ("id_supervision", "id_supcriterio")
);

CREATE TABLE "EvaluacionCriterios" (
  "id_evaluacion" INTEGER,
  "id_evacriterio" INTEGER,
  estado binary NOT NULL,
  PRIMARY KEY ("id_evaluacion", "id_evacriterio")
);

CREATE TABLE "EvaluacionDocente" (
  "id_alumno" "INTEGER",
  "id_grupo" "INTEGER",
  "id_docente" "INTEGER",
  "id_agenda" "INTEGER",
);

CREATE TABLE "AgendaSupervisor" (
  "id_agenda" "SERIAL" PRIMARY KEY,
  "fecha" "DATE" NOT NULL,
  "id_coordinador" "INTEGER",
  "id_horario" "INTEGER",
  "estado" VARCHAR(20) NOT NULL CHECK (
      estado IN ('Supervizado', 'No Supervizado')
  ),
  prioridad VARCHAR(8) NOT NULL CHECK (
      prioridad IN ('Urgente', 'Normal')
  )
);

CREATE TABLE "Horario" (
  "id_horario" "SERIAL" PRIMARY KEY,
  "id_ciclo" "INTEGER",
  dia VARCHAR(20) NOT NULL CHECK (
      dia IN ('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado')
  ),
  "hora_inicio" "TIME" NOT NULL,
  "hora_fin" "TIME" NOT NULL
);

CREATE TABLE "MateriaHorario" (
  "id_materia" "INTEGER",
  "id_horario" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_materia", "id_horario")
);

CREATE TABLE "IncripcionGrupo" (
  "id_grupo" "INTEGER",
  "id_alumno" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_grupo", "id_alumno")
);

CREATE TABLE "CoordinadorCarrera" (
  "id_coordinador" "INTEGER",
  "id_carrera" "INTEGER" UNIQUE,
  PRIMARY KEY ("id_coordinador", "id_carrera")
);

CREATE TYPE rubro AS ENUM ('Inicio de Clase', 'Desarrollo de Clase', 'Cierre de Clase', 'Desempeño General');
CREATE TABLE "CriteriosSupervicion" (
  "id_supcriterio" "SERIAL" PRIMARY KEY,
  "descripcion" "TEXT" NOT NULL,
  "rubro" rubro NOT NULL
);

CREATE TABLE "CriteriosEvaluacion" (
  "id_evacriterio" SERIAL PRIMARY KEY,
  "descripcion" TEXT NOT NULL,
  "rubro" rubro NOT NULL
);

