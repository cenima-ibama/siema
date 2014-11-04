CREATE SEQUENCE usuarios_id_usuario_seq;
ALTER TABLE usuarios ALTER COLUMN id_usuario SET DEFAULT nextval('usuarios_id_usuario_seq');
ALTER TABLE usuarios ALTER COLUMN id_usuario SET NOT NULL;
ALTER SEQUENCE usuarios_id_usuario_seq OWNED BY usuarios.id_usuario;
