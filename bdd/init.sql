\i drop.sql
\i create.sql

INSERT INTO "User" (nickname, email, password, creationDate, IPs) VALUES ('Matteo', 'matteo@gmail.com', '$2y$10$bPBV0ZNbZ9.AsVBMfl46DewzBqo5f5xcGt6ussY2YXgxtlg2LhLqy', CURRENT_TIMESTAMP, ARRAY['127.0.0.1']);
INSERT INTO "Page" (name, description, gameMode, template, creationDate, userId) VALUES ('Test', 'Ceci est un test', 1, 0, CURRENT_TIMESTAMP, 1);
INSERT INTO "Page" (name, description, gameMode, template, creationDate, userId) VALUES ('SuperbeBD', 'Une excellente BD', 1, 0, CURRENT_TIMESTAMP, 1);
INSERT INTO "Frame" (width, height, imagePtr, creationDate, pageId) VALUES (10, 10, '', '2020-08-10', 1);
