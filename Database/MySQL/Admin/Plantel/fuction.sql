-- Función: Ver la información del plantel
DELIMITER $$
CREATE FUNCTION obtener_informacion_plantel(p_id_plantel INT)
RETURNS VARCHAR(1024)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(1024);
    SELECT CONCAT('Nombre: ', nombre, ', Dirección: ', direccion, ', Teléfono: ', telefono)
    INTO resultado
    FROM Plantel
    WHERE id_plantel = p_id_plantel;
    RETURN resultado;
END$$
DELIMITER ;