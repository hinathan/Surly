rm db/development.sqlite3; cat db/local.sql |  sqlite3 db/development.sqlite3
chmod 0777 db
chmod 0777 db/development.sqlite3

