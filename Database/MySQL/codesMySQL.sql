-- SQLINES DEMO *** NCTION)

-- SQLINES FOR EVALUATION USE ONLY (14 DAYS)
DELIMITER //

CREATE FUNCTION login_usuario(p_correo LONGTEXT, p_contrasena LONGTEXT)
RETURNS INTEGER
DETERMINISTIC
 BEGIN
    DECLARE v_usuario_id INTEGER;
 
    SELECT id INTO v_usuario_id
    FROM Usuario
    WHERE correo = p_correo AND contrasena = crypt(p_contrasena, contrasena);

    RETURN v_usuario_id;
END;
$$ LANGUAGE plpgsql
//

DELIMITER ;



-- SQLINES DEMO ***  (PROCEDURE)

DELIMITER //

CREATE PROCEDURE registrar_usuario(
    p_nombre LONGTEXT,
    p_apellido_paterno LONGTEXT,
    p_apellido_materno LONGTEXT,
    p_correo LONGTEXT,
    p_contrasena LONGTEXT,
    p_rol tipo_rol
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO Usuario(nombre, apellido_paterno, apellido_materno, correo, contrasena, rol)
    VALUES (
        p_nombre,
        p_apellido_paterno,
        p_apellido_materno,
        p_correo,
        crypt(p_contrasena, gen_salt('bf')),
        p_rol
    );
END;
$$;

-- SQLINES DEMO *** rd (PROCEDURE)

DELIMITER //

CREATE PROCEDURE actualizar_password(
    p_correo LONGTEXT,
    p_nueva_contrasena LONGTEXT
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    UPDATE Usuario
    SET contrasena = crypt(p_nueva_contrasena, gen_salt('bf'))
    WHERE correo = p_correo;
END;
$$;

-- SQLINES DEMO *** _acceso (-PROCEDURE-) (Trigger)

DELIMITER //

CREATE PROCEDURE actualizar_ultimo_acceso(p_usuario_id INTEGER)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    UPDATE Usuario
    SET ultimo_acceso = CURRENT_TIMESTAMP
    WHERE id = p_usuario_id;
END;
$$;

-- SQLINES DEMO ***  de Usuarios

-- SQLINES DEMO *** (PROCEDURE)

DELIMITER //

CREATE PROCEDURE inscribir_alumno(
    p_id_alumno INTEGER,
    p_id_grupo INTEGER
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO InscripcionGrupo(id_grupo, id_alumno)
    VALUES (p_id_grupo, p_id_alumno);
END;
$$;

-- SQLINES DEMO *** no (PROCEDURE)

DELIMITER //

CREATE PROCEDURE desinscribir_alumno(
    p_id_alumno INTEGER,
    p_id_grupo INTEGER
)
LANGUAGE plpgsqd
//

DELIMITER ;

 
AS $$
BEGIN
    DELETE FROM InscripcionGrupo
    WHERE id_grupo = p_id_grupo AND id_alumno = p_id_alumno;
END;
$$;

-- SQLINES DEMO *** PROCEDURE)

DELIMITER //

CREATE PROCEDURE asignar_docente(
    p_id_docente INTEGER,
    p_id_materia INTEGER
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO DocenteMateria(id_docente, id_materia)
    VALUES (p_id_docente, p_id_materia)
    ON CONFLICT DO NOTHING; 
END;
$$;

-- SQLINES DEMO *** (PROCEDURE)
DELIMITER //

CREATE PROCEDURE eliminar_docente(
    p_id_docente INTEGER,
    p_id_materia INTEGER
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    DELETE FROM DocenteMateria
    WHERE id_docente = p_id_docente AND id_materia = p_id_materia;
END;
$$;

-- SQLINES DEMO *** docente (FUNCTION)

DELIMITER //

CREATE FUNCTION obtener_materias_docente(p_id_docente INTEGER)
RETURNS LONGTEXT
DETERMINISTICDECLARE (id_materia
DECLARE JSONDATA LONGTEXT; INTEGER
 END
//

DELIMITER ;

, nombre TEXT, nivel tipo_nivel)
AS $$
BEGIN
    RETURN QUERY
    SELECT m.id_materia, m.nombre, m.nivel
    FROM DocenteMateria dm
    JOIN Materia m ON m.id_materia = dm.id_materia
    WHERE dm.id_docente = p_id_docente;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** umno (FUNCTION)

DROP FUNCTION IF EXISTS obtener_grupos_alumno;

DELIMITER //

CREATE FUNCTION obtener_grupos_alumno(p_id_alumno INTEGER)
RETURNS LONGTEXT
DETERMINISTICDECLARE (id_grupo
DECLARE JSONDATA LONGTEXT; INTEGER
 END
//

DELIMITER ;

, acronimo TEXT, nivel tipo_nivel, turno tipo_turno)
AS $$
BEGIN
    RETURN QUERY
    SELECT g.id_grupo, g.acronimo, g.nivel, g.turno
    FROM InscripcionGrupo ig
    JOIN Grupo g ON g.id_grupo = ig.id_grupo
    WHERE ig.id_alumno = p_id_alumno;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** docente (PROCEDURE)

DROP PROCEDURE IF EXISTS actualizar_grado_docente;

DELIMITER //

CREATE PROCEDURE actualizar_grado_docente(
    p_id_docente INTEGER,
    p_nuevo_grado LONGTEXT
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    UPDATE Docente
    SET grado_academico = p_nuevo_grado
    WHERE id_docente = p_id_docente;
END;
$$;

-- SQLINES DEMO *** iones y Supervisión

-- SQLINES DEMO *** ion_docente (PROCEDURE)

DROP PROCEDURE IF EXISTS registrar_evaluacion_docente;

DELIMITER //

CREATE PROCEDURE registrar_evaluacion_docente(
    p_id_docente INTEGER,
    p_id_alumno INTEGER,
    p_calificacion INTEGER,
    p_comentario LONGTEXT
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO EvaluacionDocente(id_docente, id_alumno, calificacion, comentario, fecha)
    VALUES (p_id_docente, p_id_alumno, p_calificacion, p_comentario, CURRENT_DATE);
END;
$$;

-- SQLINES DEMO *** supervision (PROCEDURE)

DROP PROCEDURE IF EXISTS registrar_agenda_supervision;

DELIMITER //

CREATE PROCEDURE registrar_agenda_supervision(
    p_id_coordinador INTEGER,
    p_id_grupo INTEGER,
    p_fecha DATE,
    p_descripcion LONGTEXT
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO AgendaSupervisor(id_coordinador, id_grupo, fecha_supervision, descripcion)
    VALUES (p_id_coordinador, p_id_grupo, p_fecha, p_descripcion);
END;
$$;

-- SQLINES DEMO *** iones_docente (FUNCTION)

DROP FUNCTION IF EXISTS consultar_evaluaciones_docente;

DELIMITER //

CREATE FUNCTION consultar_evaluaciones_docente(p_id_docente INTEGER)
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    id_evaluacion
DECLARE JSONDATA LONGTEXT; INTEGER
 END
//

DELIMITER ;

,
    id_alumno INTEGER,
    calificacion INTEGER,
    comentario TEXT,
    fecha DATE
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_evaluacion, id_alumno, calificacion, comentario, fecha
    FROM EvaluacionDocente
    WHERE id_docente = p_id_docente
    ORDER BY fecha DESC;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** coordinador (FUNCTION)

DROP FUNCTION IF EXISTS consultar_agenda_coordinador;

DELIMITER //

CREATE FUNCTION consultar_agenda_coordinador(p_id_coordinador INTEGER)
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    id_agenda
DECLARE JSONDATA LONGTEXT; INTEGER
 END
//

DELIMITER ;

,
    id_grupo INTEGER,
    fecha_supervision DATE,
    descripcion TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT id_agenda, id_grupo, fecha_supervision, descripcion
    FROM AgendaSupervisor
    WHERE id_coordinador = p_id_coordinador
    ORDER BY fecha_supervision;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** _docente (FUNCTION)

DROP FUNCTION IF EXISTS calcular_promedio_docente;

DELIMITER //

CREATE FUNCTION calcular_promedio_docente(p_id_docente INTEGER)
RETURNS NUMERIC
DETERMINISTIC
 BEGIN
    DECLARE v_promedio NUMERIC;
 
    SELECT AVG(calificacion)::NUMERIC(5,2) INTO v_promedio
    FROM EvaluacionDocente
    WHERE id_docente = p_id_docente;

    RETURN COALESCE(v_promedio, 0);
END;
$$ LANGUAGE plpgsql
//

DELIMITER ;



-- SQLINES DEMO *** s y Analisis

-- SQLINES DEMO *** nes_docentes (FUNCTION)

DROP FUNCTION IF EXISTS reporte_evaluaciones_docentes;

DELIMITER //

CREATE FUNCTION reporte_evaluaciones_docentes()
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    id_docente
DECLARE JSONDATA LONGTEXT; INTEGER
 END
//

DELIMITER ;

,
    nombre_docente TEXT,
    promedio NUMERIC(5,2),
    total_evaluaciones INTEGER
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        d.id_docente,
        concat(ifnull(u.nombre, '') , ' ' , ifnull(u.apellido_paterno, '') , ' ' , ifnull(u.apellido_materno, '')) AS nombre_docente,
        AVG(e.calificacion)::NUMERIC(5,2),
        COUNT(e.id_evaluacion)
    FROM EvaluacionDocente e
    JOIN Docente d ON d.id_docente = e.id_docente
    JOIN Usuario u ON u.id = d.id_usuario
    GROUP BY d.id_docente, u.nombre, u.apellido_paterno, u.apellido_materno;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** a_grupo (IDEA)

-- SQLINES DEMO *** materias (FUNCTION)

DROP FUNCTION IF EXISTS reporte_docentes_por_materia;

DELIMITER //

CREATE FUNCTION reporte_docentes_por_materia()
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    nombre_docente
DECLARE JSONDATA LONGTEXT; TEXT
 END
//

DELIMITER ;

,
    materia TEXT,
    grupo TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        concat(ifnull(u.nombre, '') , ' ' , ifnull(u.apellido_paterno, '') , ' ' , ifnull(u.apellido_materno, '')) AS nombre_docente,
        m.nombre AS materia,
        g.nombre AS grupo
    FROM Docente d
    JOIN Usuario u ON u.id = d.id_usuario
    JOIN Grupo g ON g.id_docente = d.id_docente
    JOIN Materia m ON m.id_materia = g.id_materia;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** _coordinador (FUNCTION)

DROP FUNCTION IF EXISTS reporte_actividad_coordinador;

DELIMITER //

CREATE FUNCTION reporte_actividad_coordinador()
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    nombre_coordinador
DECLARE JSONDATA LONGTEXT; TEXT
 END
//

DELIMITER ;

,
    total_supervisiones INTEGER
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        concat(ifnull(u.nombre, '') , ' ' , ifnull(u.apellido_paterno, '') , ' ' , ifnull(u.apellido_materno, '')) AS nombre_coordinador,
        COUNT(a.id_agenda)
    FROM AgendaSupervisor a
    JOIN Coordinador c ON c.id_coordinador = a.id_coordinador
    JOIN Usuario u ON u.id = c.id_usuario
    GROUP BY u.nombre, u.apellido_paterno, u.apellido_materno;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO *** por_periodo (FUNCTION)

DROP FUNCTION IF EXISTS reporte_materias_por_periodo;

DELIMITER //

CREATE FUNCTION reporte_materias_por_periodo(p_periodo tipo_periodo)
RETURNS LONGTEXT
DETERMINISTICDECLARE (
    nombre_materia
DECLARE JSONDATA LONGTEXT; TEXT
 END
//

DELIMITER ;

,
    nombre_docente TEXT,
    grupo TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        m.nombre,
        concat(ifnull(u.nombre, '') , ' ' , ifnull(u.apellido_paterno, '') , ' ' , ifnull(u.apellido_materno, '')),
        g.nombre
    FROM Grupo g
    JOIN Materia m ON m.id_materia = g.id_materia
    JOIN Docente d ON d.id_docente = g.id_docente
    JOIN Usuario u ON u.id = d.id_usuario
    WHERE g.periodo = p_periodo;
END;
$$ LANGUAGE plpgsql;

-- SQLINES DEMO ***  y gestion de Accesos

-- SQLINES DEMO *** iales (FUNCTION)

DROP FUNCTION IF EXISTS verificar_credenciales;

DELIMITER //

CREATE FUNCTION verificar_credenciales(
    p_usuario LONGTEXT,
    p_contrasena LONGTEXT
) RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE v_contrasena_encriptada LONGTEXT;
 
    SELECT contrasena INTO v_contrasena_encriptada
    FROM Usuario
    WHERE usuario = p_usuario;

    RETURN v_contrasena_encriptada IS NOT NULL 
           AND v_contrasena_encriptada = crypt(p_contrasena, v_contrasena_encriptada);
END;
$$ LANGUAGE plpgsql
//

DELIMITER ;



-- SQLINES DEMO *** io (PROCEDURE)

DELIMITER //

CREATE PROCEDURE asignar_rol_usuario(
    p_id_usuario INTEGER,
    p_nuevo_rol tipo_rol
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    UPDATE Usuario
    SET rol = p_nuevo_rol
    WHERE id_usuario = p_id_usuario;
END;
$$;

-- SQLINES DEMO *** ad_usuario (PROCEDURE)

DROP PROCEDURE IF EXISTS registrar_actividad_usuario;

DELIMITER //

CREATE PROCEDURE registrar_actividad_usuario(
    p_id_usuario INTEGER,
    p_actividad LONGTEXT
)
LANGUAGE plpgsql
//

DELIMITER ;


AS $$
BEGIN
    INSERT INTO LogUsuario(id_usuario, actividad)
    VALUES (p_id_usuario, p_actividad);
END;
$$;

-- SQLINES DEMO *** _actividad (FUNCTION)

DROP FUNCTION IF EXISTS obtener_historial_actividad;

DELIMITER //

CREATE FUNCTION obtener_historial_actividad(p_id_usuario INTEGER)
RETURNS LONGTEXT
DETERMINISTIC DECLARE (
    actividad
DECLARE JSONDATA LONGTEXT; TEXT
 END
//

DELIMITER ;

,
    fecha_hora TIMESTAMP
)
AS $$
BEGIN
    RETURN QUERY
    SELECT actividad, fecha_hora
    FROM LogUsuario
    WHERE id_usuario = p_id_usuario
    ORDER BY fecha_hora DESC;
END;
$$ LANGUAGE plpgsql;

