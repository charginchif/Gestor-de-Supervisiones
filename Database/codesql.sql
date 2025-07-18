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
