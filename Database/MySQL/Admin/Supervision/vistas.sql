-- Vista: Horarios
CREATE VIEW vista_horarios AS
SELECT id_horario, dia, hora_inicio, hora_fin, id_grupo, id_materia
FROM Horario;