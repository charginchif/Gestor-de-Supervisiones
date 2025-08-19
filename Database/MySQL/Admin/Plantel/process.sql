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
