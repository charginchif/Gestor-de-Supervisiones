-- Función: Ver materia (devuelve nombre y descripción)
DELIMITER $$
CREATE FUNCTION obtener_materia(p_id_materia INT)
RETURNS VARCHAR(512)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(512);
    SELECT CONCAT('Nombre: ', nombre, ', Descripción: ', descripcion)
    INTO resultado
    FROM Materia
    WHERE id_materia = p_id_materia;
    RETURN resultado;
END$$
DELIMITER ;