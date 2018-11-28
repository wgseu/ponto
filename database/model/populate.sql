SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

USE `GrandChef`;

INSERT INTO `Categorias` (`ID`, `CategoriaID`, `Descricao`, `Servico`, `ImagemURL`, `DataAtualizacao`) VALUES
	(13, NULL, 'Verduras', 'N', NULL, NOW()),
	(14, NULL, 'Açaí', 'N', NULL, NOW()),
	(15, NULL, 'Óleos', 'N', NULL, NOW()),
	(16, NULL, 'Temperos', 'N', NULL, NOW());

INSERT INTO `Produtos` (`ID`, `Codigo`, `CategoriaID`, `UnidadeID`, `SetorEstoqueID`, `SetorPreparoID`, `TributacaoID`, `Descricao`, `Abreviacao`, `Detalhes`, `QuantidadeLimite`, `QuantidadeMaxima`, `Conteudo`, `PrecoVenda`, `CustoProducao`, `Tipo`, `CobrarServico`, `Divisivel`, `Pesavel`, `Perecivel`, `TempoPreparo`, `Visivel`, `ImagemURL`, `DataAtualizacao`) VALUES
	(1, '1', 4, 1, 1, 4, NULL, 'Coca Cola 350ml', NULL, NULL, 12, 36, 1, 3.5, NULL, 'Produto', 'Y', 'N', 'N', 'N', 0, 'Y', 'coca-cola_lata_350ml.png', NOW()),
	(2, '2', 10, 1, 1, 2, NULL, 'X-Burguer', NULL, NULL, 0, 0, 1, 15, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'Y', 'x-burguer.png', NOW()),
	(3, '3', 13, 1, 1, 2, NULL, 'Alface', NULL, NULL, 3, 6, 1, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'alface.png', NOW()),
	(4, '4', 13, 1, 1, 2, NULL, 'Tomate', NULL, NULL, 20, 40, 1, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'tomate.png', NOW()),
	(5, '5', 1, 1, 1, 2, NULL, 'Pizza', NULL, NULL, 0, 0, 1, 0, NULL, 'Pacote', 'Y', 'N', 'N', 'N', 0, 'Y', 'pizza.png', NOW()),
	(6, '6', 10, 1, 1, 2, NULL, 'X-Bacon', NULL, NULL, 0, 0, 1, 18, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'Y', 'x-bacon.png', NOW()),
	(7, '7', 1, 1, 1, 2, NULL, 'Mussarela', NULL, NULL, 0, 0, 1, 0, NULL, 'Composicao', 'Y', 'Y', 'N', 'N', 0, 'N', 'pizza_mussarela.png', NOW()),
	(8, '8', 1, 1, 1, 2, NULL, 'Calabresa', NULL, NULL, 0, 0, 1, 0, NULL, 'Composicao', 'Y', 'Y', 'N', 'N', 0, 'N', 'pizza_calabresa.png', NOW()),
	(9, '9', 1, 3, 1, 2, NULL, 'Catupiry 1kg', NULL, NULL, 2, 6, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'catupiry.png', NOW()),
	(10, '10', 1, 3, 1, 2, NULL, 'Cheddar 1kg', NULL, NULL, 2, 6, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'cheddar.png', NOW()),
	(11, '11', 1, 1, 1, 2, NULL, 'Pão', NULL, NULL, 30, 90, 1, 0, NULL, 'Produto', 'Y', 'N', 'N', 'N', 0, 'N', 'pao.png', NOW()),
	(12, '12', 1, 1, 1, 2, NULL, 'Ovo', NULL, NULL, 15, 90, 1, 0, NULL, 'Produto', 'Y', 'N', 'N', 'N', 0, 'N', 'ovo.png', NOW()),
	(13, '13', 1, 1, 1, 2, NULL, 'Cebola', NULL, NULL, 20, 40, 1, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'cebola.png', NOW()),
	(14, '14', 1, 3, 1, 2, NULL, 'Bacon 1kg', 'Bacon', NULL, 3, 6, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'bacon.png', NOW()),
	(15, '15', 1, 1, 1, 2, NULL, 'Hamburguer artesanal', 'Hamburguer', NULL, 0, 0, 1, 0, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'N', 'hamburguer_artesanal.png', NOW()),
	(16, '16', 14, 1, 1, 2, NULL, 'Açaí', NULL, NULL, 0, 0, 1, 8, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'Y', 'acai.png', NOW()),
	(17, '17', 14, 3, 1, 2, NULL, 'Morango 1kg', 'Morango', NULL, 1, 2, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'morango.png', NOW()),
	(18, '18', 14, 3, 1, 2, NULL, 'Chocolate 1kg', 'Chocolate', NULL, 1, 3, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'chocolate.png', NOW()),
	(19, '19', 14, 3, 1, NULL, NULL, 'Leite condensado 1kg', 'Leite condensado', NULL, 0.35, 1.75, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'leite_condensado.png', NOW()),
	(20, '20', 14, 3, 1, NULL, NULL, 'Polpa de açai 1kg', 'Açaí', NULL, 2, 5, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'Y', 0, 'N', 'polpa_de_acai.png', NOW()),
	(21, '21', 1, 3, 1, NULL, NULL, 'Massa de pizza', NULL, NULL, 2, 5, 1000, 0, NULL, 'Composicao', 'Y', 'Y', 'N', 'N', 0, 'N', 'massa_de_pizza.png', NOW()),
	(22, '22', 15, 2, 1, NULL, NULL, 'Óleo de soja 1L', NULL, NULL, 1.8, 7.2, 1, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'oleo_de_soja.png', NOW()),
	(23, '23', 16, 3, 1, NULL, NULL, 'Orégano 1kg', 'Orégano', NULL, 0.4, 1, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'oregano.png', NOW()),
	(24, '24', 1, 3, 1, 2, NULL, 'Farinha de trigo 1kg', 'Farinha de trigo', NULL, 10, 20, 1000, 0, NULL, 'Produto', 'Y', 'Y', 'N', 'N', 0, 'N', 'farinha_de_trigo.png', NOW()),
	(25, '25', 1, 1, 1, 2, NULL, 'Borda de Catupiry', 'Catupiry', NULL, 60, 120, 1, 0, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'N', 'borda_de_catupiry.png', NOW()),
	(26, '26', 1, 1, 1, 2, NULL, 'Borda de Cheddar', 'Cheddar', NULL, 60, 120, 1, 0, NULL, 'Composicao', 'Y', 'N', 'N', 'N', 0, 'N', 'borda_de_cheddar.png', NOW());

