-- Procedimiento: Registrar nuevo plantel
DELIMITER $$
CREATE PROCEDURE registrar_nuevo_plantel(
    IN p_nombre VARCHAR(255),
    IN p_direccion VARCHAR(255),
    IN p_telefono VARCHAR(20)
)
BEGIN
    INSERT INTO Plantel(nombre, direccion, telefono)
    VALUES (p_nombre, p_direccion, p_telefono);
END$$
DELIMITER ;

-- Procedimiento: Editar información del plantel
DELIMITER $$
CREATE PROCEDURE editar_informacion_plantel(
    IN p_id_plantel INT,
    IN p_nombre VARCHAR(255),
    IN p_direccion VARCHAR(255),
    IN p_telefono VARCHAR(20)
)
BEGIN
    UPDATE Plantel
    SET nombre = p_nombre,
        direccion = p_direccion,
        telefono = p_telefono
    WHERE id_plantel = p_id_plantel;
END$$
DELIMITER ;

-- Procedimiento: Eliminar plantel
DELIMITER $$
CREATE PROCEDURE eliminar_plantel(
    IN p_id_plantel INT
)
BEGIN
    DELETE FROM Plantel
    WHERE id_plantel = p_id_plantel;
END$$
DELIMITER ;

-- Procedimiento: Asignar descansos
DELIMITER $$
CREATE PROCEDURE asignar_descanso(
    IN p_id_plantel INT,
    IN p_fecha DATE,
    IN p_descripcion VARCHAR(255)
)
BEGIN
    INSERT INTO DescansoPlantel(id_plantel, fecha, descripcion)
    VALUES (p_id_plantel, p_fecha, p_descripcion);
END$$
DELIMITER ;

-- Procedimiento: Registrar coordinador
DELIMITER $$
CREATE PROCEDURE registrar_coordinador(
    IN p_nombre VARCHAR(255),
    IN p_apellido_paterno VARCHAR(255),
    IN p_apellido_materno VARCHAR(255),
    IN p_correo VARCHAR(255),
    IN p_telefono VARCHAR(20)
)
BEGIN
    INSERT INTO Coordinador(nombre, apellido_paterno, apellido_materno, correo, telefono)
    VALUES (p_nombre, p_apellido_paterno, p_apellido_materno, p_correo, p_telefono);
END$$
DELIMITER ;

-- Procedimiento: Editar coordinador
DELIMITER $$
CREATE PROCEDURE editar_coordinador(
    IN p_id_coordinador INT,
    IN p_nombre VARCHAR(255),
    IN p_apellido_paterno VARCHAR(255),
    IN p_apellido_materno VARCHAR(255),
    IN p_correo VARCHAR(255),
    IN p_telefono VARCHAR(20)
)
BEGIN
    UPDATE Coordinador
    SET nombre = p_nombre,
        apellido_paterno = p_apellido_paterno,
        apellido_materno = p_apellido_materno,
        correo = p_correo,
        telefono = p_telefono
    WHERE id_coordinador = p_id_coordinador;
END$$
DELIMITER ;

-- Procedimiento: Eliminar coordinador
DELIMITER $$
CREATE PROCEDURE eliminar_coordinador(
    IN p_id_coordinador INT
)
BEGIN
    DELETE FROM Coordinador
    WHERE id_coordinador = p_id_coordinador;
END$$
DELIMITER ;

-- Procedimiento: Registrar plan de estudio
DELIMITER $$
CREATE PROCEDURE registrar_plan_estudio(
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO PlanEstudio(nombre, descripcion)
    VALUES (p_nombre, p_descripcion);
END$$
DELIMITER ;

-- Procedimiento: Editar plan de estudio
DELIMITER $$
CREATE PROCEDURE editar_plan_estudio(
    IN p_id_plan INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE PlanEstudio
    SET nombre = p_nombre,
        descripcion = p_descripcion
    WHERE id_plan = p_id_plan;
END$$
DELIMITER ;

-- Procedimiento: Eliminar plan de estudio
DELIMITER $$
CREATE PROCEDURE eliminar_plan_estudio(
    IN p_id_plan INT
)
BEGIN
    DELETE FROM PlanEstudio
    WHERE id_plan = p_id_plan;
END$$
DELIMITER ;

-- Procedimiento: Asignar materias a plan de estudio
DELIMITER $$
CREATE PROCEDURE asignar_materia_a_plan(
    IN p_id_plan INT,
    IN p_id_materia INT
)
BEGIN
    INSERT INTO MateriaPlan(id_plan, id_materia)
    VALUES (p_id_plan, p_id_materia);
END$$
DELIMITER ;