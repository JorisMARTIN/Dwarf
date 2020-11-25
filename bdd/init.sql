\i drop.sql
\i create.sql

INSERT INTO "User" (nickname, email, password, creationDate, IPs) VALUES ('dwarf', 'dwarf@gmail.com', '$2y$10$bPBV0ZNbZ9.AsVBMfl46DewzBqo5f5xcGt6ussY2YXgxtlg2LhLqy', CURRENT_TIMESTAMP, ARRAY['127.0.0.1']);
