CREATE TABLE IF NOT EXISTS "User" (
userId SERIAL PRIMARY KEY,
nickname varchar(16) NOT NULL,
email varchar(64) UNIQUE,
password varchar(32) NOT NULL,
creationDate date NOT NULL,
IPs varchar(16)[]
);

CREATE TABLE IF NOT EXISTS "Board" (
boardId SERIAL PRIMARY KEY,
name varchar(32) NOT NULL,
description varchar(512),
gameMode int CHECK (gameMode >= 0 and gameMode <= 2) DEFAULT 0,
template int CHECK (template >= 0 and template <= 0) DEFAULT 0,
creationDate date NOT NULL,
completed boolean DEFAULT FALSE,
userId int NOT NULL REFERENCES "User"(userId)
);

CREATE TABLE IF NOT EXISTS "Case" (
caseId SERIAL PRIMARY KEY,
creationDate date NOT NULL,
imagePtr varchar(64) NOT NULL,
isNext boolean DEFAULT FALSE,
done boolean DEFAULT FALSE,
width int CHECK (width > 0),
height int CHECK (height > 0),
boardId int NOT NULL REFERENCES "Board"(boardId),
userId int REFERENCES "User"(userId)
);

