-- =========================================
-- Utilidades (opcionales): helpers para catálogos
-- =========================================
DELIMITER $$

CREATE OR REPLACE FUNCTION fn_get_turno_id(p_nombre VARCHAR(20))
RETURNS TINYINT UNSIGNED
DETERMINISTIC
BEGIN
  DECLARE v_id TINYINT UNSIGNED;
  SELECT id INTO v_id FROM cat_turno WHERE nombre = p_nombre LIMIT 1;
  IF v_id IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Turno no encontrado';
  END IF;
  RETURN v_id;
END$$

CREATE OR REPLACE FUNCTION fn_get_dia_id(p_nombre VARCHAR(12))
RETURNS TINYINT UNSIGNED
DETERMINISTIC
BEGIN
  DECLARE v_id TINYINT UNSIGNED;
  SELECT id INTO v_id FROM cat_dia WHERE nombre = p_nombre LIMIT 1;
  IF v_id IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Día no encontrado';
  END IF;
  RETURN v_id;
END$$

CREATE OR REPLACE FUNCTION fn_get_rubro_id(p_nombre VARCHAR(40))
RETURNS TINYINT UNSIGNED
DETERMINISTIC
BEGIN
  DECLARE v_id TINYINT UNSIGNED;
  SELECT id INTO v_id FROM cat_rubro WHERE nombre = p_nombre LIMIT 1;
  IF v_id IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Rubro no encontrado';
  END IF;
  RETURN v_id;
END$$

DELIMITER ;

DELIMITER $$

-- Registrar coordinador (recibe un usuario existente) 【coordinadores】
CREATE OR REPLACE PROCEDURE sp_admin_registrar_coordinador(IN p_usuario_id INT)
BEGIN
  IF (SELECT COUNT(*) FROM usuario WHERE id = p_usuario_id) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no existe';
  END IF;
  INSERT INTO coordinador(usuario_id) VALUES (p_usuario_id);
END$$

-- Editar coordinador (cambiar el usuario asignado)
CREATE OR REPLACE PROCEDURE sp_admin_editar_coordinador(
  IN p_id_coordinador INT,
  IN p_usuario_id INT
)
BEGIN
  IF (SELECT COUNT(*) FROM coordinador WHERE id_coordinador = p_id_coordinador) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coordinador no existe';
  END IF;
  IF (SELECT COUNT(*) FROM usuario WHERE id = p_usuario_id) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no existe';
  END IF;

  UPDATE coordinador SET usuario_id = p_usuario_id WHERE id_coordinador = p_id_coordinador;
END$$

-- Eliminar coordinador
CREATE OR REPLACE PROCEDURE sp_admin_eliminar_coordinador(IN p_id_coordinador INT)
BEGIN
  IF (SELECT COUNT(*) FROM coordinador WHERE id_coordinador = p_id_coordinador) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coordinador no existe';
  END IF;
  DELETE FROM coordinador WHERE id_coordinador = p_id_coordinador;
END$$

-- Asignar coordinador a carrera (upsert) 【carreras: asignar su coordinador】
CREATE OR REPLACE PROCEDURE sp_admin_asignar_coordinador_carrera(
  IN p_id_coordinador INT,
  IN p_id_carrera INT
)
BEGIN
  IF (SELECT COUNT(*) FROM coordinador WHERE id_coordinador = p_id_coordinador) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coordinador no existe';
  END IF;
  IF (SELECT COUNT(*) FROM carrera WHERE id_carrera = p_id_carrera) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Carrera no existe';
  END IF;

  INSERT INTO coordinador_carrera(id_coordinador, id_carrera)
  VALUES (p_id_coordinador, p_id_carrera)
  ON DUPLICATE KEY UPDATE id_coordinador = VALUES(id_coordinador);
END$$

DELIMITER ;

DELIMITER $$

-- Registrar carrera 【carreras】
CREATE OR REPLACE PROCEDURE sp_admin_registrar_carrera(IN p_nombre VARCHAR(100))
BEGIN
  IF p_nombre IS NULL OR p_nombre = '' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nombre de carrera requerido';
  END IF;
  INSERT INTO carrera(nombre) VALUES (p_nombre);
