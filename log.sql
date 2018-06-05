create database bc;
Create table bc.partido
(
	id integer NOT NULL UNIQUE,	-- ID del partido
	edicion integer NOT NULL, 	-- Edicion
	tipo Varchar(20) NOT NULL,		-- Tipo {'Fase Grupos', 'Semifinal', 'Final'}
	num_ed Integer,				-- Numero de partido en Edicion
	prorroga Boolean,			-- Indica si hubo prorroga
	penaltis Boolean,			-- Indica si hubo penaltis
	ganador_penaltis Integer,	-- ID del equipo ganador en penaltis
 primary key (id)
);
Create table bc.usuario
(
	id Integer NOT NULL UNIQUE,	-- ID del usuario
	nombre Varchar(20) NOT NULL,	-- Nombre del usuario
 primary key (id)
);
Create table bc.equipo
(
	id Integer NOT NULL UNIQUE, -- ID del equipo
	nombre Varchar(30) NOT NULL,	-- Nombre del equipo
	imagen longblob NOT NULL,		-- Imagen
 primary key (id)
);
Create table bc.marcador
(
	partido Integer NOT NULL,		-- # Partido
        edicion Integer NOT NULL,
	equipo Integer NOT NULL,		-- Equipo que lo juega
	usuario Integer NOT NULL,		-- Usuario que lo juega
	local Boolean NOT NULL,			-- Indica si es local
	goles Integer NOT NULL,			-- Goles marcados
	ta Integer NOT NULL,			-- Tarjetas Amarillas 
	tr Integer NOT NULL,			-- Tarjetas Rojas
 primary key (partido,local)
);
Create table bc.edicion
(
	id Integer NOT NULL UNIQUE,	-- Numero de edicion (desde 1)
	fecha Date NOT NULL,		-- Fecha en la que se jugo (DD-MM-AAAA)
	hora Integer NOT NULL,		-- Hora
	mins Integer NOT NULL,		-- Minutos
 primary key (id)
);
Create table bc.eleccion
(
	edicion Integer Not null,
	usuario Integer Not null,
	equipo Integer Not null,
	PRIMARY KEY(edicion, usuario, equipo)
);
INSERT INTO usuario (id, nombre) VALUES (1, 'Miguel');
INSERT INTO usuario (id, nombre) VALUES (2, 'Javi');
INSERT INTO usuario (id, nombre) VALUES (3, 'Chechu');
INSERT INTO edicion (id, fecha, hora, mins) VALUES (1, '2017-09-30', 22, 38);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (1, 1, 1);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (1, 2, 3);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (1, 3, 2);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (1, 1, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (1, 1, 3, 2, 1, 0, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (1, 1, 2, 3, 0, 0, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (2, 1, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (2, 1, 1, 1, 1, 2, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (2, 1, 3, 2, 0, 2, 3, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (3, 1, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (3, 1, 2, 3, 1, 4, 2, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (3, 1, 1, 1, 0, 1, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (4, 1, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (4, 1, 2, 3, 1, 4, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (4, 1, 3, 2, 0, 0, 2, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (2, '2017-10-07', 21, 52);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (2, 1, 4);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (2, 2, 6);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (2, 3, 5);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (5, 2, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (5, 2, 4, 1, 1, 4, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (5, 2, 6, 2, 0, 2, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (6, 2, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (6, 2, 6, 2, 1, 3, 1, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (6, 2, 5, 3, 0, 4, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (7, 2, 'Final', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (7, 2, 4, 1, 1, 3, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (7, 2, 5, 3, 0, 1, 0, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (3, '2017-10-24', 21, 31);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (3, 1, 3);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (3, 2, 8);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (3, 3, 7);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (8, 3, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (8, 3, 8, 2, 1, 1, 1, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (8, 3, 7, 3, 0, 1, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (9, 3, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (9, 3, 3, 1, 1, 4, 3, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (9, 3, 8, 2, 0, 0, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (10, 3, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (10, 3, 7, 3, 1, 0, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (10, 3, 3, 1, 0, 3, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (11, 3, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (11, 3, 3, 1, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (11, 3, 7, 3, 0, 2, 3, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (4, '2017-12-07', 22, 26);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (4, 1, 9);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (4, 2, 10);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (4, 3, 3);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (12, 4, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (12, 4, 3, 3, 1, 2, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (12, 4, 10, 2, 0, 2, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (13, 4, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (13, 4, 9, 1, 1, 5, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (13, 4, 3, 3, 0, 3, 2, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (14, 4, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (14, 4, 10, 2, 1, 1, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (14, 4, 9, 1, 0, 2, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (15, 4, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (15, 4, 9, 1, 1, 2, 2, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (15, 4, 10, 2, 0, 1, 3, 1);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (5, '2017-12-27', 21, 48);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (5, 1, 4);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (5, 2, 3);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (5, 3, 2);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (16, 5, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (16, 5, 3, 2, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (16, 5, 4, 1, 0, 1, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (17, 5, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (17, 5, 4, 1, 1, 0, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (17, 5, 2, 3, 0, 1, 2, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (18, 5, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (18, 5, 2, 3, 1, 3, 0, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (18, 5, 3, 2, 0, 2, 2, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (19, 5, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (19, 5, 2, 3, 1, 5, 4, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (19, 5, 3, 2, 0, 3, 0, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (6, '2018-01-01', 22, 05);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (6, 1, 11);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (6, 2, 2);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (6, 3, 4);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (20, 6, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (20, 6, 2, 2, 1, 3, 0, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (20, 6, 4, 3, 0, 1, 2, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (21, 6, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (21, 6, 4, 3, 1, 3, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (21, 6, 11, 1, 0, 3, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (22, 6, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (22, 6, 11, 1, 1, 0, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (22, 6, 2, 2, 0, 1, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (23, 6, 'Final', 4, true, true, 11);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (23, 6, 2, 2, 1, 2, 3, 2);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (23, 6, 11, 1, 0, 2, 1, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (7, '2018-01-05', 22, 38);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (7, 1, 12);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (7, 2, 6);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (7, 3, 3);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (24, 7, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (24, 7, 6, 2, 1, 0, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (24, 7, 3, 3, 0, 4, 0, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (25, 7, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (25, 7, 12, 1, 1, 0, 2, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (25, 7, 6, 2, 0, 1, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (26, 7, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (26, 7, 3, 3, 1, 3, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (26, 7, 12, 1, 0, 2, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (27, 7, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (27, 7, 3, 3, 1, 2, 4, 2);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (27, 7, 6, 2, 0, 3, 0, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (8, '2018-03-02', 23, 17);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (8, 1, 7);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (8, 2, 11);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (8, 3, 13);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (28, 8, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (28, 8, 11, 2, 1, 3, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (28, 8, 13, 3, 0, 1, 3, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (29, 8, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (29, 8, 13, 3, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (29, 8, 7, 1, 0, 3, 3, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (30, 8, 'Final', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (30, 8, 11, 2, 1, 4, 6, 2);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (30, 8, 7, 1, 0, 5, 5, 1);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (9, '2018-03-03', 22, 04);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (9, 1, 4);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (9, 2, 15);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (9, 3, 14);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (31, 9, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (31, 9, 14, 3, 1, 2, 0, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (31, 9, 15, 2, 0, 4, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (32, 9, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (32, 9, 4, 1, 1, 2, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (32, 9, 14, 3, 0, 0, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (33, 9, 'Final', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (33, 9, 15, 2, 1, 5, 3, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (33, 9, 4, 1, 0, 4, 3, 1);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (10, '2018-03-22', 22, 35);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (10, 1, 10);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (10, 2, 3);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (10, 3, 2);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (34, 10, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (34, 10, 3, 2, 1, 3, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (34, 10, 2, 3, 0, 1, 2, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (35, 10, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (35, 10, 2, 3, 1, 1, 1, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (35, 10, 10, 1, 0, 1, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (36, 10, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (36, 10, 10, 1, 1, 0, 2, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (36, 10, 3, 2, 0, 1, 1, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (37, 10, 'Final', 4, true, true, 3);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (37, 10, 3, 2, 1, 3, 5, 4);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (37, 10, 10, 1, 0, 3, 1, 0);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (11, '2018-03-30', 22, 33);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (11, 1, 1);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (11, 2, 13);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (11, 3, 11);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (38, 11, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (38, 11, 13, 2, 1, 1, 0, 1);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (38, 11, 1, 1, 0, 0, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (39, 11, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (39, 11, 1, 1, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (39, 11, 11, 3, 0, 1, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (40, 11, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (40, 11, 11, 3, 1, 3, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (40, 11, 13, 2, 0, 2, 1, 1);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (41, 11, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (41, 11, 11, 3, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (41, 11, 13, 2, 0, 6, 2, 1);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (12, '2018-04-26', 22, 28);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (12, 1, 14);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (12, 2, 10);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (12, 3, 2);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (42, 12, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (42, 12, 10, 2, 1, 6, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (42, 12, 2, 3, 0, 1, 3, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (43, 12, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (43, 12, 2, 3, 1, 2, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (43, 12, 14, 1, 0, 2, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (44, 12, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (44, 12, 14, 1, 1, 0, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (44, 12, 10, 2, 0, 6, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (45, 12, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (45, 12, 10, 2, 1, 8, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (45, 12, 2, 3, 0, 2, 1, 2);
INSERT INTO edicion (id, fecha, hora, mins) VALUES (13, '2018-05-25', 21, 45);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (13, 1, 16);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (13, 2, 15);
INSERT INTO eleccion (edicion, usuario, equipo) VALUES (13, 3, 17);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (46, 13, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (46, 13, 15, 2, 1, 2, 1, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (46, 13, 16, 1, 0, 2, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (47, 13, 'Fase de Grupos', 2, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (47, 13, 17, 3, 1, 0, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (47, 13, 15, 2, 0, 3, 1, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (48, 13, 'Fase de Grupos', 3, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (48, 13, 16, 1, 1, 1, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (48, 13, 17, 3, 0, 0, 0, 0);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (49, 13, 'Final', 4, false, false, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (49, 13, 15, 2, 1, 3, 0, 0);
INSERT INTO marcador (partido, edicion, equipo, usuario, local, goles, ta, tr) VALUES (49, 13, 16, 1, 0, 4, 1, 0);