-- =========================================
-- Catálogos (reemplazan a los ENUM nativos)
-- =========================================

CREATE TABLE cat_rol (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_rol (nombre) VALUES
('administrador'),('docente'),('coordinador'),('alumno');

CREATE TABLE cat_turno (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_turno (nombre) VALUES
('Matutino'),('Vespertino'),('Sabatino');

CREATE TABLE cat_dia (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(12) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_dia (nombre) VALUES
('Lunes'),('Martes'),('Miercoles'),('Jueves'),('Viernes'),('Sabado');

CREATE TABLE cat_periodo (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre CHAR(1) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_periodo (nombre) VALUES ('A'),('B'),('C');

CREATE TABLE cat_nivel (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(4) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_nivel (nombre) VALUES
('1°'),('2°'),('3°'),('4°'),('5°'),('6°'),('7°'),('8°'),('9°'),('10°');

CREATE TABLE cat_rubro (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO cat_rubro (nombre) VALUES
('Inicio de Clase'),
('Desarrollo de Clase'),
('Cierre de Clase'),
('Desempeño General');

-- =========================================
-- Principales
-- =========================================

CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido_paterno VARCHAR(100) NOT NULL,
  apellido_materno VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena TEXT NOT NULL,
  id_rol TINYINT UNSIGNED NOT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ultimo_acceso TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol) REFERENCES cat_rol(id)
) ENGINE=InnoDB;

CREATE TABLE coordinador (
  id_coordinador INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNIQUE,
  CONSTRAINT fk_coordinador_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(id)
) ENGINE=InnoDB;

CREATE TABLE carrera (
  id_carrera INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE alumno (
  id_alumno INT AUTO_INCREMENT PRIMARY KEY,
  matricula VARCHAR(15) NOT NULL UNIQUE,
  usuario_id INT UNIQUE,
  id_carrera INT NOT NULL,
  CONSTRAINT fk_alumno_usuario  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  CONSTRAINT fk_alumno_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera)
) ENGINE=InnoDB;

CREATE TABLE docente (
  id_docente INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNIQUE,
  grado_academico VARCHAR(80) NOT NULL,
  CONSTRAINT fk_docente_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(id)
) ENGINE=InnoDB;

CREATE TABLE materia (
  id_materia INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  id_nivel TINYINT UNSIGNED NOT NULL,
  CONSTRAINT uq_materia UNIQUE (nombre, id_nivel),
  CONSTRAINT fk_materia_nivel FOREIGN KEY (id_nivel) REFERENCES cat_nivel(id)
) ENGINE=InnoDB;

CREATE TABLE docente_materia (
  id_docente INT,
  id_materia INT,
  PRIMARY KEY (id_docente, id_materia),
  CONSTRAINT fk_dm_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente),
  CONSTRAINT fk_dm_materia FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
) ENGINE=InnoDB;

CREATE TABLE plantel (
  id_plantel INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  ubicacion VARCHAR(150)
) ENGINE=InnoDB;

CREATE TABLE ciclo_escolar (
  id_ciclo INT AUTO_INCREMENT PRIMARY KEY,
  anio SMALLINT NOT NULL,
  id_periodo TINYINT UNSIGNED NOT NULL,
  CONSTRAINT uq_ciclo UNIQUE (anio, id_periodo),
  CONSTRAINT fk_ciclo_periodo FOREIGN KEY (id_periodo) REFERENCES cat_periodo(id)
) ENGINE=InnoDB;

CREATE TABLE grupo (
  id_grupo INT AUTO_INCREMENT PRIMARY KEY,
  acronimo VARCHAR(15) NOT NULL,
  id_ciclo INT,
  id_turno TINYINT UNSIGNED NOT NULL,
  id_nivel TINYINT UNSIGNED NOT NULL,
  id_carrera INT NOT NULL,
  CONSTRAINT uq_grupo UNIQUE (acronimo, id_ciclo),
  CONSTRAINT fk_grupo_ciclo   FOREIGN KEY (id_ciclo)   REFERENCES ciclo_escolar(id_ciclo),
  CONSTRAINT fk_grupo_turno   FOREIGN KEY (id_turno)   REFERENCES cat_turno(id),
  CONSTRAINT fk_grupo_nivel   FOREIGN KEY (id_nivel)   REFERENCES cat_nivel(id),
  CONSTRAINT fk_grupo_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera)
) ENGINE=InnoDB;

CREATE TABLE carrera_grupo (
  id_carrera INT,
  id_grupo INT UNIQUE,
  PRIMARY KEY (id_grupo, id_carrera),
  CONSTRAINT fk_cg_grupo   FOREIGN KEY (id_grupo)   REFERENCES grupo(id_grupo),
  CONSTRAINT fk_cg_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera)
) ENGINE=InnoDB;

CREATE TABLE plantel_turno (
  id_plantel_turno INT AUTO_INCREMENT PRIMARY KEY,
  id_plantel INT NOT NULL,
  hora_descanso TIME NULL,
  duracion_bloques INT NOT NULL,
  duracion_descanso INT NOT NULL,
  id_dia TINYINT UNSIGNED NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  id_turno TINYINT UNSIGNED NOT NULL,
  CONSTRAINT fk_pt_plantel FOREIGN KEY (id_plantel) REFERENCES plantel(id_plantel),
  CONSTRAINT fk_pt_dia     FOREIGN KEY (id_dia)     REFERENCES cat_dia(id),
  CONSTRAINT fk_pt_turno   FOREIGN KEY (id_turno)   REFERENCES cat_turno(id)
) ENGINE=InnoDB;

CREATE TABLE plantel_carrera (
  id_plantel INT,
  id_carrera INT,
  PRIMARY KEY (id_plantel, id_carrera),
  CONSTRAINT fk_pc_plantel FOREIGN KEY (id_plantel) REFERENCES plantel(id_plantel),
  CONSTRAINT fk_pc_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera)
) ENGINE=InnoDB;

