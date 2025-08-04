-- Procedimiento: Crear horario
DELIMITER $$
CREATE PROCEDURE crear_horario(
    IN p_dia VARCHAR(20),
    IN p_hora_inicio TIME,
    IN p_hora_fin TIME,
    IN p_id_grupo INT,
    IN p_id_materia INT
)
BEGIN
    INSERT INTO Horario(dia, hora_inicio, hora_fin, id_grupo, id_materia)
    VALUES (p_dia, p_hora_inicio, p_hora_fin, p_id_grupo, p_id_materia);
END$$
DELIMITER ;

-- Procedimiento: Editar horario
DELIMITER $$
CREATE PROCEDURE editar_horario(
    IN p_id_horario INT,
    IN p_dia VARCHAR(20),
    IN p_hora_inicio TIME,
    IN p_hora_fin TIME,
    IN p_id_grupo INT,
    IN p_id_materia INT
)
BEGIN
    UPDATE Horario
    SET dia = p_dia,
        hora_inicio = p_hora_inicio,
        hora_fin = p_hora_fin,
        id_grupo = p_id_grupo,
        id_materia = p_id_materia
    WHERE id_horario = p_id_horario;
END$$
DELIMITER ;

-- Procedimiento: Eliminar horario
DELIMITER $$
CREATE PROCEDURE eliminar_horario(
    IN p_id_horario INT
)
BEGIN
    DELETE FROM Horario
    WHERE id_horario = p_id_horario;
END$$
DELIMITER ;
