/* Adicionado campo para informar o troco quando o pedido é para entrega */
Update (Version: "1.3.0.0") {

	ALTER TABLE Pedidos ADD `Dinheiro` DOUBLE NOT NULL DEFAULT 0 AFTER `DataSaida`;

}

/* Adicionado campo para chave */
Update (Version: "1.3.0.1") {

	ALTER TABLE Empresas ADD `RegistryKey` TEXT NULL DEFAULT NULL AFTER `AccessKey`;

}

/* Adicionado tabela sistema */
Update (Version: "1.3.0.2") {

	CREATE TABLE IF NOT EXISTS `Sistema` (
		`ID` ENUM('1') NOT NULL,
		`VersaoDB` VARCHAR(45) NULL,
		`UltimoBackup` DATETIME NULL,
		PRIMARY KEY (`ID`))
	ENGINE = InnoDB;

	INSERT INTO `Sistema` (ID, VersaoDB) VALUES
		('1', "1.3.0.2");

}

/* Adicionado permissão de mudar atendente */
Update (Version: "1.3.0.3") {

	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(43, "AlterarAtendente", "Permitir alterar o atendente no momento da venda");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 43 as PermissaoID FROM Acessos WHERE PermissaoID = 11;

}

/* Adicionado campo LicenseKey na tabela empresas */
/* Adicionado permissão de alterar o atendente nas venda para balcão */
Update (Version: "1.3.0.4") {

	ALTER TABLE Empresas ADD `LicenseKey` TEXT NULL DEFAULT NULL AFTER `RegistryKey`;
	ALTER TABLE Empresas ADD `GUID` VARCHAR(36) NULL DEFAULT NULL AFTER `LicenseKey`;
	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(44, "AlterarAtendenteBalcao", "Permitir alterar o atendente nas vendas para balcão");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 44 as PermissaoID FROM Acessos WHERE PermissaoID = 4;
}

/* Funcionário também pode ser cliente */
/* Adicionado opções do computador */
/* Adicionado detalhes do pedido */
/* Contas com prazo de validade e periodicidade */
/* Estoque com produtos nos setores e informações sobre o lote */
/* Recuperação de pedidos abandonados */
/* Adicionado insumo como tipo de produto */
Update (Version: "1.3.0.5") {

	ALTER TABLE Clientes ADD `FuncionarioID` INT NULL DEFAULT NULL AFTER `ID`;
	UPDATE Clientes SET Fone1 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '')),
		Fone2 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', ''))
		WHERE NOT ISNULL(Fone1) OR NOT ISNULL(Fone2);
	ALTER TABLE Clientes MODIFY `Fone1` VARCHAR(12) NOT NULL;
	ALTER TABLE Clientes MODIFY `Fone2` VARCHAR(12) NULL;
	ALTER TABLE Clientes ADD INDEX `FK_Clientes_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC);
	ALTER TABLE Clientes ADD UNIQUE INDEX `FuncionarioID_UNIQUE` (`FuncionarioID` ASC);
	ALTER TABLE Clientes ADD CONSTRAINT `FK_Clientes_Funcionarios_FuncionarioID`
		FOREIGN KEY (`FuncionarioID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
			
	ALTER TABLE Computadores ADD `Opcoes` INT NOT NULL DEFAULT 0 AFTER `CaixaID`;
	
	ALTER TABLE Produtos_Pedidos ADD `Detalhes` VARCHAR(255) NULL DEFAULT NULL AFTER `PrecoVenda`;
	
	ALTER TABLE Pedidos MODIFY `Estado` ENUM('Finalizado', 'Ativo', 'Entrega', 'Fechado') NOT NULL DEFAULT 'Ativo';
	ALTER TABLE Pedidos ADD `CaixaID` INT NULL DEFAULT NULL AFTER `SessaoID`;
	ALTER TABLE Pedidos ADD INDEX `FK_Pedidos_Caixas_CaixaID_idx` (`CaixaID` ASC);
	ALTER TABLE Pedidos ADD CONSTRAINT `FK_Pedidos_Caixas_CaixaID`
		FOREIGN KEY (`CaixaID`)
		REFERENCES `Caixas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
			
	ALTER TABLE Produtos MODIFY `Tipo` ENUM('Produto', 'Composicao', 'Pacote', 'Insumo') NOT NULL DEFAULT 'Produto';
	ALTER TABLE Produtos ADD `Visivel` ENUM('Y', 'N') NOT NULL DEFAULT 'Y';
	
	ALTER TABLE Contas ADD `FuncionarioID` INT NULL DEFAULT NULL AFTER `ID`;
	UPDATE Contas SET FuncionarioID = 1;
	ALTER TABLE Contas MODIFY `FuncionarioID` INT NOT NULL;
	ALTER TABLE Contas ADD `ClienteID` INT NULL DEFAULT NULL AFTER `FuncionarioID`;
	ALTER TABLE Contas MODIFY `Descricao` VARCHAR(200) NOT NULL;
	ALTER TABLE Contas ADD `Vencimento` DATETIME NULL DEFAULT NULL;
	ALTER TABLE Contas ADD `Periodicidade` INT NULL DEFAULT NULL;
	ALTER TABLE Contas ADD `DataCadastro` DATETIME NULL DEFAULT NULL;
	UPDATE Contas SET DataCadastro = NOW();
	ALTER TABLE Contas MODIFY `DataCadastro` DATETIME NOT NULL;
	ALTER TABLE Contas ADD INDEX `FK_Contas_Clientes_ClienteID_idx` (`ClienteID` ASC);
	ALTER TABLE Contas ADD INDEX `FK_Contas_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC);
	ALTER TABLE Contas ADD CONSTRAINT `FK_Contas_Clientes_ClienteID`
		FOREIGN KEY (`ClienteID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE Contas ADD CONSTRAINT `FK_Contas_Funcionarios_FuncionarioID`
		FOREIGN KEY (`FuncionarioID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE Contas DROP INDEX `Descricao_UNIQUE`;
	UPDATE Contas SET Periodicidade = 30 WHERE ID IN (2, 3, 4);
	
	ALTER TABLE Estoque MODIFY `TipoMovimento` ENUM('Entrada', 'Venda', 'Consumo', 'Transferencia') NOT NULL;
	ALTER TABLE Estoque ADD `SetorID` INT NULL DEFAULT NULL AFTER `FornecedorID`;
	UPDATE Estoque SET SetorID = 1;
	ALTER TABLE Estoque MODIFY `SetorID` INT NOT NULL;
	ALTER TABLE Estoque ADD INDEX `FK_Estoque_Setores_SetorID_idx` (`SetorID` ASC);
	ALTER TABLE Estoque ADD CONSTRAINT `FK_Estoque_Setores_SetorID`
		FOREIGN KEY (`SetorID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE Estoque ADD `Lote` VARCHAR(45) NULL DEFAULT NULL AFTER `PrecoCompra`;
	ALTER TABLE Estoque ADD `DataFabricacao` DATETIME NULL DEFAULT NULL AFTER `Lote`;
	ALTER TABLE Estoque ADD `DataVencimento` DATETIME NULL DEFAULT NULL AFTER `DataFabricacao`;
	ALTER TABLE Estoque ADD `Detalhes` VARCHAR(100) NULL DEFAULT NULL AFTER `DataVencimento`;

	UPDATE Funcionarios SET Fone = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone,'(', ''), ')', ''), ' ', ''), '-', '')) 
		WHERE NOT ISNULL(Fone);
	ALTER TABLE Funcionarios MODIFY `Fone` VARCHAR(12) NULL DEFAULT NULL;

	UPDATE Empresas SET Fone1 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '')),
		Fone2 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', ''))
		WHERE NOT ISNULL(Fone1) OR NOT ISNULL(Fone2);
	ALTER TABLE Empresas MODIFY `Fone1` VARCHAR(12) NULL DEFAULT NULL;
	ALTER TABLE Empresas MODIFY `Fone2` VARCHAR(12) NULL DEFAULT NULL;

	UPDATE Fornecedores SET Fone1 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '')),
		Fone2 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', ''))
		WHERE NOT ISNULL(Fone1) OR NOT ISNULL(Fone2);
	ALTER TABLE Fornecedores MODIFY `Fone1` VARCHAR(12) NOT NULL;
	ALTER TABLE Fornecedores MODIFY `Fone2` VARCHAR(12) NULL;

}

/* Separado Bairros e Estados da localização, para permitir cobrar valor de entrega */
/* Adicionado sub categoria e imagens em categorias e produtos */
/* Adicionado setor de preparo do produto e se o produto é perecível */
/* Adicionado opção para montar um prato */
/* Adicionado informações nutricionais sobre os produtos */
Update (Version: "1.3.0.6") {

	CREATE TABLE IF NOT EXISTS `Estados` (
		`ID` INT NOT NULL,
		`Nome` VARCHAR(64) NOT NULL,
		`UF` VARCHAR(2) NOT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC),
		UNIQUE INDEX `UF_UNIQUE` (`UF` ASC))
	ENGINE = InnoDB;
	INSERT INTO `Estados` VALUES (1,'Acre','AC'),(2,'Alagoas','AL'),(3,'Amazonas','AM'),(4,'Amapá','AP'),(5,'Bahia','BA'),(6,'Ceará','CE'),(7,'Brasília','DF'),(8,'Espírito Santo','ES'),(9,'Goiás','GO'),(10,'Maranhão','MA'),(11,'Minas Gerais','MG'),(12,'Mato Grosso do Sul','MS'),(13,'Mato Grosso','MT'),(14,'Pará','PA'),(15,'Paraíba','PB'),(16,'Pernambuco','PE'),(17,'Piauí','PI'),(18,'Paraná','PR'),(19,'Rio de Janeiro','RJ'),(20,'Rio Grande do Norte','RN'),(21,'Rondônia','RO'),(22,'Roraima','RR'),(23,'Rio Grande do Sul','RS'),(24,'Santa Catarina','SC'),(25,'Sergipe','SE'),(26,'São Paulo','SP'),(27,'Tocantins','TO');

	CREATE TABLE IF NOT EXISTS `Bairros` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`EstadoID` INT NOT NULL,
		`Nome` VARCHAR(100) NOT NULL,
		`Cidade` VARCHAR(50) NOT NULL,
		`ValorEntrega` DOUBLE NOT NULL,
		`Disponivel` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		PRIMARY KEY (`ID`),
		INDEX `FK_Bairros_Estados_EstadoID_idx` (`EstadoID` ASC),
		UNIQUE INDEX `UK_Estado_Cidade_Nome` (`EstadoID` ASC, `Cidade` ASC, `Nome` ASC),
		CONSTRAINT `FK_Bairros_Estados_EstadoID`
			FOREIGN KEY (`EstadoID`)
			REFERENCES `Estados` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE)
	ENGINE = InnoDB;

	INSERT INTO `Bairros` (EstadoID, Nome, Cidade, ValorEntrega) 
		(SELECT e.ID as EstadoID, `Bairro` as Nome, `Cidade`, 0 as ValorEntrega 
		 FROM `Localizacoes` l
		 LEFT JOIN `Estados` e ON e.UF = l.UF
		 GROUP BY `Bairro`, `Cidade`, l.`UF`);
	ALTER TABLE `Localizacoes` ADD `BairroID` INT NULL DEFAULT NULL AFTER `ClienteID`;
	UPDATE `Localizacoes` l
		LEFT JOIN `Estados` e ON e.UF = l.UF
		LEFT JOIN `Bairros` b ON b.Nome = l.Bairro AND b.Cidade = l.Cidade AND e.ID = b.EstadoID
		SET `BairroID` = b.ID;
	ALTER TABLE `Localizacoes` MODIFY `BairroID` INT NOT NULL;
	ALTER TABLE `Localizacoes` DROP `Bairro`;
	ALTER TABLE `Localizacoes` DROP `Cidade`;
	ALTER TABLE `Localizacoes` DROP `UF`;
	ALTER TABLE `Localizacoes` ADD INDEX `FK_Localizacoes_Bairros_idx` (`BairroID` ASC);
	ALTER TABLE `Localizacoes` ADD CONSTRAINT `FK_Localizacoes_Bairros`
		FOREIGN KEY (`BairroID`)
		REFERENCES `Bairros` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Categorias` ADD `CategoriaID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Categorias` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `Servico`;
	ALTER TABLE `Categorias` ADD `DataAtualizacao` DATETIME NULL DEFAULT NULL AFTER `Imagem`;
	UPDATE `Categorias` SET `DataAtualizacao` = NOW();
	ALTER TABLE `Categorias` MODIFY `DataAtualizacao` DATETIME NOT NULL;
	ALTER TABLE `Categorias` ADD INDEX `FK_Categorias_Categorias_CategoriaID_idx` (`CategoriaID` ASC);
	ALTER TABLE `Categorias` ADD CONSTRAINT `FK_Categorias_Categorias_CategoriaID`
		FOREIGN KEY (`CategoriaID`)
		REFERENCES `Categorias` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;

	INSERT INTO `Setores` (Nome, Descricao) VALUES
		("Churrasqueira", "Churrasqueira"),
		("Barman", "Barman");


	ALTER TABLE `Produtos` ADD `Perecivel` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Divisivel`;
	ALTER TABLE `Produtos` ADD `SetorPreparoID` INT NULL DEFAULT NULL AFTER `UnidadeID`;
	ALTER TABLE `Produtos` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `Visivel`;
	ALTER TABLE `Produtos` ADD `DataAtualizacao` DATETIME NULL DEFAULT NULL AFTER `Imagem`;
	UPDATE `Produtos` p
		LEFT JOIN `Setores` s ON s.Nome LIKE 'Cozinha' AND p.Tipo = 'Composicao'
		SET `SetorPreparoID` = s.ID, `DataAtualizacao` = NOW();
	ALTER TABLE `Produtos` MODIFY `DataAtualizacao` DATETIME NOT NULL;
	ALTER TABLE `Produtos` ADD INDEX `FK_Produtos_Setores_SetorPreparoID_idx` (`SetorPreparoID` ASC);
	ALTER TABLE `Produtos` ADD CONSTRAINT `FK_Produtos_Setores_SetorPreparoID`
		FOREIGN KEY (`SetorPreparoID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Produtos_Pedidos` ADD `ProdutoPedidoID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Produtos_Pedidos` ADD INDEX `FK_ProdPed_ProdPed_ProdutoPedidoID_idx` (`ProdutoPedidoID` ASC);
	ALTER TABLE `Produtos_Pedidos` ADD CONSTRAINT `FK_ProdPed_ProdPed_ProdutoPedidoID`
		FOREIGN KEY (`ProdutoPedidoID`)
		REFERENCES `Produtos_Pedidos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;


	ALTER TABLE `Empresas` ADD `EstadoID` INT NULL DEFAULT NULL AFTER `Fone2`;
	UPDATE `Empresas` emp 
		LEFT JOIN `Estados` est ON est.UF = emp.UF
		SET `EstadoID` = est.ID;
	ALTER TABLE `Empresas` DROP `UF`;
	ALTER TABLE `Empresas` ADD INDEX `FK_Empresas_Estados_EstadoID_idx` (`EstadoID` ASC);
	ALTER TABLE `Empresas` ADD CONSTRAINT `FK_Empresas_Estados_EstadoID`
		FOREIGN KEY (`EstadoID`)
		REFERENCES `Estados` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	CREATE TABLE IF NOT EXISTS `Grupos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Descricao` VARCHAR(100) NOT NULL,
		`Opcional` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		`Multiplo` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		`Tipo` ENUM('Inteiro', 'Fracionado') NOT NULL DEFAULT 'Inteiro',
		PRIMARY KEY (`ID`))
	ENGINE = InnoDB;


	ALTER TABLE `Pacotes` ADD `GrupoID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Pacotes` ADD `Valor` DOUBLE NULL DEFAULT NULL AFTER `Quantidade`;
	ALTER TABLE `Pacotes` ADD `PrecoFixo` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Valor`;
	UPDATE `Pacotes` SET `Valor` = 0;
	ALTER TABLE `Pacotes` MODIFY `Valor` DOUBLE NOT NULL;
	ALTER TABLE `Pacotes` ADD INDEX `FK_Pacotes_Grupos_GrupoID_idx` (`GrupoID` ASC);
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Grupos_GrupoID`
		FOREIGN KEY (`GrupoID`)
		REFERENCES `Grupos` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	CREATE TABLE IF NOT EXISTS `Informacoes` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`ProdutoID` INT NOT NULL,
		`ValorEnergetico` DOUBLE NOT NULL,
		`Carboidratos` DOUBLE NOT NULL,
		`Proteinas` DOUBLE NOT NULL,
		`GordurasTotais` DOUBLE NOT NULL,
		`GordurasSaturadas` DOUBLE NOT NULL,
		`GorduraTrans` DOUBLE NOT NULL,
		`FibraAlimentar` DOUBLE NOT NULL,
		`Sodio` DOUBLE NOT NULL,
		PRIMARY KEY (`ID`),
		INDEX `FK_Informacoes_Produtos_ProdutoID_idx` (`ProdutoID` ASC),
		UNIQUE INDEX `ProdutoID_UNIQUE` (`ProdutoID` ASC),
		CONSTRAINT `FK_Informacoes_Produtos_ProdutoID`
			FOREIGN KEY (`ProdutoID`)
			REFERENCES `Produtos` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE)
	ENGINE = InnoDB;


	ALTER TABLE `Fornecedores` ADD `EstadoID` INT NULL DEFAULT NULL AFTER `Fone2`;
	UPDATE `Fornecedores` frn 
		LEFT JOIN `Estados` est ON est.UF = frn.UF
		SET `EstadoID` = est.ID;
	ALTER TABLE `Fornecedores` MODIFY `EstadoID` INT NOT NULL;
	ALTER TABLE `Fornecedores` DROP `UF`;
	ALTER TABLE `Fornecedores` ADD INDEX `FK_Fornecedores_Estados_EstadoID_idx` (`EstadoID` ASC);
	ALTER TABLE `Fornecedores` ADD CONSTRAINT `FK_Fornecedores_Estados_EstadoID`
		FOREIGN KEY (`EstadoID`)
		REFERENCES `Estados` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	INSERT INTO `Funcoes` (Descricao, SalarioBase) VALUES 
		("Estoquista", 724) 
		ON DUPLICATE KEY UPDATE
		Descricao = VALUES(Descricao);
	SELECT ID INTO @func_id FROM `Funcoes` WHERE Descricao = "Estoquista";
	INSERT INTO `Acessos` (FuncaoID, PermissaoID) VALUES
		(@func_id, 1),
		(@func_id, 17),
		(@func_id, 28),
		(@func_id, 29)
		ON DUPLICATE KEY UPDATE
		PermissaoID = VALUES(PermissaoID);


	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(45, "CadastroBairros", "Permitir cadastrar ou alterar informações de um bairro");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 45 as PermissaoID FROM Acessos WHERE PermissaoID = 42;

	ALTER TABLE `Pedidos` ADD `ValorEntrega` DOUBLE NOT NULL DEFAULT 0 AFTER `Dinheiro`;

	ALTER TABLE `Produtos` ADD `Detalhes` VARCHAR(200) NULL DEFAULT NULL AFTER `Descricao`;
	ALTER TABLE `Produtos` ADD `CobrarServico` ENUM('Y', 'N') NOT NULL DEFAULT 'Y' AFTER `Tipo`;
	ALTER TABLE `Produtos` ADD `Pesavel` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Divisivel`;
	UPDATE `Produtos` p 
		LEFT JOIN `Categorias` c ON c.ID = p.CategoriaID
		SET p.Pesavel = 'Y'
		WHERE p.Tipo = 'Composicao' AND p.Divisivel = 'Y' AND NOT (c.Descricao LIKE "%Pizza%");

	ALTER TABLE `Impressoras` ADD `SetorID` INT NULL DEFAULT NULL AFTER `ComputadorID`;
	UPDATE `Impressoras` imp 
		LEFT JOIN `Computadores` cmp ON imp.ComputadorID = cmp.ID
		SET imp.`SetorID` = cmp.SetorID
		WHERE NOT ISNULL(imp.ComputadorID);
	UPDATE `Impressoras` imp 
		LEFT JOIN `Setores` sto ON sto.Nome = 'Vendas'
		SET imp.`SetorID` = sto.ID
		WHERE ISNULL(imp.ComputadorID);
	UPDATE `Impressoras` imp 
		SET imp.`SetorID` = (SELECT ID FROM Setores LIMIT 1)
		WHERE ISNULL(imp.SetorID);
	ALTER TABLE `Impressoras` MODIFY `SetorID` INT NOT NULL;
	ALTER TABLE `Impressoras` ADD INDEX `FK_Impressoras_Setores_SetorID_idx` (`SetorID` ASC);
	ALTER TABLE `Impressoras` ADD CONSTRAINT `FK_Impressoras_Setores_SetorID`
		FOREIGN KEY (`SetorID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Impressoras` DROP INDEX `Impresoras_Nome_Modo_Computador_UNIQUE`;
	INSERT INTO `Impressoras` (Nome, Descricao, Modo, ComputadorID, SetorID, Opcoes) 
		(SELECT imp.Nome, 'Impressora da cozinha' as Descricao, 'Cozinha' as Modo, imp.ComputadorID, sto.ID as SetorID, imp.Opcoes
		 FROM `Impressoras` imp
		 LEFT JOIN `Setores` sto ON sto.Nome LIKE 'Cozinha'
		 WHERE (SELECT COUNT(imp2.ID) FROM `Impressoras` imp2 WHERE imp.ComputadorID = imp2.ComputadorID AND imp2.Modo = 'Cozinha') = 0);

	CREATE TABLE IF NOT EXISTS `Servicos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Nome` VARCHAR(50) NOT NULL,
		`Descricao` VARCHAR(100) NOT NULL,
		`Detalhes` VARCHAR(200) NULL DEFAULT NULL,
		`Tipo` ENUM('Evento', 'Taxa') NOT NULL,
		`Obrigatorio` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		`DataInicio` DATETIME NULL DEFAULT NULL,
		`DataFim` DATETIME NULL DEFAULT NULL,
		`Periodicidade` INT NULL DEFAULT NULL,
		`Valor` DOUBLE NOT NULL DEFAULT 0,
		`Porcentagem` DOUBLE NOT NULL DEFAULT 0,
		`Individual` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
		`Ativo` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		PRIMARY KEY (`ID`))
	ENGINE = InnoDB;

	ALTER TABLE `Empresas` ADD `Remetente` VARCHAR(150) NULL DEFAULT NULL AFTER `GUID`;
	ALTER TABLE `Empresas` ADD `Servidor` VARCHAR(150) NULL DEFAULT NULL AFTER `Remetente`;
	ALTER TABLE `Empresas` ADD `Porta` INT NULL DEFAULT NULL AFTER `Servidor`;
	ALTER TABLE `Empresas` ADD `Criptografia` ENUM('Nenhum', 'SSL', 'TLS') NULL DEFAULT NULL AFTER `Porta`;
	ALTER TABLE `Empresas` ADD `Usuario` VARCHAR(150) NULL DEFAULT NULL AFTER `Criptografia`;
	ALTER TABLE `Empresas` ADD `Senha` VARCHAR(45) NULL DEFAULT NULL AFTER `Usuario`;

	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `Impresoras_Modo_Computador_Setor_UNIQUE` (`Modo` ASC, `ComputadorID` ASC, `SetorID` ASC);
	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `Impressoras_Descricao_Computador_UNIQUE` (`Descricao` ASC, `ComputadorID` ASC);

}

/* Adicionado controle de acesso às contas */
Update (Version: "1.3.0.7") {
		
	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(46, "CadastroContas", "Permitir cadastrar ou alterar contas a pagar ou a receber"),
		(47, "RelatorioContas", "Permitir visualizar relatórios de contas"),
		(48, "RelatorioProdutos", "Permitir visualizar relatórios de vendas de produtos");
	
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 46 as PermissaoID FROM Acessos WHERE PermissaoID = 11;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 47 as PermissaoID FROM Acessos WHERE PermissaoID = 11;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 48 as PermissaoID FROM Acessos WHERE PermissaoID = 11;

	UPDATE `Contas` SET Valor = -45 WHERE ID = 4 AND Descricao = "Gás";

}

/* Adicionado forma de pagamento como conta */
/* Adicionado campo margem, permitindo margem de erro em dias para contas periódicas */
Update (Version: "1.3.0.8") {

	ALTER TABLE `Formas_Pagto` MODIFY `Tipo` ENUM('Dinheiro', 'Cartao', 'Cheque', 'Conta') NOT NULL;
	INSERT INTO `Formas_Pagto` (Descricao, Tipo, Parcelado, ComEntrada, MinParcelas, MaxParcelas, ParcelasSemJuros, Juros) VALUES 
		("Na Conta", 'Conta', 'N', 'N', NULL, NULL, NULL, NULL);

	ALTER TABLE `Pagtos_Pedidos` ADD `ContaID` INT NULL DEFAULT NULL AFTER `ChequeID`;
	ALTER TABLE `Pagtos_Pedidos` ADD INDEX `FK_PagPed_Contas_ContaID_idx` (`ContaID` ASC);
	ALTER TABLE `Pagtos_Pedidos` ADD CONSTRAINT `FK_PagPed_Contas_ContaID`
		FOREIGN KEY (`ContaID`)
		REFERENCES `Contas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Contas` ADD `Margem` INT NULL DEFAULT NULL AFTER `Periodicidade`;
	UPDATE `Contas` SET Margem = TRUNCATE(0.1 * Periodicidade, 0) 
		WHERE ISNULL(Vencimento) AND NOT ISNULL(Periodicidade);

}

/* Adicionado número de colunas da impressora */
Update (Version: "1.3.1.0") {

	ALTER TABLE `Impressoras` ADD `Colunas` INT NOT NULL DEFAULT 48 AFTER `Opcoes`;

}

/* Adicionado porcentagem para os produtos pedidos */
Update (Version: "1.3.1.1") {

	ALTER TABLE `Produtos_Pedidos` MODIFY `DataHora` DATETIME NOT NULL AFTER `Detalhes`;
	ALTER TABLE `Produtos_Pedidos` ADD `Porcentagem` DOUBLE NOT NULL DEFAULT 0 AFTER `Quantidade`;
	UPDATE `Produtos_Pedidos` pdp
		LEFT JOIN `Funcionarios` f ON f.ID = pdp.FuncionarioID
		LEFT JOIN `Pedidos` p ON p.ID = pdp.PedidoID
		SET pdp.Porcentagem = COALESCE(f.Porcentagem, 0)
		WHERE NOT IsNull(p.MesaID);
	UPDATE Produtos_Pedidos
		SET Porcentagem = 0
		WHERE PedidoID IN
		(SELECT ID FROM (SELECT p.ID,
		(SELECT SUM(Preco * Quantidade) FROM Produtos_Pedidos WHERE PedidoID = p.ID ) as TotalProdutos,
		(SELECT SUM(Preco * Quantidade * Porcentagem / 100) FROM Produtos_Pedidos WHERE PedidoID = p.ID ) as TotalComissao,
		(SELECT sum(Total) FROM Pagtos_Pedidos WHERE PedidoID = p.ID ) as TotalPago
		FROM Pedidos p
		HAVING (TotalProdutos - 0.001 <	TotalPago AND TotalProdutos + 0.001 >	TotalPago AND TotalComissao > 0.001)) as tbl);
	ALTER TABLE `Empresas` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `Senha`;
	ALTER TABLE `Formas_Pagto` ADD `Ativa` ENUM('Y', 'N') NOT NULL DEFAULT 'Y' AFTER `Juros`;
	ALTER TABLE `Contas` ADD `Ativa` ENUM('Y', 'N') NOT NULL DEFAULT 'Y' AFTER `Margem`;
	ALTER TABLE `Impressoras` ADD `Avanco` INT NOT NULL DEFAULT 6 AFTER `Colunas`;

}

/* Adicionado permissões de relatórios */
Update (Version: "1.3.4.0") {
		
	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(49, "RelatorioCompras", "Permitir visualizar relatórios de compras de produtos");
	
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 49 as PermissaoID FROM Acessos WHERE PermissaoID = 11;
	
	UPDATE `Contas` SET `Ativa` = 'Y' WHERE `Descricao` LIKE "Pagamento do Pedido:%";
		
}

/* Adicionado comandas */
/* Adicionado taxas de serviços */
/* Adicionado taxa da operadora de cartão */
/* Alterado aberturas de caixa para movimentos */
Update (Version: "1.3.5.0") {

	ALTER TABLE `Cartoes` ADD `Taxa` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `Descricao`;
	ALTER TABLE `Cartoes` ADD `Ativo` ENUM('Y', 'N') NOT NULL DEFAULT 'Y';
	
	ALTER TABLE `Funcoes` MODIFY `SalarioBase` DECIMAL(19,4) NOT NULL;
	
	ALTER TABLE `Clientes` ADD `Sobrenome` VARCHAR(100) NULL DEFAULT NULL AFTER `Nome`;
	ALTER TABLE `Clientes` ADD `Genero` ENUM('Masculino', 'Feminino') NOT NULL DEFAULT 'Masculino' AFTER `Sobrenome`;
	UPDATE `Funcionarios` SET Fone = LPAD(ID, 10, '0') WHERE IsNull(Fone) or Fone = '';
	INSERT INTO `Clientes` (Nome, Genero, Fone1, DataCadastro) 
		(SELECT IF(func.Nome = '', func.Login, func.Nome) as Nome, func.Genero, func.Fone as Fone1, func.Cadastro as DataCadastro
		 FROM `Funcionarios` func
		 WHERE (SELECT COUNT(cli.ID) FROM `Clientes` cli WHERE func.Fone = cli.Fone1) = 0);
	UPDATE `Clientes` SET `Sobrenome` = RIGHT(CONCAT(`Nome`, ' '), LENGTH(CONCAT(`Nome`, ' ')) - LOCATE(' ', CONCAT(`Nome`, ' ')));
	UPDATE `Clientes` SET `Nome` = LEFT(CONCAT(`Nome`, ' '), LOCATE(' ', CONCAT(`Nome`, ' ')));
	UPDATE `Clientes` SET `Sobrenome` = '' WHERE IsNull(`Sobrenome`);
	ALTER TABLE `Clientes` MODIFY `Sobrenome` VARCHAR(100) NOT NULL;
	ALTER TABLE `Clientes` ADD `CPF` VARCHAR(11) NULL DEFAULT NULL AFTER `Genero`;
	ALTER TABLE `Clientes` ADD `RG` VARCHAR(15) NULL DEFAULT NULL AFTER `CPF`;
	ALTER TABLE `Clientes` ADD UNIQUE INDEX `CPF_UNIQUE` (`CPF` ASC);
	ALTER TABLE `Clientes` DROP FOREIGN KEY `FK_Clientes_Funcionarios_FuncionarioID`;
	ALTER TABLE `Clientes` DROP INDEX `FK_Clientes_Funcionarios_FuncionarioID_idx`;
	ALTER TABLE `Clientes` DROP `FuncionarioID`;
	
	ALTER TABLE `Funcionarios` DROP `Nome`;
	ALTER TABLE `Funcionarios` DROP `Genero`;
	ALTER TABLE `Funcionarios` DROP `Endereco`;
	ALTER TABLE `Funcionarios` ADD `ClienteID` INT NULL DEFAULT NULL AFTER `FuncaoID`;
	UPDATE `Funcionarios` func SET ClienteID = (SELECT cli.ID FROM `Clientes` cli WHERE func.Fone = cli.Fone1);
	ALTER TABLE `Funcionarios` DROP `Fone`;
	ALTER TABLE `Funcionarios` MODIFY `ClienteID` INT NOT NULL;
	ALTER TABLE `Funcionarios` ADD UNIQUE INDEX `UK_ClienteID` (`ClienteID` ASC);
	ALTER TABLE `Funcionarios` ADD CONSTRAINT `FK_Funcionarios_Clientes_ClienteID`
		FOREIGN KEY (`ClienteID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	
	ALTER TABLE `Bairros` MODIFY `ValorEntrega` DECIMAL(19,4) NOT NULL;
		
	CREATE TABLE IF NOT EXISTS `Comandas` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Nome` VARCHAR(50) NOT NULL,
		`Ativa` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC))
	ENGINE = InnoDB;
	
	ALTER TABLE `Pagtos_Pedidos` DROP FOREIGN KEY `FK_PagPed_Aberturas_Caixas_SessaoID_CaixaID`;
	ALTER TABLE `Pagtos_Pedidos` DROP INDEX `FK_PagPed_Aberturas_Caixas_SessaoID_CaixaID_idx`;
	ALTER TABLE `Pagtos_Contas` DROP FOREIGN KEY `FK_Pgto_Contas_Aberturas_Caixas_SessaoID_CaixaID`;
	ALTER TABLE `Pagtos_Contas` DROP INDEX `FK_Pgto_Contas_Aberturas_Caixas_SessaoID_CaixaID_idx`;
	
	RENAME TABLE `Aberturas_Caixas` TO `Movimentacoes`;
	ALTER TABLE `Movimentacoes` DROP INDEX `IDX_Aberturas_Caixas_CaixaID_Aberto`;
	ALTER TABLE `Movimentacoes` CHANGE `Aberto` `Aberta` ENUM('Y', 'N') NOT NULL DEFAULT 'Y' AFTER `CaixaID`;
	ALTER TABLE `Movimentacoes` DROP INDEX `UK_Aberturas_Caixas_SessaoID_CaixaID`;
	ALTER TABLE `Movimentacoes` DROP `Inicio`;
	ALTER TABLE `Movimentacoes` DROP FOREIGN KEY `FK_Aberturas_Caixas_Funcionarios_FuncionarioID`;
	ALTER TABLE `Movimentacoes` DROP INDEX `FK_Aberturas_Caixas_Funcionarios_FuncionarioID_idx`;
	ALTER TABLE `Movimentacoes` CHANGE `FuncionarioID` `FuncionarioAberturaID` INT NOT NULL;
	ALTER TABLE `Movimentacoes` ADD `FuncionarioFechamentoID` INT NULL DEFAULT NULL AFTER `DataAbertura`;
	ALTER TABLE `Movimentacoes` MODIFY `DataFechamento` DATETIME NULL DEFAULT NULL;
	
	ALTER TABLE `Movimentacoes` DROP FOREIGN KEY `FK_Aberturas_Caixas_Sessoes_SessaoID`;
	ALTER TABLE `Movimentacoes` DROP FOREIGN KEY `FK_Aberturas_Caixas_Caixas_CaixaID`;
	ALTER TABLE `Movimentacoes` DROP INDEX `FK_Aberturas_Caixas_Sessoes_SessaoID_idx`;
	ALTER TABLE `Movimentacoes` DROP INDEX `FK_Aberturas_Caixas_Caixas_CaixaID_idx`;
	ALTER TABLE `Movimentacoes` ADD INDEX `FK_Movimentacoes_Sessoes_SessaoID_idx` (`SessaoID` ASC);
	ALTER TABLE `Movimentacoes` ADD INDEX `FK_Movimentacoes_Caixas_CaixaID_idx` (`CaixaID` ASC);
	ALTER TABLE `Movimentacoes` ADD INDEX `FK_Movimentacoes_Funcionarios_FuncionarioAberturaID_idx` (`FuncionarioAberturaID` ASC);
	ALTER TABLE `Movimentacoes` ADD INDEX `FK_Movimentacoes_Funcionarios_FuncionarioFechamentoID_idx` (`FuncionarioFechamentoID` ASC);
	ALTER TABLE `Movimentacoes` ADD CONSTRAINT `FK_Movimentacoes_Sessoes_SessaoID`
		FOREIGN KEY (`SessaoID`)
		REFERENCES `Sessoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Movimentacoes` ADD CONSTRAINT `FK_Movimentacoes_Caixas_CaixaID`
		FOREIGN KEY (`CaixaID`)
		REFERENCES `Caixas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Movimentacoes` ADD CONSTRAINT `FK_Movimentacoes_Funcionarios_FuncionarioAberturaID`
		FOREIGN KEY (`FuncionarioAberturaID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Movimentacoes` ADD CONSTRAINT `FK_Movimentacoes_Funcionarios_FuncionarioFechamentoID`
		FOREIGN KEY (`FuncionarioFechamentoID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Movimentacoes` 
		SET `FuncionarioFechamentoID` = `FuncionarioAberturaID`
		WHERE `Aberta` = 'N';
	
	ALTER TABLE `Pedidos` ADD `ComandaID` INT NULL DEFAULT NULL AFTER `MesaID`;
	ALTER TABLE `Pedidos` MODIFY `Tipo` ENUM('Mesa', 'Comanda', 'Avulso', 'Entrega') NOT NULL DEFAULT 'Mesa';
	ALTER TABLE `Pedidos` MODIFY `Dinheiro` DECIMAL(19,4) NOT NULL DEFAULT 0;
	ALTER TABLE `Pedidos` ADD `Pessoas` INT NOT NULL DEFAULT 1 AFTER `ValorEntrega`;
	UPDATE `Pedidos` SET `DataEntrada` = `DataSaida` WHERE IsNull(`DataEntrada`) AND NOT IsNull(`DataSaida`);
	UPDATE `Pedidos` SET `DataEntrada` = NOW() WHERE IsNull(`DataEntrada`);
	ALTER TABLE `Pedidos` MODIFY `DataEntrada` DATETIME NOT NULL AFTER `Descricao`;
	ALTER TABLE `Pedidos` ADD `DataEntrega` DATETIME NULL DEFAULT NULL AFTER `DataEntrada`;
	ALTER TABLE `Pedidos` MODIFY `DataSaida` DATETIME NULL DEFAULT NULL AFTER `DataEntrega`;
	UPDATE `Pedidos` SET `DataEntrega` = `DataSaida` WHERE Tipo = 'Entrega' AND Estado <> 'Ativo';
	ALTER TABLE `Pedidos` ADD INDEX `FK_Pedidos_Comandas_ComandaID_idx` (`ComandaID` ASC);
	ALTER TABLE `Pedidos` ADD CONSTRAINT `FK_Pedidos_Comandas_ComandaID`
		FOREIGN KEY (`ComandaID`)
		REFERENCES `Comandas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Pedidos` 
		SET `CaixaID` = (SELECT ID FROM Caixas WHERE Ativo = 'Y' LIMIT 1)
		WHERE ISNULL(CaixaID) AND Estado = 'Finalizado';
	ALTER TABLE `Pedidos` ADD `MovimentacaoID` INT NULL DEFAULT NULL AFTER `ComandaID`;
	ALTER TABLE `Pedidos` ADD INDEX `FK_Pedidos_Movimentacoes_MovimentacaoID_idx` (`MovimentacaoID` ASC);
	ALTER TABLE `Pedidos` ADD CONSTRAINT `FK_Pedidos_Movimentacoes_MovimentacaoID`
		FOREIGN KEY (`MovimentacaoID`)
		REFERENCES `Movimentacoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Pedidos` p
		LEFT JOIN `Movimentacoes` mv ON mv.SessaoID = p.SessaoID AND mv.CaixaID = p.CaixaID
		SET p.`MovimentacaoID` = mv.`ID`
		WHERE NOT ISNULL(p.`CaixaID`);
		
	ALTER TABLE `Produtos` ADD `Conteudo` DOUBLE NOT NULL DEFAULT 1 AFTER `QuantidadeLimite`;
	UPDATE `Produtos` SET `Tipo` = 'Produto', Visivel = 'N' WHERE `Tipo` = 'Insumo';
	ALTER TABLE `Produtos` MODIFY `Tipo` ENUM('Produto', 'Composicao', 'Pacote') NOT NULL DEFAULT 'Produto';
	ALTER TABLE `Produtos` MODIFY `PrecoVenda` DECIMAL(19,4) NOT NULL;
	
	ALTER TABLE `Produtos_Pedidos` DROP `PrecoCompra`;
	ALTER TABLE `Produtos_Pedidos` MODIFY `Preco` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Produtos_Pedidos` MODIFY `PrecoVenda` DECIMAL(19,4) NOT NULL;
	
	ALTER TABLE `Empresas` ADD `ParceiroFantasia` VARCHAR(100) NULL DEFAULT NULL AFTER `GUID`;
	ALTER TABLE `Empresas` ADD `ParceiroNome` VARCHAR(150) NULL DEFAULT NULL AFTER `ParceiroFantasia`;
	ALTER TABLE `Empresas` ADD `ParceiroEmail` VARCHAR(100) NULL DEFAULT NULL AFTER `ParceiroNome`;
	ALTER TABLE `Empresas` ADD `ParceiroFone1` VARCHAR(12) NULL DEFAULT NULL AFTER `ParceiroEmail`;
	ALTER TABLE `Empresas` ADD `ParceiroFone2` VARCHAR(12) NULL DEFAULT NULL AFTER `ParceiroFone1`;
	ALTER TABLE `Empresas` ADD `ParceiroImagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `ParceiroFone2`;
	
	ALTER TABLE `Cheques` MODIFY `Total` DECIMAL(19,4) NOT NULL;
	
	ALTER TABLE `Contas` ADD `PedidoID` INT NULL DEFAULT NULL AFTER `ClienteID`;
	ALTER TABLE `Contas` ADD INDEX `FK_Contas_Pedidos_PedidoID_idx` (`PedidoID` ASC);
	ALTER TABLE `Contas` ADD CONSTRAINT `FK_Contas_Pedidos_PedidoID`
		FOREIGN KEY (`PedidoID`)
		REFERENCES `Pedidos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	UPDATE `Contas` ct 
		LEFT JOIN `Pagtos_Pedidos` pgp ON pgp.ContaID = ct.ID
		SET ct.`PedidoID` = pgp.`PedidoID`
		WHERE NOT IsNull(pgp.ContaID);
	ALTER TABLE `Contas` MODIFY `Valor` DECIMAL(19,4) NULL;
	
	ALTER TABLE `Pagtos_Pedidos` MODIFY `PedidoID` INT NULL DEFAULT NULL;
	ALTER TABLE `Pagtos_Pedidos` ADD `PagtoContaID` INT NULL DEFAULT NULL AFTER `PedidoID`;
	INSERT INTO `Pagtos_Pedidos`
		(SessaoID, CaixaID, FuncionarioID, Forma_PagtoID, PedidoID, PagtoContaID, CartaoID, 
		 ChequeID, ContaID, Total, Dinheiro, QtdParcelas, ValorParcela, DataHora)
		(SELECT SessaoID, CaixaID, FuncionarioID, Forma_PagtoID, NULL as PedidoID, ContaID as PagtoContaID, CartaoID, 
		 ChequeID, NULL as ContaID, Total, Dinheiro, QtdParcelas, ValorParcela, DataHora FROM `Pagtos_Contas`);
	DROP TABLE `Pagtos_Contas`;

	RENAME TABLE `Pagtos_Pedidos` TO `Pagamentos`;
	ALTER TABLE `Pagamentos` ADD `Detalhes` VARCHAR(200) NULL DEFAULT NULL AFTER `ValorParcela`;
	ALTER TABLE `Pagamentos` ADD `MovimentacaoID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Pagamentos` CHANGE `QtdParcelas` `Parcelas` INT NULL;
	UPDATE `Pagamentos` SET `Dinheiro` = 0 WHERE IsNull(`Dinheiro`);
	UPDATE `Pagamentos` SET `Parcelas` = 0 WHERE IsNull(`Parcelas`);
	UPDATE `Pagamentos` SET `ValorParcela` = 0 WHERE IsNull(`ValorParcela`);
	UPDATE `Pagamentos` SET `Parcelas` = 1, `ValorParcela` = `Total` WHERE NOT IsNull(`ContaID`);
	UPDATE `Pagamentos` SET `Detalhes` = IF(Total < 0, 'Retirada de dinheiro', 'Inserção de dinheiro') 
		WHERE NOT IsNull(`PagtoContaID`) AND PagtoContaID = 1 AND ISNULL(Detalhes);
	ALTER TABLE `Pagamentos` MODIFY `Total` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Pagamentos` MODIFY `Dinheiro` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Pagamentos` MODIFY `ValorParcela` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Pagamentos` MODIFY `Parcelas` INT NOT NULL;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Sessoes_SessaoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Funcionarios_FuncionarioID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Formas_Pagto_Forma_PagtoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Pedidos_PedidoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Cartoes_CartaoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Cheques_ChequeID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Caixas_CaixaID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_PagPed_Contas_ContaID`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Sessoes_SessaoID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Funcionarios_FuncionarioID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Formas_Pagto_Forma_PagtoID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Pedidos_PedidoID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Cartoes_CartaoID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Cheques_ChequeID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Caixas_CaixaID_idx`;
	ALTER TABLE `Pagamentos` DROP INDEX `IDX_PagPed_PedidoID_Total`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_PagPed_Contas_ContaID_idx`;
	
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Movimentacoes_MovimentacaoID_idx` (`MovimentacaoID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Sessoes_SessaoID_idx` (`SessaoID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Formas_Pagto_Forma_PagtoID_idx` (`Forma_PagtoID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Pedidos_PedidoID_idx` (`PedidoID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Cartoes_CartaoID_idx` (`CartaoID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Cheques_ChequeID_idx` (`ChequeID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Caixas_CaixaID_idx` (`CaixaID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Contas_ContaID_idx` (`ContaID` ASC);
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Contas_PagtoContaID_idx` (`PagtoContaID` ASC);
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Movimentacoes_MovimentacaoID`
		FOREIGN KEY (`MovimentacaoID`)
		REFERENCES `Movimentacoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Sessoes_SessaoID`
		FOREIGN KEY (`SessaoID`)
		REFERENCES `Sessoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Funcionarios_FuncionarioID`
		FOREIGN KEY (`FuncionarioID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Formas_Pagto_Forma_PagtoID`
		FOREIGN KEY (`Forma_PagtoID`)
		REFERENCES `Formas_Pagto` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Pedidos_PedidoID`
		FOREIGN KEY (`PedidoID`)
		REFERENCES `Pedidos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Cartoes_CartaoID`
		FOREIGN KEY (`CartaoID`)
		REFERENCES `Cartoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Cheques_ChequeID`
		FOREIGN KEY (`ChequeID`)
		REFERENCES `Cheques` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Caixas_CaixaID`
		FOREIGN KEY (`CaixaID`)
		REFERENCES `Caixas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Contas_ContaID`
		FOREIGN KEY (`ContaID`)
		REFERENCES `Contas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Contas_PagtoContaID`
		FOREIGN KEY (`PagtoContaID`)
		REFERENCES `Contas` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Pagamentos` pg
		LEFT JOIN `Movimentacoes` mv ON mv.SessaoID = pg.SessaoID AND mv.CaixaID = pg.CaixaID
		SET pg.`MovimentacaoID` = mv.`ID`;
	ALTER TABLE `Pagamentos` MODIFY `MovimentacaoID` INT NOT NULL;
	
	ALTER TABLE `Folhas_Cheques` MODIFY `Valor` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Folhas_Cheques` ADD `Recolhimento` DATETIME NULL DEFAULT NULL AFTER `Recolhido`;

	ALTER TABLE `Estoque` MODIFY `PrecoCompra` DECIMAL(19,4) NULL;
		
	ALTER TABLE `Pacotes` MODIFY `Valor` DECIMAL(19,4) NOT NULL;		
	
	ALTER TABLE `Promocoes` MODIFY `Valor` DECIMAL(19,4) NOT NULL;
	
	ALTER TABLE `Produtos_Fornecedores` MODIFY `PrecoCompra` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Produtos_Fornecedores` ADD `DataCadastro` DATETIME NULL DEFAULT NULL AFTER `PrecoCompra`;
	UPDATE `Produtos_Fornecedores` SET `DataCadastro` = NOW();
	ALTER TABLE `Produtos_Fornecedores` MODIFY `DataCadastro` DATETIME NOT NULL;

	ALTER TABLE `Servicos` DROP `Periodicidade`;
	ALTER TABLE `Servicos` DROP `Porcentagem`;
	ALTER TABLE `Servicos` MODIFY `Valor` DECIMAL(19,4) NOT NULL DEFAULT 0;
	
	CREATE TABLE IF NOT EXISTS `Taxas_Servicos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`ServicoID` INT NOT NULL,
		`PedidoID` INT NOT NULL,
		`CaixaID` INT NOT NULL,
		`OperadorID` INT NOT NULL,
		`FuncionarioID` INT NOT NULL,
		`Valor` DECIMAL(19,4) NOT NULL,
		`Quantidade` DOUBLE NOT NULL DEFAULT 1,
		`Detalhes` VARCHAR(100) NULL DEFAULT NULL,
		PRIMARY KEY (`ID`),
		INDEX `FK_Taxas_Servicos_Funcionarios_OperadorID_idx` (`OperadorID` ASC),
		INDEX `FK_Taxas_Servicos_Pedidos_PedidoID_idx` (`PedidoID` ASC),
		INDEX `FK_Taxas_Servicos_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC),
		INDEX `FK_Taxas_Servicos_Servicos_ServicoID_idx` (`ServicoID` ASC),
		INDEX `FK_Taxas_Servicos_Caixas_CaixaID_idx` (`CaixaID` ASC),
		CONSTRAINT `FK_Taxas_Servicos_Funcionarios_OperadorID`
			FOREIGN KEY (`OperadorID`)
			REFERENCES `Funcionarios` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Taxas_Servicos_Pedidos_PedidoID`
			FOREIGN KEY (`PedidoID`)
			REFERENCES `Pedidos` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Taxas_Servicos_Funcionarios_FuncionarioID`
			FOREIGN KEY (`FuncionarioID`)
			REFERENCES `Funcionarios` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Taxas_Servicos_Servicos_ServicoID`
			FOREIGN KEY (`ServicoID`)
			REFERENCES `Servicos` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Taxas_Servicos_Caixas_CaixaID`
			FOREIGN KEY (`CaixaID`)
			REFERENCES `Caixas` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
	ENGINE = InnoDB;
	
	CREATE TABLE IF NOT EXISTS `Resumos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`MovimentacaoID` INT NOT NULL,
		`Tipo` ENUM('Dinheiro', 'Cartao', 'Cheque', 'Conta') NOT NULL,
		`CartaoID` INT NULL DEFAULT NULL,
		`Valor` DECIMAL(19,4) NOT NULL,
		PRIMARY KEY (`ID`),
		INDEX `FK_Resumos_Movimentacoes_MovimentacaoID_idx` (`MovimentacaoID` ASC),
		INDEX `FK_Resumos_Cartoes_CartaoID_idx` (`CartaoID` ASC),
		UNIQUE INDEX `UK_Resumos_MovimentacaoID_Tipo_CartaoID` (`MovimentacaoID` ASC, `Tipo` ASC, `CartaoID` ASC),
		CONSTRAINT `FK_Resumos_Movimentacoes_MovimentacaoID`
			FOREIGN KEY (`MovimentacaoID`)
			REFERENCES `Movimentacoes` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Resumos_Cartoes_CartaoID`
			FOREIGN KEY (`CartaoID`)
			REFERENCES `Cartoes` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
	ENGINE = InnoDB;
	
	INSERT INTO `Resumos` (`MovimentacaoID`, `Tipo`, `CartaoID`, `Valor`)
		(SELECT ID as `MovimentacaoID`, 'Dinheiro' as `Tipo`, NULL as `CartaoID`, Final as `Valor`
		 FROM `Movimentacoes`
		 WHERE Aberta = 'N');
	INSERT INTO `Resumos` (`MovimentacaoID`, `Tipo`, `CartaoID`, `Valor`)
		(SELECT mv.ID as `MovimentacaoID`, 'Cartao' as `Tipo`, pg.`CartaoID`, 
		 COALESCE(SUM(pg.`Parcelas` * pg.`ValorParcela`), 0) as `Valor`
		 FROM `Movimentacoes` mv
		 RIGHT JOIN `Pagamentos` pg ON pg.`SessaoID` = mv.`SessaoID` AND pg.`CaixaID` = mv.`CaixaID` AND NOT IsNull(pg.`CartaoID`)
		 WHERE Aberta = 'N'
		 GROUP BY mv.ID, pg.`CartaoID`);
	INSERT INTO `Resumos` (`MovimentacaoID`, `Tipo`, `CartaoID`, `Valor`)
		(SELECT mv.ID as `MovimentacaoID`, 'Cheque' as `Tipo`, NULL as `CartaoID`, 
		 COALESCE(SUM(pg.`Parcelas` * pg.`ValorParcela`), 0) as `Valor`
		 FROM `Movimentacoes` mv
		 RIGHT JOIN `Pagamentos` pg ON pg.`SessaoID` = mv.`SessaoID` AND pg.`CaixaID` = mv.`CaixaID` AND NOT IsNull(pg.`ChequeID`)
		 WHERE Aberta = 'N'
		 GROUP BY mv.ID);
	INSERT INTO `Resumos` (`MovimentacaoID`, `Tipo`, `CartaoID`, `Valor`)
		(SELECT mv.ID as `MovimentacaoID`, 'Conta' as `Tipo`, NULL as `CartaoID`, 
		 COALESCE(SUM(pg.`Parcelas` * pg.`ValorParcela`), 0) as `Valor`
		 FROM `Movimentacoes` mv
		 RIGHT JOIN `Pagamentos` pg ON pg.`SessaoID` = mv.`SessaoID` AND pg.`CaixaID` = mv.`CaixaID` AND NOT IsNull(pg.`ContaID`)
		 WHERE Aberta = 'N'
		 GROUP BY mv.ID);
	ALTER TABLE `Movimentacoes` DROP `Final`;
	
	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(50, "CadastroComandas", "Permitir cadastrar ou alterar um número de comanda"),
		(51, "CadastroServicos", "Permitir cadastrar ou alterar uma taxa ou evento"),
		(52, "RealizarDescontos", "Permitir realizar desconto nas vendas"),
		(53, "Comandas", "Permitir acesso à venda para todas as comandas"),
		(54, "ExcluirPedidoFinalizado", "Permitir excluir um pedido que já foi finalizado");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 50 as PermissaoID FROM Acessos WHERE PermissaoID = 30;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 51 as PermissaoID FROM Acessos WHERE PermissaoID = 46;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 52 as PermissaoID FROM Acessos WHERE PermissaoID = 15;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 53 as PermissaoID FROM Acessos WHERE PermissaoID = 16;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 54 as PermissaoID FROM Acessos WHERE PermissaoID = 7;
		
	INSERT INTO `Comandas` (ID, Nome, Ativa) VALUES 
		(1, "Comanda 1", 'Y'),
		(2, "Comanda 2", 'Y'),
		(3, "Comanda 3", 'Y'),
		(4, "Comanda 4", 'Y'),
		(5, "Comanda 5", 'Y'),
		(6, "Comanda 6", 'Y'),
		(7, "Comanda 7", 'Y'),
		(8, "Comanda 8", 'Y'),
		(9, "Comanda 9", 'Y'),
		(10, "Comanda 10", 'Y');
	
	INSERT INTO `Servicos` (ID, Nome, Descricao, Tipo, Obrigatorio, Valor, Individual, Ativo) VALUES
		(1, "Desconto", "Permite realizar descontos nos pedidos", 'Taxa', 'N', 0, 'N', 'Y'),
		(2, "Entrega", "Permite cobrar taxa de entrega de pedidos", 'Taxa', 'N', 0, 'N', 'Y');
		
	INSERT INTO `Taxas_Servicos` (`ServicoID`, `PedidoID`, `CaixaID`, `OperadorID`, `FuncionarioID`, 
		`Valor`, `Quantidade`, `Detalhes`)
		(SELECT 2 as `ServicoID`, p.ID as `PedidoID`, IF(IsNull(p.`CaixaID`), 
		(SELECT mv.`CaixaID` FROM Movimentacoes mv WHERE mv.SessaoID = p.`SessaoID` LIMIT 1)
		, p.`CaixaID`) as `CaixaID`, p.`FuncionarioID` as `OperadorID`, 
		p.`FuncionarioID`, p.`ValorEntrega` as `Valor`, 1 as `Quantidade`, NULL as `Detalhes`
		FROM Pedidos p WHERE NOT IsNull(p.LocalizacaoID) AND p.Tipo = 'Entrega' AND p.`ValorEntrega` >= 0.01);
		
	ALTER TABLE `Pedidos` DROP `ValorEntrega`;

}
/* adicionado auto incremento na tabela movimentações */
/* aumentado campos de endereço */
Update (Version: "1.3.5.1") {

	ALTER TABLE `Estados` MODIFY `ID` INT NOT NULL AUTO_INCREMENT;
	ALTER TABLE `Movimentacoes` MODIFY `ID` INT NOT NULL AUTO_INCREMENT;
	ALTER TABLE `Empresas` MODIFY `ID` INT NOT NULL AUTO_INCREMENT;
	ALTER TABLE `Empresas` MODIFY `Endereco` VARCHAR(200) NULL DEFAULT NULL;
	ALTER TABLE `Fornecedores` MODIFY `Endereco` VARCHAR(200) NOT NULL;
	ALTER TABLE `Produtos_Fornecedores` MODIFY `ID` INT NOT NULL AUTO_INCREMENT;

	INSERT INTO `Permissoes` (ID, Nome, Descricao) VALUES
		(55, "RelatorioFuncionarios", "Permitir visualizar relatório de funcionários"),
		(56, "RelatorioClientes", "Permitir visualizar relatório de clientes"),
		(57, "RevogarComissao", "Permitir retirar a comissão de um pedido"),
		(58, "SelecionarEntregador", "Permitir selecionar outro entregador na entrega de pedidos para entrega"),
		(59, "TransferirProdutos", "Permitir transferir produtos de uma mesa para outra"),
		(60, "RelatorioPedidos", "Permitir visualizar relatório de pedidos"),
		(61, "AlterarConfiguracoes", "Permitir alterar informações da empresa e configurações do sistema");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 55 as PermissaoID FROM Acessos WHERE PermissaoID = 31;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 56 as PermissaoID FROM Acessos WHERE PermissaoID = 31;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 57 as PermissaoID FROM Acessos WHERE PermissaoID = 52;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 58 as PermissaoID FROM Acessos WHERE PermissaoID = 43;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 59 as PermissaoID FROM Acessos WHERE PermissaoID = 9;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 60 as PermissaoID FROM Acessos WHERE PermissaoID = 21;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 61 as PermissaoID FROM Acessos WHERE PermissaoID = 31;

}

