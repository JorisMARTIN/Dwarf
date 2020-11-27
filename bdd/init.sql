\i drop.sql
\i create.sql

INSERT INTO "User" (nickname, email, password, creationDate, IPs, birthdate) VALUES ('dwarf', 'dwarf@gmail.com', '$2y$10$.mXRTKgZLzexiBpResNYouTnoZ02y8ManasWPAFFhO7zl4iPKmu.q', CURRENT_TIMESTAMP, ARRAY['127.0.0.1'], NOW());
INSERT INTO "User" (nickname, email, password, creationDate, IPs, birthdate, admin) VALUES ('admin', 'admin@gmail.com', '$2y$10$9q0gfwIdH2xg0AItZsk7X.yxA43A253IJo5hwtq4E1LB8U14n1rSy', CURRENT_TIMESTAMP, ARRAY['127.0.0.1'], NOw(), true);
