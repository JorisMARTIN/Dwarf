CREATE TABLE IF NOT EXISTS "User" (
userId SERIAL PRIMARY KEY,
creationDate timestamp NOT NULL,
nickname varchar(16) NOT NULL,
email varchar(64) UNIQUE,
password varchar(255) NOT NULL,
IPs varchar(16)[]
);

CREATE TABLE IF NOT EXISTS "Page" (
pageId SERIAL PRIMARY KEY,
creationDate timestamp NOT NULL,
name varchar(32) NOT NULL,
description varchar(512),
gameMode int CHECK (gameMode >= 0 and gameMode <= 2) DEFAULT 0,
template int CHECK (template >= 0 and template <= 0) DEFAULT 0,
completed boolean DEFAULT FALSE,
userId int NOT NULL REFERENCES "User"(userId)
);

CREATE TABLE IF NOT EXISTS "Frame" (
frameId SERIAL PRIMARY KEY,
creationDate timestamp NOT NULL,
imagePtr varchar(64) NOT NULL,
drawable boolean DEFAULT FALSE,
done boolean DEFAULT FALSE,
width int CHECK (width > 0),
height int CHECK (height > 0),
pageId int NOT NULL REFERENCES "Frame"(frameId),
userId int REFERENCES "User"(userId)
);