/* cancelamento de pedido sem exclusão */
Update (Version: "1.3.6.0") {

	ALTER TABLE `Pedidos` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Descricao`;
	ALTER TABLE `Produtos` ADD `CustoProducao` DECIMAL(19,4) NULL DEFAULT NULL AFTER `PrecoVenda`;
	ALTER TABLE `Produtos_Pedidos` ADD `PrecoCompra` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `PrecoVenda`;
	ALTER TABLE `Produtos_Pedidos` ADD `Estado` ENUM('Adicionado', 'Enviado', 'Processado', 'Pronto', 'Disponivel', 'Entregue') NOT NULL DEFAULT 'Adicionado' AFTER `Detalhes`;
	ALTER TABLE `Produtos_Pedidos` ADD `Visualizado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Estado`;
	ALTER TABLE `Produtos_Pedidos` ADD `DataVisualizacao` DATETIME NULL DEFAULT NULL AFTER `Visualizado`;
	ALTER TABLE `Produtos_Pedidos` ADD `DataAtualizacao` DATETIME NULL DEFAULT NULL AFTER `DataVisualizacao`;
	ALTER TABLE `Produtos_Pedidos` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `DataAtualizacao`;
	ALTER TABLE `Empresas` DROP `ParceiroFantasia`;
	ALTER TABLE `Empresas` DROP `ParceiroNome`;
	ALTER TABLE `Empresas` DROP `ParceiroEmail`;
	ALTER TABLE `Empresas` DROP `ParceiroFone1`;
	ALTER TABLE `Empresas` DROP `ParceiroFone2`;
	ALTER TABLE `Empresas` DROP `ParceiroImagem`;
	ALTER TABLE `Empresas` MODIFY `Cidade` VARCHAR(50) NULL DEFAULT NULL;
	ALTER TABLE `Empresas` ADD `Computadores` INT NULL DEFAULT NULL AFTER `GUID`;
	ALTER TABLE `Empresas` ADD `ParceiroID` INT NULL DEFAULT NULL AFTER `Computadores`;
	ALTER TABLE `Empresas` ADD `RamoAtividade` INT NULL DEFAULT NULL AFTER `ParceiroID`;
	ALTER TABLE `Empresas` ADD INDEX `FK_Empresas_Fornecedores_ParceiroID_idx` (`ParceiroID` ASC);
	ALTER TABLE `Empresas` ADD CONSTRAINT `FK_Empresas_Fornecedores_ParceiroID`
		FOREIGN KEY (`ParceiroID`)
		REFERENCES `Fornecedores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Fornecedores` ADD `Email` VARCHAR(100) NULL AFTER `IM`;
	ALTER TABLE `Fornecedores` ADD `Contato` VARCHAR(100) NULL DEFAULT NULL AFTER `Fone2`;
	ALTER TABLE `Fornecedores` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `Cidade`;
	ALTER TABLE `Fornecedores` ADD `DataCadastro` DATETIME NULL DEFAULT NULL AFTER `Imagem`;
	UPDATE `Fornecedores` SET `DataCadastro` = NOW();
	ALTER TABLE `Fornecedores` MODIFY `DataCadastro` DATETIME NOT NULL;
	ALTER TABLE `Funcionarios` ADD `CodigoBarras` VARCHAR(13) NULL DEFAULT NULL AFTER `Senha`;
	ALTER TABLE `Funcionarios` ADD UNIQUE INDEX `CodigoBarras_UNIQUE` (`CodigoBarras` ASC);
	ALTER TABLE `Clientes` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL AFTER `Fone2`;
	ALTER TABLE `Pagamentos` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Detalhes`;
	ALTER TABLE `Estoque` ADD `EntradaID` INT NULL DEFAULT NULL AFTER `TransacaoID`;
	ALTER TABLE `Estoque` ADD INDEX `FK_Estoque_Estoque_EntradaID_idx` (`EntradaID` ASC);
	ALTER TABLE `Estoque` ADD CONSTRAINT `FK_Estoque_Estoque_EntradaID`
		FOREIGN KEY (`EntradaID`)
		REFERENCES `Estoque` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Computadores` DROP FOREIGN KEY `FK_Computadores_Setores_SetorID`;
	ALTER TABLE `Computadores` DROP FOREIGN KEY `FK_Computadores_Caixas_CaixaID`;
	ALTER TABLE `Computadores` DROP INDEX `FK_Computadores_Setores_SetorID_idx`;
	ALTER TABLE `Computadores` DROP INDEX `FK_Computadores_Caixas_CaixaID_idx`;
	ALTER TABLE `Impressoras` DROP FOREIGN KEY `FK_Impressoras_Computadores_ComputadorID`;
	ALTER TABLE `Impressoras` DROP INDEX `FK_Impressoras_Computadores_ComputadorID_idx`;
	ALTER TABLE `Impressoras` DROP INDEX `Impresoras_Modo_Computador_Setor_UNIQUE`;
	ALTER TABLE `Impressoras` DROP INDEX `Impressoras_Descricao_Computador_UNIQUE`;
	RENAME TABLE `Computadores` TO `Dispositivos`;
	ALTER TABLE `Dispositivos` ADD `Tipo` ENUM('Computador', 'Tablet') NOT NULL DEFAULT 'Computador' AFTER `Nome`;
	ALTER TABLE `Dispositivos` ADD `Serial` VARCHAR(45) NULL DEFAULT NULL AFTER `Opcoes`;
	ALTER TABLE `Dispositivos` ADD `Validacao` VARCHAR(40) NULL DEFAULT NULL AFTER `Serial`;
	ALTER TABLE `Dispositivos` ADD INDEX `FK_Dispositivos_Setores_SetorID_idx` (`SetorID` ASC);
	ALTER TABLE `Dispositivos` ADD INDEX `FK_Dispositivos_Caixas_CaixaID_idx` (`CaixaID` ASC);
	ALTER TABLE `Dispositivos` ADD UNIQUE INDEX `Serial_UNIQUE` (`Serial` ASC);
	ALTER TABLE `Dispositivos` ADD CONSTRAINT `FK_Dispositivos_Setores_SetorID`
		FOREIGN KEY (`SetorID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Dispositivos` ADD CONSTRAINT `FK_Dispositivos_Caixas_CaixaID`
		FOREIGN KEY (`CaixaID`)
		REFERENCES `Caixas` (`ID`)
		ON DELETE SET NULL
		ON UPDATE CASCADE;
	ALTER TABLE `Impressoras` ADD `Driver` VARCHAR(45) NULL DEFAULT NULL AFTER `Nome`;
	ALTER TABLE `Impressoras` CHANGE `ComputadorID` `DispositivoID` INT NULL;
	ALTER TABLE `Impressoras` ADD INDEX `FK_Impressoras_Dispositivos_DispositivoID_idx` (`DispositivoID` ASC);
	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `Impresoras_Modo_Dispositivo_Setor_UNIQUE` (`Modo` ASC, `DispositivoID` ASC, `SetorID` ASC);
	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `Impressoras_Descricao_Dispositivo_UNIQUE` (`Descricao` ASC, `DispositivoID` ASC);
	ALTER TABLE `Impressoras` ADD CONSTRAINT `FK_Impressoras_Dispositivos_DispositivoID`
		FOREIGN KEY (`DispositivoID`)
		REFERENCES `Dispositivos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	UPDATE `Impressoras` SET Driver =
		(CASE 
				WHEN LOCATE('Elgin', Nome) > 0 THEN 'Elgin'
				WHEN LOCATE('Bematech', Nome) > 0 THEN 'Bematech'
				WHEN LOCATE('Epson', Nome) > 0 THEN 'Epson'
				WHEN LOCATE('Daruma', Nome) > 0 THEN 'Daruma'
				WHEN LOCATE('Diebold', Nome) > 0 THEN 'Diebold'
				WHEN LOCATE('Sweda', Nome) > 0 THEN 'Sweda'
				WHEN LOCATE('Dataregis', Nome) > 0 THEN 'Dataregis'
				WHEN LOCATE('Thermal', Nome) > 0 THEN 'Thermal'
				ELSE NULL
		END);
		
}