END$$

-- Editar datos de la carrera
CREATE OR REPLACE PROCEDURE sp_admin_editar_carrera(
  IN p_id_carrera INT,
  IN p_nombre VARCHAR(100)
)
BEGIN
  IF (SELECT COUNT(*) FROM carrera WHERE id_carrera = p_id_carrera) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Carrera no existe';
  END IF;
  UPDATE carrera SET nombre = COALESCE(p_nombre, nombre) WHERE id_carrera = p_id_carrera;
END$$

-- Eliminar carrera
CREATE OR REPLACE PROCEDURE sp_admin_eliminar_carrera(IN p_id_carrera INT)
BEGIN
  IF (SELECT COUNT(*) FROM carrera WHERE id_carrera = p_id_carrera) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Carrera no existe';
  END IF;
  DELETE FROM carrera WHERE id_carrera = p_id_carrera;
END$$

DELIMITER ;

DELIMITER $$

-- Registrar materia 【materias】
CREATE OR REPLACE PROCEDURE sp_admin_registrar_materia(
  IN p_nombre VARCHAR(100),
  IN p_id_nivel TINYINT UNSIGNED
)
BEGIN
  IF p_nombre IS NULL OR p_nombre = '' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nombre de materia requerido';
  END IF;
  IF (SELECT COUNT(*) FROM cat_nivel WHERE id = p_id_nivel) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nivel no válido';
  END IF;
  INSERT INTO materia(nombre, id_nivel) VALUES (p_nombre, p_id_nivel);
END$$

-- Editar materia
CREATE OR REPLACE PROCEDURE sp_admin_editar_materia(
  IN p_id_materia INT,
  IN p_nombre VARCHAR(100),
  IN p_id_nivel TINYINT UNSIGNED
)
BEGIN
  IF (SELECT COUNT(*) FROM materia WHERE id_materia = p_id_materia) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Materia no existe';
  END IF;
  IF p_id_nivel IS NOT NULL AND (SELECT COUNT(*) FROM cat_nivel WHERE id = p_id_nivel) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nivel no válido';
  END IF;

  UPDATE materia
  SET nombre = COALESCE(p_nombre, nombre),
      id_nivel = COALESCE(p_id_nivel, id_nivel)
  WHERE id_materia = p_id_materia;
END$$

-- Eliminar materia
CREATE OR REPLACE PROCEDURE sp_admin_eliminar_materia(IN p_id_materia INT)
BEGIN
  IF (SELECT COUNT(*) FROM materia WHERE id_materia = p_id_materia) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Materia no existe';
  END IF;
  DELETE FROM materia WHERE id_materia = p_id_materia;
END$$

-- Asignar materia a carrera (plan de estudio) 【asignar materias】
CREATE OR REPLACE PROCEDURE sp_admin_asignar_materia_carrera(
  IN p_id_carrera INT,
  IN p_id_materia INT
)
BEGIN
  IF (SELECT COUNT(*) FROM carrera WHERE id_carrera = p_id_carrera) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Carrera no existe';
  END IF;
  IF (SELECT COUNT(*) FROM materia WHERE id_materia = p_id_materia) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Materia no existe';
  END IF;

  INSERT INTO carrera_materia(id_carrera, id_materia)
  VALUES (p_id_carrera, p_id_materia)
  ON DUPLICATE KEY UPDATE id_materia = VALUES(id_materia);
END$$

-- Quitar materia de carrera
CREATE OR REPLACE PROCEDURE sp_admin_quitar_materia_carrera(
  IN p_id_carrera INT,
  IN p_id_materia INT
)
BEGIN
  DELETE FROM carrera_materia
  WHERE id_carrera = p_id_carrera AND id_materia = p_id_materia;
END$$

DELIMITER ;

DELIMITER $$