CREATE TABLE horario (
  id_horario INT AUTO_INCREMENT PRIMARY KEY,
  id_ciclo INT,
  id_dia TINYINT UNSIGNED NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  CONSTRAINT fk_horario_ciclo FOREIGN KEY (id_ciclo) REFERENCES ciclo_escolar(id_ciclo),
  CONSTRAINT fk_horario_dia   FOREIGN KEY (id_dia)   REFERENCES cat_dia(id)
) ENGINE=InnoDB;

CREATE TABLE materia_horario (
  id_materia INT,
  id_horario INT UNIQUE,
  PRIMARY KEY (id_materia, id_horario),
  CONSTRAINT fk_mh_materia FOREIGN KEY (id_materia) REFERENCES materia(id_materia),
  CONSTRAINT fk_mh_horario FOREIGN KEY (id_horario) REFERENCES horario(id_horario)
) ENGINE=InnoDB;

CREATE TABLE agenda_supervisor (
  id_agenda INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  id_coordinador INT,
  id_horario INT,
  estado TINYINT(1) NOT NULL,    -- BOOLEAN en MySQL
  prioridad TINYINT(1) NOT NULL, -- BOOLEAN en MySQL
  CONSTRAINT fk_agenda_coord   FOREIGN KEY (id_coordinador) REFERENCES coordinador(id_coordinador),
  CONSTRAINT fk_agenda_horario FOREIGN KEY (id_horario)     REFERENCES horario(id_horario)
) ENGINE=InnoDB;

CREATE TABLE supervision_docente (
  id_supervision INT AUTO_INCREMENT PRIMARY KEY,
  id_docente INT,
  id_agenda INT,
  observaciones TEXT,
  tema TEXT NOT NULL,
  CONSTRAINT fk_sd_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente),
  CONSTRAINT fk_sd_agenda  FOREIGN KEY (id_agenda)  REFERENCES agenda_supervisor(id_agenda)
) ENGINE=InnoDB;

CREATE TABLE carrera_materia (
  id_carrera INT NOT NULL,
  id_materia INT NOT NULL,
  PRIMARY KEY (id_materia, id_carrera),
  CONSTRAINT fk_carrmateria_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera),
  CONSTRAINT fk_carrmateria_materia FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
);

