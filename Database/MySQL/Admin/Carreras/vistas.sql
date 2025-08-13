-- Vista: Carreras con coordinador y plan de estudio
CREATE VIEW vista_carreras AS
SELECT 
    c.id_carrera,
    c.nombre AS carrera,
    c.descripcion,
    co.nombre AS coordinador,
    p.nombre AS plan_estudio
FROM Carrera c
LEFT JOIN Coordinador co ON c.id_coordinador = co.id_coordinador
LEFT JOIN PlanEstudio p ON c.id_plan = p.id_plan;