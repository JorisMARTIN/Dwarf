\i drop.sql
\i create.sql

INSERT INTO "User" (nickname, email, password, creationDate, IPs) VALUES ('Matteo', 'matteo@gmail.com', '$2y$10$BuIzjzkW4AJj6DAbuy7e/Ol3kZ8xZlAfPlUlDyA4Mdh1X3qxEn966', '2020-08-10', '{"192.168.0.1", "200.200.200.200"}');
INSERT INTO "Page" (name, description, gameMode, template, creationDate, userId) VALUES ('Test', 'Ceci est un test', 1, 0, '2020-08-10', 1);
INSERT INTO "Frame" (width, height, imagePtr, creationDate, pageId) VALUES (10, 10, '', '2020-08-10', 1);
