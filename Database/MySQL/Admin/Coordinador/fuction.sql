-- Función: Obtener coordinador
DELIMITER $$
CREATE FUNCTION obtener_coordinador(p_id_coordinador INT)
RETURNS VARCHAR(1024)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(1024);
    SELECT CONCAT('Nombre: ', nombre, ', Apellido paterno: ', apellido_paterno, ', Apellido materno: ', apellido_materno, ', Correo: ', correo, ', Teléfono: ', telefono)
    INTO resultado
    FROM Coordinador
    WHERE id_coordinador = p_id_coordinador;
    RETURN resultado;
END$$
DELIMITER ;