INSERT INTO `Composicoes` (`ID`, `ComposicaoID`, `ProdutoID`, `Tipo`, `Quantidade`, `Valor`, `Ativa`) VALUES
	(1, 2, 3, 'Opcional', 0.2, 0, 'Y'),
	(2, 2, 4, 'Opcional', 0.5, 0, 'Y'),
	(6, 2, 11, 'Composicao', 1, 0, 'Y'),
	(7, 2, 12, 'Adicional', 1, 1, 'Y'),
	(8, 2, 13, 'Opcional', 0.5, 0, 'Y'),
	(9, 2, 14, 'Adicional', 0.2, 1, 'Y'),
	(10, 2, 15, 'Adicional', 1, 2.5, 'Y'),
	(11, 6, 3, 'Opcional', 0.2, 0, 'Y'),
	(12, 6, 4, 'Opcional', 0.5, 0, 'Y'),
	(13, 6, 11, 'Composicao', 1, 0, 'Y'),
	(14, 6, 12, 'Adicional', 1, 1, 'Y'),
	(15, 6, 13, 'Opcional', 0.5, 0, 'Y'),
	(16, 6, 14, 'Adicional', 0.2, 1, 'Y'),
	(17, 6, 15, 'Adicional', 1, 2.5, 'Y'),
	(18, 16, 17, 'Adicional', 0.08, 1, 'Y'),
	(19, 16, 18, 'Adicional', 0.1, 2.5, 'Y'),
	(20, 16, 19, 'Adicional', 0.05, 3, 'Y'),
	(21, 16, 20, 'Composicao', 0.3, 0, 'Y'),
	(22, 7, 21, 'Composicao', 0.2, 0, 'Y'),
	(23, 8, 21, 'Composicao', 0.2, 0, 'Y'),
	(24, 21, 22, 'Composicao', 0.01, 0, 'Y'),
	(25, 8, 13, 'Opcional', 0.5, 0, 'Y'),
	(26, 7, 23, 'Opcional', 0.005, 0, 'Y'),
	(27, 21, 24, 'Composicao', 1, 0, 'Y'),
	(28, 25, 9, 'Composicao', 0.1, 0, 'Y'),
	(29, 26, 10, 'Composicao', 0.1, 0, 'Y');