CREATE TABLE criterios_supervision (
  id_supcriterio INT AUTO_INCREMENT PRIMARY KEY,
  descripcion TEXT NOT NULL,
  id_rubro TINYINT UNSIGNED NOT NULL,
  CONSTRAINT fk_cs_rubro FOREIGN KEY (id_rubro) REFERENCES cat_rubro(id)
) ENGINE=InnoDB;

CREATE TABLE supervision_criterios (
  id_supervision INT,
  id_supcriterio INT,
  estado TINYINT(1) NOT NULL,
  PRIMARY KEY (id_supervision, id_supcriterio),
  CONSTRAINT fk_sc_supervision FOREIGN KEY (id_supervision)  REFERENCES supervision_docente(id_supervision),
  CONSTRAINT fk_sc_criterio    FOREIGN KEY (id_supcriterio)  REFERENCES criterios_supervision(id_supcriterio)
) ENGINE=InnoDB;

CREATE TABLE criterios_evaluacion (
  id_evacriterio INT AUTO_INCREMENT PRIMARY KEY,
  descripcion TEXT NOT NULL,
  id_rubro TINYINT UNSIGNED NOT NULL,
  CONSTRAINT fk_ce_rubro FOREIGN KEY (id_rubro) REFERENCES cat_rubro(id)
) ENGINE=InnoDB;

CREATE TABLE evaluacion_docente (
  id_evaluacion INT AUTO_INCREMENT PRIMARY KEY,
  id_alumno INT,
  id_grupo INT,
  id_docente INT,
  id_agenda INT,
  CONSTRAINT fk_ed_alumno  FOREIGN KEY (id_alumno)  REFERENCES alumno(id_alumno),
  CONSTRAINT fk_ed_grupo   FOREIGN KEY (id_grupo)   REFERENCES grupo(id_grupo),
  CONSTRAINT fk_ed_docente FOREIGN KEY (id_docente) REFERENCES docente(id_docente),
  CONSTRAINT fk_ed_agenda  FOREIGN KEY (id_agenda)  REFERENCES agenda_supervisor(id_agenda)
) ENGINE=InnoDB;

CREATE TABLE evaluacion_criterios (
  id_evaluacion INT,
  id_evacriterio INT,
  estado TINYINT(1) NOT NULL,
  PRIMARY KEY (id_evaluacion, id_evacriterio),
  CONSTRAINT fk_ec_eval   FOREIGN KEY (id_evaluacion)   REFERENCES evaluacion_docente(id_evaluacion),
  CONSTRAINT fk_ec_criter FOREIGN KEY (id_evacriterio)  REFERENCES criterios_evaluacion(id_evacriterio)
) ENGINE=InnoDB;

CREATE TABLE inscripcion_grupo (
  id_grupo INT,
  id_alumno INT UNIQUE,
  PRIMARY KEY (id_grupo, id_alumno),
  CONSTRAINT fk_ig_grupo  FOREIGN KEY (id_grupo)  REFERENCES grupo(id_grupo),
  CONSTRAINT fk_ig_alumno FOREIGN KEY (id_alumno) REFERENCES alumno(id_alumno)
) ENGINE=InnoDB;

CREATE TABLE coordinador_carrera (
  id_coordinador INT,
  id_carrera INT UNIQUE,
  PRIMARY KEY (id_coordinador, id_carrera),
  CONSTRAINT fk_cc_coord  FOREIGN KEY (id_coordinador) REFERENCES coordinador(id_coordinador),
  CONSTRAINT fk_cc_carrera FOREIGN KEY (id_carrera) REFERENCES carrera(id_carrera)
) ENGINE=InnoDB;

CREATE TABLE login_usuario (
  id_log INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT,
  actividad TEXT NOT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_login_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id)
) ENGINE=InnoDB;

-- =========================================
-- Índices sugeridos (performance)
-- =========================================
CREATE INDEX idx_usuario_correo ON usuario(correo);
CREATE INDEX idx_alumno_matricula ON alumno(matricula);
CREATE INDEX idx_docente_usuario ON docente(usuario_id);
CREATE INDEX idx_grupo_ciclo ON grupo(id_ciclo);
CREATE INDEX idx_plantel_turno ON plantel_turno(id_plantel, id_turno, id_dia);
CREATE INDEX idx_horario_ciclo_dia ON horario(id_ciclo, id_dia);
