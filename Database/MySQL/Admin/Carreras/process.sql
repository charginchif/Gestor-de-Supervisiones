-- Procedimiento: Registrar carrera
DELIMITER $$
CREATE PROCEDURE registrar_carrera(
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_id_plan INT
)
BEGIN
    INSERT INTO Carrera(nombre, descripcion, id_plan)
    VALUES (p_nombre, p_descripcion, p_id_plan);
END$$
DELIMITER ;

-- Procedimiento: Editar datos de la carrera
DELIMITER $$
CREATE PROCEDURE editar_carrera(
    IN p_id_carrera INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_id_plan INT
)
BEGIN
    UPDATE Carrera
    SET nombre = p_nombre,
        descripcion = p_descripcion,
        id_plan = p_id_plan
    WHERE id_carrera = p_id_carrera;
END$$
DELIMITER ;

-- Procedimiento: Eliminar carrera
DELIMITER $$
CREATE PROCEDURE eliminar_carrera(
    IN p_id_carrera INT
)
BEGIN
    DELETE FROM Carrera
    WHERE id_carrera = p_id_carrera;
END$$
DELIMITER ;

-- Procedimiento: Asignar coordinador a carrera
DELIMITER $$
CREATE PROCEDURE asignar_coordinador_carrera(
    IN p_id_carrera INT,
    IN p_id_coordinador INT
)
BEGIN
    UPDATE Carrera
    SET id_coordinador = p_id_coordinador
    WHERE id_carrera = p_id_carrera;
END$$
DELIMITER ;

-- Procedimiento: Editar plan de estudio de carrera
DELIMITER $$
CREATE PROCEDURE editar_plan_estudio_carrera(
    IN p_id_carrera INT,
    IN p_id_plan INT
)
BEGIN
    UPDATE Carrera
    SET id_plan = p_id_plan
    WHERE id_carrera = p_id_carrera;
END$$
DELIMITER ;
