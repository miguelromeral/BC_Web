INSERT INTO usuario (id, nombre) VALUES (1, 'Miguel');
INSERT INTO usuario (id, nombre) VALUES (2, 'Javi');
INSERT INTO usuario (id, nombre) VALUES (3, 'Chechu');
INSERT INTO edicion (id, fecha, hora, mins) VALUES (1, '2018-06-03', 12, 16);
INSERT INTO partido (id, edicion, tipo, num_ed, prorroga, penaltis, ganador_penaltis) VALUES (1, 1, 'Fase de Grupos', 1, false, false, 0);
INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES (1, 2, 1, 1, 2, 0, 0);
INSERT INTO marcador (partido, equipo, usuario, local, goles, ta, tr) VALUES (1, 1, 2, 0, 3, 2, 0);
