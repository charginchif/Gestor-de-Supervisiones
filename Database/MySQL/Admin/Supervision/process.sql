-- Procedimiento: Crear criterio/rubro de supervisión
DELIMITER $$
CREATE PROCEDURE crear_criterio_supervision(
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    INSERT INTO CriterioSupervision(nombre, descripcion)
    VALUES (p_nombre, p_descripcion);
END$$
DELIMITER ;

-- Procedimiento: Editar criterio/rubro de supervisión
DELIMITER $$
CREATE PROCEDURE editar_criterio_supervision(
    IN p_id_criterio INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion TEXT
)
BEGIN
    UPDATE CriterioSupervision
    SET nombre = p_nombre,
        descripcion = p_descripcion
    WHERE id_criterio = p_id_criterio;
END$$
DELIMITER ;

-- Procedimiento: Eliminar criterio/rubro de supervisión
DELIMITER $$
CREATE PROCEDURE eliminar_criterio_supervision(
    IN p_id_criterio INT
)
BEGIN
    DELETE FROM CriterioSupervision
    WHERE id_criterio = p_id_criterio;
END$$
DELIMITER ;
