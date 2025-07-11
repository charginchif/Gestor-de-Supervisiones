-- login_usuario (FUNCTION)

CREATE OR REPLACE FUNCTION login_usuario(p_correo TEXT, p_contrasena TEXT)
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

CREATE OR REPLACE PROCEDURE registrar_usuario(
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

CREATE OR REPLACE PROCEDURE actualizar_password(
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

-- actualizar_ultimo_acceso (PROCEDURE)

CREATE OR REPLACE PROCEDURE actualizar_ultimo_acceso(p_usuario_id INTEGER)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Usuario
    SET ultimo_acceso = CURRENT_TIMESTAMP
    WHERE id = p_usuario_id;
END;
$$;