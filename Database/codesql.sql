-- login_usuario (FUNCTION)

CREATE FUNCTION login_usuario(p_correo TEXT, p_contrasena TEXT)
RETURNS INTEGER AS $$
DECLARE
    v_usuario_id INTEGER;
BEGIN
    SELECT id INTO v_usuario_id
    FROM Usuario
    WHERE correo = p_correo AND contrasena = crypt(p_contrasena, contrasena);

    RETURN v_usuario_id;
END;
$$ LANGUAGE plpgsql;

-- registrar_usuario (PROCEDURE)

CREATE PROCEDURE registrar_usuario(
    p_nombre TEXT,
    p_apellido_paterno TEXT,
    p_apellido_materno TEXT,
    p_correo TEXT,
    p_contrasena TEXT,
    p_rol tipo_rol
)
LANGUAGE plpgsql
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

-- actualizar_password (PROCEDURE)

CREATE PROCEDURE actualizar_password(
    p_correo TEXT,
    p_nueva_contrasena TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Usuario
    SET contrasena = crypt(p_nueva_contrasena, gen_salt('bf'))
    WHERE correo = p_correo;
END;
$$;

-- actualizar_ultimo_acceso (-PROCEDURE-) (Trigger)

CREATE PROCEDURE actualizar_ultimo_acceso(p_usuario_id INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Usuario
    SET ultimo_acceso = CURRENT_TIMESTAMP
    WHERE id = p_usuario_id;
END;
$$;

-- GROUP B - Gestion de Usuarios

-- inscribir alumno (PROCEDURE)

CREATE PROCEDURE inscribir_alumno(
    p_id_alumno INTEGER,
    p_id_grupo INTEGER
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO InscripcionGrupo(id_grupo, id_alumno)
    VALUES (p_id_grupo, p_id_alumno);
END;
$$;

-- desinscribir_alumno (PROCEDURE)

CREATE PROCEDURE desinscribir_alumno(
    p_id_alumno INTEGER,
    p_id_grupo INTEGER
)
LANGUAGE plpgsqd 
AS $$
BEGIN
    DELETE FROM InscripcionGrupo
    WHERE id_grupo = p_id_grupo AND id_alumno = p_id_alumno;
END;
$$;

-- asignar_docente (PROCEDURE)

CREATE PROCEDURE asignar_docente(
    p_id_docente INTEGER,
    p_id_materia INTEGER
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO DocenteMateria(id_docente, id_materia)
    VALUES (p_id_docente, p_id_materia)
    ON CONFLICT DO NOTHING; 
END;
$$;

-- eliminar_docente (PROCEDURE)
CREATE PROCEDURE eliminar_docente(
    p_id_docente INTEGER,
    p_id_materia INTEGER
)
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM DocenteMateria
    WHERE id_docente = p_id_docente AND id_materia = p_id_materia;
END;
$$;

-- obtener_materias_docente (FUNCTION)

CREATE FUNCTION obtener_materias_docente(p_id_docente INTEGER)
RETURNS TABLE(id_materia INTEGER, nombre TEXT, nivel tipo_nivel)
AS $$
BEGIN
    RETURN QUERY
    SELECT m.id_materia, m.nombre, m.nivel
    FROM DocenteMateria dm
    JOIN Materia m ON m.id_materia = dm.id_materia
    WHERE dm.id_docente = p_id_docente;
END;
$$ LANGUAGE plpgsql;

-- obtener_grupos_alumno (FUNCTION)

CREATE OR REPLACE FUNCTION obtener_grupos_alumno(p_id_alumno INTEGER)
RETURNS TABLE(id_grupo INTEGER, acronimo TEXT, nivel tipo_nivel, turno tipo_turno)
AS $$
BEGIN
    RETURN QUERY
    SELECT g.id_grupo, g.acronimo, g.nivel, g.turno
    FROM InscripcionGrupo ig
    JOIN Grupo g ON g.id_grupo = ig.id_grupo
    WHERE ig.id_alumno = p_id_alumno;
END;
$$ LANGUAGE plpgsql;

-- actualizar_grado_docente (PROCEDURE)

CREATE OR REPLACE PROCEDURE actualizar_grado_docente(
    p_id_docente INTEGER,
    p_nuevo_grado TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Docente
    SET grado_academico = p_nuevo_grado
    WHERE id_docente = p_id_docente;
END;
$$;

-- Grupo C - Evaluaciones y Supervisión

-- registrar_evaluacion_docente (PROCEDURE)

CREATE OR REPLACE PROCEDURE registrar_evaluacion_docente(
    p_id_docente INTEGER,
    p_id_alumno INTEGER,
    p_calificacion INTEGER,
    p_comentario TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO EvaluacionDocente(id_docente, id_alumno, calificacion, comentario, fecha)
    VALUES (p_id_docente, p_id_alumno, p_calificacion, p_comentario, CURRENT_DATE);
END;
$$;

-- registrar_agenda_supervision (PROCEDURE)

CREATE OR REPLACE PROCEDURE registrar_agenda_supervision(
    p_id_coordinador INTEGER,
    p_id_grupo INTEGER,
    p_fecha DATE,
    p_descripcion TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO AgendaSupervisor(id_coordinador, id_grupo, fecha_supervision, descripcion)
    VALUES (p_id_coordinador, p_id_grupo, p_fecha, p_descripcion);
END;
$$;

-- consultar_evaluaciones_docente (FUNCTION)

CREATE OR REPLACE FUNCTION consultar_evaluaciones_docente(p_id_docente INTEGER)
RETURNS TABLE(
    id_evaluacion INTEGER,
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

-- consultar_agenda_coordinador (FUNCTION)

CREATE OR REPLACE FUNCTION consultar_agenda_coordinador(p_id_coordinador INTEGER)
RETURNS TABLE(
    id_agenda INTEGER,
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

-- calcular_promedio_docente (FUNCTION)

CREATE OR REPLACE FUNCTION calcular_promedio_docente(p_id_docente INTEGER)
RETURNS NUMERIC AS $$
DECLARE
    v_promedio NUMERIC;
BEGIN
    SELECT AVG(calificacion)::NUMERIC(5,2) INTO v_promedio
    FROM EvaluacionDocente
    WHERE id_docente = p_id_docente;

    RETURN COALESCE(v_promedio, 0);
END;
$$ LANGUAGE plpgsql;

-- Grupo D - Reportes y Analisis

-- reporte_evaluaciones_docentes (FUNCTION)

CREATE OR REPLACE FUNCTION reporte_evaluaciones_docentes()
RETURNS TABLE(
    id_docente INTEGER,
    nombre_docente TEXT,
    promedio NUMERIC(5,2),
    total_evaluaciones INTEGER
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        d.id_docente,
        u.nombre || ' ' || u.apellido_paterno || ' ' || u.apellido_materno AS nombre_docente,
        AVG(e.calificacion)::NUMERIC(5,2),
        COUNT(e.id_evaluacion)
    FROM EvaluacionDocente e
    JOIN Docente d ON d.id_docente = e.id_docente
    JOIN Usuario u ON u.id = d.id_usuario
    GROUP BY d.id_docente, u.nombre, u.apellido_paterno, u.apellido_materno;
END;
$$ LANGUAGE plpgsql;

-- reporte_asistencia_grupo (IDEA)

-- reporte_docentes_materias (FUNCTION)

CREATE OR REPLACE FUNCTION reporte_docentes_por_materia()
RETURNS TABLE(
    nombre_docente TEXT,
    materia TEXT,
    grupo TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        u.nombre || ' ' || u.apellido_paterno || ' ' || u.apellido_materno AS nombre_docente,
        m.nombre AS materia,
        g.nombre AS grupo
    FROM Docente d
    JOIN Usuario u ON u.id = d.id_usuario
    JOIN Grupo g ON g.id_docente = d.id_docente
    JOIN Materia m ON m.id_materia = g.id_materia;
END;
$$ LANGUAGE plpgsql;

-- reporte_actividad_coordinador (FUNCTION)

CREATE OR REPLACE FUNCTION reporte_actividad_coordinador()
RETURNS TABLE(
    nombre_coordinador TEXT,
    total_supervisiones INTEGER
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        u.nombre || ' ' || u.apellido_paterno || ' ' || u.apellido_materno AS nombre_coordinador,
        COUNT(a.id_agenda)
    FROM AgendaSupervisor a
    JOIN Coordinador c ON c.id_coordinador = a.id_coordinador
    JOIN Usuario u ON u.id = c.id_usuario
    GROUP BY u.nombre, u.apellido_paterno, u.apellido_materno;
END;
$$ LANGUAGE plpgsql;

-- reporte_materias_por_periodo (FUNCTION)

CREATE OR REPLACE FUNCTION reporte_materias_por_periodo(p_periodo tipo_periodo)
RETURNS TABLE(
    nombre_materia TEXT,
    nombre_docente TEXT,
    grupo TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        m.nombre,
        u.nombre || ' ' || u.apellido_paterno || ' ' || u.apellido_materno,
        g.nombre
    FROM Grupo g
    JOIN Materia m ON m.id_materia = g.id_materia
    JOIN Docente d ON d.id_docente = g.id_docente
    JOIN Usuario u ON u.id = d.id_usuario
    WHERE g.periodo = p_periodo;
END;
$$ LANGUAGE plpgsql;

-- Grupo E Seguridad y gestion de Accesos

