-- Función: Obtener carrera (nombre, descripción, coordinador, plan de estudio)
DELIMITER $$
CREATE FUNCTION obtener_carrera(p_id_carrera INT)
RETURNS VARCHAR(1024)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(1024);
    SELECT CONCAT(
        'Nombre: ', c.nombre,
        ', Descripción: ', c.descripcion,
        ', Coordinador: ', co.nombre,
        ', Plan de estudio: ', p.nombre
    )
    INTO resultado
    FROM Carrera c
    LEFT JOIN Coordinador co ON c.id_coordinador = co.id_coordinador
    LEFT JOIN PlanEstudio p ON c.id_plan = p.id_plan
    WHERE c.id_carrera = p_id_carrera;
    RETURN resultado;
END$$
DELIMITER ;
