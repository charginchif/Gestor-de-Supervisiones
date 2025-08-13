-- Vista: Información de planteles
CREATE VIEW vista_informacion_planteles AS
SELECT id_plantel, nombre, direccion, telefono
FROM Plantel;

-- Vista: Descansos asignados
CREATE VIEW vista_descansos_plantel AS
SELECT p.nombre AS plantel, d.fecha, d.descripcion
FROM DescansoPlantel d
JOIN Plantel p ON p.id_plantel = d.id_plantel;