-- Crear horario 【horario CRUD】
CREATE OR REPLACE PROCEDURE sp_admin_crear_horario(
  IN p_id_ciclo INT,
  IN p_id_dia TINYINT UNSIGNED,
  IN p_hora_inicio TIME,
  IN p_hora_fin TIME
)
BEGIN
  IF (SELECT COUNT(*) FROM ciclo_escolar WHERE id_ciclo = p_id_ciclo) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ciclo escolar no existe';
  END IF;
  IF (SELECT COUNT(*) FROM cat_dia WHERE id = p_id_dia) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Día no válido';
  END IF;

  INSERT INTO horario(id_ciclo, id_dia, hora_inicio, hora_fin)
  VALUES (p_id_ciclo, p_id_dia, p_hora_inicio, p_hora_fin);
END$$

-- Editar horario
CREATE OR REPLACE PROCEDURE sp_admin_editar_horario(
  IN p_id_horario INT,
  IN p_id_ciclo INT,
  IN p_id_dia TINYINT UNSIGNED,
  IN p_hora_inicio TIME,
  IN p_hora_fin TIME
)
BEGIN
  IF (SELECT COUNT(*) FROM horario WHERE id_horario = p_id_horario) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Horario no existe';
  END IF;

  IF p_id_ciclo IS NOT NULL AND (SELECT COUNT(*) FROM ciclo_escolar WHERE id_ciclo = p_id_ciclo) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ciclo escolar no existe';
  END IF;
  IF p_id_dia IS NOT NULL AND (SELECT COUNT(*) FROM cat_dia WHERE id = p_id_dia) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Día no válido';
  END IF;

  UPDATE horario
  SET id_ciclo = COALESCE(p_id_ciclo, id_ciclo),
      id_dia = COALESCE(p_id_dia, id_dia),
      hora_inicio = COALESCE(p_hora_inicio, hora_inicio),
      hora_fin = COALESCE(p_hora_fin, hora_fin)
  WHERE id_horario = p_id_horario;
END$$

-- Eliminar horario
CREATE OR REPLACE PROCEDURE sp_admin_eliminar_horario(IN p_id_horario INT)
BEGIN
  IF (SELECT COUNT(*) FROM horario WHERE id_horario = p_id_horario) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Horario no existe';
  END IF;
  DELETE FROM horario WHERE id_horario = p_id_horario;
END$$

DELIMITER ;

DELIMITER $$

-- Crear criterio de supervisión 【criterios/rubro supervisión】
CREATE OR REPLACE PROCEDURE sp_admin_crear_criterio_supervision(
  IN p_descripcion TEXT,
  IN p_id_rubro TINYINT UNSIGNED
)
BEGIN
  IF p_descripcion IS NULL OR p_descripcion = '' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Descripción requerida';
  END IF;
  IF (SELECT COUNT(*) FROM cat_rubro WHERE id = p_id_rubro) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Rubro no válido';
  END IF;

  INSERT INTO criterios_supervision(descripcion, id_rubro)
  VALUES (p_descripcion, p_id_rubro);
END$$

-- Editar criterio de supervisión
CREATE OR REPLACE PROCEDURE sp_admin_editar_criterio_supervision(
  IN p_id_supcriterio INT,
  IN p_descripcion TEXT,
  IN p_id_rubro TINYINT UNSIGNED
)
BEGIN
  IF (SELECT COUNT(*) FROM criterios_supervision WHERE id_supcriterio = p_id_supcriterio) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Criterio no existe';
  END IF;

  IF p_id_rubro IS NOT NULL AND (SELECT COUNT(*) FROM cat_rubro WHERE id = p_id_rubro) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Rubro no válido';
  END IF;

  UPDATE criterios_supervision
  SET descripcion = COALESCE(p_descripcion, descripcion),
      id_rubro    = COALESCE(p_id_rubro, id_rubro)
  WHERE id_supcriterio = p_id_supcriterio;
END$$

-- Eliminar criterio de supervisión
CREATE OR REPLACE PROCEDURE sp_admin_eliminar_criterio_supervision(IN p_id_supcriterio INT)
BEGIN
  IF (SELECT COUNT(*) FROM criterios_supervision WHERE id_supcriterio = p_id_supcriterio) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Criterio no existe';
  END IF;
  DELETE FROM criterios_supervision WHERE id_supcriterio = p_id_supcriterio;
END$$

DELIMITER ;

