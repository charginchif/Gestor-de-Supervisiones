-- Función: Ver horario (devuelve información básica)
DELIMITER $$
CREATE FUNCTION obtener_horario(p_id_horario INT)
RETURNS VARCHAR(512)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(512);
    SELECT CONCAT('Día: ', dia, ', Inicio: ', hora_inicio, ', Fin: ', hora_fin)
    INTO resultado
    FROM Horario
    WHERE id_horario = p_id_horario;
    RETURN resultado;
END$$
DELIMITER ;