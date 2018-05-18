SET NAMES 'utf8' COLLATE 'utf8_general_ci';

GRANT ALL PRIVILEGES ON GrandChef.* TO 'grandweb'@'localhost' IDENTIFIED BY 'Pd#@5+*-Las83jd564';
SET PASSWORD FOR 'grandweb'@'localhost' = PASSWORD('Pd#@5+*-Las83jd564');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'grandweb'@'127.0.0.1' IDENTIFIED BY 'Pd#@5+*-Las83jd564';
SET PASSWORD FOR 'grandweb'@'127.0.0.1' = PASSWORD('Pd#@5+*-Las83jd564');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'grandweb'@'::1' IDENTIFIED BY 'Pd#@5+*-Las83jd564';
SET PASSWORD FOR 'grandweb'@'::1' = PASSWORD('Pd#@5+*-Las83jd564');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'%' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
SET PASSWORD FOR 'GrandChef'@'%' = PASSWORD('U#@5*8la-K76+9Hs23');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'localhost' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
SET PASSWORD FOR 'GrandChef'@'localhost' = PASSWORD('U#@5*8la-K76+9Hs23');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'127.0.0.1' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
SET PASSWORD FOR 'GrandChef'@'127.0.0.1' = PASSWORD('U#@5*8la-K76+9Hs23');

GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'::1' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
SET PASSWORD FOR 'GrandChef'@'::1' = PASSWORD('U#@5*8la-K76+9Hs23');

FLUSH PRIVILEGES;
