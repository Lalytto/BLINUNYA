/*CREATE DATABASE "BLINUNYA"
  WITH OWNER = postgres
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'Spanish_Ecuador.1252'
       LC_CTYPE = 'Spanish_Ecuador.1252'
       CONNECTION LIMIT = -1;*/


CREATE USER BLINUNYA_Admin PASSWORD 'RootBLINUNYA';
ALTER ROLE BLINUNYA_Admin WITH SUPERUSER;
CREATE DATABASE BLINUNYA WITH OWNER BLINUNYA_Admin;

GRANT ALL PRIVILEGES ON DATABASE BLINUNYA TO BLINUNYA_Admin;

create table tbPersona(
perso_id serial primary key,
perso_nombre text not null,
perso_cedula text not null,
perso_fechanacimiento text,
perso_email text not null,
perso_telefono text
);

create table tbPerfil(
per_id serial primary key,
per_nombre text not null,
per_comentario text
);

create table tbUsuario(
usu_id serial primary key,
usu_login text not null,
usu_psw text not null,
usu_estado text not null,
usu_comentario text,
fk_persoid serial references tbPersona(perso_id),
fk_perid serial references tbPerfil(per_id)
);

INSERT INTO tbperfil(
            per_nombre)
    VALUES ('SU');
INSERT INTO tbpersona(
            perso_nombre, perso_cedula, perso_fechanacimiento, 
            perso_email, perso_telefono)
    VALUES ('Javier Espinoza', '1723694798', 'Sábado, 12 de Septiembre de 1992', 
            'ejespinozas@pucesd.edu.ec', '0986977143');
INSERT INTO tbusuario(
            usu_login, usu_psw, usu_estado, fk_persoid, fk_perid)
    VALUES ('monino7', '202cb962ac59075b964b07152d234b70', 'Activo', 1, 1);

INSERT INTO tbperfil(
            per_nombre)
    VALUES ('Administrador');
INSERT INTO tbpersona(
            perso_nombre, perso_cedula, perso_fechanacimiento, 
            perso_email, perso_telefono)
    VALUES ('Lalytto', '1721953188', 'Lunes, 27 de Julio de 1992', 
            'lalytto@live.com', '0986865422');
INSERT INTO tbusuario(
            usu_login, usu_psw, usu_estado, fk_persoid, fk_perid)
    VALUES ('Lalytto', '202cb962ac59075b964b07152d234b70', 'Activo', 2, 2);

INSERT INTO tbperfil(
            per_nombre)
    VALUES ('Bibliotecaria');

--------------
-- libros
--------------

create table tbCategoria(
categoria_id serial primary key,
categoria_nombre text not null,
categoria_comentario text
);

create table tbTipo(
tipo_id serial primary key,
tipo_nombre text not null,
tipo_comentario text
);

create table tbLibro(
libro_id serial primary key,
libro_titulo text not null,
libro_autor text not null,
libro_fechapublicacion text not null,
libro_editorial text not null,
libro_comentario text,
fk_tipoid serial references tbTipo(tipo_id),
fk_categoriaid serial references tbCategoria(categoria_id)
);

create table tbEjemplar(
ejemplar_id serial primary key,
ejemplar_codigo text not null,
ejemplar_estado text,
fk_libroid serial references tbLibro(libro_id)
);

create table tbSocio(
socio_id serial primary key,
socio_nombre text not null,
socio_cedula text not null,
socio_direccion text,
socio_tipo text,
socio_email text not null,
socio_telefono text,
socio_comentario text
);

create table tbPrestamo(
prestamo_id serial primary key,
prestamo_fechaemision text not null,
prestamo_fechaentrega text not null,
prestamo_estadoo text,
prestamo_comentario text,
fk_libroid serial references tbLibro(libro_id),
fk_socioid serial references tbSocio(socio_id)
);

create table tbMulta(
multa_id serial primary key,
multa_valor text not null,
multa_estado text not null,
multa_comentario text,
fk_prestamoid serial references tbPrestamo(prestamo_id)
);
