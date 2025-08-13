-- Función: Ver plan de estudio (devuelve nombre y descripción)
DELIMITER $$
CREATE FUNCTION obtener_plan_estudio(p_id_plan INT)
RETURNS VARCHAR(1024)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(1024);
    SELECT CONCAT('Nombre: ', nombre, ', Descripción: ', descripcion)
    INTO resultado
    FROM PlanEstudio
    WHERE id_plan = p_id_plan;
    RETURN resultado;
END$$
DELIMITER ;