\i drop.sql
\i create.sql

INSERT INTO "User" (nickname, email, password, creationDate, IPs) VALUES ('dwarf', 'dwarf@gmail.com', '$2y$10$bPBV0ZNbZ9.AsVBMfl46DewzBqo5f5xcGt6ussY2YXgxtlg2LhLqy', CURRENT_TIMESTAMP, ARRAY['127.0.0.1']);
INSERT INTO "Page" (name, description, gameMode, template, creationDate, userId) VALUES ('Page 1', 'Ceci est la page 1', 1, 0, CURRENT_TIMESTAMP, 1);
INSERT INTO "Page" (name, description, gameMode, template, creationDate, userId) VALUES ('Page 2', 'Ceci est la page 2', 1, 0, CURRENT_TIMESTAMP, 1);
INSERT INTO "Frame" (width, height, imagePtr, creationDate, pageId) VALUES (10, 10, '', CURRENT_TIMESTAMP, 1);