INSERT INTO `Estoque` (`ID`, `ProdutoID`, `TransacaoID`, `EntradaID`, `FornecedorID`, `SetorID`, `PrestadorID`, `TipoMovimento`, `Quantidade`, `PrecoCompra`, `Lote`, `DataFabricacao`, `DataVencimento`, `Detalhes`, `Cancelado`, `DataMovimento`) VALUES
	(1, 1, NULL, NULL, NULL, 1, 1, 'Entrada', 24, 2, '546546', NOW(), NULL, NULL, 'N', NOW()),
	(2, 3, NULL, NULL, NULL, 1, 1, 'Entrada', 4, 2, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(3, 4, NULL, NULL, NULL, 1, 1, 'Entrada', 30, 0.5, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(4, 9, NULL, NULL, NULL, 1, 1, 'Entrada', 3, 50, '60555', NOW(), NULL, NULL, 'N', NOW()),
	(5, 10, NULL, NULL, NULL, 1, 1, 'Entrada', 3, 56, '10055', NOW(), NULL, NULL, 'N', NOW()),
	(6, 11, NULL, NULL, NULL, 1, 1, 'Entrada', 40, 0.3, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(7, 12, NULL, NULL, NULL, 1, 1, 'Entrada', 30, 0.33, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(8, 13, NULL, NULL, NULL, 1, 1, 'Entrada', 30, 0.3, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(9, 14, NULL, NULL, NULL, 1, 1, 'Entrada', 4, 30, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(10, 17, NULL, NULL, NULL, 1, 1, 'Entrada', 1.5, 40, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(11, 18, NULL, NULL, NULL, 1, 1, 'Entrada', 2, 20, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(12, 19, NULL, NULL, NULL, 1, 1, 'Entrada', 1.05, 11.43, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(13, 20, NULL, NULL, NULL, 1, 1, 'Entrada', 3, 12, NULL, NOW(), NOW(), NULL, 'N', NOW()),
	(14, 22, NULL, NULL, NULL, 1, 1, 'Entrada', 4.5, 4.22, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(15, 23, NULL, NULL, NULL, 1, 1, 'Entrada', 0.6, 20, NULL, NOW(), NULL, NULL, 'N', NOW()),
	(16, 24, NULL, NULL, NULL, 1, 1, 'Entrada', 15, 6, NULL, NOW(), NULL, NULL, 'N', NOW());

INSERT INTO `Grupos` (`ID`, `ProdutoID`, `Nome`, `Descricao`, `Multiplo`, `Tipo`, `QuantidadeMinima`, `QuantidadeMaxima`, `Funcao`) VALUES
	(1, 5, 'Tamanho', 'Tamanho', 'N', 'Inteiro', 1, 1, 'Soma'),
	(2, 5, 'Sabores', 'Sabores', 'Y', 'Fracionado', 1, 2, 'Media'),
	(3, 5, 'Borda', 'Borda', 'N', 'Inteiro', 0, 1, 'Soma');

INSERT INTO `Propriedades` (`ID`, `GrupoID`, `Nome`, `Abreviacao`, `DataAtualizacao`) VALUES
	(1, 1, 'Pequena', 'P', NOW()),
	(2, 1, 'Média', 'M', NOW()),
	(3, 1, 'Grande', 'G', NOW());

INSERT INTO `Pacotes` (`ID`, `PacoteID`, `GrupoID`, `ProdutoID`, `PropriedadeID`, `AssociacaoID`, `QuantidadeMinima`, `QuantidadeMaxima`, `Valor`, `Selecionado`, `Visivel`) VALUES
	(1, 5, 1, NULL, 1, NULL, 0, 1, 0, 'N', 'Y'),
	(2, 5, 1, NULL, 2, NULL, 0, 1, 0, 'N', 'Y'),
	(3, 5, 1, NULL, 3, NULL, 0, 1, 0, 'Y', 'Y'),
	(6, 5, 3, 25, NULL, 1, 0, 1, 0.5, 'N', 'Y'),
	(7, 5, 3, 25, NULL, 2, 0, 1, 0.8, 'N', 'Y'),
	(8, 5, 3, 25, NULL, 3, 0, 1, 1.2, 'N', 'Y'),
	(9, 5, 3, 26, NULL, 3, 0, 1, 1.2, 'N', 'Y'),
	(10, 5, 3, 26, NULL, 2, 0, 1, 0.8, 'N', 'Y'),
	(11, 5, 3, 26, NULL, 1, 0, 1, 0.5, 'N', 'Y'),
	(12, 5, 2, 7, NULL, 1, 0, 1, 32.9, 'N', 'Y'),
	(13, 5, 2, 8, NULL, 1, 0, 1, 34.9, 'N', 'Y'),
	(14, 5, 2, 7, NULL, 2, 0, 1, 36.9, 'N', 'Y'),
	(15, 5, 2, 8, NULL, 2, 0, 1, 38.9, 'N', 'Y'),
	(16, 5, 2, 7, NULL, 3, 0, 1, 42.9, 'N', 'Y'),
	(17, 5, 2, 8, NULL, 3, 0, 1, 44.9, 'N', 'Y');

INSERT INTO `Sessoes` (`ID`, `DataInicio`, `DataTermino`, `Aberta`) VALUES
	(1, NOW(), NULL, 'Y');

INSERT INTO `Movimentacoes` (`ID`, `SessaoID`, `CaixaID`, `Aberta`, `IniciadorID`, `FechadorID`, `DataFechamento`, `DataAbertura`) VALUES
	(1, 1, 1, 'Y', 1, NULL, NULL, NOW());

SET SQL_MODE=@OLD_SQL_MODE;