/* Forma de pagamento para pedidos para entrega */
/* Grupos de acessos, endereço de apartamento */
/* Formação de pacotes, agendamento de pedidos */
Update (Version: "1.3.7.0") {
		
	UPDATE `Localizacoes` SET `CEP` = IF(REPLACE(REPLACE(`CEP`, '-', ''), ' ', '') = '', NULL, 
		REPLACE(REPLACE(`CEP`, '-', ''), ' ', ''));
	ALTER TABLE `Localizacoes` MODIFY `CEP` VARCHAR(8) NULL DEFAULT NULL;
	ALTER TABLE `Localizacoes` ADD `Tipo` ENUM('Casa', 'Apartamento') NOT NULL DEFAULT 'Casa' AFTER `Numero`;
	ALTER TABLE `Localizacoes` MODIFY `Complemento` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Localizacoes` ADD `Condominio` VARCHAR(64) NULL DEFAULT NULL AFTER `Complemento`;
	ALTER TABLE `Localizacoes` ADD `Bloco` VARCHAR(20) NULL DEFAULT NULL AFTER `Condominio`;
	ALTER TABLE `Localizacoes` ADD `Apartamento` VARCHAR(20) NULL DEFAULT NULL AFTER `Bloco`;
	ALTER TABLE `Localizacoes` MODIFY `Referencia` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Localizacoes` MODIFY `Apelido` VARCHAR(45) NULL DEFAULT NULL;
	ALTER TABLE `Pedidos` MODIFY `Estado` ENUM('Finalizado', 'Ativo', 'Agendado', 'Entrega', 'Fechado') NOT NULL DEFAULT 'Ativo';
	ALTER TABLE `Pedidos` ADD `FormaPagtoID` INT NULL DEFAULT NULL AFTER `Estado`;
	UPDATE `Pedidos` SET `FormaPagtoID` = 1 WHERE Tipo = 'Entrega';
	ALTER TABLE `Pedidos` ADD `CartaoID` INT NULL DEFAULT NULL AFTER `FormaPagtoID`;
	ALTER TABLE `Pedidos` CHANGE `DataEntrada` `DataCriacao` DATETIME NOT NULL;
	ALTER TABLE `Pedidos` ADD `DataAgendamento` DATETIME NULL DEFAULT NULL AFTER `DataCriacao`;
	ALTER TABLE `Pedidos` CHANGE `DataSaida` `DataConclusao` DATETIME NULL DEFAULT NULL;
	ALTER TABLE `Pedidos` ADD INDEX `FK_Pedidos_Formas_Pagto_FormaPagtoID_idx` (`FormaPagtoID` ASC);
	ALTER TABLE `Pedidos` ADD INDEX `FK_Pedidos_Cartoes_CartaoID_idx` (`CartaoID` ASC);
	ALTER TABLE `Pedidos` ADD CONSTRAINT `FK_Pedidos_Formas_Pagto_FormaPagtoID`
		FOREIGN KEY (`FormaPagtoID`)
		REFERENCES `Formas_Pagto` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pedidos` ADD CONSTRAINT `FK_Pedidos_Cartoes_CartaoID`
		FOREIGN KEY (`CartaoID`)
		REFERENCES `Cartoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Produtos_Pedidos` DROP FOREIGN KEY `FK_ProdPed_ProdPed_ProdutoPedidoID`;
	ALTER TABLE `Produtos_Pedidos` DROP INDEX `FK_ProdPed_ProdPed_ProdutoPedidoID_idx`;
	ALTER TABLE `Produtos_Pedidos` DROP `ProdutoPedidoID`;
	ALTER TABLE `Produtos_Pedidos` ADD `Descricao` VARCHAR(200) NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_Pagamentos_Formas_Pagto_Forma_PagtoID`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_Pagamentos_Formas_Pagto_Forma_PagtoID_idx`;
	ALTER TABLE `Pagamentos` CHANGE `Forma_PagtoID` `FormaPagtoID` INT NOT NULL; 
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Formas_Pagto_FormaPagtoID_idx` (`FormaPagtoID` ASC);
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Formas_Pagto_FormaPagtoID`
		FOREIGN KEY (`FormaPagtoID`)
		REFERENCES `Formas_Pagto` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Auditoria` ADD `AutorizadorID` INT NULL DEFAULT NULL AFTER `FuncionarioID`;
	UPDATE `Auditoria` SET `AutorizadorID` = `FuncionarioID`;
	ALTER TABLE `Auditoria` MODIFY `AutorizadorID` INT NOT NULL;
	ALTER TABLE `Auditoria` ADD INDEX `FK_Auditoria_Funcionarios_AutorizadorID_idx` (`AutorizadorID` ASC);
	ALTER TABLE `Auditoria` ADD CONSTRAINT `FK_Auditoria_Funcionarios_AutorizadorID`
		FOREIGN KEY (`AutorizadorID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Composicoes` ADD `Selecionavel` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Quantidade`;
	ALTER TABLE `Composicoes` ADD `Valor` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `Selecionavel`;
	ALTER TABLE `Grupos` DROP `Opcional`;
	ALTER TABLE `Grupos` ADD `QuantidadeMinima` INT NOT NULL DEFAULT 1;
	ALTER TABLE `Grupos` ADD `QuantidadeMaxima` INT NOT NULL DEFAULT 0;
	ALTER TABLE `Grupos` ADD `Funcao` ENUM('Minimo', 'Media', 'Maximo', 'Soma') NOT NULL DEFAULT 'Soma';
	CREATE TABLE IF NOT EXISTS `Propriedades` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Descricao` VARCHAR(100) NOT NULL,
		`Abreviacao` VARCHAR(64) NULL DEFAULT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Descricao_UNIQUE` (`Descricao` ASC))
	ENGINE = InnoDB;
	ALTER TABLE `Pacotes` DROP INDEX `UK_Pacotes_PacID_ProdID`;
	INSERT INTO Grupos VALUES (1, "Grupo Padrão", 'Y', 'Inteiro', 1, 0, 'Soma');
	UPDATE `Pacotes` SET `GrupoID` = 1;
	ALTER TABLE `Pacotes` MODIFY `GrupoID` INT NOT NULL AFTER `PacoteID`;
	ALTER TABLE `Pacotes` MODIFY `ProdutoID` INT NULL DEFAULT NULL;
	ALTER TABLE `Pacotes` ADD `PropriedadeID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Pacotes` ADD `AssociacaoID` INT NULL DEFAULT NULL AFTER `PropriedadeID`;
	ALTER TABLE `Pacotes` ADD `Abreviacao` VARCHAR(100) NULL DEFAULT NULL AFTER `AssociacaoID`;
	ALTER TABLE `Pacotes` DROP `PrecoFixo`;
	ALTER TABLE `Pacotes` ADD `Selecionado` ENUM('Y', 'N') NOT NULL DEFAULT 'N';
	ALTER TABLE `Pacotes` ADD UNIQUE INDEX `UK_Pacotes_PacID_ProdID_PropID_AssocID` (`PacoteID` ASC, `ProdutoID` ASC, `PropriedadeID` ASC, `AssociacaoID` ASC);
	ALTER TABLE `Pacotes` ADD INDEX `FK_Pacotes_Propriedades_PropriedadeID_idx` (`PropriedadeID` ASC);
	ALTER TABLE `Pacotes` ADD INDEX `FK_Pacotes_Propriedades_AssociacaoID_idx` (`AssociacaoID` ASC);
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Propriedades_PropriedadeID`
		FOREIGN KEY (`PropriedadeID`)
		REFERENCES `Propriedades` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Propriedades_AssociacaoID`
		FOREIGN KEY (`AssociacaoID`)
		REFERENCES `Propriedades` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	CREATE TABLE IF NOT EXISTS `Funcionalidades` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Nome` VARCHAR(64) NOT NULL,
		`Descricao` VARCHAR(100) NOT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC))
	ENGINE = InnoDB;
	INSERT INTO `Funcionalidades` (ID, Nome, Descricao) VALUES
		(1, "Operacional", "Controla operações nas telas do sistema"),
		(2, "Vendas", "Controla cancelamentos e outros operações de vendas"),
		(3, "Mesas e Comandas", "Controla operações sobre mesas e comandas"),
		(4, "Financeiro", "Controla caixas e pagamentos de pedidos"),
		(5, "Estoque", "Controla acessos ao gerenciamento do estoque"),
		(6, "Cadastros", "Permite acessos às telas de cadastros"),
		(7, "Relatórios", "Controla a visualização ou emissão de relatórios"),
		(8, "Sistema", "Controla dispositivos e comportamento do sistema");
	ALTER TABLE `Permissoes` ADD `FuncionalidadeID` INT NULL DEFAULT NULL AFTER `ID`;
	UPDATE `Permissoes` SET FuncionalidadeID = 
		(CASE 
			WHEN ID = 1 THEN 1
			WHEN ID = 2 THEN 8
			WHEN ID = 3 THEN 8
			WHEN ID = 4 THEN 2
			WHEN ID = 5 THEN 2
			WHEN ID = 6 THEN 3
			WHEN ID = 7 THEN 2
			WHEN ID = 8 THEN 3
			WHEN ID = 9 THEN 3
			WHEN ID = 10 THEN 4
			WHEN ID = 11 THEN 4
			WHEN ID = 12 THEN 4
			WHEN ID = 13 THEN 4
			WHEN ID = 14 THEN 4
			WHEN ID = 15 THEN 3
			WHEN ID = 16 THEN 3
			WHEN ID = 17 THEN 5
			WHEN ID = 18 THEN 8
			WHEN ID = 19 THEN 8
			WHEN ID = 20 THEN 8
			WHEN ID = 21 THEN 7
			WHEN ID = 22 THEN 7
			WHEN ID = 23 THEN 7
			WHEN ID = 24 THEN 7
			WHEN ID = 25 THEN 7
			WHEN ID = 26 THEN 7
			WHEN ID = 27 THEN 7
			WHEN ID = 28 THEN 6
			WHEN ID = 29 THEN 6
			WHEN ID = 30 THEN 6
			WHEN ID = 31 THEN 6
			WHEN ID = 32 THEN 6
			WHEN ID = 33 THEN 6
			WHEN ID = 34 THEN 6
			WHEN ID = 35 THEN 6
			WHEN ID = 36 THEN 6
			WHEN ID = 37 THEN 6
			WHEN ID = 38 THEN 6
			WHEN ID = 39 THEN 6
			WHEN ID = 40 THEN 1
			WHEN ID = 41 THEN 6
			WHEN ID = 42 THEN 1
			WHEN ID = 43 THEN 1
			WHEN ID = 44 THEN 1
			WHEN ID = 45 THEN 6
			WHEN ID = 46 THEN 6
			WHEN ID = 47 THEN 7
			WHEN ID = 48 THEN 7
			WHEN ID = 49 THEN 7
			WHEN ID = 50 THEN 6
			WHEN ID = 51 THEN 6
			WHEN ID = 52 THEN 2
			WHEN ID = 53 THEN 3
			WHEN ID = 54 THEN 4
			WHEN ID = 55 THEN 7
			WHEN ID = 56 THEN 7
			WHEN ID = 57 THEN 4
			WHEN ID = 58 THEN 1
			WHEN ID = 59 THEN 3
			WHEN ID = 60 THEN 7
			WHEN ID = 61 THEN 8
			ELSE 1
		END);
	ALTER TABLE `Permissoes` MODIFY `FuncionalidadeID` INT NOT NULL;
	ALTER TABLE `Permissoes` ADD INDEX `FK_Permissoes_Funcionalidades_FuncionalidadeID_idx` (`FuncionalidadeID` ASC);
	ALTER TABLE `Permissoes` ADD CONSTRAINT `FK_Permissoes_Funcionalidades_FuncionalidadeID`
		FOREIGN KEY (`FuncionalidadeID`)
		REFERENCES `Funcionalidades` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	CREATE TABLE IF NOT EXISTS `Formacoes` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`ProdutoPedidoID` INT NOT NULL,
		`PacoteID` INT NOT NULL,
		PRIMARY KEY (`ID`),
		INDEX `FK_Formacoes_ProdPed_ProdutoPedidoID_idx` (`ProdutoPedidoID` ASC),
		INDEX `FK_Formacoes_Pacotes_PacoteID_idx` (`PacoteID` ASC),
		UNIQUE INDEX `UK_Formacoes_ProdutoPedidoID_PacoteID` (`ProdutoPedidoID` ASC, `PacoteID` ASC),
		CONSTRAINT `FK_Formacoes_ProdPed_ProdutoPedidoID`
			FOREIGN KEY (`ProdutoPedidoID`)
			REFERENCES `Produtos_Pedidos` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Formacoes_Pacotes_PacoteID`
			FOREIGN KEY (`PacoteID`)
			REFERENCES `Pacotes` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
	ENGINE = InnoDB;
		
}

/* Adição de triggers */
Update (Version: "1.3.7.1") {

	UPDATE Pedidos SET Pessoas = 1 WHERE Pessoas = 0;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Sessoes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Sessoes_BEFORE_INSERT` BEFORE INSERT ON `Sessoes` FOR EACH ROW
	BEGIN
		DECLARE _existe INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT 1 INTO _existe FROM Sessoes WHERE Aberta = 'Y';
		IF _existe = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma sessão aberta";
		ELSEIF NEW.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A sessão não pode iniciar fechada";
		ELSEIF NOT ISNULL(NEW.DataTermino) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de término da sessão não deve ser informada agora";
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Sessoes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Sessoes_BEFORE_UPDATE` BEFORE UPDATE ON `Sessoes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _abertos INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT COUNT(ID) INTO _abertos FROM Movimentacoes WHERE SessaoID = OLD.ID AND Aberta = 'Y';
		IF NEW.Aberta = 'N' AND _abertos = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um caixa aberto";
		ELSEIF NEW.Aberta = 'N' AND _abertos > 1 THEN
			SET _error_msg = CONCAT("Ainda há ", _abertos, " caixas abertos");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'Y' AND OLD.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A sessão não pode ser reaberta";
		ELSEIF OLD.DataInicio <> NEW.DataInicio THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de início da sessão não pode ser alterada";
		ELSEIF OLD.Aberta = 'N' AND OLD.DataTermino <> NEW.DataTermino THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de término da sessão não pode ser alterada";
		ELSEIF NEW.Aberta = 'Y' AND NOT ISNULL(NEW.DataTermino) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de término da sessão não deve ser informada agora";
		ELSEIF NEW.Aberta = 'N' AND ISNULL(NEW.DataTermino) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de término da sessão deve ser informada";
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Caixas_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Caixas_BEFORE_UPDATE` BEFORE UPDATE ON `Caixas` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _mov_count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		IF NEW.Ativo = 'N' AND OLD.Ativo = 'Y' THEN
			SELECT COUNT(ID) INTO _mov_count FROM Movimentacoes WHERE CaixaID = OLD.ID AND Aberta = 'Y';
			IF _mov_count > 0 THEN
				SET _error_msg = CONCAT("O caixa '", OLD.Descricao, "' está aberto");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Movimentacoes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Movimentacoes_BEFORE_INSERT` BEFORE INSERT ON `Movimentacoes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _existe INT DEFAULT 0;
		DECLARE _aberta, _ativo, _f_ativo VARCHAR(1);
		DECLARE _descricao, _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT 1 INTO _existe FROM Movimentacoes WHERE SessaoID = NEW.SessaoID AND CaixaID = NEW.CaixaID AND Aberta = 'Y' AND 
			FuncionarioAberturaID = NEW.FuncionarioAberturaID;
		SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
		SELECT Ativo, Descricao INTO _ativo, _descricao FROM Caixas WHERE ID = NEW.CaixaID;
		SELECT Ativo, Login INTO _f_ativo, _login FROM Funcionarios WHERE ID = NEW.FuncionarioAberturaID;
		IF _existe = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe um caixa aberto para o funcionário informado";
		ELSEIF _aberta = 'N' THEN
			SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _descricao, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _f_ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser aberto com status de fechado";
		ELSEIF NOT ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário de fechamento do caixa não pode ser informado agora";
		ELSEIF NOT ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento do caixa não pode ser informada agora";
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Movimentacoes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Movimentacoes_BEFORE_UPDATE` BEFORE UPDATE ON `Movimentacoes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _abertos INT;
		DECLARE _outros_abertos INT;
		DECLARE _outros_caixas INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT COUNT(ID) INTO _abertos FROM Pedidos WHERE MovimentacaoID = OLD.ID AND Estado <> 'Finalizado' AND Cancelado = 'N';
		SELECT COUNT(ID) INTO _outros_abertos FROM Pedidos WHERE SessaoID = OLD.SessaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
		SELECT COUNT(ID) INTO _outros_caixas FROM Movimentacoes WHERE ID <> OLD.ID AND Aberta = 'Y';
		
		IF NEW.Aberta = 'N' AND _outros_abertos = 1 AND _outros_caixas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pedido não finalizado";
		ELSEIF NEW.Aberta = 'N' AND _outros_abertos > 1 AND _outros_caixas = 0 THEN
			SET _error_msg = CONCAT("Ainda há ", _outros_abertos, " pedidos não finalizados");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'N' AND _abertos = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pedido não finalizado";
		ELSEIF NEW.Aberta = 'N' AND _abertos > 1 THEN
			SET _error_msg = CONCAT("Ainda há ", _abertos, " pedidos não finalizados");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'Y' AND OLD.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser reaberto";
		ELSEIF NEW.Aberta = 'N' AND ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que está fechando o caixa deve ser informado";
		ELSEIF NEW.Aberta = 'Y' AND NOT ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que fechará o caixa não pode ser informado agora";
		ELSEIF NEW.Aberta = 'N' AND ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento deve ser informada";
		ELSEIF NEW.Aberta = 'Y' AND NOT ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento não pode ser informada agora";
		ELSEIF NEW.CaixaID <> OLD.CaixaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não pode ser alterado";
		ELSEIF NEW.SessaoID <> OLD.SessaoID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A sessão do caixa não pode ser alterada";
		ELSEIF NEW.FuncionarioAberturaID <> OLD.FuncionarioAberturaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que abriu o caixa não pode ser alterado";
		ELSEIF NEW.DataAbertura <> OLD.DataAbertura THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de abertura do caixa não pode ser alterada";
		ELSEIF OLD.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Este caixa já foi fechado e não pode mais ser alterado";
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
			ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSE
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		
		IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível cadastrar um pedido como cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
		ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
		ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
		ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
		ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
		ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
		ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
		ELSEIF NEW.Estado NOT IN ('Ativo', 'Agendado') THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não está ativo ou não está agendado";
		ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
		ELSEIF NOT ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser entregue ao mesmo instante que é criado";
		ELSEIF NOT ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode estar concluído ao ser criado";
		ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
		ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
		ELSEIF NEW.Tipo = 'Mesa' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
				SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Comanda' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
				SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Avulso' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
			END IF;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
			ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		
		IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
		ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
		ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
		ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
		ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
		ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
		ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
		ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
		ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
		ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
		ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
		ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
		ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
		ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
		ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
				SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
				SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
			END IF;
		END IF;
		IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo VARCHAR(1);
		DECLARE _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
		SELECT Ativo, Login INTO _ativo, _login FROM Funcionarios WHERE ID = NEW.FuncionarioID;
		
		IF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
		ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
		ELSEIF NEW.Preco < 0 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Preco < 0.01 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser nulo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto como cancelado";
		ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto em um pedido cancelado";
		ELSEIF _estado = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto em um pedido finalizado";
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Estado <> 'Adicionado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto deve ser inserido com estado de 'Adicionado'";
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado = 'N' AND NOT ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto não visualizado com a data e a hora";
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo VARCHAR(1);
		DECLARE _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
		SELECT Ativo, Login INTO _ativo, _login FROM Funcionarios WHERE ID = NEW.FuncionarioID;
		
		IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto cancelado";
		ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido cancelado";
		ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido finalizado";
		ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
		ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
		ELSEIF NEW.Preco < 0 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Preco < 0.01 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser nulo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
		IF NEW.Cancelado = 'Y' THEN
			DELETE FROM Estoque WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_INSERT` BEFORE INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome, _estado VARCHAR(75);
		DECLARE _aberta, _cancelado VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _forma_count INT DEFAULT 0;
		DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		SET _forma_count = IF(ISNULL(NEW.CartaoID), 0, 1) + IF(ISNULL(NEW.ChequeID), 0, 1) + IF(ISNULL(NEW.ContaID), 0, 1);
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento cancelado";
		ELSEIF _forma_count > 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Várias formas de pagamento em uma única transação";
		ELSEIF _forma_count = 0 AND (NEW.Parcelas > 0 OR NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento em uma forma de pagamento não parcelada";
		ELSEIF NEW.Total > -0.005 AND NEW.Total < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor total não informado";
		ELSEIF NEW.Parcelas < 0 OR (NEW.Parcelas = 0 AND (NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento incorreto";
		ELSEIF NEW.Parcelas = 0 AND NEW.Dinheiro > -0.005 AND NEW.Dinheiro < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro não informado";
		ELSEIF _forma_count = 1 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento não informado";
		ELSEIF NEW.Total + 0.005 < NEW.Dinheiro + NEW.Parcelas * NEW.ValorParcela THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor totalizado menor que o total";
		ELSEIF NEW.Total - 0.005 > NEW.Dinheiro AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro maior que o total";
		ELSEIF NOT ISNULL(NEW.PedidoID) AND NOT ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Vários destinos para o pagamento";
		ELSEIF ISNULL(NEW.PedidoID) AND ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum destino para o pagamento";
		ELSEIF NOT ISNULL(NEW.PedidoID) THEN
			SELECT MovimentacaoID, Estado, Cancelado INTO _movimentacao_id, _estado, _cancelado FROM Pedidos
				WHERE ID = NEW.PedidoID;
			IF _cancelado = 'Y' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido cancelado";
			ELSEIF _estado = 'Finalizado' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido finalizado";
			ELSEIF NOT ISNULL(_movimentacao_id) AND _movimentacao_id <> NEW.MovimentacaoID THEN
				SET _error_msg = CONCAT("O pedido já está associado à movimentacão ", _movimentacao_id);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(_movimentacao_id) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NEW.Dinheiro < 0 THEN
			SELECT SUM(Dinheiro) INTO _dinheiro FROM Pagamentos
				WHERE MovimentacaoID = NEW.MovimentacaoID;
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
			END IF;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_UPDATE` BEFORE UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
		DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pagamento que já foi cancelado";
		ELSEIF NEW.Total <> OLD.Total THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor total não pode ser alterado";
		ELSEIF NEW.Parcelas <> OLD.Parcelas OR NEW.ValorParcela <> OLD.ValorParcela THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O parcelamento não pode ser alterado";
		ELSEIF NEW.Dinheiro <> OLD.Dinheiro THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor em dinheiro não pode ser alterado";
		ELSEIF NEW.FormaPagtoID <> OLD.FormaPagtoID OR NOT (NEW.CartaoID <=> OLD.CartaoID) OR NOT (NEW.ChequeID <=> OLD.ChequeID) OR
				 NOT (NEW.ContaID <=> OLD.ContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A forma de pagamento não pode ser alterada";
		ELSEIF NEW.DataHora <> OLD.DataHora THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data do pagamento não pode ser alterada";
		ELSEIF NOT (NEW.PedidoID <=> OLD.PedidoID) OR NOT (NEW.PagtoContaID <=> OLD.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O destino do pagamento não pode ser alterado";
		ELSEIF NEW.MovimentacaoID <> OLD.MovimentacaoID THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Estoque_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_BEFORE_INSERT` BEFORE INSERT ON `Estoque` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao VARCHAR(75);
		DECLARE _tipo VARCHAR(20);
		DECLARE _divisivel VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Tipo, Divisivel
		INTO _descricao, _tipo, _divisivel
		FROM Produtos
		WHERE ID = NEW.ProdutoID;
		IF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
		ELSEIF NEW.Quantidade > 0 AND NOT ISNULL(NEW.TransacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A venda não pode adicionar o produto ao estoque";
		ELSEIF NEW.Quantidade < 0 AND ISNULL(NEW.EntradaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Retirada do estoque sem informação de entrada";
		ELSEIF _tipo = 'Composicao' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é uma composição e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _tipo = 'Pacote' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é um pacote e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Estoque_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_AFTER_INSERT` AFTER INSERT ON `Estoque` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao VARCHAR(75);
		DECLARE _quantidade DOUBLE;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT SUM(Quantidade)
		INTO _quantidade
		FROM Estoque
		WHERE SetorID = NEW.SetorID AND ProdutoID = NEW.ProdutoID;
		
		IF _quantidade <= -0.0005 AND NEW.Quantidade < 0 THEN
			SELECT Descricao
			INTO _descricao
			FROM Produtos
			WHERE ID = NEW.ProdutoID;
			SET _error_msg = CONCAT("Não há estoque para o produto '", _descricao, "'");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
		IF NOT ISNULL(NEW.TransacaoID) then
			UPDATE Produtos_Pedidos SET PrecoCompra = PrecoCompra + (-NEW.Quantidade * NEW.PrecoCompra) / Quantidade
				WHERE ID = NEW.TransacaoID;
		END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Corrigido verificação do total do pagamento */
/* Adicionado verificação de fechamento de pedido vazio */
Update (Version: "1.3.7.2") {

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
			ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		
		IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
		ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
		ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
		ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
		ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
		ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
		ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
		ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
		ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
		ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
		ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
		ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
		ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
		ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
		ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
		ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
				SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
				SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
			IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
			END IF;
		END IF;
		IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto foi adicionado ao pedido";
			END IF;
		END IF;
		IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_INSERT` BEFORE INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome, _estado VARCHAR(75);
		DECLARE _aberta, _cancelado VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _forma_count INT DEFAULT 0;
		DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		SET _forma_count = IF(ISNULL(NEW.CartaoID), 0, 1) + IF(ISNULL(NEW.ChequeID), 0, 1) + IF(ISNULL(NEW.ContaID), 0, 1);
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento cancelado";
		ELSEIF _forma_count > 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Várias formas de pagamento em uma única transação";
		ELSEIF _forma_count = 0 AND (NEW.Parcelas > 0 OR NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento em uma forma de pagamento não parcelada";
		ELSEIF NEW.Total > -0.005 AND NEW.Total < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor total não informado";
		ELSEIF NEW.Parcelas < 0 OR (NEW.Parcelas = 0 AND (NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento incorreto";
		ELSEIF NEW.Parcelas = 0 AND NEW.Dinheiro > -0.005 AND NEW.Dinheiro < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro não informado";
		ELSEIF _forma_count = 1 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento não informado";
		ELSEIF NEW.Dinheiro + NEW.Parcelas * NEW.ValorParcela < NEW.Total - 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor totalizado menor que o total";
		ELSEIF NEW.Dinheiro > NEW.Total + 0.005 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro maior que o total";
		ELSEIF NOT ISNULL(NEW.PedidoID) AND NOT ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Vários destinos para o pagamento";
		ELSEIF ISNULL(NEW.PedidoID) AND ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum destino para o pagamento";
		ELSEIF NOT ISNULL(NEW.PedidoID) THEN
			SELECT MovimentacaoID, Estado, Cancelado INTO _movimentacao_id, _estado, _cancelado FROM Pedidos
				WHERE ID = NEW.PedidoID;
			IF _cancelado = 'Y' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido cancelado";
			ELSEIF _estado = 'Finalizado' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido finalizado";
			ELSEIF NOT ISNULL(_movimentacao_id) AND _movimentacao_id <> NEW.MovimentacaoID THEN
				SET _error_msg = CONCAT("O pedido já está associado à movimentacão ", _movimentacao_id);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(_movimentacao_id) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado, _nome VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo, _aberta VARCHAR(1);
		DECLARE _login VARCHAR(50);
		DECLARE _movimentacao_id INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT MovimentacaoID, Cancelado, Estado INTO _movimentacao_id, _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
		SELECT Ativo, Login INTO _ativo, _login FROM Funcionarios WHERE ID = NEW.FuncionarioID;
		
		IF NOT ISNULL(_movimentacao_id) THEN
			SELECT mv.Aberta, c.Descricao INTO _aberta, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = _movimentacao_id;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", _movimentacao_id, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
		
		IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto cancelado";
		ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido cancelado";
		ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido finalizado";
		ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
		ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
		ELSEIF NEW.Preco < 0 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Preco < 0.01 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser nulo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
		IF NEW.Cancelado = 'Y' THEN
			DELETE FROM Estoque WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Funcionarios_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Funcionarios_BEFORE_INSERT` BEFORE INSERT ON `Funcionarios` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
		IF NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário não pode ser cadastrado como inativo";
		ELSEIF NOT ISNULL(NEW.Saida) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de saída do funcionário não pode ser informada agora";
		END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Criação de módulos */
/* Criação de consumação mínima */
/* Criado abstração de clientes, empresas e fornecedores */
Update (Version: "1.3.8.0") {

	ALTER DATABASE `GrandChef` CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Mesas` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Sessoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Caixas` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Formas_Pagto` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Cartoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Pedidos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Produtos_Pedidos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Produtos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Funcionarios` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Pagamentos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Auditoria` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Contas` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Folhas_Cheques` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Bancos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Cheques` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Funcoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Categorias` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Composicoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Unidades` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Estoque` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Pacotes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Movimentacoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Dispositivos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Impressoras` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Setores` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Promocoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Acessos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Permissoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Fornecedores` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Produtos_Fornecedores` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Clientes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Localizacoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Sistema` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Grupos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Informacoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Bairros` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Estados` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Servicos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Taxas_Servicos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Comandas` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Resumos` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Funcionalidades` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Formacoes` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Propriedades` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
	ALTER TABLE `Empresas` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

	ALTER TABLE `Cartoes` CHANGE `Taxa` `Transacao` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `ImageIndex`;
	ALTER TABLE `Cartoes` ADD `Mensalidade` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `ImageIndex`;
	ALTER TABLE `Cartoes` ADD `Taxa` DOUBLE NOT NULL DEFAULT 0 AFTER `Transacao`;
	ALTER TABLE `Cartoes` ADD `DiasRepasse` INT UNSIGNED NOT NULL DEFAULT 30 AFTER `Taxa`;

	ALTER TABLE `Clientes` DROP INDEX `IDX_Clientes_Nome`;
	ALTER TABLE `Clientes` ADD INDEX `Nome_INDEX` (`Nome` ASC);
	ALTER TABLE `Clientes` ADD `Tipo` ENUM('Fisica', 'Juridica') NOT NULL DEFAULT 'Fisica' AFTER `ID`;
	ALTER TABLE `Clientes` ADD `AcionistaID` INT NULL DEFAULT NULL AFTER `Tipo`;
	ALTER TABLE `Clientes` MODIFY `Nome` VARCHAR(100) NOT NULL;
	ALTER TABLE `Clientes` MODIFY `Sobrenome` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `Genero` ENUM('Masculino', 'Feminino') NULL DEFAULT 'Masculino';
	ALTER TABLE `Clientes` MODIFY `CPF` VARCHAR(20) NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `RG` VARCHAR(20) NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `Fone1` VARCHAR(12) NULL;
	ALTER TABLE `Clientes` ADD `IM` VARCHAR(20) NULL DEFAULT NULL AFTER `RG`;
	ALTER TABLE `Clientes` ADD `Slogan` VARCHAR(100) NULL DEFAULT NULL AFTER `Fone2`;

	CREATE TABLE IF NOT EXISTS `Modulos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Nome` VARCHAR(50) NOT NULL,
		`Descricao` VARCHAR(200) NOT NULL,
		`ImageIndex` INT NOT NULL,
		`Habilitado` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC))
	ENGINE = InnoDB;

	INSERT INTO `Modulos` (ID, Nome, Descricao, ImageIndex, Habilitado) VALUES
		(1, "Mesas", "Permite realizar vendas para mesas", 4, 'Y'),
		(2, "Venda rápida", "Permite realizar vendas rápidas com código de barras", 5, 'Y'),
		(3, "Cartão de consumo", "Permite realizar vendas com cartão de consumo", 6, 'Y'),
		(4, "Delivery", "Permite realizar vendas para entrega", 7, 'Y'),
		(5, "Estoque", "Permite controlar estoque com cadastro de fornecedores", 8, 'Y'),
		(6, "Controle de contas", "Permite cadastrar contas a pagar e a receber", 9, 'Y'),
		(7, "Comanda eletrônica", "Permite realizar pedidos pelo celular ou tablet", 10, 'Y');

	CREATE TABLE IF NOT EXISTS `Classificacoes` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Descricao` VARCHAR(100) NOT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `Descricao_UNIQUE` (`Descricao` ASC))
	ENGINE = InnoDB;

	INSERT INTO `Classificacoes` (ID, Descricao) VALUES
		(1, "Movimentações do caixa"),
		(2, "Pagamento de contas");

	CREATE TABLE IF NOT EXISTS `Listas_Compras` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Descricao` VARCHAR(50) NOT NULL,
		`Estado` ENUM('Analise', 'Fechada', 'Comprada') NOT NULL DEFAULT 'Analise',
		`CompradorID` INT NULL DEFAULT NULL,
		`DataCompra` DATETIME NOT NULL,
		`DataCadastro` DATETIME NOT NULL,
		PRIMARY KEY (`ID`))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Listas_Produtos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`ListaCompraID` INT NOT NULL,
		`ProdutoID` INT NOT NULL,
		`Quantidade` DOUBLE NULL,
		`PrecoMaximo` DECIMAL(19,4) NOT NULL,
		`Preco` DECIMAL(19,4) NULL DEFAULT NULL,
		`FornecedorID` INT NULL DEFAULT NULL,
		`Observacoes` VARCHAR(100) NULL DEFAULT NULL,
		PRIMARY KEY (`ID`),
		INDEX `FK_ListaProd_ListaComp_ListaCompraID_idx` (`ListaCompraID` ASC),
		INDEX `FK_ListaProd_Produtos_ProdutoID_idx` (`ProdutoID` ASC),
		INDEX `FK_ListaProd_Fornecedores_FornecedorID_idx` (`FornecedorID` ASC),
		CONSTRAINT `FK_ListaProd_ListaComp_ListaCompraID`
			FOREIGN KEY (`ListaCompraID`)
			REFERENCES `Listas_Compras` (`ID`)
			ON DELETE CASCADE
			ON UPDATE CASCADE,
		CONSTRAINT `FK_ListaProd_Produtos_ProdutoID`
			FOREIGN KEY (`ProdutoID`)
			REFERENCES `Produtos` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE,
		CONSTRAINT `FK_ListaProd_Fornecedores_FornecedorID`
			FOREIGN KEY (`FornecedorID`)
			REFERENCES `Fornecedores` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Consumacoes` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`Modulo` ENUM('Mesa', 'Comanda', 'Avulso', 'Entrega') NOT NULL,
		`Dia` INT NOT NULL,
		`Valor` DECIMAL(19,4) NOT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `UK_Consumacoes_Modulo_Dia` (`Modulo` ASC, `Dia` ASC))
	ENGINE = InnoDB;

	INSERT INTO `Servicos` (ID, Nome, Descricao, Tipo, Obrigatorio, Valor, Individual, Ativo) VALUES
		(3, "Consumação", "Taxa de consumação mínima", 'Taxa', 'N', 0, 'N', 'Y');

	CREATE TABLE IF NOT EXISTS `Cidades` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `EstadoID` INT NOT NULL,
	  `Nome` VARCHAR(100) NOT NULL,
	  `CEP` VARCHAR(8) NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Cidades_Estados_EstadoID_idx` (`EstadoID` ASC),
	  UNIQUE INDEX `EstadoID_Nome_UNIQUE` (`EstadoID` ASC, `Nome` ASC),
	  UNIQUE INDEX `CEP_UNIQUE` (`CEP` ASC),
	  CONSTRAINT `FK_Cidades_Estados_EstadoID`
	    FOREIGN KEY (`EstadoID`)
	    REFERENCES `Estados` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	INSERT INTO `Cidades` (SELECT NULL as ID, EstadoID, Cidade as Nome, 
		NULL as CEP FROM `Bairros` GROUP BY Cidade);

	ALTER TABLE `Bairros` ADD `CidadeID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Bairros` DROP FOREIGN KEY `FK_Bairros_Estados_EstadoID`;
	ALTER TABLE `Bairros` DROP INDEX `FK_Bairros_Estados_EstadoID_idx`;
	ALTER TABLE `Bairros` DROP INDEX `UK_Estado_Cidade_Nome`;
	UPDATE `Bairros` b
		LEFT JOIN `Cidades` c ON c.EstadoID = b.EstadoID AND c.Nome = b.Cidade 
		SET CidadeID = c.ID;
	ALTER TABLE `Bairros` DROP `Cidade`;
	ALTER TABLE `Bairros` DROP `EstadoID`;
	ALTER TABLE `Bairros` ADD UNIQUE INDEX `CidadeID_Nome_UNIQUE` (`CidadeID` ASC, `Nome` ASC);
	ALTER TABLE `Bairros` ADD INDEX `FK_Bairros_Cidades_CidadeID_idx` (`CidadeID` ASC);
	ALTER TABLE `Bairros` ADD CONSTRAINT `FK_Bairros_Cidades_CidadeID`
		FOREIGN KEY (`CidadeID`)
		REFERENCES `Cidades` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Bairros` MODIFY `CidadeID` INT NOT NULL;

	ALTER TABLE `Localizacoes` ADD `Latitude` DOUBLE NULL DEFAULT NULL AFTER `Referencia`;
	ALTER TABLE `Localizacoes` ADD `Longitude` DOUBLE NULL DEFAULT NULL AFTER `Latitude`;
	ALTER TABLE `Localizacoes` MODIFY `Referencia` VARCHAR(200) NULL DEFAULT NULL;
	ALTER TABLE `Localizacoes` MODIFY `Condominio` VARCHAR(100) NULL DEFAULT NULL;

	ALTER TABLE `Setores` ADD `Financeiro` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Descricao`;
	ALTER TABLE `Setores` ADD UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC);
	INSERT INTO `Setores` (Nome, Descricao, Financeiro) VALUES
		("Tesouraria", "Tesouraria", 'Y');

	ALTER TABLE `Produtos` ADD `Abreviacao` VARCHAR(100) NULL DEFAULT NULL AFTER `Descricao`;

	ALTER TABLE `Contas` ADD `ClassificacaoID` INT NOT NULL DEFAULT 2 AFTER `ID`;
	UPDATE `Contas` SET ClassificacaoID = 1 WHERE ID = 1;
	ALTER TABLE `Contas` MODIFY `ClassificacaoID` INT NOT NULL;
	ALTER TABLE `Contas` ADD INDEX `FK_Contas_Classificacoes_ClassificacaoID_idx` (`ClassificacaoID` ASC);
	ALTER TABLE `Contas` ADD CONSTRAINT `FK_Contas_Classificacoes_ClassificacaoID`
		FOREIGN KEY (`ClassificacaoID`)
		REFERENCES `Classificacoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Bancos` ADD UNIQUE INDEX `RazaoSocial_UNIQUE` (`RazaoSocial` ASC);

	ALTER TABLE `Pagamentos` ADD `SetorID` INT NULL DEFAULT NULL AFTER `ID`;
	SET @OLD_DISABLE_TRIGGERS=@DISABLE_TRIGGERS, @DISABLE_TRIGGERS=1;
	UPDATE `Pagamentos`
		SET `SetorID` = (SELECT ID FROM Setores LIMIT 1);
	SET @DISABLE_TRIGGERS=@OLD_DISABLE_TRIGGERS;
	ALTER TABLE `Pagamentos` MODIFY `SetorID` INT NOT NULL;
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Setores_SetorID_idx` (`SetorID` ASC);
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Setores_SetorID`
		FOREIGN KEY (`SetorID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	UPDATE Fornecedores SET CNPJ = IF(REPLACE(REPLACE(REPLACE(CNPJ,'.', ''), '/', ''), '-', '') = '', 
			NULL, REPLACE(REPLACE(REPLACE(CNPJ,'.', ''), '/', ''), '-', ''));
	UPDATE Fornecedores SET Fone1 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		LPAD(ID + 1111000, 10, '0'), REPLACE(REPLACE(REPLACE(REPLACE(Fone1,'(', ''), ')', ''), ' ', ''), '-', '')),
		Fone2 = IF(REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', '') = '', 
		NULL, REPLACE(REPLACE(REPLACE(REPLACE(Fone2,'(', ''), ')', ''), ' ', ''), '-', ''));

	INSERT INTO `Clientes`
		(SELECT 
			NULL as `ID`,
			'Juridica' as `Tipo`,
			NULL as `AcionistaID`,
			COALESCE(f.`Fantasia`, "")  as `Nome`,
			f.`RazaoSocial` as `Sobrenome`,
			'Feminino' as `Genero`,
			f.`CNPJ` as `CPF`,
			f.`IE` as `RG`,
			f.`IM`,
			f.`Email`,
			NULL as `DataAniversario`,
			f.`Fone1`,
			f.`Fone2`,
			NULL as `Slogan`,
			f.`Imagem`,
			f.`DataCadastro`
		 FROM `Fornecedores` f)
		 ON DUPLICATE KEY UPDATE 
		 	`Clientes`.Tipo = VALUES(`Clientes`.Tipo), 
		 	`Clientes`.Nome = VALUES(`Clientes`.Nome), 
		 	`Clientes`.Sobrenome = VALUES(`Clientes`.Sobrenome), 
		 	`Clientes`.Genero = VALUES(`Clientes`.Genero), 
		 	`Clientes`.RG = VALUES(`Clientes`.RG), 
		 	`Clientes`.IM = VALUES(`Clientes`.IM), 
		 	`Clientes`.Fone2 = VALUES(`Clientes`.Fone2), 
		 	`Clientes`.Imagem = VALUES(`Clientes`.Imagem), 
		 	`Clientes`.DataCadastro = VALUES(`Clientes`.DataCadastro);
	ALTER TABLE `Fornecedores` ADD `EmpresaID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Fornecedores` ADD `PrazoPagamento` INT NOT NULL DEFAULT 0 AFTER `EmpresaID`;
	ALTER TABLE `Fornecedores` ADD INDEX `FK_Fornecedores_Clientes_EmpresaID_idx` (`EmpresaID` ASC);
	ALTER TABLE `Fornecedores` ADD CONSTRAINT `FK_Fornecedores_Clientes_EmpresaID`
		FOREIGN KEY (`EmpresaID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Fornecedores` f
		LEFT JOIN `Clientes` c ON c.Fone1 = f.Fone1 OR (ISNULL(f.Fone1) AND c.Nome = f.Fantasia)
		SET f.`EmpresaID` = c.ID
		WHERE NOT ISNULL(c.ID) AND c.Tipo = 'Juridica';
	ALTER TABLE `Fornecedores` MODIFY `EmpresaID` INT NOT NULL;

	ALTER TABLE `Fornecedores` DROP INDEX `RazaoSocial_UNIQUE`;
	ALTER TABLE `Fornecedores` DROP INDEX `Fantasia_UNIQUE`;
	ALTER TABLE `Fornecedores` DROP INDEX `CNPJ_UNIQUE`;
	ALTER TABLE `Fornecedores` DROP FOREIGN KEY `FK_Fornecedores_Estados_EstadoID`;
	ALTER TABLE `Fornecedores` DROP INDEX `FK_Fornecedores_Estados_EstadoID_idx`;
	ALTER TABLE `Fornecedores` DROP `RazaoSocial`;
	ALTER TABLE `Fornecedores` DROP `Fantasia`;
	ALTER TABLE `Fornecedores` DROP `CNPJ`;
	ALTER TABLE `Fornecedores` DROP `IE`;
	ALTER TABLE `Fornecedores` DROP `IM`;
	ALTER TABLE `Fornecedores` DROP `Email`;
	ALTER TABLE `Fornecedores` DROP `Endereco`;
	ALTER TABLE `Fornecedores` DROP `Fone1`;
	ALTER TABLE `Fornecedores` DROP `Fone2`;
	ALTER TABLE `Fornecedores` DROP `Contato`;
	ALTER TABLE `Fornecedores` DROP `EstadoID`;
	ALTER TABLE `Fornecedores` DROP `Cidade`;
	ALTER TABLE `Fornecedores` DROP `Imagem`;

	DELETE FROM `Pacotes`;
	ALTER TABLE `Pacotes` DROP `Abreviacao`;
	ALTER TABLE `Pacotes` DROP FOREIGN KEY `FK_Pacotes_Propriedades_AssociacaoID`;
	ALTER TABLE `Pacotes` DROP FOREIGN KEY `FK_Pacotes_Produtos_ProdutoID`;
	ALTER TABLE `Pacotes` DROP INDEX `FK_Pacotes_Propriedades_AssociacaoID_idx`;
	ALTER TABLE `Pacotes` ADD INDEX `FK_Pacotes_Pacotes_AssociacaoID_idx` (`AssociacaoID` ASC);
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Produtos_ProdutoID`
		FOREIGN KEY (`ProdutoID`)
		REFERENCES `Produtos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Pacotes_AssociacaoID`
		FOREIGN KEY (`AssociacaoID`)
		REFERENCES `Pacotes` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;

	DELETE FROM `Grupos`;
	ALTER TABLE `Grupos` ADD `ProdutoID` INT NOT NULL AFTER `ID`;
	ALTER TABLE `Grupos` ADD INDEX `FK_Grupos_Produtos_ProdutoID_idx` (`ProdutoID` ASC);
	ALTER TABLE `Grupos` ADD CONSTRAINT `FK_Grupos_Produtos_ProdutoID`
		FOREIGN KEY (`ProdutoID`)
		REFERENCES `Produtos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Grupos` ADD UNIQUE INDEX `UK_Grupos_Produto_Descricao` (`ProdutoID` ASC, `Descricao` ASC);

	ALTER TABLE `Propriedades` ADD `GrupoID` INT NOT NULL AFTER `ID`;
	ALTER TABLE `Propriedades` DROP INDEX `Descricao_UNIQUE`;
	ALTER TABLE `Propriedades` CHANGE `Descricao` `Nome` VARCHAR(100) NOT NULL;
	ALTER TABLE `Propriedades` MODIFY `Abreviacao` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Propriedades` ADD INDEX `FK_Propriedades_Grupos_GrupoID_idx` (`GrupoID` ASC);
	ALTER TABLE `Propriedades` ADD UNIQUE INDEX `GrupoID_Nome_UNIQUE` (`GrupoID` ASC, `Nome` ASC);
	ALTER TABLE `Propriedades` ADD CONSTRAINT `FK_Propriedades_Grupos_GrupoID`
	    FOREIGN KEY (`GrupoID`)
	    REFERENCES `Grupos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

	ALTER TABLE `Produtos_Fornecedores` ADD `PrecoVenda` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `PrecoCompra`;
	ALTER TABLE `Produtos_Fornecedores` ADD `QuantidadeMinima` DOUBLE NOT NULL DEFAULT 1 AFTER `PrecoVenda`;
	ALTER TABLE `Produtos_Fornecedores` ADD `Estoque` DOUBLE NOT NULL DEFAULT 0 AFTER `QuantidadeMinima`;
	ALTER TABLE `Produtos_Fornecedores` ADD `Limitado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Estoque`;
	ALTER TABLE `Produtos_Fornecedores` CHANGE `DataCadastro` `DataConsulta` DATETIME NULL DEFAULT NULL;

	ALTER TABLE `Sistema` ADD `EmpresaID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Sistema` ADD `ParceiroID` INT NULL DEFAULT NULL AFTER `EmpresaID`;
	ALTER TABLE `Sistema` ADD `AccessKey` VARCHAR(255) NULL DEFAULT NULL AFTER `ParceiroID`;
	ALTER TABLE `Sistema` ADD `RegistryKey` TEXT NULL DEFAULT NULL AFTER `AccessKey`;
	ALTER TABLE `Sistema` ADD `LicenseKey` TEXT NULL DEFAULT NULL AFTER `RegistryKey`;
	ALTER TABLE `Sistema` ADD `Computadores` INT NULL DEFAULT NULL AFTER `LicenseKey`;
	ALTER TABLE `Sistema` ADD `GUID` VARCHAR(36) NULL DEFAULT NULL AFTER `Computadores`;
	ALTER TABLE `Sistema` ADD `Opcoes` TEXT NULL DEFAULT NULL AFTER `GUID`;
	ALTER TABLE `Sistema` MODIFY `UltimoBackup` DATETIME NULL DEFAULT NULL;
	ALTER TABLE `Sistema` MODIFY `VersaoDB` VARCHAR(45) NULL AFTER `UltimoBackup`;
	ALTER TABLE `Sistema` ADD INDEX `FK_Sistema_Clientes_EmpresaID_idx` (`EmpresaID` ASC);
	ALTER TABLE `Sistema` ADD INDEX `FK_Sistema_Clientes_ParceiroID_idx` (`ParceiroID` ASC);
	ALTER TABLE `Sistema` ADD CONSTRAINT `FK_Sistema_Clientes_EmpresaID`
		FOREIGN KEY (`EmpresaID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Sistema` ADD CONSTRAINT `FK_Sistema_Clientes_ParceiroID`
		FOREIGN KEY (`ParceiroID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	delimiter |

	DROP TABLE IF EXISTS base64_data |
	CREATE TABLE base64_data (c VARCHAR(1) BINARY, val TINYINT) |
	INSERT INTO base64_data VALUES 
		('A',0), ('B',1), ('C',2), ('D',3), ('E',4), ('F',5), ('G',6), ('H',7), ('I',8), ('J',9),
		('K',10), ('L',11), ('M',12), ('N',13), ('O',14), ('P',15), ('Q',16), ('R',17), ('S',18), ('T',19),
		('U',20), ('V',21), ('W',22), ('X',23), ('Y',24), ('Z',25), ('a',26), ('b',27), ('c',28), ('d',29),
		('e',30), ('f',31), ('g',32), ('h',33), ('i',34), ('j',35), ('k',36), ('l',37), ('m',38), ('n',39),
		('o',40), ('p',41), ('q',42), ('r',43), ('s',44), ('t',45), ('u',46), ('v',47), ('w',48), ('x',49),
		('y',50), ('z',51), ('0',52), ('1',53), ('2',54), ('3',55), ('4',56), ('5',57), ('6',58), ('7',59),
		('8',60), ('9',61), ('+',62), ('/',63), ('=',0) |


	DROP FUNCTION IF EXISTS BASE64_DECODE |
	CREATE FUNCTION BASE64_DECODE (input BLOB)
		RETURNS BLOB
		CONTAINS SQL
		DETERMINISTIC
		SQL SECURITY INVOKER
	BEGIN
		DECLARE ret BLOB DEFAULT '';
		DECLARE done TINYINT DEFAULT 0;

		IF input IS NULL THEN
			RETURN NULL;
		END IF;

		WHILE NOT done DO BEGIN
			DECLARE accum_value BIGINT UNSIGNED DEFAULT 0;
			DECLARE in_count TINYINT DEFAULT 0;
			DECLARE out_count TINYINT DEFAULT 3;

			WHILE in_count < 4 DO BEGIN
				DECLARE first_char VARCHAR(1);
				DECLARE error TINYINT DEFAULT 0;
		
				IF LENGTH(input) = 0 THEN
					RETURN ret;
				END IF;
		
				SET first_char = SUBSTRING(input,1,1);
				SET input = SUBSTRING(input,2);
		
				BEGIN
					DECLARE tempval TINYINT UNSIGNED;
					DECLARE base64_getval CURSOR FOR SELECT val FROM base64_data WHERE c = first_char;
					DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET error = 1;
		
					OPEN base64_getval;
					FETCH base64_getval INTO tempval;
					CLOSE base64_getval;

					IF NOT error THEN
						SET accum_value = (accum_value << 6) + tempval;
					END IF;

				END;

				IF NOT error THEN
					SET in_count = in_count + 1;

					IF first_char = '=' THEN
						SET done = 1;
						SET out_count = out_count - 1;
					END IF;
				END IF;
			END; END WHILE;

			-- We've now accumulated 24 bits; deaccumulate into bytes

			-- We have to work from the left, so use the third byte position and shift left
			WHILE out_count > 0 DO BEGIN
				SET ret = CONCAT(ret,CHAR((accum_value & 0xff0000) >> 16));
				SET out_count = out_count - 1;
				SET accum_value = (accum_value << 8) & 0xffffff;
			END; END WHILE;
		
		END; END WHILE;

		RETURN ret;
	END |

	DROP FUNCTION IF EXISTS BASE64_ENCODE |
	CREATE FUNCTION BASE64_ENCODE (input BLOB)
		RETURNS BLOB
		CONTAINS SQL
		DETERMINISTIC
		SQL SECURITY INVOKER
	BEGIN
		DECLARE ret BLOB DEFAULT '';
		DECLARE done TINYINT DEFAULT 0;

		IF input IS NULL THEN
			RETURN NULL;
		END IF;

		WHILE NOT done DO BEGIN
			DECLARE accum_value BIGINT UNSIGNED DEFAULT 0;
			DECLARE in_count TINYINT DEFAULT 0;
			DECLARE out_count TINYINT;
			DECLARE leave_while TINYINT DEFAULT 0;

			WHILE NOT leave_while AND in_count < 3 DO BEGIN
				DECLARE first_char VARCHAR(1);
		
				IF LENGTH(input) = 0 THEN
					SET done = 1;
					SET accum_value = accum_value << (8 * (3 - in_count));
					SET leave_while = 1;
				ELSE
					SET first_char = SUBSTRING(input,1,1);
					SET input = SUBSTRING(input,2);
			
					SET accum_value = (accum_value << 8) + ASCII(first_char);

					SET in_count = in_count + 1;
				END IF;
		
			END; END WHILE;

			-- We've now accumulated 24 bits; deaccumulate into base64 characters

			-- We have to work from the left, so use the third byte position and shift left
			CASE
				WHEN in_count = 3 THEN SET out_count = 4;
				WHEN in_count = 2 THEN SET out_count = 3;
				WHEN in_count = 1 THEN SET out_count = 2;
				ELSE RETURN ret;
			END CASE;

			WHILE out_count > 0 DO BEGIN
				BEGIN
					DECLARE out_char VARCHAR(1);
					DECLARE base64_getval CURSOR FOR SELECT c FROM base64_data WHERE val = (accum_value >> 18);

					OPEN base64_getval;
					FETCH base64_getval INTO out_char;
					CLOSE base64_getval;

					SET ret = CONCAT(ret,out_char);
					SET out_count = out_count - 1;
					SET accum_value = accum_value << 6 & 0xffffff;
				END;
			END; END WHILE;

			CASE
				WHEN in_count = 2 THEN SET ret = CONCAT(ret,'=');
				WHEN in_count = 1 THEN SET ret = CONCAT(ret,'==');
				ELSE BEGIN END;
			END CASE;
		
		END; END WHILE;

		RETURN ret;
	END |

	delimiter ;

	UPDATE `Sistema` SET Opcoes = BASE64_ENCODE(CONCAT(
		"[Imprimir]\n", "Empresa.Fantasia_Destacado=", (SELECT IF((Opcoes >> 10) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.CNPJ=", (SELECT IF((Opcoes >> 0) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.Endereco=", (SELECT IF((Opcoes >> 1) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.Slogan=", (SELECT IF((Opcoes >> 7) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.Logomarca=", (SELECT IF((Opcoes >> 22) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.Telefone_1=", (SELECT IF((Opcoes >> 2) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Empresa.Telefone_2=", (SELECT IF((Opcoes >> 3) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Garcom=", (SELECT IF((Opcoes >> 5) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Garcom.Todos=", (SELECT IF((Opcoes >> 4) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Atendente=", (SELECT IF((Opcoes >> 6) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Data=", (SELECT IF((Opcoes >> 9) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Hora=", (SELECT IF((Opcoes >> 8) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Total_Destacado=", (SELECT IF((Opcoes >> 11) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Mesa.Atendente_Dividir=0\n",
		"Permanencia=", (SELECT IF((Opcoes >> 20) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Conta.Divisao=", (SELECT IF((Opcoes >> 24) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Cozinha.Produto.Codigo=", (SELECT IF((Opcoes >> 25) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Cozinha.Produto.Detalhes=", (SELECT IF((Opcoes >> 27) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Cozinha.Local_Destacado=", (SELECT IF((Opcoes >> 14) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"[Cupom]\n", "Perguntar=", (SELECT IF((Opcoes >> 12) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"Servicos.Perguntar=", (SELECT IF((Opcoes >> 13) & 1 = 1, "0", "1") FROM Empresas LIMIT 1), "\n",
		"[Sistema]\n", "Auto.Logout=", (SELECT IF((Opcoes >> 19) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"[Comandas]\n", "PrePaga=", (SELECT IF((Opcoes >> 26) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"[Vendas]\n", "Exibir.Cancelados=", (SELECT IF((Opcoes >> 28) & 1 = 1, "1", "0") FROM Empresas LIMIT 1), "\n",
		"[Email]\n", "Remetente=", (SELECT COALESCE(Remetente, "") FROM Empresas LIMIT 1), "\n",
		"Servidor=", (SELECT COALESCE(Servidor, "") FROM Empresas LIMIT 1), "\n",
		"Porta=", (SELECT COALESCE(Porta, "0") FROM Empresas LIMIT 1), "\n",
		"Criptografia=", (SELECT (CASE 
									WHEN Criptografia = 'SSL' THEN "1"
									WHEN Criptografia = 'TLS' THEN "2"
									ELSE "0"
								 END)
						  FROM Empresas LIMIT 1), "\n",
		"Usuario=", (SELECT COALESCE(Usuario, "") FROM Empresas LIMIT 1), "\n",
		"Senha=", (SELECT COALESCE(Senha, "") FROM Empresas LIMIT 1), "\n")),

		`AccessKey` = (SELECT AccessKey FROM Empresas LIMIT 1),
		`RegistryKey` = (SELECT RegistryKey FROM Empresas LIMIT 1),
		`LicenseKey` = (SELECT LicenseKey FROM Empresas LIMIT 1),
		`Computadores` = (SELECT Computadores FROM Empresas LIMIT 1),
		`GUID` = (SELECT GUID FROM Empresas LIMIT 1);

	UPDATE `Sistema` SET ParceiroID = (SELECT f.EmpresaID FROM `Empresas` e
		LEFT JOIN `Fornecedores` f ON f.ID = e.ParceiroID
		LIMIT 1);

	UPDATE Empresas SET CNPJ = IF(REPLACE(REPLACE(REPLACE(CNPJ,'.', ''), '/', ''), '-', '') = '', 
			NULL, REPLACE(REPLACE(REPLACE(CNPJ,'.', ''), '/', ''), '-', ''));
	INSERT INTO `Clientes`
		(SELECT 
			NULL as `ID`,
			'Juridica' as `Tipo`,
			NULL as `AcionistaID`,
			COALESCE(e.`RazaoSocial`, "") as `Nome`,
			e.`RazaoSocial` as `Sobrenome`,
			'Feminino' as `Genero`,
			e.`CNPJ` as `CPF`,
			e.`IE` as `RG`,
			e.`IM`,
			NULL as `Email`,
			NULL as `DataAniversario`,
			e.`Fone1`,
			e.`Fone2`,
			e.`Slogan` as `Slogan`,
			e.`Imagem`,
			NOW() as `DataCadastro`
		 FROM `Empresas` e)
		 ON DUPLICATE KEY UPDATE 
		 	`Clientes`.Tipo = VALUES(`Clientes`.Tipo), 
		 	`Clientes`.Nome = VALUES(`Clientes`.Nome), 
		 	`Clientes`.Sobrenome = VALUES(`Clientes`.Sobrenome), 
		 	`Clientes`.Genero = VALUES(`Clientes`.Genero), 
		 	`Clientes`.RG = VALUES(`Clientes`.RG), 
		 	`Clientes`.IM = VALUES(`Clientes`.IM), 
		 	`Clientes`.Fone2 = VALUES(`Clientes`.Fone2), 
		 	`Clientes`.Slogan = VALUES(`Clientes`.Slogan), 
		 	`Clientes`.Imagem = VALUES(`Clientes`.Imagem), 
		 	`Clientes`.DataCadastro = VALUES(`Clientes`.DataCadastro);
	UPDATE `Sistema` SET EmpresaID = 
		(SELECT c.ID FROM `Empresas` e
		LEFT JOIN `Clientes` c ON c.Fone1 = e.Fone1
		LIMIT 1);

	DROP TABLE `Empresas`;
	DROP FUNCTION IF EXISTS BASE64_ENCODE;
	DROP FUNCTION IF EXISTS BASE64_DECODE;
	DROP TABLE IF EXISTS base64_data;

	CREATE TABLE IF NOT EXISTS `Enderecos` (
		`ID` INT NOT NULL AUTO_INCREMENT,
		`CidadeID` INT NOT NULL,
		`BairroID` INT NOT NULL,
		`Logradouro` VARCHAR(200) NOT NULL,
		`CEP` VARCHAR(8) NOT NULL,
		PRIMARY KEY (`ID`),
		UNIQUE INDEX `CEP_UNIQUE` (`CEP` ASC),
		INDEX `FK_Enderecos_Cidades_CidadeID_idx` (`CidadeID` ASC),
		INDEX `FK_Enderecos_Bairros_BairroID_idx` (`BairroID` ASC),
		UNIQUE INDEX `BairroID_Logradouro_UNIQUE` (`BairroID` ASC, `Logradouro` ASC),
		CONSTRAINT `FK_Enderecos_Cidades_CidadeID`
			FOREIGN KEY (`CidadeID`)
			REFERENCES `Cidades` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE,
		CONSTRAINT `FK_Enderecos_Bairros_BairroID`
			FOREIGN KEY (`BairroID`)
			REFERENCES `Bairros` (`ID`)
			ON DELETE RESTRICT
			ON UPDATE CASCADE)
	ENGINE = InnoDB;

	ALTER TABLE `Formacoes` MODIFY `ProdutoPedidoID` INT NOT NULL;
	ALTER TABLE `Formacoes` MODIFY `PacoteID` INT NULL DEFAULT NULL;
	ALTER TABLE `Formacoes` ADD `Tipo` ENUM('Pacote', 'Composicao') NOT NULL DEFAULT 'Pacote' AFTER `ProdutoPedidoID`;
	ALTER TABLE `Formacoes` ADD `ComposicaoID` INT NULL DEFAULT NULL AFTER `PacoteID`;
	ALTER TABLE `Formacoes` ADD INDEX `FK_Formacoes_Composicoes_ComposicaoID_idx` (`ComposicaoID` ASC);
	ALTER TABLE `Formacoes` ADD CONSTRAINT `FK_Formacoes_Composicoes_ComposicaoID`
		FOREIGN KEY (`ComposicaoID`)
		REFERENCES `Composicoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;


	UPDATE `Permissoes` SET Nome = "PedidoMesa", Descricao = "Permitir realizar pedidos para uma mesa"
		WHERE ID = 4;
	UPDATE `Permissoes` SET Descricao = "Permitir realizar um pagamento e efetuar vendas rápidas"
		WHERE ID = 5;
	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(44, 2, "PedidoComanda", "Permitir realizar pedidos para cartões de consumo")
		ON DUPLICATE KEY UPDATE 
			FuncionalidadeID = VALUES(FuncionalidadeID),
			Nome = VALUES(Nome),
			Descricao = VALUES(Descricao); 
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 44 as PermissaoID FROM Acessos WHERE PermissaoID = 4
		ON DUPLICATE KEY UPDATE 
			FuncaoID = VALUES(FuncaoID);

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(62, 6, "ListaCompras", "Permitir cadastrar lista de compras de produtos"),
		(63, 7, "RelatorioMensal", "Permitir visualizar e emitir relatórios de vendas mensais"),
		(64, 6, "CadastroConsumacoes", "Permitir cadastrar ou alterar as taxas de consumações mínimas"),
		(65, 6, "CadastroCidades", "Permitir cadastrar ou alterar as cidades dos estados"),
		(66, 5, "RetirarDoEstoque", "Permitir retirar produtos do estoque");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 62 as PermissaoID FROM Acessos WHERE PermissaoID = 23;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 63 as PermissaoID FROM Acessos WHERE PermissaoID = 23;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 64 as PermissaoID FROM Acessos WHERE PermissaoID = 57;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 65 as PermissaoID FROM Acessos WHERE PermissaoID = 42;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 66 as PermissaoID FROM Acessos WHERE PermissaoID = 29;

	ALTER TABLE `Dispositivos` MODIFY `SetorID` INT NOT NULL AFTER `ID`;
	ALTER TABLE `Dispositivos` MODIFY `CaixaID` INT NULL DEFAULT NULL AFTER `SetorID`;

	ALTER TABLE `Impressoras` MODIFY `SetorID` INT NOT NULL AFTER `ID`;
	ALTER TABLE `Impressoras` MODIFY `DispositivoID` INT NULL DEFAULT NULL AFTER `SetorID`;
	ALTER TABLE `Impressoras` DROP INDEX `Impresoras_Modo_Dispositivo_Setor_UNIQUE`;
	ALTER TABLE `Impressoras` DROP INDEX `Impressoras_Descricao_Dispositivo_UNIQUE`;
	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `UK_Impresoras_Setor_Dispositivo_Modo` (`SetorID` ASC, `DispositivoID` ASC, `Modo` ASC);
	ALTER TABLE `Impressoras` ADD UNIQUE INDEX `UK_Impressoras_Dispositivo_Descricao` (`DispositivoID` ASC, `Descricao` ASC);

}

/* Corrigido cadastro de mesmo fornecedor */
Update (Version: "1.3.8.2") {
	ALTER TABLE `Funcionarios` ADD `LinguagemID` INT NOT NULL DEFAULT 0 AFTER `Porcentagem`;
	ALTER TABLE `Fornecedores` ADD UNIQUE INDEX `EmpresaID_UNIQUE` (`EmpresaID` ASC);
}

/* Adicionado permissões de relatório de bairros */
Update (Version: "1.3.9.0") {
	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(67, 7, "RelatorioBairros", "Permitir visualizar relatórios de bairros");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 67 as PermissaoID FROM Acessos WHERE PermissaoID = 55;
}

/* Adicionado créditos de clientes e horário de funcionamento */
Update (Version: "1.4.0.0") {
	INSERT INTO `Unidades` (Nome, Descricao, Sigla) VALUES
		("Caloria", "Unidade de medida de energia", "cal"),
		("Joule", "Unidade de medida de energia", "J")
		ON DUPLICATE KEY UPDATE
			Nome = VALUES(Nome),
			Descricao = VALUES(Descricao);
	ALTER TABLE `Produtos` ADD `QuantidadeMaxima` DOUBLE NOT NULL DEFAULT 0 AFTER `QuantidadeLimite`;
	ALTER TABLE `Classificacoes` ADD `ClassificacaoID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Classificacoes` ADD INDEX `FK_Classificacoes_ClassificacaoID_idx` (`ClassificacaoID` ASC);
	ALTER TABLE `Classificacoes` ADD CONSTRAINT `FK_Classificacoes_ClassificacaoID`
		FOREIGN KEY (`ClassificacaoID`)
		REFERENCES `Classificacoes` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Contas` ADD `SubClassificacaoID` INT NULL DEFAULT NULL AFTER `FuncionarioID`;
	ALTER TABLE `Contas` ADD INDEX `FK_Contas_Classificacoes_SubClassificacaoID_idx` (`SubClassificacaoID` ASC);
	ALTER TABLE `Contas` ADD CONSTRAINT `FK_Contas_Classificacoes_SubClassificacaoID`
		FOREIGN KEY (`SubClassificacaoID`)
		REFERENCES `Classificacoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Estoque` DROP FOREIGN KEY `FK_Estoque_Produtos_ProdutoID`;
	ALTER TABLE `Estoque` ADD CONSTRAINT `FK_Estoque_Produtos_ProdutoID`
		FOREIGN KEY (`ProdutoID`)
		REFERENCES `Produtos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Estoque` DROP FOREIGN KEY `FK_Estoque_Estoque_EntradaID`;
	ALTER TABLE `Estoque` ADD CONSTRAINT `FK_Estoque_Estoque_EntradaID`
		FOREIGN KEY (`EntradaID`)
		REFERENCES `Estoque` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Propriedades` ADD `Imagem` MEDIUMBLOB NULL DEFAULT NULL;
	ALTER TABLE `Propriedades` ADD `DataAtualizacao` DATETIME NOT NULL;
	ALTER TABLE `Pacotes` DROP FOREIGN KEY `FK_Pacotes_Grupos_GrupoID`;
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Grupos_GrupoID`
		FOREIGN KEY (`GrupoID`)
		REFERENCES `Grupos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;
	ALTER TABLE `Informacoes` ADD `UnidadeID` INT NULL DEFAULT NULL;
	UPDATE `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g'
		SET i.UnidadeID = u.ID;
	ALTER TABLE `Informacoes` MODIFY `UnidadeID` INT NOT NULL;
	ALTER TABLE `Informacoes` ADD `Porcao` DOUBLE NOT NULL DEFAULT 0;
	ALTER TABLE `Informacoes` MODIFY `Porcao` DOUBLE NOT NULL;
	ALTER TABLE `Informacoes` ADD `Dieta` DOUBLE NOT NULL DEFAULT 2000000;
	ALTER TABLE `Informacoes` ADD `Ingredientes` TEXT NULL DEFAULT NULL;
	ALTER TABLE `Informacoes` ADD INDEX `FK_Informacoes_Unidades_UnidadeID_idx` (`UnidadeID` ASC);
	ALTER TABLE `Informacoes` ADD CONSTRAINT `FK_Informacoes_Unidades_UnidadeID`
		FOREIGN KEY (`UnidadeID`)
		REFERENCES `Unidades` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	CREATE TABLE IF NOT EXISTS `Valores_Nutricionais` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `InformacaoID` INT NOT NULL,
	  `UnidadeID` INT NOT NULL,
	  `Nome` VARCHAR(100) NOT NULL,
	  `Quantidade` DOUBLE NOT NULL,
	  `ValorDiario` DOUBLE NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `UK_Informacao_Nome` (`InformacaoID` ASC, `Nome` ASC),
	  INDEX `FK_Valores_Nutricionais_Unidades_UnidadeID_idx` (`UnidadeID` ASC),
	  CONSTRAINT `FK_Valores_Nutricionais_Informacoes_InformacaoID`
	    FOREIGN KEY (`InformacaoID`)
	    REFERENCES `Informacoes` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Valores_Nutricionais_Unidades_UnidadeID`
	    FOREIGN KEY (`UnidadeID`)
	    REFERENCES `Unidades` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Valor Energético" as Nome, i.`ValorEnergetico` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'cal';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Carboidratos" as Nome, i.`Carboidratos` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Proteínas" as Nome, i.`Proteinas` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Gorduras Totais" as Nome, i.`GordurasTotais` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Gorduras Saturadas" as Nome, i.`GordurasSaturadas` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Gordura Trans" as Nome, i.`GorduraTrans` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Fibra Alimentar" as Nome, i.`FibraAlimentar` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	INSERT INTO `Valores_Nutricionais` (InformacaoID, UnidadeID, Nome, Quantidade)
		SELECT i.ID as InformacaoID, u.ID as UnidadeID, "Sódio" as Nome, i.`Sodio` as Quantidade 
		FROM `Informacoes` i
		LEFT JOIN `Unidades` u ON u.Sigla = 'g';
	ALTER TABLE `Informacoes` DROP `ValorEnergetico`;
	ALTER TABLE `Informacoes` DROP `Carboidratos`;
	ALTER TABLE `Informacoes` DROP `Proteinas`;
	ALTER TABLE `Informacoes` DROP `GordurasTotais`;
	ALTER TABLE `Informacoes` DROP `GordurasSaturadas`;
	ALTER TABLE `Informacoes` DROP `GorduraTrans`;
	ALTER TABLE `Informacoes` DROP `FibraAlimentar`;
	ALTER TABLE `Informacoes` DROP `Sodio`;
	ALTER TABLE `Listas_Compras` ADD INDEX `FK_Listas_Compras_Funcionarios_CompradorID_idx` (`CompradorID` ASC);
	ALTER TABLE `Listas_Compras` ADD CONSTRAINT `FK_Listas_Compras_Funcionarios_CompradorID`
		FOREIGN KEY (`CompradorID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Listas_Produtos` MODIFY `FornecedorID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Listas_Produtos` MODIFY `Quantidade` DOUBLE NULL DEFAULT NULL AFTER `FornecedorID`;
	CREATE TABLE IF NOT EXISTS `Creditos` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `ClienteID` INT NOT NULL,
	  `PagamentoID` INT NULL DEFAULT NULL,
	  `Valor` DECIMAL(19,4) NOT NULL,
	  `Detalhes` VARCHAR(255) NULL DEFAULT NULL,
	  `DataCadastro` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Creditos_Clientes_ClienteID_idx` (`ClienteID` ASC),
	  INDEX `FK_Creditos_Pagamentos_PagamentoID_idx` (`PagamentoID` ASC),
	  CONSTRAINT `FK_Creditos_Clientes_ClienteID`
	    FOREIGN KEY (`ClienteID`)
	    REFERENCES `Clientes` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Creditos_Pagamentos_PagamentoID`
	    FOREIGN KEY (`PagamentoID`)
	    REFERENCES `Pagamentos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;
	CREATE TABLE IF NOT EXISTS `Horarios` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Dia` INT NOT NULL,
	  `Hora` TIME NOT NULL,
	  `Duracao` INT NOT NULL,
	  `TempoEntrega` INT NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`))
	ENGINE = InnoDB;

	UPDATE Funcionalidades SET
		Descricao = "Controla cancelamentos e outras operações de vendas"
		WHERE ID = 2;
	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(68, 1, "AlterarHorario", "Permitir alterar o horário de funcionamento do estabelecimento"),
		(69, 6, "CadastrarCreditos", "Permitir cadastrar e alterar créditos de clientes"),
		(70, 1, "AlterarStatus", "Permitir alterar os estados de preparo dos produtos");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 68 as PermissaoID FROM Acessos WHERE PermissaoID = 61;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 69 as PermissaoID FROM Acessos WHERE PermissaoID = 52;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 70 as PermissaoID FROM Acessos WHERE PermissaoID = 48;
}


/* Adicionado produtos do pacote nos itens vendidos */
Update (Version: "1.4.0.1") {
	ALTER TABLE `Produtos_Pedidos` ADD `ProdutoPedidoID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Produtos_Pedidos` ADD INDEX `FK_ProdPed_ProdPed_ProdutoPedidoID_idx` (`ProdutoPedidoID` ASC);
	ALTER TABLE `Produtos_Pedidos` ADD CONSTRAINT `FK_ProdPed_ProdPed_ProdutoPedidoID`
		FOREIGN KEY (`ProdutoPedidoID`)
		REFERENCES `Produtos_Pedidos` (`ID`)
		ON DELETE CASCADE
		ON UPDATE CASCADE;

	CREATE TABLE IF NOT EXISTS `Transferencias` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `PedidoID` INT NOT NULL,
	  `DestinoPedidoID` INT NOT NULL,
	  `Tipo` ENUM('Pedido', 'Produto') NOT NULL,
	  `Modulo` ENUM('Mesa', 'Comanda') NOT NULL,
	  `MesaID` INT NULL DEFAULT NULL,
	  `DestinoMesaID` INT NULL DEFAULT NULL,
	  `ComandaID` INT NULL DEFAULT NULL,
	  `DestinoComandaID` INT NULL DEFAULT NULL,
	  `ProdutoPedidoID` INT NULL DEFAULT NULL,
	  `FuncionarioID` INT NOT NULL,
	  `DataHora` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Transf_Pedidos_PedidoID_idx` (`PedidoID` ASC),
	  INDEX `FK_Transf_Pedidos_DestinoPedidoID_idx` (`DestinoPedidoID` ASC),
	  INDEX `FK_Transf_Mesas_MesaID_idx` (`MesaID` ASC),
	  INDEX `FK_Transf_Mesas_DestinoMesaID_idx` (`DestinoMesaID` ASC),
	  INDEX `FK_Transf_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC),
	  INDEX `FK_Transf_Comandas_ComandaID_idx` (`ComandaID` ASC),
	  INDEX `FK_Transf_Comandas_DestinoComandaID_idx` (`DestinoComandaID` ASC),
	  INDEX `FK_Transf_ProdPed_ProdutoPedidoID_idx` (`ProdutoPedidoID` ASC),
	  CONSTRAINT `FK_Transf_Pedidos_PedidoID`
	    FOREIGN KEY (`PedidoID`)
	    REFERENCES `Pedidos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Pedidos_DestinoPedidoID`
	    FOREIGN KEY (`DestinoPedidoID`)
	    REFERENCES `Pedidos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Mesas_MesaID`
	    FOREIGN KEY (`MesaID`)
	    REFERENCES `Mesas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Mesas_DestinoMesaID`
	    FOREIGN KEY (`DestinoMesaID`)
	    REFERENCES `Mesas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Funcionarios_FuncionarioID`
	    FOREIGN KEY (`FuncionarioID`)
	    REFERENCES `Funcionarios` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Comandas_ComandaID`
	    FOREIGN KEY (`ComandaID`)
	    REFERENCES `Comandas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_Comandas_DestinoComandaID`
	    FOREIGN KEY (`DestinoComandaID`)
	    REFERENCES `Comandas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Transf_ProdPed_ProdutoPedidoID`
	    FOREIGN KEY (`ProdutoPedidoID`)
	    REFERENCES `Produtos_Pedidos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	ALTER TABLE `Formas_Pagto` MODIFY `Tipo` ENUM('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia') NOT NULL;
	ALTER TABLE `Resumos` MODIFY `Tipo` ENUM('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia') NOT NULL;
	ALTER TABLE `Bancos` ADD `Numero` VARCHAR(40) NULL DEFAULT NULL AFTER `ID`;
	UPDATE `Bancos` SET `Numero` = CONVERT(ID, CHAR(40));
	ALTER TABLE `Bancos` ADD UNIQUE INDEX `Numero_UNIQUE` (`Numero` ASC);
	ALTER TABLE `Bancos` MODIFY `Numero` VARCHAR(40) NOT NULL;
	UPDATE `Bancos` SET `ID` = ID + 1 ORDER BY ID DESC;
	ALTER TABLE `Bancos` MODIFY `ID` INT NOT NULL AUTO_INCREMENT;

	ALTER TABLE `Contas` DROP `Editavel`;
	ALTER TABLE `Contas` DROP `Periodicidade`;
	ALTER TABLE `Contas` DROP `Margem`;
	ALTER TABLE `Contas` DROP `Ativa`;
	UPDATE `Contas` SET `Valor` = 0 WHERE ISNULL(`Valor`);
	ALTER TABLE `Contas` MODIFY `Valor` DECIMAL(19,4) NOT NULL;
	ALTER TABLE `Contas` ADD `DataEmissao` DATETIME NULL DEFAULT NULL AFTER `Vencimento`;
	ALTER TABLE `Contas` ADD `NumeroDoc` VARCHAR(64) NULL DEFAULT NULL AFTER `DataEmissao`;
	ALTER TABLE `Contas` ADD `AnexoCaminho` VARCHAR(200) NULL DEFAULT NULL AFTER `NumeroDoc`;

	ALTER TABLE `Pacotes` DROP INDEX `UK_Pacotes_PacID_ProdID_PropID_AssocID`;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(71, 7, "RelatorioEntrega", "Permitir visualizar relatório de entrega por entregador"),
		(72, 7, "RelatorioFornecedores", "Permitir visualizar relatório de fornecedores");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 71 as PermissaoID FROM Acessos WHERE PermissaoID = 55;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 72 as PermissaoID FROM Acessos WHERE PermissaoID = 17;

	ALTER TABLE `Estoque` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Detalhes`;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_INSERT` BEFORE INSERT ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE ProdutoID = NEW.ProdutoID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE PropriedadeID = NEW.PropriedadeID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND Selecionado = 'Y';
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_UPDATE` BEFORE UPDATE ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE ProdutoID = NEW.ProdutoID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE PropriedadeID = NEW.PropriedadeID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID AND Selecionado = 'Y';
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND pc.ID <> OLD.ID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Transferencias_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Transferencias_BEFORE_INSERT` BEFORE INSERT ON `Transferencias` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _estado, _estado_dest VARCHAR(75);
		DECLARE _cancelado, _cancelado_dest VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
		SELECT Cancelado, Estado INTO _cancelado_dest, _estado_dest FROM Pedidos WHERE ID = NEW.DestinoPedidoID;
	    
	    IF NEW.Modulo = 'Mesa' AND (ISNULL(NEW.MesaID) OR ISNULL(NEW.DestinoMesaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa de origem e destino deve ser informada";
	    ELSEIF NEW.Modulo = 'Comanda' AND (ISNULL(NEW.ComandaID) OR ISNULL(NEW.DestinoComandaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda de origem e destino deve ser informada";
	    ELSEIF NEW.Modulo = 'Mesa' AND (NOT ISNULL(NEW.ComandaID) OR NOT ISNULL(NEW.DestinoComandaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não deve ser informada em transferência de mesas";
	    ELSEIF NEW.Modulo = 'Comanda' AND (NOT ISNULL(NEW.MesaID) OR NOT ISNULL(NEW.DestinoMesaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não deve ser informada em transferência de comandas";
	    ELSEIF NEW.Tipo = 'Produto' AND ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A produto a ser transferido deve ser informado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível transferir de um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível transferir de um pedido finalizado";
	    ELSEIF _cancelado_dest = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível transferir para um pedido cancelado";
	    ELSEIF _estado_dest = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível transferir para um pedido finalizado";
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Estoque_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_BEFORE_INSERT` BEFORE INSERT ON `Estoque` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao VARCHAR(75);
		DECLARE _tipo VARCHAR(20);
		DECLARE _divisivel VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Tipo, Divisivel
		INTO _descricao, _tipo, _divisivel
		FROM Produtos
		WHERE ID = NEW.ProdutoID;
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir no estoque uma entrada cancelada";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Quantidade > 0 AND NOT ISNULL(NEW.TransacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A venda não pode adicionar o produto ao estoque";
	    ELSEIF NEW.Quantidade < 0 AND ISNULL(NEW.EntradaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Retirada do estoque sem informação de entrada";
		ELSEIF _tipo = 'Composicao' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é uma composição e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _tipo = 'Pacote' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é um pacote e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Estoque_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_AFTER_INSERT` AFTER INSERT ON `Estoque` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao VARCHAR(75);
		DECLARE _quantidade DOUBLE;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT SUM(Quantidade)
		INTO _quantidade
		FROM Estoque
		WHERE SetorID = NEW.SetorID AND ProdutoID = NEW.ProdutoID AND Cancelado = 'N';
		
		IF _quantidade <= -0.0005 AND NEW.Quantidade < 0 THEN
			SELECT Descricao
			INTO _descricao
			FROM Produtos
			WHERE ID = NEW.ProdutoID;
			SET _error_msg = CONCAT("Não há estoque para o produto '", _descricao, "'");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	    IF NOT ISNULL(NEW.TransacaoID) then
			UPDATE Produtos_Pedidos SET PrecoCompra = PrecoCompra + (-NEW.Quantidade * NEW.PrecoCompra) / Quantidade
				WHERE ID = NEW.TransacaoID;
	    END IF;
	END IF;
	END $$

	DELIMITER ;

	ALTER TABLE `Creditos` DROP FOREIGN KEY `FK_Creditos_Pagamentos_PagamentoID`;
	ALTER TABLE `Creditos` DROP INDEX `FK_Creditos_Pagamentos_PagamentoID_idx`;
	ALTER TABLE `Creditos` DROP `PagamentoID`;
	ALTER TABLE `Pagamentos` ADD `CreditoID` INT NULL DEFAULT NULL AFTER `ContaID`;
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Creditos_CreditoID_idx` (`CreditoID` ASC);
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Creditos_CreditoID`
	    FOREIGN KEY (`CreditoID`)
	    REFERENCES `Creditos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

	DELIMITER $$
	
	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_INSERT` BEFORE INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome, _estado VARCHAR(75);
		DECLARE _aberta, _cancelado VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _forma_count INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		SET _forma_count = IF(ISNULL(NEW.CartaoID), 0, 1) + IF(ISNULL(NEW.ChequeID), 0, 1) + IF(ISNULL(NEW.ContaID), 0, 1) + IF(ISNULL(NEW.CreditoID), 0, 1);
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento cancelado";
		ELSEIF _forma_count > 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Várias formas de pagamento em uma única transação";
		ELSEIF _forma_count = 0 AND (NEW.Parcelas > 0 OR NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento em uma forma de pagamento não parcelada";
		ELSEIF NEW.Total > -0.005 AND NEW.Total < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor total não informado";
		ELSEIF NEW.Parcelas < 0 OR (NEW.Parcelas = 0 AND (NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento incorreto";
		ELSEIF NEW.Parcelas = 0 AND NEW.Dinheiro > -0.005 AND NEW.Dinheiro < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro não informado";
		ELSEIF _forma_count = 1 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento não informado";
		ELSEIF NEW.Dinheiro + NEW.Parcelas * NEW.ValorParcela < NEW.Total - 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor totalizado menor que o total";
		ELSEIF NEW.Dinheiro > NEW.Total + 0.005 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro maior que o total";
	    ELSEIF NOT ISNULL(NEW.PedidoID) AND NOT ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Vários destinos para o pagamento";
	    ELSEIF ISNULL(NEW.PedidoID) AND ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum destino para o pagamento";
	    ELSEIF NOT ISNULL(NEW.PedidoID) THEN
			SELECT MovimentacaoID, Estado, Cancelado INTO _movimentacao_id, _estado, _cancelado FROM Pedidos
				WHERE ID = NEW.PedidoID;
			IF _cancelado = 'Y' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido cancelado";
			ELSEIF _estado = 'Finalizado' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido finalizado";
			ELSEIF NOT ISNULL(_movimentacao_id) AND _movimentacao_id <> NEW.MovimentacaoID THEN
				SET _error_msg = CONCAT("O pedido já está associado à movimentacão ", _movimentacao_id);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(_movimentacao_id) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF NEW.Dinheiro < 0 THEN
			SELECT SUM(Dinheiro) INTO _dinheiro FROM Pagamentos
				WHERE SetorID = NEW.SetorID AND MovimentacaoID = NEW.MovimentacaoID;
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_UPDATE` BEFORE UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pagamento que já foi cancelado";
		ELSEIF NEW.Total <> OLD.Total THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor total não pode ser alterado";
		ELSEIF NEW.Parcelas <> OLD.Parcelas OR NEW.ValorParcela <> OLD.ValorParcela THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O parcelamento não pode ser alterado";
		ELSEIF NEW.Dinheiro <> OLD.Dinheiro THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor em dinheiro não pode ser alterado";
		ELSEIF NEW.FormaPagtoID <> OLD.FormaPagtoID OR NOT (NEW.CartaoID <=> OLD.CartaoID) OR NOT (NEW.ChequeID <=> OLD.ChequeID) OR
			   NOT (NEW.ContaID <=> OLD.ContaID) OR NOT (NEW.CreditoID <=> OLD.CreditoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A forma de pagamento não pode ser alterada";
		ELSEIF NEW.DataHora <> OLD.DataHora THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data do pagamento não pode ser alterada";
		ELSEIF NOT (NEW.PedidoID <=> OLD.PedidoID) OR NOT (NEW.PagtoContaID <=> OLD.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O destino do pagamento não pode ser alterado";
		ELSEIF NEW.MovimentacaoID <> OLD.MovimentacaoID THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
	        IF NOT ISNULL(NEW.PedidoID) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Estoque_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_BEFORE_INSERT` BEFORE INSERT ON `Estoque` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao VARCHAR(75);
		DECLARE _tipo VARCHAR(20);
		DECLARE _divisivel VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Tipo, Divisivel
		INTO _descricao, _tipo, _divisivel
		FROM Produtos
		WHERE ID = NEW.ProdutoID;
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir no estoque uma entrada cancelada";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Quantidade > 0 AND NOT ISNULL(NEW.TransacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A venda não pode adicionar o produto ao estoque";
		ELSEIF _tipo = 'Composicao' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é uma composição e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _tipo = 'Pacote' THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' é um pacote e não pode ser ", IF(NEW.Quantidade < 0, 'removido do', 'inserido no'), " estoque");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Estoque_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Estoque_AFTER_INSERT` AFTER INSERT ON `Estoque` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
	    IF NOT ISNULL(NEW.TransacaoID) then
			UPDATE Produtos_Pedidos SET PrecoCompra = PrecoCompra + (-NEW.Quantidade * NEW.PrecoCompra) / Quantidade
				WHERE ID = NEW.TransacaoID;
	    END IF;
	END IF;
	END $$

	DELIMITER ;

}


/* Corrigido trigger dos pacotes iguais */
Update (Version: "1.4.0.2") {

	DELIMITER $$
	
	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_INSERT` BEFORE INSERT ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ProdutoID = NEW.ProdutoID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND PropriedadeID = NEW.PropriedadeID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND Selecionado = 'Y';
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_UPDATE` BEFORE UPDATE ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ProdutoID = NEW.ProdutoID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND PropriedadeID = NEW.PropriedadeID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID AND Selecionado = 'Y';
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND pc.ID <> OLD.ID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado permissão mudar de comanda */
/* Alterado campos de login para clientes */
/* Adicionado restrições de horários intercalados */
Update (Version: "1.6.0.0") {
	
	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(73, 3, "MudarDeComanda", "Permitir mudar os pedidos de uma comanda para comanda");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 73 as PermissaoID FROM Acessos WHERE PermissaoID = 6;

	ALTER TABLE `Clientes` ADD `Login` VARCHAR(50) NULL DEFAULT NULL AFTER `AcionistaID`;
	ALTER TABLE `Clientes` ADD `Senha` VARCHAR(40) NULL AFTER `Login`;
	ALTER TABLE `Clientes` ADD `Secreto` VARCHAR(40) NULL DEFAULT NULL AFTER `Slogan`;
	ALTER TABLE `Clientes` ADD UNIQUE INDEX `Login_UNIQUE` (`Login` ASC);
	ALTER TABLE `Clientes` ADD INDEX `Fone2_INDEX` (`Fone2` ASC);
	ALTER TABLE `Clientes` ADD UNIQUE INDEX `Secreto_UNIQUE` (`Secreto` ASC);

	UPDATE `Clientes` c
		RIGHT JOIN `Funcionarios` f ON f.ClienteID = c.ID
		SET c.Login = f.Login, c.Senha = f.Senha;

	ALTER TABLE `Funcionarios` DROP INDEX `Login_UNIQUE`;
	ALTER TABLE `Funcionarios` DROP `Login`;
	ALTER TABLE `Funcionarios` DROP `Senha`;

	ALTER TABLE `Promocoes` DROP INDEX `UK_Promocoes_Produto_Dia`;
	ALTER TABLE `Promocoes` CHANGE `Dia` `Inicio` INT NOT NULL;
	ALTER TABLE `Promocoes` ADD `Fim` INT NOT NULL AFTER `Inicio`;
	ALTER TABLE `Promocoes` ADD `Proibir` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Valor`;
	UPDATE `Promocoes` SET `Fim` = `Inicio` * 1440 + 1439, `Inicio` = `Inicio` * 1440;

	ALTER TABLE `Horarios` CHANGE `Dia` `Inicio` INT NOT NULL;
	ALTER TABLE `Horarios` DROP `Hora`;
	ALTER TABLE `Horarios` CHANGE `Duracao` `Fim` INT NOT NULL;

	DELIMITER $$
	
	DROP TRIGGER IF EXISTS `Promocoes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Promocoes_BEFORE_INSERT` BEFORE INSERT ON `Promocoes` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Promocoes WHERE ProdutoID = NEW.ProdutoID AND (
			(DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL NEW.Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)) OR
			(DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL     Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)));
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma promoção nesse intervalo";
		END IF;

	END IF;
	END
	$$


	DROP TRIGGER IF EXISTS `Promocoes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Promocoes_BEFORE_UPDATE` BEFORE UPDATE ON `Promocoes` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Promocoes WHERE ProdutoID = NEW.ProdutoID AND ID <> OLD.ID AND (
			(DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL NEW.Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)) OR
			(DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL     Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)));
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma promoção nesse intervalo";
		END IF;

	END IF;
	END
	$$


	DROP TRIGGER IF EXISTS `Horarios_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Horarios_BEFORE_INSERT` BEFORE INSERT ON `Horarios` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Horarios WHERE
			(DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL NEW.Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)) OR
			(DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL     Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE));
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe um horário nesse intervalo";
		END IF;

	END IF;
	END
	$$


	DROP TRIGGER IF EXISTS `Horarios_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Horarios_BEFORE_UPDATE` BEFORE UPDATE ON `Horarios` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Horarios WHERE ID <> OLD.ID AND (
			(DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL NEW.Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)) OR
			(DATE_ADD(CURDATE(), INTERVAL NEW.Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) BETWEEN 
			 DATE_ADD(CURDATE(), INTERVAL     Inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL     Fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)));
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe um horário nesse intervalo";
		END IF;

	END IF;
	END
	$$

	DROP TRIGGER IF EXISTS `Movimentacoes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Movimentacoes_BEFORE_INSERT` BEFORE INSERT ON `Movimentacoes` FOR EACH ROW
	BEGIN
	    DECLARE _error_msg VARCHAR(255);
		DECLARE _existe INT DEFAULT 0;
	    DECLARE _aberta, _ativo, _f_ativo VARCHAR(1);
	    DECLARE _descricao, _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT 1 INTO _existe FROM Movimentacoes WHERE SessaoID = NEW.SessaoID AND CaixaID = NEW.CaixaID AND Aberta = 'Y' AND 
			FuncionarioAberturaID = NEW.FuncionarioAberturaID;
		SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
		SELECT Ativo, Descricao INTO _ativo, _descricao FROM Caixas WHERE ID = NEW.CaixaID;
		SELECT f.Ativo, cf.Login INTO _f_ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioAberturaID;
		IF _existe = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe um caixa aberto para o funcionário informado";
		ELSEIF _aberta = 'N' THEN
			SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _descricao, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _f_ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser aberto com status de fechado";
		ELSEIF NOT ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário de fechamento do caixa não pode ser informado agora";
		ELSEIF NOT ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento do caixa não pode ser informada agora";
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo VARCHAR(1);
		DECLARE _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Preco < 0 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Preco < 0.01 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser nulo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto como cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto em um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto em um pedido finalizado";
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Estado <> 'Adicionado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto deve ser inserido com estado de 'Adicionado'";
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado = 'N' AND NOT ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto não visualizado com a data e a hora";
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado, _nome VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo, _aberta VARCHAR(1);
		DECLARE _login VARCHAR(50);
	    DECLARE _movimentacao_id INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT MovimentacaoID, Cancelado, Estado INTO _movimentacao_id, _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NOT ISNULL(_movimentacao_id) THEN
			SELECT mv.Aberta, c.Descricao INTO _aberta, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = _movimentacao_id;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", _movimentacao_id, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto de um pedido finalizado";
	    ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Preco < 0 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Preco < 0.01 THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser nulo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	    IF NEW.Cancelado = 'Y' THEN
			DELETE FROM Estoque WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$

	DELIMITER ;
	
}

/* Adicionado cancelamento de conta ao cancelar pagamento */
/* Adicionado data de compensação do pagamento */
/* Informado quem imprimiu a conta do pedido */
Update (Version: "1.6.2.0") {
	ALTER TABLE `Clientes` ADD `DataAtualizacao` DATETIME NULL DEFAULT NULL AFTER `Imagem`;
	UPDATE `Clientes` SET `DataAtualizacao` = `DataCadastro`;
	ALTER TABLE `Clientes` MODIFY `DataAtualizacao` DATETIME NOT NULL;

	ALTER TABLE `Creditos` ADD `FuncionarioID` INT NOT NULL AFTER `Detalhes`;
	ALTER TABLE `Creditos` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `FuncionarioID`;
	ALTER TABLE `Creditos` ADD INDEX `FK_Creditos_Funcionarios_FuncionarioID_idx` (`FuncionarioID` ASC);
	ALTER TABLE `Creditos` ADD CONSTRAINT `FK_Creditos_Funcionarios_FuncionarioID`
		FOREIGN KEY (`FuncionarioID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Composicoes` ADD `Ativa` ENUM('Y', 'N') NULL DEFAULT 'Y' AFTER `Valor`;
	ALTER TABLE `Pacotes` ADD `Visivel` ENUM('Y', 'N') NULL DEFAULT 'Y' AFTER `Selecionado`;
	UPDATE `Formas_Pagto` fp 
		LEFT JOIN `Formas_Pagto` fpo ON fpo.Tipo = fp.Tipo AND fpo.ComEntrada = 'N' AND fpo.Ativa = 'Y'
		SET fp.Ativa = 'N' 
		WHERE fp.ComEntrada = 'Y' AND fp.Tipo <> 'Dinheiro' AND NOT ISNULL(fpo.ID);
	ALTER TABLE `Formas_Pagto` DROP `ComEntrada`;
	INSERT INTO `Formas_Pagto` (Descricao, Tipo, Parcelado, MinParcelas, MaxParcelas, ParcelasSemJuros, Juros, Ativa) VALUES 
		("Crédito", 'Credito', 'N', NULL, NULL, NULL, NULL, 'Y')
		ON DUPLICATE KEY UPDATE
		Descricao = Descricao;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(74, 7, "RelatorioAuditoria", "Permitir visualizar o relatório de auditoria");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 74 as PermissaoID FROM Acessos WHERE PermissaoID = 31;

	SET @OLD_DISABLE_TRIGGERS=@DISABLE_TRIGGERS, @DISABLE_TRIGGERS=1;

	ALTER TABLE `Contas` ADD `Cancelada` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `AnexoCaminho`;
	UPDATE `Contas` c 
		LEFT JOIN Pagamentos pg ON pg.ContaID = c.ID AND pg.Cancelado = 'Y'
		SET `Cancelada` = 'Y'
		WHERE NOT ISNULL(pg.ID) AND c.Cancelada = 'N';
	UPDATE `Pagamentos` pg 
		LEFT JOIN Contas c ON c.ID = pg.PagtoContaID AND c.Cancelada = 'Y'
		SET `Cancelado` = 'Y'
		WHERE NOT ISNULL(c.ID) AND pg.Cancelado = 'N';

	ALTER TABLE `Pagamentos` ADD `Taxas` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `ValorParcela`;
	ALTER TABLE `Pagamentos` ADD `Ativo` ENUM('Y', 'N') NOT NULL DEFAULT 'Y' AFTER `Cancelado`;
	ALTER TABLE `Pagamentos` ADD `DataCompensacao` DATETIME NULL DEFAULT NULL AFTER `Ativo`;
	UPDATE `Pagamentos` SET `DataCompensacao` = `DataHora`;
	ALTER TABLE `Pagamentos` MODIFY `DataCompensacao` DATETIME NOT NULL;
	UPDATE `Pagamentos` pg 
		LEFT JOIN Cartoes ct ON ct.ID = pg.CartaoID
		SET `DataCompensacao` = DATE_ADD(`DataCompensacao`, INTERVAL ct.DiasRepasse DAY), Taxas = (ct.Transacao + ct.Taxa / 100 * (pg.Total - pg.Dinheiro))
		WHERE NOT ISNULL(pg.CartaoID);

	INSERT INTO `Pagamentos` (SetorID, MovimentacaoID, SessaoID, CaixaID, FuncionarioID, 
		FormaPagtoID, PedidoID, Total, Dinheiro, Parcelas, ValorParcela, Ativo, DataCompensacao, DataHora)
		(SELECT (SELECT SetorID FROM Dispositivos WHERE Tipo = 'Computador' LIMIT 1) as SetorID,
		COALESCE(MovimentacaoID, (SELECT ID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as MovimentacaoID, p.SessaoID,
		COALESCE(CaixaID, (SELECT CaixaID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as CaixaID, p.FuncionarioID, 
		(SELECT ID FROM Formas_Pagto WHERE Tipo = 'Dinheiro' LIMIT 1) as FormaPagtoID, p.ID as PedidoID, 
		p.Dinheiro as Total, p.Dinheiro, 0 as Parcelas, 0 as ValorParcela, 'N' as Ativo, p.DataCriacao as DataCompensacao, p.DataCriacao as DataHora
		FROM Pedidos p
		WHERE p.Tipo = 'Entrega' AND p.Estado = 'Ativo' AND p.Cancelado = 'N' AND p.Dinheiro > 0.005);
	INSERT INTO `Pagamentos` (SetorID, MovimentacaoID, SessaoID, CaixaID, FuncionarioID, 
		FormaPagtoID, PedidoID, CartaoID, Total, Dinheiro, Parcelas, ValorParcela, Taxas, Ativo, DataCompensacao, DataHora)
		(SELECT (SELECT SetorID FROM Dispositivos WHERE Tipo = 'Computador' LIMIT 1) as SetorID,
		COALESCE(MovimentacaoID, (SELECT ID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as MovimentacaoID, p.SessaoID,
		COALESCE(CaixaID, (SELECT CaixaID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as CaixaID, p.FuncionarioID, 
		(SELECT ID FROM Formas_Pagto WHERE Tipo = 'Cartao' LIMIT 1) as FormaPagtoID, p.ID as PedidoID, p.CartaoID,
		((SELECT SUM(pp.Preco * pp.Quantidade * (100 + pp.Porcentagem) / 100) FROM Produtos_Pedidos pp WHERE pp.PedidoID = p.ID AND pp.Cancelado = 'N') + 
		 (SELECT SUM(tx.Valor * tx.Quantidade) FROM Taxas_Servicos tx WHERE tx.PedidoID = p.ID)) as Total, 
		0 as Dinheiro, 1 as Parcelas, 
		((SELECT SUM(pp.Preco * pp.Quantidade * (100 + pp.Porcentagem) / 100) FROM Produtos_Pedidos pp WHERE pp.PedidoID = p.ID AND pp.Cancelado = 'N') + 
		 (SELECT SUM(tx.Valor * tx.Quantidade) FROM Taxas_Servicos tx WHERE tx.PedidoID = p.ID)) as ValorParcela,
		 (ct.Transacao + ct.Taxa / 100 * ((SELECT SUM(pp.Preco * pp.Quantidade * (100 + pp.Porcentagem) / 100) FROM Produtos_Pedidos pp WHERE pp.PedidoID = p.ID AND pp.Cancelado = 'N') + 
		 (SELECT SUM(tx.Valor * tx.Quantidade) FROM Taxas_Servicos tx WHERE tx.PedidoID = p.ID))) as Taxas, 'N' as Ativo, DATE_ADD(p.DataCriacao, INTERVAL ct.DiasRepasse DAY) as DataCompensacao, p.DataCriacao as DataHora
		FROM Pedidos p
		LEFT JOIN Cartoes ct ON ct.ID = p.CartaoID
		WHERE p.Tipo = 'Entrega' AND p.Estado = 'Ativo' AND p.Cancelado = 'N' AND NOT ISNULL(p.CartaoID));
	INSERT INTO `Pagamentos` (SetorID, MovimentacaoID, SessaoID, CaixaID, FuncionarioID, 
		FormaPagtoID, PedidoID, Total, Dinheiro, Parcelas, ValorParcela, Ativo, DataCompensacao, DataHora)
		(SELECT (SELECT SetorID FROM Dispositivos WHERE Tipo = 'Computador' LIMIT 1) as SetorID,
		COALESCE(MovimentacaoID, (SELECT ID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as MovimentacaoID, p.SessaoID,
		COALESCE(CaixaID, (SELECT CaixaID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as CaixaID, p.FuncionarioID, 
		(SELECT ID FROM Formas_Pagto WHERE Tipo = 'Dinheiro' LIMIT 1) as FormaPagtoID, p.ID as PedidoID, 
		-p.Dinheiro as Total, -p.Dinheiro, 0 as Parcelas, 0 as ValorParcela, 'N' as Ativo, p.DataCriacao as DataCompensacao, p.DataCriacao as DataHora
		FROM Pedidos p
		WHERE p.Tipo = 'Entrega' AND p.Estado = 'Entrega' AND p.Cancelado = 'N' AND p.Dinheiro > 0.005);
	INSERT INTO `Pagamentos` (SetorID, MovimentacaoID, SessaoID, CaixaID, FuncionarioID, 
		FormaPagtoID, PagtoContaID, Total, Dinheiro, Parcelas, ValorParcela, Detalhes, Ativo, DataCompensacao, DataHora)
		(SELECT (SELECT SetorID FROM Dispositivos WHERE Tipo = 'Computador' LIMIT 1) as SetorID,
		COALESCE(MovimentacaoID, (SELECT ID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as MovimentacaoID, p.SessaoID,
		COALESCE(CaixaID, (SELECT CaixaID FROM Movimentacoes WHERE Aberta = 'Y' LIMIT 1)) as CaixaID, p.EntregadorID as FuncionarioID, 
		(SELECT ID FROM Formas_Pagto WHERE Tipo = 'Dinheiro' LIMIT 1) as FormaPagtoID, 1 as PagtoContaID, 
		p.Dinheiro as Total, p.Dinheiro, 0 as Parcelas, 0 as ValorParcela, 'Correção de troco de entrega' as Detalhes, 'Y' as Ativo, p.DataCriacao as DataCompensacao, p.DataCriacao as DataHora
		FROM Pedidos p
		WHERE p.Tipo = 'Entrega' AND p.Estado = 'Entrega' AND p.Cancelado = 'N' AND p.Dinheiro > 0.005);
	ALTER TABLE `Pedidos` DROP FOREIGN KEY `FK_Pedidos_Formas_Pagto_FormaPagtoID`;
	ALTER TABLE `Pedidos` DROP FOREIGN KEY `FK_Pedidos_Cartoes_CartaoID`;
	ALTER TABLE `Pedidos` DROP INDEX `FK_Pedidos_Formas_Pagto_FormaPagtoID_idx`;
	ALTER TABLE `Pedidos` DROP INDEX `FK_Pedidos_Cartoes_CartaoID_idx`;
	ALTER TABLE `Pedidos` DROP `FormaPagtoID`;
	ALTER TABLE `Pedidos` DROP `CartaoID`;
	ALTER TABLE `Pedidos` DROP `Dinheiro`;
	ALTER TABLE `Pedidos` ADD `FechadorID` INT NULL DEFAULT NULL AFTER `Descricao`;
	ALTER TABLE `Pedidos` ADD `DataImpressao` DATETIME NULL DEFAULT NULL AFTER `FechadorID`;
	ALTER TABLE `Pedidos` ADD INDEX `FK_Pedidos_Funcionarios_FechadorID_idx` (`FechadorID` ASC);
	ALTER TABLE `Pedidos` ADD CONSTRAINT `FK_Pedidos_Funcionarios_FechadorID`
		FOREIGN KEY (`FechadorID`)
		REFERENCES `Funcionarios` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	SET @DISABLE_TRIGGERS=@OLD_DISABLE_TRIGGERS;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Movimentacoes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Movimentacoes_BEFORE_UPDATE` BEFORE UPDATE ON `Movimentacoes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _abertos INT;
		DECLARE _desativados INT;
		DECLARE _outros_abertos INT;
		DECLARE _outros_caixas INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT COUNT(ID) INTO _abertos FROM Pedidos WHERE MovimentacaoID = OLD.ID AND Estado <> 'Finalizado' AND Cancelado = 'N';
		SELECT COUNT(ID) INTO _desativados FROM Pagamentos WHERE MovimentacaoID = OLD.ID AND Ativo = 'N' AND Cancelado = 'N';
		SELECT COUNT(ID) INTO _outros_abertos FROM Pedidos WHERE SessaoID = OLD.SessaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
		SELECT COUNT(ID) INTO _outros_caixas FROM Movimentacoes WHERE ID <> OLD.ID AND Aberta = 'Y';
	    
		IF NEW.Aberta = 'N' AND _outros_abertos = 1 AND _outros_caixas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pedido não finalizado";
		ELSEIF NEW.Aberta = 'N' AND _outros_abertos > 1 AND _outros_caixas = 0 THEN
			SET _error_msg = CONCAT("Ainda há ", _outros_abertos, " pedidos não finalizados");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'N' AND _abertos = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pedido não finalizado";
		ELSEIF NEW.Aberta = 'N' AND _abertos > 1 THEN
			SET _error_msg = CONCAT("Ainda há ", _abertos, " pedidos não finalizados");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Aberta = 'N' AND _desativados = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
		ELSEIF NEW.Aberta = 'N' AND _desativados > 1 THEN
			SET _error_msg = CONCAT("Ainda há ", _desativados, " pagamentos não finalizados");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Aberta = 'Y' AND OLD.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser reaberto";
	    ELSEIF NEW.Aberta = 'N' AND ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que está fechando o caixa deve ser informado";
	    ELSEIF NEW.Aberta = 'Y' AND NOT ISNULL(NEW.FuncionarioFechamentoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que fechará o caixa não pode ser informado agora";
	    ELSEIF NEW.Aberta = 'N' AND ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento deve ser informada";
	    ELSEIF NEW.Aberta = 'Y' AND NOT ISNULL(NEW.DataFechamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de fechamento não pode ser informada agora";
	    ELSEIF NEW.CaixaID <> OLD.CaixaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não pode ser alterado";
	    ELSEIF NEW.SessaoID <> OLD.SessaoID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A sessão do caixa não pode ser alterada";
	    ELSEIF NEW.FuncionarioAberturaID <> OLD.FuncionarioAberturaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário que abriu o caixa não pode ser alterado";
	    ELSEIF NEW.DataAbertura <> OLD.DataAbertura THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de abertura do caixa não pode ser alterada";
	    ELSEIF OLD.Aberta = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Este caixa já foi fechado e não pode mais ser alterado";
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
	        ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Contas_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Contas_AFTER_UPDATE` AFTER UPDATE ON `Contas` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF OLD.Cancelada = 'N' AND NEW.Cancelada = 'Y' THEN
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PagtoContaID = NEW.ID AND Cancelado = 'N';
		END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_INSERT` BEFORE INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome, _estado VARCHAR(75);
		DECLARE _aberta, _cancelado VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _forma_count INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		SET _forma_count = IF(ISNULL(NEW.CartaoID), 0, 1) + IF(ISNULL(NEW.ChequeID), 0, 1) + IF(ISNULL(NEW.ContaID), 0, 1) + IF(ISNULL(NEW.CreditoID), 0, 1);
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento cancelado";
		ELSEIF _forma_count > 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Várias formas de pagamento em uma única transação";
		ELSEIF _forma_count = 0 AND (NEW.Parcelas > 0 OR NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento em uma forma de pagamento não parcelada";
		ELSEIF NEW.Total > -0.005 AND NEW.Total < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor total não informado";
		ELSEIF NEW.Parcelas < 0 OR (NEW.Parcelas = 0 AND (NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento incorreto";
		ELSEIF NEW.Parcelas = 0 AND NEW.Dinheiro > -0.005 AND NEW.Dinheiro < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro não informado";
		ELSEIF _forma_count = 1 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento não informado";
		ELSEIF NEW.Dinheiro + NEW.Parcelas * NEW.ValorParcela < NEW.Total - 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor totalizado menor que o total";
		ELSEIF NEW.Dinheiro > NEW.Total + 0.005 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor em dinheiro maior que o total";
	    ELSEIF NOT ISNULL(NEW.PedidoID) AND NOT ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Vários destinos para o pagamento";
	    ELSEIF ISNULL(NEW.PedidoID) AND ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum destino para o pagamento";
	    ELSEIF NOT ISNULL(NEW.ContaID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um pagamento inativo em conta";
	    ELSEIF NOT ISNULL(NEW.CreditoID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um pagamento inativo em crédito";
	    ELSEIF NOT ISNULL(NEW.PedidoID) THEN
			SELECT MovimentacaoID, Estado, Cancelado INTO _movimentacao_id, _estado, _cancelado FROM Pedidos
				WHERE ID = NEW.PedidoID;
			IF _cancelado = 'Y' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido cancelado";
			ELSEIF _estado = 'Finalizado' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido finalizado";
			ELSEIF NOT ISNULL(_movimentacao_id) AND _movimentacao_id <> NEW.MovimentacaoID THEN
				SET _error_msg = CONCAT("O pedido já está associado à movimentacão ", _movimentacao_id);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(_movimentacao_id) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF NEW.Dinheiro < 0 AND NEW.Ativo = 'Y' THEN
			SELECT SUM(Dinheiro) INTO _dinheiro FROM Pagamentos
				WHERE SetorID = NEW.SetorID AND MovimentacaoID = NEW.MovimentacaoID AND Ativo = 'Y' AND Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_UPDATE` BEFORE UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		IF _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _caixa_id <> NEW.CaixaID THEN
			SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _sessao_id <> NEW.SessaoID THEN
			SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pagamento que já foi cancelado";
		ELSEIF NEW.Total <> OLD.Total THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor total não pode ser alterado";
		ELSEIF NEW.Parcelas <> OLD.Parcelas OR NEW.ValorParcela <> OLD.ValorParcela THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O parcelamento não pode ser alterado";
		ELSEIF NEW.Dinheiro <> OLD.Dinheiro THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor em dinheiro não pode ser alterado";
		ELSEIF NEW.FormaPagtoID <> OLD.FormaPagtoID OR NOT (NEW.CartaoID <=> OLD.CartaoID) OR NOT (NEW.ChequeID <=> OLD.ChequeID) OR
			   NOT (NEW.ContaID <=> OLD.ContaID) OR NOT (NEW.CreditoID <=> OLD.CreditoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A forma de pagamento não pode ser alterada";
		ELSEIF NEW.DataHora <> OLD.DataHora THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data do pagamento não pode ser alterada";
		ELSEIF NOT (NEW.PedidoID <=> OLD.PedidoID) OR NOT (NEW.PagtoContaID <=> OLD.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O destino do pagamento não pode ser alterado";
	    ELSEIF NOT ISNULL(NEW.ContaID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um pagamento em conta para inativo";
	    ELSEIF NOT ISNULL(NEW.CreditoID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um pagamento em crédito para inativo";
		ELSEIF NEW.MovimentacaoID <> OLD.MovimentacaoID THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
	        IF NOT ISNULL(NEW.PedidoID) THEN
				UPDATE Pedidos SET SessaoID = NEW.SessaoID, MovimentacaoID = NEW.MovimentacaoID, CaixaID = NEW.CaixaID 
					WHERE ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_UPDATE` AFTER UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF NEW.Dinheiro < 0 AND OLD.Ativo = 'N' AND NEW.Ativo = 'Y' THEN
			SELECT SUM(Dinheiro) INTO _dinheiro FROM Pagamentos
				WHERE SetorID = NEW.SetorID AND MovimentacaoID = NEW.MovimentacaoID AND Ativo = 'Y' AND Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
	        END IF;
	    END IF;
		IF (OLD.Cancelado = 'N' OR OLD.ContaID <> NEW.ContaID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ContaID) THEN
			UPDATE Contas SET Cancelada = 'Y' WHERE ID = NEW.ContaID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.CreditoID <> NEW.CreditoID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.CreditoID) THEN
			UPDATE Creditos SET Cancelado = 'Y' WHERE ID = NEW.CreditoID;
		END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Creditos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Creditos_BEFORE_INSERT` BEFORE INSERT ON `Creditos` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O crédito não pode ser cadastrado já cancelado";
		END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Creditos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Creditos_AFTER_INSERT` AFTER INSERT ON `Creditos` FOR EACH ROW
	BEGIN
	    DECLARE _total DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN

	    IF NEW.Valor < 0 THEN
			SELECT SUM(Valor) INTO _total FROM Creditos
				WHERE ClienteID = NEW.ClienteID AND Cancelado = 'N';
			IF _total < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não possui créditos suficientes";
	        END IF;
	    END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Creditos_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Creditos_AFTER_UPDATE` AFTER UPDATE ON `Creditos` FOR EACH ROW
	BEGIN
	    DECLARE _total DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    IF NEW.Valor < 0 OR (OLD.Cancelado = 'N' AND NEW.Cancelado = 'Y') THEN
			SELECT SUM(Valor) INTO _total FROM Creditos
				WHERE ClienteID = NEW.ClienteID AND Cancelado = 'N';
			IF _total < 0 AND NEW.Valor < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não possui créditos suficientes";
			ELSEIF _total < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cancelamento não pode negativar os créditos";
	        END IF;
	    END IF;
		IF OLD.Valor > 0 AND NEW.ClienteID <> OLD.ClienteID THEN
			SELECT SUM(Valor) INTO _total FROM Creditos
				WHERE ClienteID = OLD.ClienteID AND Cancelado = 'N';
			IF _total < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A transferência não pode negativar os créditos do cliente";
			END IF;
		END IF;
	    
	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado pontuação para os funcionários */
Update (Version: "1.6.3.0") {
	ALTER TABLE `Funcionarios` ADD `Pontuacao` INT NOT NULL DEFAULT 0 AFTER `LinguagemID`;
	ALTER TABLE `Composicoes` MODIFY `Ativa` ENUM('Y', 'N') NOT NULL DEFAULT 'Y';
	ALTER TABLE `Pacotes` MODIFY `Visivel` ENUM('Y', 'N') NOT NULL DEFAULT 'Y';
}

/* Corrigido pedido simultãneo para balcão, mesmo com funcionário diferente */
Update (Version: "1.6.4.0") {

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
	        ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		ELSE
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível cadastrar um pedido como cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado NOT IN ('Ativo', 'Agendado') THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não está ativo ou não está agendado";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NOT ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser entregue ao mesmo instante que é criado";
	    ELSEIF NOT ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode estar concluído ao ser criado";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF ISNULL(NEW.CaixaID) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O id do caixa não foi informado";
	        ELSEIF _caixa_id <> NEW.CaixaID THEN
				SET _error_msg = CONCAT("O caixa de id ", NEW.CaixaID, " não faz parte da movimentação ", NEW.MovimentacaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
		ELSEIF ISNULL(NEW.MovimentacaoID) AND NOT ISNULL(NEW.CaixaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa não pode ser informado sem a movimentação";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND (ISNULL(NEW.MovimentacaoID) OR ISNULL(NEW.CaixaID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada neste tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$

	DELIMITER ;
}


/* Adicionado permissão para relatórios e cadastros */
Update (Version: "1.6.5.0") {

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(75, 7, "RelatorioConsumidor", "Permitir visualizar o relatório de vendas por cliente"),
		(76, 7, "RelatorioCreditos", "Permitir visualizar o relatório de créditos de clientes");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 75 as PermissaoID FROM Acessos WHERE PermissaoID = 23;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 76 as PermissaoID FROM Acessos WHERE PermissaoID = 47;

}

/* Adicionado carteiras financeiras ou bancárias */
/* Adicionado setor de estoque do produto */
/* Unificado itens de serviços e produtos */
/* Melhorado tabelas de pagamento e pedidos */
Update (Version: "1.7.0.0") {

	CREATE TABLE IF NOT EXISTS `Carteiras` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Tipo` ENUM('Bancaria', 'Financeira') NOT NULL,
	  `BancoID` INT NULL DEFAULT NULL,
	  `Descricao` VARCHAR(100) NOT NULL,
	  `Conta` VARCHAR(100) NULL DEFAULT NULL,
	  `Agencia` VARCHAR(200) NULL DEFAULT NULL,
	  `Ativa` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Carteiras_Bancos_BancoID_idx` (`BancoID` ASC),
	  CONSTRAINT `FK_Carteiras_Bancos_BancoID`
	    FOREIGN KEY (`BancoID`)
	    REFERENCES `Bancos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;
	INSERT INTO `Carteiras` (ID, Tipo, Descricao, Ativa) VALUES
		(1, 'Financeira', 'Caixa da empresa', 'Y');

	ALTER TABLE `Formas_Pagto` MODIFY `Tipo` ENUM('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia') NOT NULL AFTER `ID`;
	ALTER TABLE `Formas_Pagto` ADD `CarteiraID` INT NULL DEFAULT NULL AFTER `Tipo`;
	ALTER TABLE `Formas_Pagto` ADD INDEX `FK_Formas_Pagto_Carteiras_CarteiraID_idx` (`CarteiraID` ASC);
	ALTER TABLE `Formas_Pagto` ADD CONSTRAINT `FK_Formas_Pagto_Carteiras_CarteiraID`
		FOREIGN KEY (`CarteiraID`)
		REFERENCES `Carteiras` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Cartoes` ADD `CarteiraID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Cartoes` ADD INDEX `FK_Cartoes_Carteiras_CarteiraID_idx` (`CarteiraID` ASC);
	ALTER TABLE `Cartoes` ADD CONSTRAINT `FK_Cartoes_Carteiras_CarteiraID`
		FOREIGN KEY (`CarteiraID`)
		REFERENCES `Carteiras` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pedidos` DROP FOREIGN KEY `FK_Pedidos_Caixas_CaixaID`;
	ALTER TABLE `Pedidos` DROP INDEX `FK_Pedidos_Caixas_CaixaID_idx`;
	ALTER TABLE `Pedidos` DROP `CaixaID`;
	ALTER TABLE `Pedidos` ADD INDEX `IDX_Pedidos_ComandaID_Estado` (`ComandaID` ASC, `Estado` ASC);
	ALTER TABLE `Setores` DROP `Financeiro`;
	ALTER TABLE `Produtos` ADD `SetorEstoqueID` INT NULL DEFAULT NULL AFTER `UnidadeID`;
	ALTER TABLE `Produtos` ADD INDEX `FK_Produtos_Setores_SetorEstoqueID_idx` (`SetorEstoqueID` ASC);
	ALTER TABLE `Produtos` ADD CONSTRAINT `FK_Produtos_Setores_SetorEstoqueID`
		FOREIGN KEY (`SetorEstoqueID`)
		REFERENCES `Setores` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Produtos_Pedidos` MODIFY `ProdutoID` INT NULL DEFAULT NULL;
	ALTER TABLE `Produtos_Pedidos` ADD `ServicoID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Produtos_Pedidos` ADD INDEX `FK_ProdPed_Servicos_ServicoID_idx` (`ServicoID` ASC);
	ALTER TABLE `Produtos_Pedidos` ADD CONSTRAINT `FK_ProdPed_Servicos_ServicoID`
		FOREIGN KEY (`ServicoID`)
		REFERENCES `Servicos` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_Pagamentos_Setores_SetorID`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_Pagamentos_Setores_SetorID_idx`;
	ALTER TABLE `Pagamentos` DROP `SetorID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_Pagamentos_Sessoes_SessaoID`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_Pagamentos_Sessoes_SessaoID_idx`;
	ALTER TABLE `Pagamentos` DROP `SessaoID`;
	ALTER TABLE `Pagamentos` DROP FOREIGN KEY `FK_Pagamentos_Caixas_CaixaID`;
	ALTER TABLE `Pagamentos` DROP INDEX `FK_Pagamentos_Caixas_CaixaID_idx`;
	ALTER TABLE `Pagamentos` DROP `CaixaID`;
	ALTER TABLE `Pagamentos` ADD `CarteiraID` INT NOT NULL DEFAULT 1 AFTER `ID`;
	ALTER TABLE `Pagamentos` MODIFY `CarteiraID` INT NOT NULL;
	ALTER TABLE `Pagamentos` ADD INDEX `FK_Pagamentos_Carteiras_CarteiraID_idx` (`CarteiraID` ASC);
	ALTER TABLE `Pagamentos` ADD CONSTRAINT `FK_Pagamentos_Carteiras_CarteiraID`
		FOREIGN KEY (`CarteiraID`)
		REFERENCES `Carteiras` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	ALTER TABLE `Pagamentos` MODIFY `MovimentacaoID` INT NULL DEFAULT NULL;

	SET @OLD_DISABLE_TRIGGERS=@DISABLE_TRIGGERS, @DISABLE_TRIGGERS=1;
	INSERT INTO `Produtos_Pedidos` (PedidoID, FuncionarioID, ServicoID, Preco, Quantidade, PrecoVenda, Detalhes, Cancelado, DataHora)
		SELECT tx.PedidoID, tx.OperadorID as FuncionarioID, tx.ServicoID, tx.Valor as Preco, tx.Quantidade, tx.Valor as PrecoVenda, 
		tx.Detalhes, p.Cancelado, p.DataCriacao as DataHora FROM `Taxas_Servicos` tx
		LEFT JOIN Pedidos p ON p.ID = tx.PedidoID
		WHERE tx.Valor < -0.005 OR tx.Valor > 0.005;
	INSERT INTO `Pagamentos` (CarteiraID, MovimentacaoID, FuncionarioID, FormaPagtoID, PedidoID,
		PagtoContaID, Total, Dinheiro, Parcelas, ValorParcela, Detalhes, Cancelado, Ativo, DataCompensacao, DataHora)
		SELECT CarteiraID, MovimentacaoID, FuncionarioID, (SELECT ID FROM Formas_Pagto WHERE Tipo = 'Dinheiro' LIMIT 1) as FormaPagtoID, 
		PedidoID, PagtoContaID, Dinheiro as Total, 0 as Dinheiro, 0 as Parcelas, 0 as ValorParcela, Detalhes, Cancelado, Ativo, DataCompensacao, DataHora 
		FROM Pagamentos WHERE Dinheiro <> 0 AND Dinheiro <> Total;
	UPDATE `Pagamentos` SET Total = Total - Dinheiro WHERE Dinheiro <> 0 AND Dinheiro <> Total;
	SET @DISABLE_TRIGGERS=@OLD_DISABLE_TRIGGERS;
	ALTER TABLE `Pagamentos` DROP `Dinheiro`;
	DROP TABLE `Taxas_Servicos`;

	ALTER TABLE `Listas_Produtos` MODIFY `Quantidade` DOUBLE NOT NULL DEFAULT 0;
	ALTER TABLE `Listas_Produtos` MODIFY `Preco` DECIMAL(19,4) NOT NULL DEFAULT 0;
	ALTER TABLE `Listas_Produtos` ADD `Comprado` ENUM('Y', 'N') NULL DEFAULT 'N' AFTER `Observacoes`;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(77, 6, "CadastroCarteiras", "Permitir cadastrar carteiras e contas bancárias"),
		(78, 7, "RelatorioFluxo", "Permitir visualizar o relatório de fluxo de caixa"),
		(79, 4, "TransferirValores", "Permitir transferir dinheiro de um caixa para outro");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 77 as PermissaoID FROM Acessos WHERE PermissaoID = 36;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 78 as PermissaoID FROM Acessos WHERE PermissaoID = 63;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 79 as PermissaoID FROM Acessos WHERE PermissaoID = 13;

	CREATE TABLE IF NOT EXISTS `Patrimonios` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `EmpresaID` INT NOT NULL,
	  `FornecedorID` INT NULL DEFAULT NULL,
	  `Numero` VARCHAR(45) NOT NULL,
	  `Descricao` VARCHAR(200) NOT NULL,
	  `Quantidade` DOUBLE NOT NULL,
	  `Altura` DOUBLE NOT NULL DEFAULT 0,
	  `Largura` DOUBLE NOT NULL DEFAULT 0,
	  `Comprimento` DOUBLE NOT NULL DEFAULT 0,
	  `Estado` ENUM('Novo', 'Conservado', 'Ruim') NOT NULL DEFAULT 'Novo',
	  `Custo` DECIMAL(19,4) NOT NULL DEFAULT 0,
	  `Valor` DECIMAL(19,4) NOT NULL DEFAULT 0,
	  `Ativo` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
	  `ImagemAnexada` VARCHAR(200) NULL DEFAULT NULL,
	  `DataAtualizacao` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Numero_Estado_UNIQUE` (`Numero` ASC, `Estado` ASC),
	  INDEX `FK_Patrimonios_Fornecedores_FornecedorID_idx` (`FornecedorID` ASC),
	  INDEX `FK_Patrimonios_Clientes_EmpresaID_idx` (`EmpresaID` ASC),
	  CONSTRAINT `FK_Patrimonios_Fornecedores_FornecedorID`
	    FOREIGN KEY (`FornecedorID`)
	    REFERENCES `Fornecedores` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Patrimonios_Clientes_EmpresaID`
	    FOREIGN KEY (`EmpresaID`)
	    REFERENCES `Clientes` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(80, 6, "CadastroPatrimonio", "Permitir cadastrar e atualizar a quantidade de bens de uma empresa"),
		(81, 7, "RelatorioPatrimonio", "Permitir visualizar a lista de bens de uma empresa");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 80 as PermissaoID FROM Acessos WHERE PermissaoID = 17;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 81 as PermissaoID FROM Acessos WHERE PermissaoID = 17;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Formas_Pagto_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Formas_Pagto_BEFORE_UPDATE` BEFORE UPDATE ON `Formas_Pagto` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT COUNT(ID) INTO _count FROM Pagamentos 
			WHERE FormaPagtoID = OLD.ID;
		IF _count > 0 AND OLD.Tipo <> NEW.Tipo THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A forma de pagamento já foi utilizada e não pode mais ser alterada";
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		ELSE
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível cadastrar um pedido como cancelado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado NOT IN ('Ativo', 'Agendado') THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não está ativo ou não está agendado";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NOT ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser entregue ao mesmo instante que é criado";
	    ELSEIF NOT ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode estar concluído ao ser criado";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.LocalizacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O endereço de entrega não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto ou serviço foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo VARCHAR(1);
		DECLARE _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou serviço deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Apenas um produto ou serviço deve ser informado por vez";
	    ELSEIF NOT ISNULL(NEW.ServicoID) AND NOT ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um serviço não pode fazer parte de outro serviço ou produto";
	    ELSEIF NEW.Preco < 0 AND NOT ISNULL(NEW.ProdutoID) THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Preco > -0.01 AND NEW.Preco < 0.01 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor do produto ou serviço não pode ser nulo";
	    ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço como cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço em um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço em um pedido finalizado";
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Estado <> 'Adicionado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto ou serviço deve ser inserido com estado de 'Adicionado'";
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado = 'N' AND NOT ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto não visualizado com a data e a hora";
		ELSEIF NOT ISNULL(NEW.ProdutoID) AND _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado, _nome VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo, _aberta VARCHAR(1);
		DECLARE _login VARCHAR(50);
	    DECLARE _movimentacao_id INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT MovimentacaoID, Cancelado, Estado INTO _movimentacao_id, _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NOT ISNULL(_movimentacao_id) THEN
			SELECT mv.Aberta, c.Descricao INTO _aberta, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = _movimentacao_id;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", _movimentacao_id, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido finalizado";
	    ELSEIF ISNULL(OLD.ProdutoID) <> ISNULL(NEW.ProdutoID) OR ISNULL(OLD.ServicoID) <> ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar de produto para serviço ou vice versa";
	    ELSEIF NOT ISNULL(NEW.ServicoID) AND NOT ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um serviço não pode fazer parte de outro serviço ou produto";
	    ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Preco < 0 AND NOT ISNULL(NEW.ProdutoID) THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Preco > -0.01 AND NEW.Preco < 0.01 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor do produto ou serviço não pode ser nulo";
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF NOT ISNULL(NEW.ProdutoID) AND _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	    IF NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ProdutoID) THEN
			UPDATE Estoque SET Cancelado = 'Y' WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_INSERT` BEFORE INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome, _estado VARCHAR(75);
		DECLARE _aberta, _cancelado VARCHAR(1);
		DECLARE _existe, _sessao_id, _forma_count INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, c.Descricao INTO _aberta, _sessao_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		SET _forma_count = IF(ISNULL(NEW.CartaoID), 0, 1) + IF(ISNULL(NEW.ChequeID), 0, 1) + IF(ISNULL(NEW.ContaID), 0, 1) + IF(ISNULL(NEW.CreditoID), 0, 1);
		IF NOT ISNULL(NEW.MovimentacaoID) AND _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento cancelado";
		ELSEIF _forma_count > 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Várias formas de pagamento em uma única transação";
		ELSEIF _forma_count = 0 AND (NEW.Parcelas > 0 OR NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento em uma forma de pagamento não parcelada";
		ELSEIF NEW.Total > -0.005 AND NEW.Total < 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Valor total não informado";
		ELSEIF NEW.Parcelas < 0 OR (NEW.Parcelas = 0 AND (NEW.ValorParcela < -0.005 OR NEW.ValorParcela > 0.005)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento incorreto";
		ELSEIF _forma_count = 1 AND NEW.Parcelas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Parcelamento não informado";
		ELSEIF NEW.Parcelas > 0 AND NEW.Parcelas * NEW.ValorParcela < NEW.Total - 0.005 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Total parcelado menor que o total do pagamento";
	    ELSEIF NOT ISNULL(NEW.PedidoID) AND NOT ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Vários destinos para o pagamento";
	    ELSEIF ISNULL(NEW.PedidoID) AND ISNULL(NEW.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum destino para o pagamento";
	    ELSEIF NOT ISNULL(NEW.ContaID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um pagamento inativo em conta";
	    ELSEIF NOT ISNULL(NEW.CreditoID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um pagamento inativo em crédito";
	    ELSEIF NOT ISNULL(NEW.PedidoID) AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A movimentação do caixa não foi informada para a realização do pagamento do pedido";
	    ELSEIF NOT ISNULL(NEW.PedidoID) THEN
			SELECT MovimentacaoID, Estado, Cancelado INTO _movimentacao_id, _estado, _cancelado FROM Pedidos
				WHERE ID = NEW.PedidoID;
			IF _cancelado = 'Y' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido cancelado";
			ELSEIF _estado = 'Finalizado' THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível realizar um pagamento em um pedido finalizado";
			ELSEIF NOT ISNULL(_movimentacao_id) AND _movimentacao_id <> NEW.MovimentacaoID THEN
				SET _error_msg = CONCAT("O pedido já está associado à movimentação ", _movimentacao_id);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			ELSEIF ISNULL(_movimentacao_id) THEN
				UPDATE Pedidos p
					LEFT JOIN Movimentacoes mv ON mv.ID = NEW.MovimentacaoID
	                SET p.SessaoID = mv.SessaoID, p.MovimentacaoID = mv.ID 
					WHERE p.ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF NOT ISNULL(NEW.MovimentacaoID) AND _tipo = 'Dinheiro' AND NEW.Total < 0 AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE fp.Tipo = 'Dinheiro' AND pg.MovimentacaoID = NEW.MovimentacaoID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_BEFORE_UPDATE` BEFORE UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _sessao_id INT DEFAULT 0;
	    DECLARE _movimentacao_id INT DEFAULT NULL;
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		SELECT mv.Aberta, mv.SessaoID, c.Descricao INTO _aberta, _sessao_id, _nome FROM Movimentacoes mv
			LEFT JOIN Caixas c ON c.ID = mv.CaixaID
			WHERE mv.ID = NEW.MovimentacaoID;
		IF NOT ISNULL(NEW.MovimentacaoID) AND _aberta = 'N' THEN
			SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pagamento que já foi cancelado";
		ELSEIF NEW.Total <> OLD.Total THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O valor total não pode ser alterado";
		ELSEIF NEW.Parcelas <> OLD.Parcelas OR NEW.ValorParcela <> OLD.ValorParcela THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O parcelamento não pode ser alterado";
		ELSEIF ISNULL(NEW.MovimentacaoID) <> ISNULL(OLD.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O caixa do pagamento não pode ser alterado";
		ELSEIF NOT (NEW.CarteiraID <=> OLD.CarteiraID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A carteira do pagamento não pode ser alterada";
		ELSEIF NEW.FormaPagtoID <> OLD.FormaPagtoID OR NOT (NEW.CartaoID <=> OLD.CartaoID) OR NOT (NEW.ChequeID <=> OLD.ChequeID) OR
			   NOT (NEW.ContaID <=> OLD.ContaID) OR NOT (NEW.CreditoID <=> OLD.CreditoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A forma de pagamento não pode ser alterada";
		ELSEIF NEW.DataHora <> OLD.DataHora THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data do pagamento não pode ser alterada";
		ELSEIF NOT (NEW.PedidoID <=> OLD.PedidoID) OR NOT (NEW.PagtoContaID <=> OLD.PagtoContaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O destino do pagamento não pode ser alterado";
	    ELSEIF NOT ISNULL(NEW.ContaID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um pagamento em conta para inativo";
	    ELSEIF NOT ISNULL(NEW.CreditoID) AND NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um pagamento em crédito para inativo";
		ELSEIF NOT ISNULL(NEW.MovimentacaoID) AND NEW.MovimentacaoID <> OLD.MovimentacaoID THEN
			SELECT mv.Aberta, mv.SessaoID, c.Descricao INTO _aberta, _sessao_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
	        IF NOT ISNULL(NEW.PedidoID) THEN
				UPDATE Pedidos p
					LEFT JOIN Movimentacoes mv ON mv.ID = NEW.MovimentacaoID
	                SET p.SessaoID = mv.SessaoID, p.MovimentacaoID = mv.ID 
					WHERE p.ID = NEW.PedidoID;
			END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_UPDATE` AFTER UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF NOT ISNULL(NEW.MovimentacaoID) AND _tipo = 'Dinheiro' AND NEW.Total < 0 AND OLD.Ativo = 'N' AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE fp.Tipo = 'Dinheiro' AND pg.MovimentacaoID = NEW.MovimentacaoID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não há dinheiro suficiente no caixa";
	        END IF;
	    END IF;
		IF (OLD.Cancelado = 'N' OR OLD.ContaID <> NEW.ContaID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ContaID) THEN
			UPDATE Contas SET Cancelada = 'Y' WHERE ID = NEW.ContaID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.CreditoID <> NEW.CreditoID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.CreditoID) THEN
			UPDATE Creditos SET Cancelado = 'Y' WHERE ID = NEW.CreditoID;
		END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Contas_AFTER_UPDATE` $$
	
	DELIMITER ;

}

/* Adicionado adicional nas composições */
/* Adicionado tempo de preparo nos produtos */
/* Adicionado mascaras de conta e agência dos bancos */
Update (Version: "1.7.0.2") {

	ALTER TABLE `Bancos` ADD `AgenciaMascara` VARCHAR(45) NULL DEFAULT NULL AFTER `RazaoSocial`;
	ALTER TABLE `Bancos` ADD `ContaMascara` VARCHAR(45) NULL DEFAULT NULL AFTER `AgenciaMascara`;
	
	UPDATE `Bancos` SET AgenciaMascara = "9999->a", ContaMascara = "99.999->a" WHERE Numero = "1";
	UPDATE `Bancos` SET AgenciaMascara = "9999", ContaMascara = "9.999.999.999" WHERE Numero = "33";
	UPDATE `Bancos` SET AgenciaMascara = "9999", ContaMascara = "99.999.999-9" WHERE Numero = "104";
	UPDATE `Bancos` SET AgenciaMascara = "9999", ContaMascara = "9.999.999-9" WHERE Numero = "237";
	UPDATE `Bancos` SET AgenciaMascara = "9999", ContaMascara = "99.999-9" WHERE Numero = "341";

	UPDATE `Formas_Pagto` SET `CarteiraID` = 1 WHERE `CarteiraID` IS NULL;
	ALTER TABLE `Formas_Pagto` MODIFY `CarteiraID` INT NOT NULL;
	ALTER TABLE `Formas_Pagto` ADD `CarteiraPagtoID` INT NOT NULL DEFAULT 1 AFTER `CarteiraID`;
	ALTER TABLE `Formas_Pagto` MODIFY `CarteiraPagtoID` INT NOT NULL;
	ALTER TABLE `Formas_Pagto` ADD INDEX `FK_Formas_Pagto_Carteiras_CarteiraPagtoID_idx` (`CarteiraPagtoID` ASC);
	ALTER TABLE `Formas_Pagto` ADD CONSTRAINT `FK_Formas_Pagto_Carteiras_CarteiraPagtoID`
		FOREIGN KEY (`CarteiraPagtoID`)
		REFERENCES `Carteiras` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Cartoes` ADD `CarteiraPagtoID` INT NULL DEFAULT NULL AFTER `CarteiraID`;
	ALTER TABLE `Cartoes` ADD INDEX `FK_Cartoes_Carteiras_CarteiraPagtoID_idx` (`CarteiraPagtoID` ASC);
	ALTER TABLE `Cartoes` ADD CONSTRAINT `FK_Cartoes_Carteiras_CarteiraPagtoID`
		FOREIGN KEY (`CarteiraPagtoID`)
		REFERENCES `Carteiras` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Produtos` ADD `TempoPreparo` INT NOT NULL DEFAULT 0 AFTER `Perecivel`;

	ALTER TABLE `Cheques` ADD `Cancelado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Total`;
	ALTER TABLE `Cheques` ADD `DataCadastro` DATETIME NOT NULL AFTER `Cancelado`;

	ALTER TABLE `Contas` ADD `Acrescimo` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `Valor`;
	ALTER TABLE `Contas` ADD `Multa` DECIMAL(19,4) NOT NULL DEFAULT 0 AFTER `Acrescimo`;
	ALTER TABLE `Contas` ADD `Juros` DOUBLE NOT NULL DEFAULT 0 AFTER `Multa`;

	ALTER TABLE `Composicoes` ADD `Tipo` ENUM('Composicao', 'Opcional', 'Adicional') NOT NULL DEFAULT 'Composicao' AFTER `ProdutoID`;
	UPDATE `Composicoes` SET `Tipo` = 'Opcional' WHERE `Selecionavel` = 'Y';
	ALTER TABLE `Composicoes` DROP `Selecionavel`;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(82, 7, "RelatorioCarteiras", "Permitir visualizar o relatório de carteiras");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 82 as PermissaoID FROM Acessos WHERE PermissaoID = 78;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Cheques_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Cheques_BEFORE_INSERT` BEFORE INSERT ON `Cheques` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
		IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cheque não pode ser cadastrado já cancelado";
		END IF;
	    
	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado data de pagamento nas contas */
Update (Version: "1.7.0.3") {

	ALTER TABLE `Contas` ADD `DataPagamento` DATETIME NULL DEFAULT NULL AFTER `Cancelada`;
	ALTER TABLE `Contas` ADD `AutoAcrescimo` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Juros`;

}

/* Corrigido trigger sem saldo pelas carteiras */
/* Melhorado cliente do cheque */
Update (Version: "1.7.5.0") {

	UPDATE `Permissoes` SET Descricao = "Permitir cancelar produtos de um pedido" WHERE ID = 7;

	ALTER TABLE `Cheques` CHANGE `Cliente` `ClienteID` INT NOT NULL;
	ALTER TABLE `Cheques` ADD INDEX `FK_Cheques_Clientes_ClienteID_idx` (`ClienteID` ASC);
	ALTER TABLE `Cheques` ADD CONSTRAINT `FK_Cheques_Clientes_ClienteID`
		FOREIGN KEY (`ClienteID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Folhas_Cheques` ADD `Compensacao` VARCHAR(10) NOT NULL AFTER `ChequeID`;
	ALTER TABLE `Folhas_Cheques` MODIFY `Numero` VARCHAR(20) NOT NULL;
	ALTER TABLE `Folhas_Cheques` ADD `Serie` VARCHAR(10) NULL DEFAULT NULL AFTER `C3`;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(83, 7, "RelatorioCheques", "Permitir visualizar o relatório de cheques");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 83 as PermissaoID FROM Acessos WHERE PermissaoID = 78;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
		DECLARE _carteira VARCHAR(100);
		DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF (_tipo = 'Dinheiro' OR _tipo = 'Transferencia') AND NEW.Total < 0 AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE fp.Tipo IN ('Dinheiro', 'Transferencia') AND (ISNULL(NEW.MovimentacaoID) OR pg.MovimentacaoID = NEW.MovimentacaoID) AND 
	            pg.CarteiraID = NEW.CarteiraID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SELECT Descricao INTO _carteira FROM Carteiras WHERE ID = NEW.CarteiraID;
				SET _error_msg = CONCAT("Não há dinheiro suficiente na carteira '", _carteira, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    END IF;
	    
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_UPDATE` AFTER UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
	    DECLARE _carteira VARCHAR(100);
	    DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF (_tipo = 'Dinheiro' OR _tipo = 'Transferencia') AND NEW.Total < 0 AND OLD.Ativo = 'N' AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE fp.Tipo IN ('Dinheiro', 'Transferencia') AND (ISNULL(NEW.MovimentacaoID) OR pg.MovimentacaoID = NEW.MovimentacaoID) AND 
	            pg.CarteiraID = NEW.CarteiraID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SELECT Descricao INTO _carteira FROM Carteiras WHERE ID = NEW.CarteiraID;
				SET _error_msg = CONCAT("Não há dinheiro suficiente na carteira '", _carteira, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    END IF;
		IF (OLD.Cancelado = 'N' OR OLD.ContaID <> NEW.ContaID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ContaID) THEN
			UPDATE Contas SET Cancelada = 'Y' WHERE ID = NEW.ContaID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.CreditoID <> NEW.CreditoID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.CreditoID) THEN
			UPDATE Creditos SET Cancelado = 'Y' WHERE ID = NEW.CreditoID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.ChequeID <> NEW.ChequeID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ChequeID) THEN
			UPDATE Cheques SET Cancelado = 'Y' WHERE ID = NEW.ChequeID;
		END IF;
	    
	END IF;
	END $$

	DELIMITER ;

}


/* Adicionado internacionalização e moedas */
Update (Version: "1.8.0.0") {

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(84, 2, "PagamentoConta", "Permitir pagar um pedido na forma de pagamento Conta"),
		(85, 6, "CadastroPaises", "Permitir cadastrar ou alterar paises"),
		(86, 6, "CadastroEstados", "Permitir cadastrar ou alterar os estados de um país"),
		(87, 6, "CadastroMoedas", "Permitir cadastrar ou alterar os tipos de moedas"),
		(88, 8, "AlterarPaginas", "Permitir alterar as páginas do site da empresa");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 84 as PermissaoID FROM Acessos WHERE PermissaoID = 46;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 85 as PermissaoID FROM Acessos WHERE PermissaoID = 61;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 86 as PermissaoID FROM Acessos WHERE PermissaoID = 61;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 87 as PermissaoID FROM Acessos WHERE PermissaoID = 61;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 88 as PermissaoID FROM Acessos WHERE PermissaoID = 61;

	ALTER TABLE `Clientes` ADD `LimiteCompra` DECIMAL(19,4) NULL DEFAULT NULL AFTER `Secreto`;
	ALTER TABLE `Clientes` ADD `FacebookURL` VARCHAR(200) NULL DEFAULT NULL AFTER `LimiteCompra`;
	ALTER TABLE `Clientes` ADD `TwitterURL` VARCHAR(200) NULL DEFAULT NULL AFTER `FacebookURL`;
	ALTER TABLE `Clientes` ADD `LinkedInURL` VARCHAR(200) NULL DEFAULT NULL AFTER `TwitterURL`;

	CREATE TABLE IF NOT EXISTS `Moedas` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Nome` VARCHAR(45) NOT NULL,
	  `Simbolo` VARCHAR(10) NOT NULL,
	  `Codigo` VARCHAR(45) NULL DEFAULT NULL,
	  `Divisao` INT NOT NULL,
	  `Fracao` VARCHAR(45) NULL DEFAULT NULL,
	  `Formato` VARCHAR(45) NOT NULL,
	  PRIMARY KEY (`ID`))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Paises` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Nome` VARCHAR(100) NOT NULL,
	  `Sigla` VARCHAR(10) NOT NULL,
	  `MoedaID` INT NOT NULL,
	  `BandeiraIndex` INT NOT NULL,
	  `LinguagemID` INT NOT NULL DEFAULT 0,
	  `Unitario` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
	  `FoneMascara` VARCHAR(45) NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC),
	  INDEX `FK_Paises_Moedas_MoedaID_idx` (`MoedaID` ASC),
	  UNIQUE INDEX `Sigla_UNIQUE` (`Sigla` ASC),
	  CONSTRAINT `FK_Paises_Moedas_MoedaID`
	    FOREIGN KEY (`MoedaID`)
	    REFERENCES `Moedas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	INSERT INTO `Moedas` VALUES
		(1, "Real", "R$", "BRL", 100, "Centavo", "R$ %s"),
		(2, "Dollar", "$", "USD", 100, "Cent", "$ %s"),
		(3, "Euro", "€", "EUR", 100, "Cent", "€ %s"),
		(4, "Metical", "MT", "MZN", 100, "Centavo", "%s MT");

	INSERT INTO `Paises` VALUES
		(1, "Brasil", "BRA", 1, 28, 1046, 'N', "(99) 9999-99999"),
		(2, "United States of America", "USA", 2, 220, 1033, 'N', "(999) 999-9999"),
		(3, "España", "ESP", 3, 66, 1034, 'Y', NULL),
		(4, "Moçambique", "MOZ", 4, 151, 1046, 'Y', NULL);

	ALTER TABLE `Sistema` ADD `PaisID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Sistema` ADD INDEX `FK_Sistema_Paises_PaisID_idx` (`PaisID` ASC);
	ALTER TABLE `Sistema` ADD CONSTRAINT `FK_Sistema_Paises_PaisID`
		FOREIGN KEY (`PaisID`)
		REFERENCES `Paises` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Sistema` SET `PaisID` = 1;

	ALTER TABLE `Estados` DROP INDEX `Nome_UNIQUE`;
	ALTER TABLE `Estados` DROP INDEX `UF_UNIQUE`;
	ALTER TABLE `Estados` ADD `PaisID` INT NULL DEFAULT NULL AFTER `ID`;
	ALTER TABLE `Estados` ADD UNIQUE INDEX `PaisID_Nome_UNIQUE` (`PaisID` ASC, `Nome` ASC);
	ALTER TABLE `Estados` ADD UNIQUE INDEX `PaisID_UF_UNIQUE` (`PaisID` ASC, `UF` ASC);
	ALTER TABLE `Estados` ADD CONSTRAINT `FK_Estados_Paises_PaisID`
		FOREIGN KEY (`PaisID`)
		REFERENCES `Paises` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;
	UPDATE `Estados` SET `PaisID` = 1;
	ALTER TABLE `Estados` MODIFY `PaisID` INT NOT NULL;
	ALTER TABLE `Estados` MODIFY `UF` VARCHAR(48) NOT NULL;

	CREATE TABLE IF NOT EXISTS `Paginas` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Nome` VARCHAR(45) NOT NULL,
	  `LinguagemID` INT NOT NULL DEFAULT 0,
	  `Conteudo` TEXT NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Nome_LinguagemID_UNIQUE` (`Nome` ASC, `LinguagemID` ASC))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Entradas` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `PaisID` INT NOT NULL,
	  `Nome` VARCHAR(45) NOT NULL,
	  `Formato` VARCHAR(255) NOT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Pais_Nome_UNIQUE` (`PaisID` ASC, `Nome` ASC),
	  CONSTRAINT `FK_Entradas_Paises_PaisID`
	    FOREIGN KEY (`PaisID`)
	    REFERENCES `Paises` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		ELSE
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível cadastrar um pedido como cancelado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado NOT IN ('Ativo', 'Agendado') THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não está ativo ou não está agendado";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NOT ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser entregue ao mesmo instante que é criado";
	    ELSEIF NOT ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode estar concluído ao ser criado";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Mesa' AND MesaID = NEW.MesaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Mesas WHERE ID = NEW.MesaID;
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto ou serviço foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
		END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Melhorado esquema de tradução de entradas */
Update (Version: "1.8.0.1") {

	ALTER TABLE `Paises` DROP `FoneMascara`;
	ALTER TABLE `Paises` ADD `Entradas` TEXT NULL DEFAULT NULL AFTER `LinguagemID`;
	DROP TABLE `Entradas`;
	UPDATE `Paises` SET Entradas = 'W1RpdHVsb10NCkNOUEo9TlVJVA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNOUEo9OS45OTk5OTk5LTkNCkNFUD05OTk5DQpUZWxlZm9uZT05OTk5LTk5OTk5DQoNCg=='
		WHERE Sigla = 'MOZ';
	UPDATE `Paises` SET Entradas = 'W1RpdHVsb10NCkNFUD1aSVANCkNQRj1TU04NCg0KW01hc2NhcmFdDQpDRVA9OTk5OTkNCkNQRj05OTktOTktOTk5OQ0KVGVsZWZvbmU9KDk5OSkgOTk5LTk5OTkNCg0KW0F1ZGl0b3JpYV0NCg0K'
		WHERE Sigla = 'USA';
	UPDATE `Paises` SET Entradas = 'W1RpdHVsb10NCkNOUEo9UlVUDQpDUEY9TlVJUA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNQRj05Ljk5OS45OTkuOTk5DQpDRVA9OTk5OTkNCkNOUEo9OTkuOTk5Ljk5OS05DQpUZWxlZm9uZT05OTkgOTk5IDk5OQ0KDQo='
		WHERE Sigla = 'ESP';

}

/* Adicionado mecanismo para juntar mesas e reservá-las */
Update (Version: "1.8.0.2") {

	CREATE TABLE IF NOT EXISTS `Juncoes` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `MesaID` INT NOT NULL,
	  `PedidoID` INT NOT NULL,
	  `Estado` ENUM('Associado', 'Liberado', 'Cancelado') NOT NULL,
	  `DataMovimento` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Juncoes_Mesas_MesaID_idx` (`MesaID` ASC),
	  INDEX `FK_Juncoes_Pedidos_PedidoID_idx` (`PedidoID` ASC),
	  INDEX `MesaEstado_INDEX` (`MesaID` ASC, `Estado` ASC),
	  CONSTRAINT `FK_Juncoes_Mesas_MesaID`
	    FOREIGN KEY (`MesaID`)
	    REFERENCES `Mesas` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Juncoes_Pedidos_PedidoID`
	    FOREIGN KEY (`PedidoID`)
	    REFERENCES `Pedidos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		ELSE
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível cadastrar um pedido como cancelado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado NOT IN ('Ativo', 'Agendado') THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não está ativo ou não está agendado";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NOT ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser entregue ao mesmo instante que é criado";
	    ELSEIF NOT ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode estar concluído ao ser criado";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' THEN
			SELECT 1, m.Nome INTO _existe, _nome FROM Pedidos p
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE p.Tipo = 'Mesa' AND p.MesaID = NEW.MesaID AND p.Estado <> 'Finalizado' AND p.Cancelado = 'N';
	        IF _existe = 1 THEN
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
			SELECT 1, m.Nome INTO _existe, _nome FROM Juncoes j
				LEFT JOIN Pedidos p ON p.ID = j.PedidoID
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE j.MesaID = NEW.MesaID AND j.Estado = 'Associado';
	        IF _existe = 1 THEN
				SET _error_msg = CONCAT("A mesa informada já está junta com a mesa '", _nome, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1, m.Nome INTO _existe, _nome FROM Pedidos p
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE p.Tipo = 'Mesa' AND p.MesaID = NEW.MesaID AND p.Estado <> 'Finalizado' AND p.Cancelado = 'N';
	        IF _existe = 1 THEN
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
			SELECT 1, m.Nome INTO _existe, _nome FROM Juncoes j
				LEFT JOIN Pedidos p ON p.ID = j.PedidoID
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE j.MesaID = NEW.MesaID AND j.Estado = 'Associado';
	        IF _existe = 1 THEN
				SET _error_msg = CONCAT("A mesa informada já está junta com a mesa '", _nome, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado AND NEW.Cancelado = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto ou serviço foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
	        END IF;
		ELSEIF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Cancelado' WHERE PedidoID = OLD.ID AND Estado <> 'Cancelado';
	        END IF;
		ELSEIF OLD.Tipo <> NEW.Tipo AND OLD.Tipo = 'Mesa' THEN
			UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
		END IF;

	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Juncoes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Juncoes_BEFORE_INSERT` BEFORE INSERT ON `Juncoes` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(75);
		DECLARE _existe, _aberto, _mesa_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT 1 INTO _existe FROM Juncoes WHERE MesaID = NEW.MesaID AND Estado = 'Associado';
		SELECT 1, Tipo, MesaID INTO _aberto, _tipo, _mesa_id FROM Pedidos WHERE ID = NEW.PedidoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	    
		IF _existe = 1 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa informada já está junta com outra mesa";
	    ELSEIF _aberto = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa de destino precisa estar aberta";
	    ELSEIF _mesa_id = NEW.MesaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode juntar com ela mesma";
	    ELSEIF _tipo <> 'Mesa' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido de destino deve ser de uma mesa";
	    ELSEIF NEW.Estado <> 'Associado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A junção deve ser cadastrada como associada";
		END IF;

	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Juncoes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Juncoes_BEFORE_UPDATE` BEFORE UPDATE ON `Juncoes` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(75);
		DECLARE _existe, _aberto, _mesa_id INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT 1 INTO _existe FROM Juncoes WHERE MesaID = NEW.MesaID AND Estado = 'Associado' AND ID <> OLD.ID;
		SELECT 1, Tipo, MesaID INTO _aberto, _tipo, _mesa_id FROM Pedidos WHERE ID = NEW.PedidoID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	    
		IF _existe = 1 AND NEW.Estado = 'Associado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa informada já está junta com outra mesa";
	    ELSEIF _aberto = 0 AND NEW.Estado = 'Associado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa de destino precisa estar aberta";
	    ELSEIF _aberto = 1 AND _mesa_id = NEW.MesaID THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode juntar com ela mesma";
	    ELSEIF _aberto = 1 AND _tipo <> 'Mesa' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido de destino deve ser de uma mesa";
		END IF;

	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado comando customizado para impressoras */
Update (Version: "1.8.0.3") {

	ALTER TABLE `Impressoras` MODIFY `Descricao` VARCHAR(45) NOT NULL DEFAULT '';
	ALTER TABLE `Impressoras` MODIFY `Modo` ENUM('Terminal', 'Caixa', 'Cozinha', 'Estoque', 'Servico') NOT NULL DEFAULT 'Terminal';
	UPDATE `Impressoras` SET Modo = 'Servico' WHERE Modo = 'Cozinha';
	ALTER TABLE `Impressoras` MODIFY `Modo` ENUM('Terminal', 'Caixa', 'Servico', 'Estoque') NOT NULL DEFAULT 'Terminal';
	ALTER TABLE `Impressoras` ADD `Comandos` TEXT NULL DEFAULT NULL AFTER `Avanco`;
	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(89, 1, "AlterarEntregador", "Permitir alterar o entregador após enviar os pedidos"),
		(90, 7, "RelatorioBalanco", "Permitir visualizar o relatório de balanço de contas"),
		(91, 1, "TransformarEntrega", "Permitir transformar um pedido de entrega para viagem e vice versa"),
		(92, 4, "ConferirCaixa", "Permitir alterar os valores de conferência de um caixa");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 89 as PermissaoID FROM Acessos WHERE PermissaoID = 43;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 90 as PermissaoID FROM Acessos WHERE PermissaoID = 78;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 91 as PermissaoID FROM Acessos WHERE PermissaoID = 43;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 92 as PermissaoID FROM Acessos WHERE PermissaoID = 78;

}

/* Melhorado campos nulos */
Update (Version: "1.8.0.4") {

	ALTER TABLE `Sessoes` MODIFY `DataTermino` DATETIME NULL DEFAULT NULL;
	ALTER TABLE `Formas_Pagto` MODIFY `MinParcelas` INT NULL DEFAULT NULL;
	ALTER TABLE `Formas_Pagto` MODIFY `MaxParcelas` INT NULL DEFAULT NULL;
	ALTER TABLE `Formas_Pagto` MODIFY `ParcelasSemJuros` INT NULL DEFAULT NULL;
	ALTER TABLE `Formas_Pagto` MODIFY `Juros` DOUBLE NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `Senha` VARCHAR(40) NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `Sobrenome` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Clientes` MODIFY `Fone1` VARCHAR(12) NULL DEFAULT NULL;
	UPDATE `Funcionarios` SET Porcentagem = 0 WHERE ISNULL(Porcentagem);
	UPDATE `Funcionarios` SET `Porcentagem` = 0 WHERE ISNULL(`Porcentagem`);
	ALTER TABLE `Funcionarios` MODIFY `Porcentagem` DOUBLE NOT NULL DEFAULT 0;
	ALTER TABLE `Funcionarios` CHANGE `Saida` `DataSaida` DATETIME NULL DEFAULT NULL AFTER `Ativo`;
	ALTER TABLE `Funcionarios` CHANGE `Cadastro` `DataCadastro` DATETIME NOT NULL AFTER `DataSaida`;
	ALTER TABLE `Pedidos` MODIFY `MesaID` INT NULL DEFAULT NULL;
	ALTER TABLE `Unidades` MODIFY `Descricao` VARCHAR(45) NULL DEFAULT NULL;
	ALTER TABLE `Produtos` MODIFY `CodigoBarras` VARCHAR(13) NULL DEFAULT NULL;
	ALTER TABLE `Estoque` MODIFY `TransacaoID` INT NULL DEFAULT NULL;
	UPDATE `Estoque` SET `PrecoCompra` = 0 WHERE ISNULL(`PrecoCompra`);
	ALTER TABLE `Estoque` MODIFY `PrecoCompra` DECIMAL(19,4) NOT NULL DEFAULT 0;
	ALTER TABLE `Dispositivos` MODIFY `Descricao` VARCHAR(45) NULL DEFAULT NULL;
	ALTER TABLE `Sistema` MODIFY `VersaoDB` VARCHAR(45) NOT NULL;
	ALTER TABLE `Horarios` MODIFY `TempoEntrega` INT NULL DEFAULT NULL;

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(93, 2, "ContaViagem", "Permitir imprimir conta de pedidos para viagem"),
		(94, 2, "EntregaAdicionar", "Permitir adicionar produtos na tela de entrega");
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 93 as PermissaoID FROM Acessos WHERE PermissaoID = 25;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 94 as PermissaoID FROM Acessos WHERE PermissaoID = 25;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Funcionarios_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Funcionarios_BEFORE_INSERT` BEFORE INSERT ON `Funcionarios` FOR EACH ROW
	BEGIN
	IF @DISABLE_TRIGGERS IS NULL THEN
		IF NEW.Ativo = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O funcionário não pode ser cadastrado como inativo";
		ELSEIF NOT ISNULL(NEW.DataSaida) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de saída do funcionário não pode ser informada agora";
		END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado tabelas do módulo fiscal */
Update (Version: "1.9.0.0") {

	ALTER TABLE `Caixas` ADD `Serie` INT NOT NULL DEFAULT 1 AFTER `Descricao`;
	ALTER TABLE `Caixas` ADD `NumeroInicial` INT NOT NULL DEFAULT 1 AFTER `Serie`;

	ALTER TABLE `Pedidos` ADD `Motivo` VARCHAR(200) NULL DEFAULT NULL AFTER `Cancelado`;
	ALTER TABLE `Pedidos` MODIFY `Descricao` VARCHAR(255) NULL DEFAULT NULL;

	CREATE TABLE IF NOT EXISTS `Origens` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Codigo` INT NOT NULL,
	  `Descricao` VARCHAR(200) NOT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Codigo_UNIQUE` (`Codigo` ASC))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Operacoes` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Codigo` INT NOT NULL,
	  `Descricao` VARCHAR(255) NOT NULL,
	  `Detalhes` TEXT NULL DEFAULT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Codigo_UNIQUE` (`Codigo` ASC))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Impostos` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Grupo` ENUM('ICMS', 'PIS', 'COFINS', 'IPI', 'II') NOT NULL,
	  `Simples` ENUM('Y', 'N') NOT NULL,
	  `Substituicao` ENUM('Y', 'N') NOT NULL,
	  `Codigo` INT NOT NULL,
	  `Descricao` VARCHAR(255) NOT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `UK_Imposto` (`Grupo` ASC, `Simples` ASC, `Substituicao` ASC, `Codigo` ASC))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Tributacoes` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `NCM` VARCHAR(10) NOT NULL,
	  `CEST` VARCHAR(20) NULL DEFAULT NULL,
	  `OrigemID` INT NOT NULL,
	  `OperacaoID` INT NOT NULL,
	  `ImpostoID` INT NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Tributacoes_Origens_OrigemID_idx` (`OrigemID` ASC),
	  INDEX `FK_Tributacoes_Operacoes_OperacaoID_idx` (`OperacaoID` ASC),
	  INDEX `FK_Tributacoes_Impostos_ImpostoID_idx` (`ImpostoID` ASC),
	  CONSTRAINT `FK_Tributacoes_Origens_OrigemID`
	    FOREIGN KEY (`OrigemID`)
	    REFERENCES `Origens` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Tributacoes_Operacoes_OperacaoID`
	    FOREIGN KEY (`OperacaoID`)
	    REFERENCES `Operacoes` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Tributacoes_Impostos_ImpostoID`
	    FOREIGN KEY (`ImpostoID`)
	    REFERENCES `Impostos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	ALTER TABLE `Produtos` MODIFY `CodigoBarras` VARCHAR(100) NULL DEFAULT NULL;
	ALTER TABLE `Produtos` ADD `TributacaoID` INT NULL DEFAULT NULL AFTER `SetorPreparoID`;
	ALTER TABLE `Produtos` ADD INDEX `FK_Produtos_Tributacoes_TributacaoID_idx` (`TributacaoID` ASC);
	ALTER TABLE `Produtos` ADD CONSTRAINT `FK_Produtos_Tributacoes_TributacaoID`
		FOREIGN KEY (`TributacaoID`)
		REFERENCES `Tributacoes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	CREATE TABLE IF NOT EXISTS `Regimes` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Codigo` INT NOT NULL,
	  `Descricao` VARCHAR(200) NOT NULL,
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Codigo_UNIQUE` (`Codigo` ASC))
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Emitentes` (
	  `ID` ENUM('1') NOT NULL DEFAULT '1',
	  `ContadorID` INT NULL DEFAULT NULL,
	  `RegimeID` INT NOT NULL,
	  `Ambiente` ENUM('Homologacao', 'Producao') NOT NULL,
	  `CSC` VARCHAR(100) NOT NULL,
	  `Token` VARCHAR(10) NOT NULL,
	  `IBPT` VARCHAR(100) NULL DEFAULT NULL,
	  `ChavePrivada` VARCHAR(100) NOT NULL,
	  `ChavePublica` VARCHAR(100) NOT NULL,
	  `DataExpiracao` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Emitentes_Clientes_ContadorID_idx` (`ContadorID` ASC),
	  INDEX `FK_Emitentes_Regimes_RegimeID_idx` (`RegimeID` ASC),
	  CONSTRAINT `FK_Emitentes_Clientes_ContadorID`
	    FOREIGN KEY (`ContadorID`)
	    REFERENCES `Clientes` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Emitentes_Regimes_RegimeID`
	    FOREIGN KEY (`RegimeID`)
	    REFERENCES `Regimes` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Notas` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Tipo` ENUM('Nota', 'Inutilizacao') NOT NULL,
	  `Ambiente` ENUM('Homologacao', 'Producao') NOT NULL,
	  `Acao` ENUM('Autorizar', 'Cancelar', 'Inutilizar') NOT NULL,
	  `Estado` ENUM('Aberto', 'Assinado', 'Pendente', 'Processamento', 'Denegado', 'Rejeitado', 'Cancelado', 'Inutilizado', 'Autorizado') NOT NULL,
	  `Serie` INT NOT NULL,
	  `NumeroInicial` INT NOT NULL,
	  `NumeroFinal` INT NOT NULL,
	  `Sequencia` INT NOT NULL,
	  `Chave` VARCHAR(50) NULL DEFAULT NULL,
	  `Recibo` VARCHAR(50) NULL DEFAULT NULL,
	  `Protocolo` VARCHAR(80) NULL DEFAULT NULL,
	  `PedidoID` INT NULL DEFAULT NULL,
	  `Motivo` VARCHAR(255) NULL DEFAULT NULL,
	  `Contingencia` ENUM('Y', 'N') NOT NULL,
	  `ConsultaURL` VARCHAR(255) NULL DEFAULT NULL,
	  `QRCode` TEXT NULL DEFAULT NULL,
	  `Tributos` DECIMAL(19,4) NULL DEFAULT NULL,
	  `Detalhes` VARCHAR(255) NULL DEFAULT NULL,
	  `Corrigido` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
	  `Concluido` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
	  `DataAutorizacao` DATETIME NULL DEFAULT NULL,
	  `DataEmissao` DATETIME NOT NULL,
	  `DataLancamento` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Notas_Pedidos_PedidoID_idx` (`PedidoID` ASC),
	  INDEX `IDX_Chave` (`Chave` ASC),
	  CONSTRAINT `FK_Notas_Pedidos_PedidoID`
	    FOREIGN KEY (`PedidoID`)
	    REFERENCES `Pedidos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	CREATE TABLE IF NOT EXISTS `Eventos` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `NotaID` INT NOT NULL,
	  `Estado` ENUM('Aberto', 'Assinado', 'Validado', 'Pendente', 'Processamento', 'Denegado', 'Cancelado', 'Rejeitado', 'Contingencia', 'Inutilizado', 'Autorizado') NOT NULL,
	  `Mensagem` TEXT NOT NULL,
	  `Codigo` VARCHAR(20) NOT NULL,
	  `DataCriacao` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Eventos_Notas_NotaID_idx` (`NotaID` ASC),
	  CONSTRAINT `FK_Eventos_Notas_NotaID`
	    FOREIGN KEY (`NotaID`)
	    REFERENCES `Notas` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	INSERT INTO `Origens` (`ID`, `Codigo`, `Descricao`) VALUES
		(1, 0, 'Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8'),
		(2, 1, 'Estrangeira - Importação direta, exceto a indicada no código 6'),
		(3, 2, 'Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7'),
		(4, 3, 'Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%'),
		(5, 4, 'Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei nº 288/67, e as Leis nºs 8.248/91, 8.387/91, 10.176/01 e 11.484/07'),
		(6, 5, 'Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%'),
		(7, 6, 'Estrangeira - Importação direta, sem similar nacional, constante em lista de Resolução CAMEX e gás natural'),
		(8, 7, 'Estrangeira - Adquirida no mercado interno, sem similar nacional, constante em lista de Resolução CAMEX e gás natural'),
		(9, 8, 'Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%');

	INSERT INTO `Regimes` (`ID`, `Codigo`, `Descricao`) VALUES
		(1, 1, 'Simples Nacional'),
		(2, 2, 'Simples Nacional - excesso de sublimite de receita bruta'),
		(3, 3, 'Regime Normal');

	INSERT INTO `Impostos` (`ID`, `Grupo`, `Simples`, `Substituicao`, `Codigo`, `Descricao`) VALUES
		(1, 'ICMS', 'Y', 'N', 102, 'Tributada pelo Simples Nacional sem permissão de crédito'),
		(2, 'ICMS', 'Y', 'N', 103, 'Isenção do ICMS no Simples Nacional para faixa de receita bruta'),
		(3, 'ICMS', 'Y', 'N', 300, 'Imune'),
		(4, 'ICMS', 'Y', 'N', 400, 'Não tributada pelo Simples Nacional'),
		(5, 'ICMS', 'Y', 'N', 500, 'ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação');

	INSERT INTO `Operacoes` (`ID`, `Codigo`, `Descricao`, `Detalhes`) VALUES
		(1, 5101, 'Venda de produção do estabelecimento', 'Classificam-se neste código as vendas de produtos industrializados no estabelecimento. Também serão classificadas neste código as vendas de mercadorias por estabelecimento industrial de cooperativa destinadas a seus cooperados ou a estabelecimento de outra cooperativa'),
		(2, 5102, 'Venda de mercadoria adquirida ou recebida de terceiros', 'Classificam-se neste código as vendas de mercadorias adquiridas ou recebidas de terceiros para industrialização ou comercialização, que não tenham sido objeto de qualquer processo industrial no estabelecimento. Também serão classificadas neste código as vendas de mercadorias por estabelecimento comercial de cooperativa destinadas a seus cooperados ou estabelecimento de outra cooperativa'),
		(3, 5103, 'Venda de produção do estabelecimento, efetuada fora do estabelecimento', 'Classificam-se neste código as vendas efetuadas fora do estabelecimento, inclusive por meio de veículo, de produtos industrializados no estabelecimento'),
		(4, 5104, 'Venda de mercadoria adquirida ou recebida de terceiros, efetuada fora do estabelecimento', 'Classificam-se neste código as vendas efetuadas fora do estabelecimento, inclusive por meio de veículo, de mercadorias adquiridas ou recebidas de terceiros para industrialização ou comercialização, que não tenham sido objeto de qualquer processo industrial no estabelecimento'),
		(5, 5115, 'Venda de mercadoria adquirida ou recebida de terceiros, recebida anteriormente em consignação mercantil', 'Classificam-se neste código as vendas de mercadorias adquiridas ou recebidas de terceiros, recebidas anteriormente a título de consignação mercantil'),
		(6, 5401, 'Venda de produção do estabelecimento em operação com produto sujeito ao regime de substituição tributária, na condição de contribuinte substituto', 'Classificam-se neste código as vendas de produtos industrializados no estabelecimento em operações com produtos sujeitos ao regime de substituição tributária, na condição de contribuinte substituto. Também serão classificadas neste código as vendas de produtos industrializados por estabelecimento industrial de cooperativa sujeitos ao regime de substituição tributária, na condição de contribuinte substituto.'),
		(7, 5405, 'Venda de mercadoria adquirida ou recebida de terceiros em operação com mercadoria sujeita ao regime de substituição tributária, na condição de contribuinte substituído', 'Classificam-se neste código as vendas de mercadorias adquiridas ou recebidas de terceiros em operação com mercadorias sujeitas ao regime de substituição tributária, na condição de contribuinte substituído');

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Notas_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Notas_BEFORE_INSERT` BEFORE INSERT ON `Notas` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Notas WHERE
			((NumeroInicial BETWEEN NEW.NumeroInicial AND NEW.NumeroFinal) OR
			(NEW.NumeroInicial BETWEEN NumeroInicial AND NumeroFinal)) AND 
	        Sequencia = NEW.Sequencia AND Serie = NEW.Serie AND Ambiente = NEW.Ambiente;
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma nota com esse número ou nesse intervalo";
		ELSEIF NEW.NumeroFinal > 999999999 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da nota não pode ser maior que 999.999.999";
		ELSEIF NEW.NumeroInicial <= 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da nota não pode ser nulo ou negativo";
		ELSEIF NEW.NumeroInicial > NEW.NumeroFinal THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número inicial da nota não pode ser maior que o número final";
		END IF;

	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Notas_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Notas_BEFORE_UPDATE` BEFORE UPDATE ON `Notas` FOR EACH ROW
	BEGIN
		DECLARE _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN

		SELECT COUNT(ID) INTO _count FROM Notas WHERE ID <> OLD.ID AND
			((NumeroInicial BETWEEN NEW.NumeroInicial AND NEW.NumeroFinal) OR
			(NEW.NumeroInicial BETWEEN NumeroInicial AND NumeroFinal)) AND 
	        Sequencia = NEW.Sequencia AND Serie = NEW.Serie AND Ambiente = NEW.Ambiente;
		IF _count > 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma nota com esse número ou nesse intervalo";
		ELSEIF NEW.NumeroFinal > 999999999 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da nota não pode ser maior que 999.999.999";
		ELSEIF NEW.NumeroInicial <= 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da nota não pode ser nulo ou negativo";
		ELSEIF NEW.NumeroInicial > NEW.NumeroFinal THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número inicial da nota não pode ser maior que o número final";
		END IF;

	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1, m.Nome INTO _existe, _nome FROM Pedidos p
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE p.Tipo = 'Mesa' AND p.MesaID = NEW.MesaID AND p.Estado <> 'Finalizado' AND p.Cancelado = 'N';
	        IF _existe = 1 THEN
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
			SELECT 1, m.Nome INTO _existe, _nome FROM Juncoes j
				LEFT JOIN Pedidos p ON p.ID = j.PedidoID
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE j.MesaID = NEW.MesaID AND j.Estado = 'Associado';
	        IF _existe = 1 THEN
				SET _error_msg = CONCAT("A mesa informada já está junta com a mesa '", _nome, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado AND NEW.Cancelado = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto ou serviço foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
	        END IF;
		ELSEIF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Cancelado' WHERE PedidoID = OLD.ID AND Estado <> 'Cancelado';
	        END IF;
	        UPDATE Notas SET Acao = IF(Estado = 'Aberto', 'Inutilizar', 'Cancelar'), Motivo = NEW.Motivo, Concluido = 'N', Corrigido = 'Y' WHERE PedidoID = OLD.ID AND Acao = 'Autorizar';
		ELSEIF OLD.Tipo <> NEW.Tipo AND OLD.Tipo = 'Mesa' THEN
			UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
		END IF;

	END IF;
	END $$

	DELIMITER ;

}

/* Corrigido seleção de associação repetida */
/* Melhorado exclusão de produtos */
Update (Version: "1.9.1.0") {

	ALTER TABLE `Pacotes` DROP FOREIGN KEY `FK_Pacotes_Produtos_ProdutoID`;
	ALTER TABLE `Pacotes` ADD CONSTRAINT `FK_Pacotes_Produtos_ProdutoID`
	    FOREIGN KEY (`ProdutoID`)
	    REFERENCES `Produtos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

	ALTER TABLE `Promocoes` DROP FOREIGN KEY `FK_Promocoes_Produtos_ProdutoID`;
	ALTER TABLE `Promocoes` ADD CONSTRAINT `FK_Promocoes_Produtos_ProdutoID`
	    FOREIGN KEY (`ProdutoID`)
	    REFERENCES `Produtos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE;

	ALTER TABLE `Propriedades` DROP FOREIGN KEY `FK_Propriedades_Grupos_GrupoID`;
	ALTER TABLE `Propriedades` ADD CONSTRAINT `FK_Propriedades_Grupos_GrupoID`
	    FOREIGN KEY (`GrupoID`)
	    REFERENCES `Grupos` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE;

	SET @OLD_DISABLE_TRIGGERS=@DISABLE_TRIGGERS, @DISABLE_TRIGGERS=1;
	UPDATE `Pagamentos`
		SET `Taxas` = -`Taxas` WHERE (`Taxas` < 0 AND `Total` < 0) OR (`Taxas` > 0 AND `Total` > 0);
	UPDATE `Pagamentos`
		SET `Taxas` = 0 WHERE `Taxas` > 0;
	SET @DISABLE_TRIGGERS=@OLD_DISABLE_TRIGGERS;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_INSERT` BEFORE INSERT ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ProdutoID = NEW.ProdutoID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND PropriedadeID = NEW.PropriedadeID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND Selecionado = 'Y' AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Pacotes_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pacotes_BEFORE_UPDATE` BEFORE UPDATE ON `Pacotes` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _count, _prod_count, _prop_count INT DEFAULT 0;
	    DECLARE _grupo_id INT DEFAULT NULL;
		DECLARE _multiplo VARCHAR(1);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Multiplo INTO _multiplo FROM Grupos WHERE ID = NEW.GrupoID;
	    SELECT COALESCE(SUM(IF(ISNULL(ProdutoID), 0, 1)), 0), COALESCE(SUM(IF(ISNULL(PropriedadeID), 0, 1)), 0) INTO _prod_count, _prop_count
			FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID;
	    
	    IF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou propriedade deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.PropriedadeID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Produto e propriedade não deve ser informado ao mesmo tempo";
	    END IF;
	    IF NOT ISNULL(NEW.ProdutoID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ProdutoID = NEW.ProdutoID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto informado já está cadastrado";
	        END IF;
	    ELSEIF NOT ISNULL(NEW.PropriedadeID) THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND PropriedadeID = NEW.PropriedadeID AND ID <> OLD.ID AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A propriedade informada já está cadastrada";
	        END IF;
		END IF;
	    IF NEW.Selecionado = 'Y' AND _multiplo = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Pacotes WHERE GrupoID = NEW.GrupoID AND ID <> OLD.ID AND Selecionado = 'Y' AND AssociacaoID <=> NEW.AssociacaoID;
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe outro pacote selecionado e esse grupo não permite múltiplos itens";
	        END IF;
	    END IF;
	    IF (_prod_count > 0 AND NOT ISNULL(NEW.PropriedadeID)) OR (_prop_count > 0 AND NOT ISNULL(NEW.ProdutoID)) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Em um grupo não pode existir produto e propriedade juntos";
	    ELSE
			SELECT GrupoID INTO _grupo_id FROM Pacotes WHERE ID = NEW.AssociacaoID;
	    	SELECT COUNT(pc.ID) INTO _count FROM Pacotes pc 
				LEFT JOIN Pacotes pca ON pca.ID = pc.AssociacaoID
				WHERE pc.GrupoID = NEW.GrupoID AND pc.ID <> OLD.ID AND NOT (pca.GrupoID <=> _grupo_id);
	        IF _count > 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Os pacotes desse grupo estão associados a um grupo diferente do grupo da associação";
	        END IF;
	    END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Corrigido transferência de valores entre carteiras */
Update (Version: "1.9.2.0") {

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_INSERT` AFTER INSERT ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
		DECLARE _carteira VARCHAR(100);
		DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF (_tipo = 'Dinheiro' OR _tipo = 'Transferencia') AND NEW.Total < 0 AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE (ISNULL(NEW.MovimentacaoID) OR fp.Tipo IN ('Dinheiro', 'Transferencia')) AND (ISNULL(NEW.MovimentacaoID) OR pg.MovimentacaoID = NEW.MovimentacaoID) AND 
	            pg.CarteiraID = NEW.CarteiraID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SELECT Descricao INTO _carteira FROM Carteiras WHERE ID = NEW.CarteiraID;
				SET _error_msg = CONCAT("Não há dinheiro suficiente na carteira '", _carteira, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    END IF;
	    
	END IF;
	END $$


	DROP TRIGGER IF EXISTS `Pagamentos_AFTER_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pagamentos_AFTER_UPDATE` AFTER UPDATE ON `Pagamentos` FOR EACH ROW
	BEGIN
		DECLARE _tipo VARCHAR(40);
	    DECLARE _carteira VARCHAR(100);
	    DECLARE _error_msg VARCHAR(255);
	    DECLARE _dinheiro DECIMAL(19,4);
	IF @DISABLE_TRIGGERS IS NULL THEN
	    
	    SELECT Tipo INTO _tipo FROM Formas_Pagto WHERE ID = NEW.FormaPagtoID;
		IF (_tipo = 'Dinheiro' OR _tipo = 'Transferencia') AND NEW.Total < 0 AND OLD.Ativo = 'N' AND NEW.Ativo = 'Y' THEN
			SELECT SUM(pg.Total) INTO _dinheiro FROM Pagamentos pg
				LEFT JOIN Formas_Pagto fp ON fp.ID = pg.FormaPagtoID
				WHERE (ISNULL(NEW.MovimentacaoID) OR fp.Tipo IN ('Dinheiro', 'Transferencia')) AND (ISNULL(NEW.MovimentacaoID) OR pg.MovimentacaoID = NEW.MovimentacaoID) AND 
	            pg.CarteiraID = NEW.CarteiraID AND pg.Ativo = 'Y' AND pg.Cancelado = 'N';
			IF _dinheiro < 0 THEN
				SELECT Descricao INTO _carteira FROM Carteiras WHERE ID = NEW.CarteiraID;
				SET _error_msg = CONCAT("Não há dinheiro suficiente na carteira '", _carteira, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    END IF;
		IF (OLD.Cancelado = 'N' OR OLD.ContaID <> NEW.ContaID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ContaID) THEN
			UPDATE Contas SET Cancelada = 'Y' WHERE ID = NEW.ContaID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.CreditoID <> NEW.CreditoID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.CreditoID) THEN
			UPDATE Creditos SET Cancelado = 'Y' WHERE ID = NEW.CreditoID;
		ELSEIF (OLD.Cancelado = 'N' OR OLD.ChequeID <> NEW.ChequeID) AND NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ChequeID) THEN
			UPDATE Cheques SET Cancelado = 'Y' WHERE ID = NEW.ChequeID;
		END IF;
	    
	END IF;
	END $$

	DELIMITER ;

}

/* Remoção da taxa de consumação mínima */
/* Corrigido taxas informadas pelo módulo iFood */
/* Criação de módulos de integração */
Update (Version: "1.9.2.1") {

	DROP TABLE IF EXISTS `Consumacoes`;

	DELETE FROM Acessos WHERE PermissaoID = 64;
	DELETE FROM Permissoes WHERE ID = 64;

	ALTER TABLE `Clientes` ADD INDEX `FK_Clientes_Clientes_AcionistaID_idx` (`AcionistaID` ASC);
	ALTER TABLE `Clientes` ADD CONSTRAINT `FK_Clientes_Clientes_AcionistaID`
		FOREIGN KEY (`AcionistaID`)
		REFERENCES `Clientes` (`ID`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;

	ALTER TABLE `Composicoes` DROP INDEX `UK_Composicoes_CompID_ProdID`;
	ALTER TABLE `Composicoes` ADD UNIQUE INDEX `UK_Composicoes_ComposicaoID_ProdutoID_Tipo` (`ComposicaoID` ASC, `ProdutoID` ASC, `Tipo` ASC);

	CREATE TABLE IF NOT EXISTS `Integracoes` (
	  `ID` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador da integração[N:ID][G:o]',
	  `Nome` VARCHAR(45) NOT NULL COMMENT 'Nome do módulo de integração[G:o][N:Nome]',
	  `AcessoURL` VARCHAR(100) NOT NULL COMMENT 'Nome da URL de acesso[N:URL][G:a]',
	  `Descricao` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Descrição do módulo integrador[G:a][N:Descrição]',
	  `IconeURL` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Nome do ícone do módulo integrador[G:o][N:Ícone][I:128x128|integracao|integracao.png]',
	  `Ativo` ENUM('Y', 'N') NOT NULL DEFAULT 'N' COMMENT 'Informa de o módulo de integração está habilitado[G:o][N:Habilitado]',
	  `Token` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Token de acesso à API de sincronização[N:Token][G:o]',
	  `Secret` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Chave secreta para acesso à API[G:a][N:Chave secreta]',
	  `DataAtualizacao` DATETIME NOT NULL COMMENT 'Data de atualização dos dados do módulo de integração[G:a][N:Data de atualização]',
	  PRIMARY KEY (`ID`),
	  UNIQUE INDEX `Nome_UNIQUE` (`Nome` ASC),
	  UNIQUE INDEX `AcessoURL_UNIQUE` (`AcessoURL` ASC))
	ENGINE = InnoDB;

	INSERT INTO `Integracoes` (`Nome`, `AcessoURL`, `Descricao`, `IconeURL`, `Ativo`, `DataAtualizacao`) VALUES
		('iFood', 'ifood', 'Módulo de integração com o iFood', 'ifood.png', 'N', NOW());

	SET @OLD_DISABLE_TRIGGERS=@DISABLE_TRIGGERS, @DISABLE_TRIGGERS=1;
	UPDATE `Pagamentos`
		SET `Taxas` = -`Taxas` WHERE (`Taxas` < 0 AND `Total` < 0) OR (`Taxas` > 0 AND `Total` > 0);
	UPDATE `Pagamentos`
		SET `Taxas` = 0 WHERE `Taxas` > 0;
	SET @DISABLE_TRIGGERS=@OLD_DISABLE_TRIGGERS;

}

/* Criado outro usuário e senha para o banco de dados */
Update (Version: "1.9.2.4") {

	GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'%' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
	SET PASSWORD FOR 'GrandChef'@'%' = PASSWORD('U#@5*8la-K76+9Hs23');

	GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'localhost' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
	SET PASSWORD FOR 'GrandChef'@'localhost' = PASSWORD('U#@5*8la-K76+9Hs23');

	GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'127.0.0.1' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
	SET PASSWORD FOR 'GrandChef'@'127.0.0.1' = PASSWORD('U#@5*8la-K76+9Hs23');

	GRANT ALL PRIVILEGES ON GrandChef.* TO 'GrandChef'@'::1' IDENTIFIED BY 'U#@5*8la-K76+9Hs23';
	SET PASSWORD FOR 'GrandChef'@'::1' = PASSWORD('U#@5*8la-K76+9Hs23');

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_INSERT` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_INSERT` BEFORE INSERT ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo VARCHAR(1);
		DECLARE _login VARCHAR(50);
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT Cancelado, Estado INTO _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF ISNULL(NEW.ProdutoID) AND ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um produto ou serviço deve ser informado";
	    ELSEIF NOT ISNULL(NEW.ProdutoID) AND NOT ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Apenas um produto ou serviço deve ser informado por vez";
	    ELSEIF NOT ISNULL(NEW.ServicoID) AND NOT ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um serviço não pode fazer parte de outro serviço ou produto";
	    ELSEIF NEW.Preco < 0 AND NOT ISNULL(NEW.ProdutoID) THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço como cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço em um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço em um pedido finalizado";
		ELSEIF _ativo = 'N' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	    ELSEIF NEW.Estado <> 'Adicionado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O produto ou serviço deve ser inserido com estado de 'Adicionado'";
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto ou serviço visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado = 'N' AND NOT ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível inserir um produto não visualizado com a data e a hora";
		ELSEIF NOT ISNULL(NEW.ProdutoID) AND _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado, _nome VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo, _aberta VARCHAR(1);
		DECLARE _login VARCHAR(50);
	    DECLARE _movimentacao_id INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT MovimentacaoID, Cancelado, Estado INTO _movimentacao_id, _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NOT ISNULL(_movimentacao_id) THEN
			SELECT mv.Aberta, c.Descricao INTO _aberta, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = _movimentacao_id;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", _movimentacao_id, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido finalizado";
	    ELSEIF ISNULL(OLD.ProdutoID) <> ISNULL(NEW.ProdutoID) OR ISNULL(OLD.ServicoID) <> ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar de produto para serviço ou vice versa";
	    ELSEIF NOT ISNULL(NEW.ServicoID) AND NOT ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um serviço não pode fazer parte de outro serviço ou produto";
	    ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Preco < 0 AND NOT ISNULL(NEW.ProdutoID) THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF NOT ISNULL(NEW.ProdutoID) AND _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	    IF NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ProdutoID) THEN
			UPDATE Estoque SET Cancelado = 'Y' WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$

	DELIMITER ;
}

/* Refatoração e melhoramento da lista de compras */
Update (Version: "1.9.2.5") {

	RENAME TABLE `Produtos_Fornecedores` TO `Catalogos`;
	ALTER TABLE `Catalogos` DROP FOREIGN KEY `FK_ProdFornec_Produtos_ProdutoID`;
	ALTER TABLE `Catalogos` DROP INDEX `FK_ProdFornec_Produtos_ProdutoID_idx`;

	ALTER TABLE `Catalogos` DROP FOREIGN KEY `FK_ProdFornec_Fornecedores_FornecedorID`;
	ALTER TABLE `Catalogos` DROP INDEX `FK_ProdFornec_Fornecedores_FornecedorID_idx`;

	ALTER TABLE `Catalogos` ADD INDEX `FK_Catalogos_Produtos_ProdutoID_idx` (`ProdutoID` ASC);
	ALTER TABLE `Catalogos` ADD INDEX `FK_Catalogos_Fornecedores_FornecedorID_idx` (`FornecedorID` ASC);
	ALTER TABLE `Catalogos` ADD CONSTRAINT `FK_Catalogos_Produtos_ProdutoID`
	    FOREIGN KEY (`ProdutoID`)
	    REFERENCES `Produtos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;
  	ALTER TABLE `Catalogos` ADD CONSTRAINT `FK_Catalogos_Fornecedores_FornecedorID`
	    FOREIGN KEY (`FornecedorID`)
	    REFERENCES `Fornecedores` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

	RENAME TABLE `Listas_Compras` TO `Listas`;

	ALTER TABLE `Listas` DROP FOREIGN KEY `FK_Listas_Compras_Funcionarios_CompradorID`;
	ALTER TABLE `Listas` DROP INDEX `FK_Listas_Compras_Funcionarios_CompradorID_idx`;

	ALTER TABLE `Listas` MODIFY `Descricao` VARCHAR(100) NOT NULL;
	ALTER TABLE `Listas` CHANGE `CompradorID` `EncarregadoID` INT NOT NULL;
	ALTER TABLE `Listas` CHANGE `DataCompra` `DataViagem` DATETIME NOT NULL;

	ALTER TABLE `Listas` ADD INDEX `FK_Listas_Funcionario_EncarregadoID_idx` (`EncarregadoID` ASC);
	ALTER TABLE `Listas` ADD CONSTRAINT `FK_Listas_Funcionario_EncarregadoID`
	    FOREIGN KEY (`EncarregadoID`)
	    REFERENCES `Funcionarios` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

	CREATE TABLE IF NOT EXISTS `Compras` (
	  `ID` INT NOT NULL AUTO_INCREMENT,
	  `Numero` VARCHAR(50) NULL DEFAULT NULL,
	  `CompradorID` INT NOT NULL,
	  `FornecedorID` INT NOT NULL,
	  `DocumentoURL` VARCHAR(150) NULL DEFAULT NULL,
	  `DataCompra` DATETIME NOT NULL,
	  PRIMARY KEY (`ID`),
	  INDEX `FK_Compras_Fornecedores_FornecedorID_idx` (`FornecedorID` ASC),
	  INDEX `FK_Compras_Funcionarios_CompradorID_idx` (`CompradorID` ASC),
	  UNIQUE INDEX `Numero_UNIQUE` (`Numero` ASC),
	  CONSTRAINT `FK_Compras_Fornecedores_FornecedorID`
	    FOREIGN KEY (`FornecedorID`)
	    REFERENCES `Fornecedores` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE,
	  CONSTRAINT `FK_Compras_Funcionarios_CompradorID`
	    FOREIGN KEY (`CompradorID`)
	    REFERENCES `Funcionarios` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE)
	ENGINE = InnoDB;

	RENAME TABLE `Listas_Produtos` TO `Requisitos`;

	ALTER TABLE `Requisitos` DROP FOREIGN KEY `FK_ListaProd_ListaComp_ListaCompraID`;
	ALTER TABLE `Requisitos` DROP INDEX `FK_ListaProd_ListaComp_ListaCompraID_idx`;

	ALTER TABLE `Requisitos` DROP FOREIGN KEY `FK_ListaProd_Produtos_ProdutoID`;
	ALTER TABLE `Requisitos` DROP INDEX `FK_ListaProd_Produtos_ProdutoID_idx`;

	ALTER TABLE `Requisitos` DROP FOREIGN KEY `FK_ListaProd_Fornecedores_FornecedorID`;
	ALTER TABLE `Requisitos` DROP INDEX `FK_ListaProd_Fornecedores_FornecedorID_idx`;

	ALTER TABLE `Requisitos` CHANGE `ListaCompraID` `ListaID` INT NOT NULL;
	ALTER TABLE `Requisitos` ADD `CompraID` INT NULL DEFAULT NULL AFTER `ProdutoID`;
	ALTER TABLE `Requisitos` CHANGE `Comprado` `DataRecolhimento` DATETIME NULL DEFAULT NULL;
	ALTER TABLE `Requisitos` ADD `Comprado` DOUBLE NOT NULL DEFAULT 0 AFTER `Quantidade`;

	ALTER TABLE `Requisitos` ADD INDEX `FK_Requisitos_Listas_ListaID_idx` (`ListaID` ASC);
	ALTER TABLE `Requisitos` ADD INDEX `FK_Requisitos_Produtos_ProdutoID_idx` (`ProdutoID` ASC);
	ALTER TABLE `Requisitos` ADD INDEX `FK_Requisitos_Fornecedores_FornecedorID_idx` (`FornecedorID` ASC);
	ALTER TABLE `Requisitos` ADD INDEX `FK_Requisitos_Compras_CompraID_idx` (`CompraID` ASC);
	ALTER TABLE `Requisitos` ADD CONSTRAINT `FK_Requisitos_Listas_ListaID`
	    FOREIGN KEY (`ListaID`)
	    REFERENCES `Listas` (`ID`)
	    ON DELETE CASCADE
	    ON UPDATE CASCADE;
	ALTER TABLE `Requisitos` ADD CONSTRAINT `FK_Requisitos_Produtos_ProdutoID`
	    FOREIGN KEY (`ProdutoID`)
	    REFERENCES `Produtos` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;
	ALTER TABLE `Requisitos` ADD CONSTRAINT `FK_Requisitos_Fornecedores_FornecedorID`
	    FOREIGN KEY (`FornecedorID`)
	    REFERENCES `Fornecedores` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;
	ALTER TABLE `Requisitos` ADD CONSTRAINT `FK_Requisitos_Compras_CompraID`
	    FOREIGN KEY (`CompraID`)
	    REFERENCES `Compras` (`ID`)
	    ON DELETE RESTRICT
	    ON UPDATE CASCADE;

}

/* Refatoração e melhoramento da lista de compras */
Update (Version: "1.9.3.0") {

	INSERT INTO `Permissoes` (ID, FuncionalidadeID, Nome, Descricao) VALUES
		(95, 1, "EntregarPedidos", "Permitir realizar entrega de pedidos"),
		(96, 5, "InformarDesperdicio", "Permitir informar um desperdício ao cancelar um produto");

	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 95 as PermissaoID FROM Acessos WHERE PermissaoID = 71;
	INSERT INTO `Acessos` (FuncaoID, PermissaoID)
		SELECT FuncaoID, 96 as PermissaoID FROM Acessos WHERE PermissaoID = 66;

	ALTER TABLE `Produtos_Pedidos` ADD `Motivo` VARCHAR(200) NULL DEFAULT NULL AFTER `Cancelado`;
	ALTER TABLE `Produtos_Pedidos` ADD `Desperdicado` ENUM('Y', 'N') NOT NULL DEFAULT 'N' AFTER `Motivo`;

	DELIMITER $$

	DROP TRIGGER IF EXISTS `Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _nome VARCHAR(75);
		DECLARE _aberta VARCHAR(1);
		DECLARE _existe, _caixa_id, _sessao_id, _count INT DEFAULT 0;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		IF NOT ISNULL(NEW.MovimentacaoID) AND (NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) OR NEW.Cancelado = 'Y') THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = NEW.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", NEW.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        ELSEIF _sessao_id <> NEW.SessaoID THEN
				SET _error_msg = CONCAT("A movimentação de id ", NEW.MovimentacaoID, " não faz parte da sessão ", NEW.SessaoID);
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    IF NOT ISNULL(OLD.MovimentacaoID) AND NOT (NEW.MovimentacaoID <=> OLD.MovimentacaoID) THEN
			SELECT mv.Aberta, mv.SessaoID, mv.CaixaID, c.Descricao INTO _aberta, _sessao_id, _caixa_id, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = OLD.MovimentacaoID;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", OLD.MovimentacaoID, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
		IF NEW.SessaoID <> OLD.SessaoID THEN
			SELECT Aberta INTO _aberta FROM Sessoes WHERE ID = NEW.SessaoID;
			IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("A sessão ", NEW.SessaoID, " já foi fechada");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
			END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um pedido cancelado";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Estado <> 'Finalizado' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido não pode ser reaberto";
	    ELSEIF OLD.Estado = 'Finalizado' AND NEW.Cancelado = 'N' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O pedido já foi fechado e não pode mais ser alterado";
	    ELSEIF NEW.Pessoas < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser negativa";
	    ELSEIF NEW.Pessoas = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade de pessoas não pode ser nula";
	    ELSEIF NEW.Pessoas > 1 AND NEW.Tipo = 'Comanda' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Pedidos de comandas são individuais";
	    ELSEIF NEW.Tipo = 'Mesa' AND ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da mesa não foi informado";
	    ELSEIF NEW.Tipo = 'Avulso' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido avulso";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.MovimentacaoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Caixa não informado para o pedido de entrega";
	    ELSEIF NEW.Estado = 'Agendado' AND ISNULL(NEW.DataAgendamento) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de agendamento não foi informada";
	    ELSEIF NEW.Estado = 'Entrega' AND ISNULL(NEW.DataEntrega) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de entrega não foi informada";
	    ELSEIF NEW.Estado = 'Finalizado' AND ISNULL(NEW.DataConclusao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de conclusão não foi informada";
	    ELSEIF NEW.Tipo NOT IN ('Mesa', 'Comanda') AND NOT ISNULL(NEW.MesaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A mesa não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Comanda' AND ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O número da comanda não foi informado";
	    ELSEIF NEW.Tipo <> 'Comanda' AND NOT ISNULL(NEW.ComandaID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A comanda não pode ser informada nesse tipo de pedido";
	    ELSEIF NEW.Tipo = 'Entrega' AND ISNULL(NEW.ClienteID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "O cliente não foi informado";
	    ELSEIF NEW.Tipo = 'Mesa' AND NEW.MesaID <> OLD.MesaID THEN
			SELECT 1, m.Nome INTO _existe, _nome FROM Pedidos p
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE p.Tipo = 'Mesa' AND p.MesaID = NEW.MesaID AND p.Estado <> 'Finalizado' AND p.Cancelado = 'N';
	        IF _existe = 1 THEN
	            SET _error_msg = CONCAT("A mesa '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
			SELECT 1, m.Nome INTO _existe, _nome FROM Juncoes j
				LEFT JOIN Pedidos p ON p.ID = j.PedidoID
				LEFT JOIN Mesas m ON m.ID = p.MesaID
				WHERE j.MesaID = NEW.MesaID AND j.Estado = 'Associado';
	        IF _existe = 1 THEN
				SET _error_msg = CONCAT("A mesa informada já está junta com a mesa '", _nome, "'");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Comanda' AND NEW.ComandaID <> OLD.ComandaID THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Comanda' AND ComandaID = NEW.ComandaID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SELECT Nome INTO _nome FROM Comandas WHERE ID = NEW.ComandaID;
	            SET _error_msg = CONCAT("A comanda '", _nome, "' já está aberta");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	    ELSEIF NEW.Tipo = 'Avulso' AND NEW.Tipo <> OLD.Tipo THEN
			SELECT 1 INTO _existe FROM Pedidos WHERE Tipo = 'Avulso' AND MovimentacaoID = NEW.MovimentacaoID AND FuncionarioID = NEW.FuncionarioID AND Estado <> 'Finalizado' AND Cancelado = 'N';
	        IF _existe = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Já existe uma venda avulsa em andamento";
	        END IF;
		END IF;
	    IF NEW.Estado = 'Finalizado' AND NEW.Estado <> OLD.Estado AND NEW.Cancelado = 'N' THEN
			SELECT COUNT(ID) INTO _count FROM Produtos_Pedidos WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF _count = 0 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Nenhum produto ou serviço foi adicionado ao pedido";
	        END IF;
			SELECT COUNT(ID) INTO _count FROM Pagamentos WHERE PedidoID = OLD.ID AND Cancelado = 'N' AND Ativo = 'N';
	        IF _count = 1 THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Ainda há um pagamento não finalizado";
	        ELSEIF _count > 1 THEN
				SET _error_msg = CONCAT("Ainda há ", _count, " pagamentos não finalizados");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
	        END IF;
		ELSEIF NEW.Cancelado = 'Y' THEN
			UPDATE Produtos_Pedidos SET Motivo = NEW.Motivo, Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
			UPDATE Pagamentos SET Cancelado = 'Y' WHERE PedidoID = OLD.ID AND Cancelado = 'N';
	        IF OLD.Tipo = 'Mesa' THEN
				UPDATE Juncoes SET Estado = 'Cancelado' WHERE PedidoID = OLD.ID AND Estado <> 'Cancelado';
	        END IF;
	        UPDATE Notas SET Acao = IF(Estado = 'Aberto', 'Inutilizar', 'Cancelar'), Motivo = NEW.Motivo, Concluido = 'N', Corrigido = 'Y' WHERE PedidoID = OLD.ID AND Acao = 'Autorizar';
		ELSEIF OLD.Tipo <> NEW.Tipo AND OLD.Tipo = 'Mesa' THEN
			UPDATE Juncoes SET Estado = 'Liberado' WHERE PedidoID = OLD.ID AND Estado = 'Associado';
		END IF;

	END IF;
	END $$

	DROP TRIGGER IF EXISTS `Produtos_Pedidos_BEFORE_UPDATE` $$
	CREATE DEFINER = CURRENT_USER TRIGGER `Produtos_Pedidos_BEFORE_UPDATE` BEFORE UPDATE ON `Produtos_Pedidos` FOR EACH ROW
	BEGIN
		DECLARE _error_msg VARCHAR(255);
		DECLARE _descricao, _estado, _nome VARCHAR(75);
		DECLARE _divisivel, _cancelado, _ativo, _aberta VARCHAR(1);
		DECLARE _login VARCHAR(50);
	    DECLARE _movimentacao_id INT;
	IF @DISABLE_TRIGGERS IS NULL THEN
		
		SELECT Descricao, Divisivel INTO _descricao, _divisivel FROM Produtos WHERE ID = NEW.ProdutoID;
		SELECT MovimentacaoID, Cancelado, Estado INTO _movimentacao_id, _cancelado, _estado FROM Pedidos WHERE ID = NEW.PedidoID;
	    SELECT f.Ativo, cf.Login INTO _ativo, _login FROM Funcionarios f LEFT JOIN Clientes cf ON cf.ID = f.ClienteID WHERE f.ID = NEW.FuncionarioID;
	    
	    IF NOT ISNULL(_movimentacao_id) THEN
			SELECT mv.Aberta, c.Descricao INTO _aberta, _nome FROM Movimentacoes mv
				LEFT JOIN Caixas c ON c.ID = mv.CaixaID
				WHERE mv.ID = _movimentacao_id;
	        IF _aberta = 'N' THEN
				SET _error_msg = CONCAT("O caixa '", _nome, "' da movimentacão ", _movimentacao_id, " já foi fechado");
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
	        END IF;
		END IF;
	    
	    IF OLD.Cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço cancelado";
	    ELSEIF _cancelado = 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido cancelado";
	    ELSEIF _estado = 'Finalizado' AND NEW.Cancelado <> 'Y' THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar um produto ou serviço de um pedido finalizado";
	    ELSEIF ISNULL(OLD.ProdutoID) <> ISNULL(NEW.ProdutoID) OR ISNULL(OLD.ServicoID) <> ISNULL(NEW.ServicoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível alterar de produto para serviço ou vice versa";
	    ELSEIF NOT ISNULL(NEW.ServicoID) AND NOT ISNULL(NEW.ProdutoPedidoID) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Um serviço não pode fazer parte de outro serviço ou produto";
	    ELSEIF NEW.Quantidade < 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser negativa";
	    ELSEIF NEW.Quantidade = 0 THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A quantidade não pode ser nula";
	    ELSEIF NEW.Preco < 0 AND NOT ISNULL(NEW.ProdutoID) THEN
			SET _error_msg = CONCAT("O valor do produto '", _descricao, "' não pode ser negativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF _ativo = 'N' AND NEW.Cancelado <> 'Y' THEN
			SET _error_msg = CONCAT("O funcionário '", _login, "' não está ativo");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		ELSEIF NEW.Visualizado = 'Y' AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Não é possível atualizar um produto visualizado sem a data e a hora";
		ELSEIF NEW.Visualizado <> OLD.Visualizado AND ISNULL(NEW.DataVisualizacao) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "A data de visualização não pode mais ser nula";
		ELSEIF NOT ISNULL(NEW.ProdutoID) AND _divisivel = 'N' AND MOD(NEW.Quantidade, 1) > 0 THEN
			SET _error_msg = CONCAT("O produto '", _descricao, "' não é divisível");
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = _error_msg;
		END IF;
	    IF NEW.Cancelado = 'Y' AND NOT ISNULL(NEW.ProdutoID) AND NEW.Desperdicado = 'N' THEN
			UPDATE Estoque SET Cancelado = 'Y' WHERE TransacaoID = OLD.ID;
		END IF;
	END IF;
	END $$

	DELIMITER ;

}

/* Adicionado quantidade máxima e mínima nos pacotes */
Update (Version: "1.9.3.5") {
	ALTER TABLE `Pacotes` DROP `Quantidade`;
	ALTER TABLE `Pacotes` ADD `QuantidadeMinima` INT NOT NULL DEFAULT 0 AFTER `AssociacaoID`;
	ALTER TABLE `Pacotes` ADD `QuantidadeMaxima` INT NOT NULL DEFAULT 1 AFTER `QuantidadeMinima`;

	INSERT INTO `Integracoes` (`Nome`, `AcessoURL`, `Descricao`, `IconeURL`, `Ativo`, `DataAtualizacao`) VALUES
		('Kromax', 'kromax', 'Módulo de integração com a Kromax', 'kromax.png', 'N', NOW());

	ALTER TABLE `Paises` ADD `Codigo` VARCHAR(10) NULL DEFAULT NULL AFTER `Sigla`;
	UPDATE `Paises` SET `Codigo` = 
		(CASE Sigla
			WHEN 'BRA' THEN 'BR'
			WHEN 'USA' THEN 'US'
			WHEN 'ESP' THEN 'ES'
			WHEN 'MOZ' THEN 'MZ'
			ELSE Sigla
		END);
	ALTER TABLE `Paises` MODIFY `Codigo` VARCHAR(10) NOT NULL;
	ALTER TABLE `Paises` ADD UNIQUE INDEX `Codigo_UNIQUE` (`Codigo` ASC);
	ALTER TABLE `Formacoes` ADD `Quantidade` DOUBLE NOT NULL DEFAULT 1;
}
