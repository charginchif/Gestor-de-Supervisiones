-- Procedimiento: Registrar materia
DELIMITER $$
CREATE PROCEDURE registrar_materia(
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO Materia(nombre, descripcion)
    VALUES (p_nombre, p_descripcion);
END$$
DELIMITER ;

-- Procedimiento: Editar materia
DELIMITER $$
CREATE PROCEDURE editar_materia(
    IN p_id_materia INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE Materia
    SET nombre = p_nombre,
        descripcion = p_descripcion
    WHERE id_materia = p_id_materia;
END$$
DELIMITER ;

-- Procedimiento: Eliminar materia
DELIMITER $$
CREATE PROCEDURE eliminar_materia(
    IN p_id_materia INT
)
BEGIN
    DELETE FROM Materia
    WHERE id_materia = p_id_materia;
END$$
DELIMITER ;