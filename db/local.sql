
CREATE TABLE "links" (
"id" INTEGER PRIMARY KEY,
"hashed" TEXT KEY,
"url" TEXT,
"message" TEXT,
"notify_user_id" INTEGER,
"active" INTEGER DEFAULT 1,
"clicks" INTEGER DEFAULT 0,
"verify" TEXT,
"verified" INTEGER DEFAULT 0
);



CREATE TABLE "users" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT,
"email" TEXT
);

INSERT INTO "links" VALUES (1, 'abc', 'http://google.com/', 'first!' 1, 1, 27, 'ver', 0);
INSERT INTO "users" VALUES (1, 'nschmidt@gmail.com');
