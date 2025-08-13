-- Vista: Planes de estudio y sus materias
CREATE VIEW vista_planes_estudio AS
SELECT p.id_plan, p.nombre AS plan, p.descripcion, m.id_materia, m.nombre AS materia
FROM PlanEstudio p
LEFT JOIN MateriaPlan mp ON p.id_plan = mp.id_plan
LEFT JOIN Materia m ON mp.id_materia = m.id_materia;