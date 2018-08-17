DROP TRIGGER IF EXISTS `(.*)_AFTER_INSERT` \$\$
USE `GrandChef`\$\$
CREATE DEFINER = CURRENT_USER TRIGGER `GrandChef`.`\1_AFTER_INSERT` AFTER INSERT ON `\1` FOR EACH ROW
BEGIN
	DECLARE LOCAL_ID INT;
IF @@FOREIGN_KEY_CHECKS THEN

	SELECT ServidorID INTO LOCAL_ID FROM Sistema WHERE ID = '1';
	INSERT INTO `Registros` \(`ServidorID`, `Tabela`, `Linha`, `Evento`, `Momento`\) VALUES
[^']*'\1', NEW.ID, 'Inserido', UNIX_TIMESTAMP\(\)\);
	INSERT INTO `Mapeamentos` \(`ServidorID`, `Tabela`, `De`, `Para`\) VALUES
[^']*'\1', COALESCE\(@FROM_ID, NEW.ID\), NEW.ID\);

END IF;
END\$\$

DROP TRIGGER IF EXISTS `(.*)_AFTER_UPDATE` \$\$
USE `GrandChef`\$\$
CREATE DEFINER = CURRENT_USER TRIGGER `GrandChef`.`\1_AFTER_UPDATE` AFTER UPDATE ON `\1` FOR EACH ROW
BEGIN
	DECLARE LOCAL_ID INT;
IF @@FOREIGN_KEY_CHECKS THEN

	SELECT ServidorID INTO LOCAL_ID FROM Sistema WHERE ID = '1';
	INSERT INTO `Registros` \(`ServidorID`, `Tabela`, `Linha`, `Evento`, `Momento`\) VALUES
[^']*'\1', NEW.ID, 'Atualizado', UNIX_TIMESTAMP\(\)\);

END IF;
END\$\$

DROP TRIGGER IF EXISTS `(.*)_AFTER_DELETE` \$\$
USE `GrandChef`\$\$
CREATE DEFINER = CURRENT_USER TRIGGER `GrandChef`.`\1_AFTER_DELETE` AFTER DELETE ON `\1` FOR EACH ROW
BEGIN
	DECLARE LOCAL_ID INT;
IF @@FOREIGN_KEY_CHECKS THEN

	SELECT ServidorID INTO LOCAL_ID FROM Sistema WHERE ID = '1';
	INSERT INTO `Registros` \(`ServidorID`, `Tabela`, `Linha`, `Evento`, `Momento`\) VALUES
[^']*'\1', OLD.ID, 'Deletado', UNIX_TIMESTAMP\(\)\);
	DELETE FROM `Mapeamentos`
		WHERE `Tabela` = '\1' AND `Para` = OLD.ID;

END IF;
END\$\$

