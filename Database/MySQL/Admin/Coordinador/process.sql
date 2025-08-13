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
