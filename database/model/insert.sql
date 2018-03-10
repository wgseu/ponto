SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

USE `GrandChef`;

INSERT INTO `Moedas` (`ID`, `Nome`, `Simbolo`, `Codigo`, `Divisao`, `Fracao`, `Formato`) VALUES
	(1, "Real", "R$", "BRL", 100, "Centavo", "R$ %s"),
	(2, "Dollar", "$", "USD", 100, "Cent", "$ %s"),
	(3, "Euro", "€", "EUR", 100, "Cent", "€ %s"),
	(4, "Metical", "MT", "MZN", 100, "Centavo", "%s MT");

INSERT INTO `Paises` (`ID`, `Nome`, `Sigla`, `MoedaID`, `BandeiraIndex`, `LinguagemID`, `Entradas`, `Unitario`) VALUES
	(1, "Brasil", "BRA", 1, 28, 1046, NULL, 'N'),
	(2, "United States of America", "USA", 2, 220, 1033, 'W1RpdHVsb10NCkNFUD1aSVANCkNQRj1TU04NCg0KW01hc2NhcmFdDQpDRVA9OTk5OTkNCkNQRj05OTktOTktOTk5OQ0KVGVsZWZvbmU9KDk5OSkgOTk5LTk5OTkNCg0KW0F1ZGl0b3JpYV0NCg0K', 'N'),
	(3, "España", "ESP", 3, 66, 1034, 'W1RpdHVsb10NCkNOUEo9UlVUDQpDUEY9TlVJUA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNQRj05Ljk5OS45OTkuOTk5DQpDRVA9OTk5OTkNCkNOUEo9OTkuOTk5Ljk5OS05DQpUZWxlZm9uZT05OTkgOTk5IDk5OQ0KDQo=', 'Y'),
	(4, "Moçambique", "MOZ", 4, 151, 1046, 'W1RpdHVsb10NCkNOUEo9TlVJVA0KQ0VQPUNPUA0KDQpbTWFzY2FyYV0NCkNOUEo9OS45OTk5OTk5LTkNCkNFUD05OTk5DQpUZWxlZm9uZT05OTk5LTk5OTk5DQoNCg==', 'Y');

INSERT INTO `Estados` (`ID`, `PaisID`, `Nome`, `UF`) VALUES
	(1, 1, "Acre", "AC"),
	(2, 1, "Alagoas", "AL"),
	(3, 1, "Amazonas", "AM"),
	(4, 1, "Amapá", "AP"),
	(5, 1, "Bahia", "BA"),
	(6, 1, "Ceará", "CE"),
	(7, 1, "Brasília", "DF"),
	(8, 1, "Espírito Santo", "ES"),
	(9, 1, "Goiás", "GO"),
	(10, 1, "Maranhão", "MA"),
	(11, 1, "Minas Gerais", "MG"),
	(12, 1, "Mato Grosso do Sul", "MS"),
	(13, 1, "Mato Grosso", "MT"),
	(14, 1, "Pará", "PA"),
	(15, 1, "Paraíba", "PB"),
	(16, 1, "Pernambuco", "PE"),
	(17, 1, "Piauí", "PI"),
	(18, 1, "Paraná", "PR"),
	(19, 1, "Rio de Janeiro", "RJ"),
	(20, 1, "Rio Grande do Norte", "RN"),
	(21, 1, "Rondônia", "RO"),
	(22, 1, "Roraima", "RR"),
	(23, 1, "Rio Grande do Sul", "RS"),
	(24, 1, "Santa Catarina", "SC"),
	(25, 1, "Sergipe", "SE"),
	(26, 1, "São Paulo", "SP"),
	(27, 1, "Tocantins", "TO");

INSERT INTO `Funcoes` (`ID`, `Descricao`, `SalarioBase`) VALUES
	(1, "Administrador", 1400),
	(2, "Garçom", 880),
	(3, "Operador(a) de Caixa", 1200),
	(4, "Cozinheiro(a)", 880),
	(5, "Zelador(a)", 880),
	(6, "Entregador(a)", 880),
	(7, "Estoquista", 880);

INSERT INTO `Modulos` (`ID`, `Nome`, `Descricao`, `ImageIndex`, `Habilitado`) VALUES
	(1, "Mesas", "Permite realizar vendas para mesas", 4, 'Y'),
	(2, "Venda rápida", "Permite realizar vendas rápidas com código de barras", 5, 'Y'),
	(3, "Cartão de consumo", "Permite realizar vendas com cartão de consumo", 6, 'Y'),
	(4, "Delivery", "Permite realizar vendas para entrega", 7, 'Y'),
	(5, "Estoque", "Permite controlar estoque com cadastro de fornecedores", 8, 'Y'),
	(6, "Controle de contas", "Permite cadastrar contas a pagar e a receber", 9, 'Y'),
	(7, "Comanda eletrônica", "Permite realizar pedidos pelo celular ou tablet", 10, 'Y');

INSERT INTO `Funcionalidades` (`ID`, `Nome`, `Descricao`) VALUES
	(1, "Operacional", "Controla operações nas telas do sistema"),
	(2, "Vendas", "Controla cancelamentos e outras operações de vendas"),
	(3, "Mesas e Comandas", "Controla operações sobre mesas e comandas"),
	(4, "Financeiro", "Controla caixas e pagamentos de pedidos"),
	(5, "Estoque", "Controla acessos ao gerenciamento do estoque"),
	(6, "Cadastros", "Permite acessos às telas de cadastros"),
	(7, "Relatórios", "Controla a visualização ou emissão de relatórios"),
	(8, "Sistema", "Controla dispositivos e comportamento do sistema");

INSERT INTO `Permissoes` (`ID`, `FuncionalidadeID`, `Nome`, `Descricao`) VALUES
	(1, 1, "Sistema", "Permitir acesso ao sistema"),
	(2, 8, "Restauracao", "Permitir restaurar o banco de dados"),
	(3, 8, "Backup", "Permitir realização de cópia de segurança do banco de dados"),
	(4, 2, "PedidoMesa", "Permitir realizar pedidos para uma mesa"),
	(5, 2, "Pagamento", "Permitir realizar um pagamento e efetuar vendas rápidas"),
	(6, 3, "MudarDeMesa", "Permitir mudar os pedidos de uma mesa para outra"),
	(7, 2, "ExcluirPedido", "Permitir cancelar produtos de um pedido"),
	(8, 3, "ReservarMesa", "Permitir reservar uma mesa"),
	(9, 3, "LiberarMesa", "Permitir liberar mesa reservada por outro funcionário"),
	(10, 4, "SelecionarCaixa", "Permitir selecionar outro caixa aberto"),
	(11, 4, "AbrirCaixa", "Permitir abrir o caixa"),
	(12, 4, "InserirNoCaixa", "Permitir inserir dinheiro no caixa"),
	(13, 4, "RetirarDoCaixa", "Permitir retirar dinheiro do caixa"),
	(14, 4, "FecharCaixa", "Permitir fechar qualquer caixa aberto"),
	(15, 3, "AlterarPreco", "Permitir alterar o preço de um produto no momento da venda"),
	(16, 3, "Mesas", "Permitir acesso à venda para todas as mesas"),
	(17, 5, "Estoque", "Permitir dar entrada de produtos no estoque"),
	(18, 8, "OpcoesImpressao", "Permitir alteração das opções de impressão de relatórios"),
	(19, 8, "TrocarPlano", "Permitir trocar plano de fundo da aplicação"),
	(20, 8, "ModoTela", "Permitir trocar o modo de tela da aplicação"),
	(21, 7, "RelatorioVendas", "Permitir visualizar todas as vendas"),
	(22, 7, "RelatorioVendedor", "Permitir visualizar o total das vendas de todos vendedores"),
	(23, 7, "RelatorioCaixa", "Permitir visualizar o relatório de vendas por caixa"),
	(24, 7, "RelatorioSessao", "Permitir visualizar o total de vendas de cada sessão"),
	(25, 7, "RelatorioConsumo", "Permitir a impressão do relatório de consumo da mesa"),
	(26, 7, "RelatorioCozinha", "Permitir a reimpressão dos pedidos enviados para a cozinha"),
	(27, 7, "RankDeVendas", "Permitir visualizar o ranking dos funcionários nas vendas"),
	(28, 6, "CadastroProdutos", "Permitir cadastrar ou alterar um produto"),
	(29, 6, "CadastroFornecedores", "Permitir cadastrar ou alterar um fornecedor"),
	(30, 6, "CadastroMesas", "Permitir cadastrar ou alterar uma mesa"),
	(31, 6, "CadastroFuncionarios", "Permitir cadastrar ou alterar um funcionário"),
	(32, 6, "CadastroFormasPagto", "Permitir cadastrar ou alterar uma forma de pagamento"),
	(33, 6, "CadastroPromocoes", "Permitir cadastrar ou alterar uma promoção"),
	(34, 6, "CadastroCartoes", "Permitir cadastrar ou alterar um cartão"),
	(35, 6, "CadastroBancos", "Permitir cadastrar ou alterar um banco"),
	(36, 6, "CadastroCaixas", "Permitir cadastrar ou alterar um caixa"),
	(37, 6, "CadastroTiposDeContas", "Permitir cadastrar ou alterar um tipo de conta"),
	(38, 6, "CadastroImpressoras", "Permitir cadastrar ou alterar uma impressora"),
	(39, 6, "CadastroComputadores", "Permitir cadastrar ou alterar um computador"),
	(40, 1, "ComputadorCaixa", "Permitir acessar computadores reservados para o caixa"),
	(41, 6, "CadastroClientes", "Permitir cadastrar ou alterar um cliente"),
	(42, 1, "EntregaPedidos", "Permitir acessar os pedidos de produtos que são para entrega"),
	(43, 1, "AlterarAtendente", "Permitir alterar o atendente no momento da venda"),
	(44, 2, "PedidoComanda", "Permitir realizar pedidos para cartões de consumo"),
	(45, 6, "CadastroBairros", "Permitir cadastrar ou alterar informações de um bairro"),
	(46, 6, "CadastroContas", "Permitir cadastrar ou alterar contas a pagar ou a receber"),
	(47, 7, "RelatorioContas", "Permitir visualizar relatórios de contas"),
	(48, 7, "RelatorioProdutos", "Permitir visualizar relatórios de vendas de produtos"),
	(49, 7, "RelatorioCompras", "Permitir visualizar relatórios de compras de produtos"),
	(50, 6, "CadastroComandas", "Permitir cadastrar ou alterar um número de comanda"),
	(51, 6, "CadastroServicos", "Permitir cadastrar ou alterar uma taxa ou evento"),
	(52, 2, "RealizarDescontos", "Permitir realizar desconto nas vendas"),
	(53, 3, "Comandas", "Permitir acesso à venda para todas as comandas"),
	(54, 4, "ExcluirPedidoFinalizado", "Permitir excluir um pedido que já foi finalizado"),
	(55, 7, "RelatorioFuncionarios", "Permitir visualizar relatório de funcionários"),
	(56, 7, "RelatorioClientes", "Permitir visualizar relatório de clientes"),
	(57, 4, "RevogarComissao", "Permitir retirar a comissão de um pedido"),
	(58, 1, "SelecionarEntregador", "Permitir selecionar outro entregador na entrega de pedidos para entrega"),
	(59, 3, "TransferirProdutos", "Permitir transferir produtos de uma mesa para outra"),
	(60, 7, "RelatorioPedidos", "Permitir visualizar relatório de pedidos"),
	(61, 8, "AlterarConfiguracoes", "Permitir alterar informações da empresa e configurações do sistema"),
	(62, 6, "ListaCompras", "Permitir cadastrar lista de compras de produtos"),
	(63, 7, "RelatorioMensal", "Permitir visualizar e emitir relatórios de vendas mensais"),
	(65, 6, "CadastroCidades", "Permitir cadastrar ou alterar as cidades dos estados"),
	(66, 5, "RetirarDoEstoque", "Permitir retirar produtos do estoque"),
	(67, 7, "RelatorioBairros", "Permitir visualizar relatórios de bairros"),
	(68, 1, "AlterarHorario", "Permitir alterar o horário de funcionamento do estabelecimento"),
	(69, 6, "CadastrarCreditos", "Permitir cadastrar e alterar créditos de clientes"),
	(70, 1, "AlterarStatus", "Permitir alterar os estados de preparo dos produtos"),
	(71, 7, "RelatorioEntrega", "Permitir visualizar relatório de entrega por entregador"),
	(72, 7, "RelatorioFornecedores", "Permitir visualizar relatório de fornecedores"),
	(73, 3, "MudarDeComanda", "Permitir mudar os pedidos de uma comanda para comanda"),
	(74, 7, "RelatorioAuditoria", "Permitir visualizar o relatório de auditoria"),
	(75, 7, "RelatorioConsumidor", "Permitir visualizar o relatório de vendas por cliente"),
	(76, 7, "RelatorioCreditos", "Permitir visualizar o relatório de créditos de clientes"),
	(77, 6, "CadastroCarteiras", "Permitir cadastrar carteiras e contas bancárias"),
	(78, 7, "RelatorioFluxo", "Permitir visualizar o relatório de fluxo de caixa"),
	(79, 4, "TransferirValores", "Permitir transferir dinheiro de um caixa para outro"),
	(80, 6, "CadastroPatrimonio", "Permitir cadastrar e atualizar a quantidade de bens de uma empresa"),
	(81, 7, "RelatorioPatrimonio", "Permitir visualizar a lista de bens de uma empresa"),
	(82, 7, "RelatorioCarteiras", "Permitir visualizar o relatório de carteiras"),
	(83, 7, "RelatorioCheques", "Permitir visualizar o relatório de cheques"),
	(84, 2, "PagamentoConta", "Permitir pagar um pedido na forma de pagamento Conta"),
	(85, 6, "CadastroPaises", "Permitir cadastrar ou alterar paises"),
	(86, 6, "CadastroEstados", "Permitir cadastrar ou alterar os estados de um país"),
	(87, 6, "CadastroMoedas", "Permitir cadastrar ou alterar os tipos de moedas"),
	(88, 8, "AlterarPaginas", "Permitir alterar as páginas do site da empresa"),
	(89, 1, "AlterarEntregador", "Permitir alterar o entregador após enviar os pedidos"),
	(90, 7, "RelatorioBalanco", "Permitir visualizar o relatório de balanço de contas"),
	(91, 1, "TransformarEntrega", "Permitir transformar um pedido de entrega para viagem e vice versa"),
	(92, 4, "ConferirCaixa", "Permitir alterar os valores de conferência de um caixa"),
	(93, 2, "ContaViagem", "Permitir imprimir conta de pedidos para viagem"),
	(94, 2, "EntregaAdicionar", "Permitir adicionar produtos na tela de entrega"),
	(95, 1, "EntregarPedidos", "Permitir realizar entrega de pedidos"),
	(96, 5, "InformarDesperdicio", "Permitir informar um desperdício ao cancelar um produto");

INSERT INTO `Acessos` (`FuncaoID`, `PermissaoID`) VALUES
	(1, 1),
	(1, 3),
	(1, 4),
	(1, 5),
	(1, 6),
	(1, 7),
	(1, 8),
	(1, 9),
	(1, 10),
	(1, 11),
	(1, 12),
	(1, 13),
	(1, 14),
	(1, 15),
	(1, 16),
	(1, 17),
	(1, 18),
	(1, 19),
	(1, 20),
	(1, 21),
	(1, 22),
	(1, 23),
	(1, 24),
	(1, 25),
	(1, 26),
	(1, 27),
	(1, 28),
	(1, 29),
	(1, 30),
	(1, 31),
	(1, 32),
	(1, 33),
	(1, 34),
	(1, 35),
	(1, 36),
	(1, 37),
	(1, 38),
	(1, 39),
	(1, 40),
	(1, 41),
	(1, 42),
	(1, 43),
	(1, 44),
	(1, 45),
	(1, 46),
	(1, 47),
	(1, 48),
	(1, 49),
	(1, 50),
	(1, 51),
	(1, 52),
	(1, 53),
	(1, 54),
	(1, 55),
	(1, 56),
	(1, 57),
	(1, 58),
	(1, 59),
	(1, 60),
	(1, 61),
	(1, 62),
	(1, 63),
	(1, 65),
	(1, 66),
	(1, 67),
	(1, 68),
	(1, 69),
	(1, 70),
	(1, 71),
	(1, 72),
	(1, 73),
	(1, 74),
	(1, 75),
	(1, 76),
	(1, 77),
	(1, 78),
	(1, 79),
	(1, 80),
	(1, 81),
	(1, 82),
	(1, 83),
	(1, 84),
	(1, 85),
	(1, 86),
	(1, 87),
	(1, 88),
	(1, 89),
	(1, 90),
	(1, 91),
	(1, 92),
	(1, 93),
	(1, 94),
	(1, 95),
	(1, 96),
	(2, 1),
	(2, 4),
	(2, 6),
	(2, 8),
	(2, 16),
	(2, 21),
	(2, 22),
	(2, 25),
	(2, 44),
	(2, 53),
	(2, 60),
	(2, 73),
	(2, 93),
	(2, 94),
	(3, 1),
	(3, 4),
	(3, 5),
	(3, 6),
	(3, 8),
	(3, 9),
	(3, 11),
	(3, 12),
	(3, 13),
	(3, 14),
	(3, 16),
	(3, 21),
	(3, 22),
	(3, 25),
	(3, 26),
	(3, 41),
	(3, 42),
	(3, 43),
	(3, 44),
	(3, 46),
	(3, 47),
	(3, 48),
	(3, 49),
	(3, 51),
	(3, 53),
	(3, 58),
	(3, 59),
	(3, 60),
	(3, 70),
	(3, 73),
	(3, 76),
	(3, 79),
	(3, 84),
	(3, 89),
	(3, 91),
	(3, 93),
	(3, 94),
	(3, 96),
	(4, 1),
	(4, 70),
	(6, 1),
	(6, 42),
	(6, 45),
	(6, 65),
	(6, 71),
	(6, 95),
	(7, 1),
	(7, 17),
	(7, 28),
	(7, 29),
	(7, 66),
	(7, 72),
	(7, 80),
	(7, 81),
	(7, 96);

INSERT INTO `Mesas` (`ID`, `Nome`, `Ativa`) VALUES
	(1, "Mesa 1", 'Y'),
	(2, "Mesa 2", 'Y'),
	(3, "Mesa 3", 'Y'),
	(4, "Mesa 4", 'Y'),
	(5, "Mesa 5", 'Y'),
	(6, "Mesa 6", 'Y'),
	(7, "Mesa 7", 'Y'),
	(8, "Mesa 8", 'Y'),
	(9, "Mesa 9", 'Y'),
	(10, "Mesa 10", 'Y');
	
INSERT INTO `Comandas` (`ID`, `Nome`, `Ativa`) VALUES
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

INSERT INTO `Clientes` (`ID`, `Tipo`, `Login`, `Senha`, `Nome`, `Sobrenome`, `Genero`, `Fone1`, `DataAtualizacao`, `DataCadastro`) VALUES
	(1, 'Fisica', "Admin", "e14268a48adfacfdaed1d420573f69df7ce4b829", "Administrador", "do Sistema", 'Masculino', "0000000001", NOW(), NOW());

INSERT INTO `Funcionarios` (`ID`, `FuncaoID`, `ClienteID`, `Ativo`, `DataCadastro`) VALUES
	(1, 1, 1, 'Y', NOW());

INSERT INTO `Caixas` (`ID`, `Descricao`, `Ativo`) VALUES
	(1, "Caixa 1", 'Y');

INSERT INTO `Carteiras` (`ID`, `Tipo`, `Descricao`, `Ativa`) VALUES
	(1, 'Financeira', 'Caixa da empresa', 'Y');

INSERT INTO `Formas_Pagto` (`ID`, `Descricao`, `Tipo`, `CarteiraID`, `CarteiraPagtoID`, `Parcelado`, `MinParcelas`, `MaxParcelas`, `ParcelasSemJuros`, `Juros`, `Ativa`) VALUES
	(1, "Dinheiro", 'Dinheiro', 1, 1, 'N', NULL, NULL, NULL, NULL, 'Y'),
	(2, "Cartão", 'Cartao', 1, 1, 'Y', 1, 1, 1, 2.5, 'Y'),
	(3, "Cheque", 'Cheque', 1, 1, 'Y', 1, 6, 3, 2.5, 'N'),
	(4, "Conta", 'Conta', 1, 1, 'N', NULL, NULL, NULL, NULL, 'Y'),
	(5, "Crédito", 'Credito', 1, 1, 'N', NULL, NULL, NULL, NULL, 'Y');

INSERT INTO `Cartoes` (`ID`, `Descricao`, `ImageIndex`, `Ativo`) VALUES
	(1, "Visa", 3, 'Y'),
	(2, "MasterCard", 4, 'Y'),
	(3, "Hipercard", 2, 'Y'),
	(4, "Credishop", 1, 'N'),
	(5, "American Express", 5, 'N'),
	(6, "Diners Club", 6, 'N'),
	(7, "Elo", 7, 'N'),
	(8, "Sodexo", 8, 'N'),
	(9, "Maestro", 9, 'N'),
	(10, "Ticket", 10, 'N');

INSERT INTO `Categorias` (`ID`, `Descricao`, `Servico`, `DataAtualizacao`) VALUES
	(1, "Pizzas e massas", 'Y', NOW()),
	(2, "Refeições", 'Y', NOW()),
	(3, "Cervejas", 'N', NOW()),
	(4, "Refrigerantes", 'N', NOW()),
	(5, "Sucos", 'Y', NOW()),
	(6, "Energéticos", 'N', NOW()),
	(7, "Cigarros", 'N', NOW()),
	(8, "Destilados", 'N', NOW()),
	(9, "Águas", 'N', NOW()),
	(10, "Lanches", 'Y', NOW()),
	(11, "Porções", 'Y', NOW()),
	(12, "Bebidas Tropicais", 'N', NOW());

INSERT INTO `Setores` (`ID`, `Nome`, `Descricao`) VALUES
	(1, "Vendas", "Setor de vendas"),
	(2, "Cozinha", "Cozinha"),
	(3, "Churrasqueira", "Churrasqueira"),
	(4, "Bar", "Setor de bebidas");

INSERT INTO `Unidades` (`Nome`, `Descricao`, `Sigla`) VALUES
	("Unidade", "Unidade", "UN"),
	("Litro", "Unidade Líquida", "L"),
	("Grama", "Unidade de Peso", "g"),
	("Caloria", "Unidade de medida de energia", "cal"),
	("Joule", "Unidade de medida de energia", "J");

INSERT INTO `Classificacoes` (`ID`, `Descricao`) VALUES
	(1, "Movimentações do caixa"),
	(2, "Pagamento de contas");

INSERT INTO `Contas` (`ID`, `ClassificacaoID`, `FuncionarioID`, `Descricao`, `Valor`, `Cancelada`, `DataCadastro`) VALUES
	(1, 1, 1, "Movimentação no caixa", 0, 'N', NOW());

INSERT INTO `Servicos` (`ID`, `Nome`, `Descricao`, `Tipo`, `Obrigatorio`, `Valor`, `Individual`, `Ativo`) VALUES
	(1, "Desconto", "Permite realizar descontos nos pedidos", 'Taxa', 'N', 0, 'N', 'Y'),
	(2, "Entrega", "Permite cobrar taxa de entrega de pedidos", 'Taxa', 'N', 0, 'N', 'Y');

INSERT INTO `Bancos` (`Numero`, `RazaoSocial`, `AgenciaMascara`, `ContaMascara`) VALUES
	("1", "Banco do Brasil S.A.", "9999->a", "99.999->a"),
	("3", "Banco da Amazônia S.A.", NULL, NULL),
	("4", "Banco do Nordeste do Brasil S.A.", NULL, NULL),
	("7", "Banco Nacional de Desenvolvimento Econômico e Social", NULL, NULL),
	("12", "Banco INBURSA de Investimentos S.A.", NULL, NULL),
	("14", "Natixis Brasil S.A. Banco Múltiplo", NULL, NULL),
	("17", "BNY Mellon Banco S.A.", NULL, NULL),
	("18", "Banco Tricury S.A.", NULL, NULL),
	("19", "Banco Azteca do Brasil S.A.", NULL, NULL),
	("21", "BANESTES S.A. Banco do Estado do Espírito Santo", NULL, NULL),
	("24", "Banco BANDEPE S.A.", NULL, NULL),
	("25", "Banco Alfa S.A.", NULL, NULL),
	("29", "Banco Itaú Consignado S.A.", NULL, NULL),
	("33", "Banco Santander (Brasil) S.A.", "9999", "9.999.999.999"),
	("36", "Banco Bradesco BBI S.A.", NULL, NULL),
	("37", "Banco do Estado do Pará S.A.", NULL, NULL),
	("39", "Banco do Estado do Piauí S.A. - BEP", NULL, NULL),
	("40", "Banco Cargill S.A.", NULL, NULL),
	("41", "Banco do Estado do Rio Grande do Sul S.A.", NULL, NULL),
	("44", "Banco BVA S.A.", NULL, NULL),
	("47", "Banco do Estado de Sergipe S.A.", NULL, NULL),
	("62", "Hipercard Banco Múltiplo S.A.", NULL, NULL),
	("63", "Banco Bradescard S.A.", NULL, NULL),
	("64", "Goldman Sachs do Brasil Banco Múltiplo S.A.", NULL, NULL),
	("65", "Banco Andbank (Brasil) S.A.", NULL, NULL),
	("66", "Banco Morgan Stanley S.A.", NULL, NULL),
	("69", "Banco Crefisa S.A.", NULL, NULL),
	("70", "BRB - Banco de Brasília S.A.", NULL, NULL),
	("72", "Banco Mais S.A.", NULL, NULL),
	("74", "Banco J. Safra S.A.", NULL, NULL),
	("75", "Banco ABN AMRO S.A.", NULL, NULL),
	("76", "Banco KDB S.A.", NULL, NULL),
	("77", "Banco Inter S.A.", NULL, NULL),
	("78", "Haitong Banco de Investimento do Brasil S.A.", NULL, NULL),
	("79", "Banco Original do Agronegócio S.A.", NULL, NULL),
	("81", "BBN Banco Brasileiro de Negócios S.A.", NULL, NULL),
	("82", "Banco Topázio S.A.", NULL, NULL),
	("83", "Banco da China Brasil S.A.", NULL, NULL),
	("84", "Uniprime Norte do Paraná - Coop de Economia e Crédito Mútuo dos Médicos, Profissionais das Ciências", NULL, NULL),
	("92", "Brickell S.A. Crédito, Financiamento e Investimento", NULL, NULL),
	("94", "Banco Finaxis S.A.", NULL, NULL),
	("95", "Banco Confidence de Câmbio S.A.", NULL, NULL),
	("96", "Banco BM&FBOVESPA de Serviços de Liquidação e Custódia S.A", NULL, NULL),
	("97", "Cooperativa Central de Crédito Noroeste Brasileiro Ltda.", NULL, NULL),
	("104", "Caixa Econômica Federal", "9999", "99.999.999-9"),
	("107", "Banco BBM S.A.", NULL, NULL),
	("118", "Standard Chartered Bank (Brasil) S/A–Bco Invest.", NULL, NULL),
	("119", "Banco Western Union do Brasil S.A.", NULL, NULL),
	("120", "Banco Rodobens S.A.", NULL, NULL),
	("121", "Banco Agiplan S.A.", NULL, NULL),
	("122", "Banco Bradesco BERJ S.A.", NULL, NULL),
	("124", "Banco Woori Bank do Brasil S.A.", NULL, NULL),
	("125", "Brasil Plural S.A. - Banco Múltiplo", NULL, NULL),
	("126", "BR Partners Banco de Investimento S.A.", NULL, NULL),
	("128", "MS Bank S.A. Banco de Câmbio", NULL, NULL),
	("129", "UBS Brasil Banco de Investimento S.A.", NULL, NULL),
	("132", "ICBC do Brasil Banco Múltiplo S.A.", NULL, NULL),
	("135", "Gradual Corretora de Câmbio,Títulos e Valores Mobiliários S.A.", NULL, NULL),
	("136", "CONFEDERACAO NACIONAL DAS COOPERATIVAS CENTRAIS UNICREDS", NULL, NULL),
	("139", "Intesa Sanpaolo Brasil S.A. - Banco Múltiplo", NULL, NULL),
	("144", "BEXS Banco de Câmbio S.A.", NULL, NULL),
	("163", "Commerzbank Brasil S.A. - Banco Múltiplo", NULL, NULL),
	("169", "Banco Olé Bonsucesso Consignado S.A.", NULL, NULL),
	("184", "Banco Itaú BBA S.A.", NULL, NULL),
	("204", "Banco Bradesco Cartões S.A.", NULL, NULL),
	("208", "Banco BTG Pactual S.A.", NULL, NULL),
	("212", "Banco Original S.A.", NULL, NULL),
	("213", "Banco Arbi S.A.", NULL, NULL),
	("214", "Banco Dibens S.A.", NULL, NULL),
	("217", "Banco John Deere S.A.", NULL, NULL),
	("218", "Banco BS2 S.A.", NULL, NULL),
	("222", "Banco Credit Agricole Brasil S.A.", NULL, NULL),
	("224", "Banco Fibra S.A.", NULL, NULL),
	("225", "Banco Brascan S.A.", NULL, NULL),
	("229", "Banco Cruzeiro do Sul S.A.", NULL, NULL),
	("230", "Unicard Banco Múltiplo S.A.", NULL, NULL),
	("233", "Banco Cifra S.A.", NULL, NULL),
	("237", "Banco Bradesco S.A.", "9999", "9.999.999-9"),
	("241", "Banco Clássico S.A.", NULL, NULL),
	("243", "Banco Máxima S.A.", NULL, NULL),
	("246", "Banco ABC Brasil S.A.", NULL, NULL),
	("248", "Banco Boavista Interatlântico S.A.", NULL, NULL),
	("249", "Banco Investcred Unibanco S.A.", NULL, NULL),
	("250", "BCV - Banco de Crédito e Varejo S.A.", NULL, NULL),
	("254", "Paraná Banco S.A.", NULL, NULL),
	("260", "Nu Pagamentos S.A.", "\\0\\0\\0\\1", "9999999-9"),
	("263", "Banco Cacique S.A.", NULL, NULL),
	("265", "Banco Fator S.A.", NULL, NULL),
	("266", "Banco Cédula S.A.", NULL, NULL),
	("300", "Banco de La Nacion Argentina", NULL, NULL),
	("318", "Banco BMG S.A.", NULL, NULL),
	("320", "China Construction Bank (Brasil) Banco Múltiplo S.A.", NULL, NULL),
	("341", "Itaú Unibanco S.A.", "9999", "99.999-9"),
	("366", "Banco Société Générale Brasil S.A.", NULL, NULL),
	("370", "Banco Mizuho do Brasil S.A.", NULL, NULL),
	("376", "Banco J. P. Morgan S.A.", NULL, NULL),
	("389", "Banco Mercantil do Brasil S.A.", NULL, NULL),
	("394", "Banco Bradesco Financiamentos S.A.", NULL, NULL),
	("399", "Kirton Bank S.A. - Banco Múltiplo", NULL, NULL),
	("409", "UNIBANCO - União de Bancos Brasileiros S.A.", NULL, NULL),
	("412", "Banco Capital S.A.", NULL, NULL),
	("422", "Banco Safra S.A.", NULL, NULL),
	("453", "Banco Rural S.A.", NULL, NULL),
	("456", "Banco de Tokyo-Mitsubishi UFJ Brasil S.A.", NULL, NULL),
	("464", "Banco Sumitomo Mitsui Brasileiro S.A.", NULL, NULL),
	("473", "Banco Caixa Geral - Brasil S.A.", NULL, NULL),
	("477", "Citibank N.A.", NULL, NULL),
	("479", "Banco ItauBank S.A", NULL, NULL),
	("487", "Deutsche Bank S.A. - Banco Alemão", NULL, NULL),
	("488", "JPMorgan Chase Bank, National Association", NULL, NULL),
	("492", "ING Bank N.V.", NULL, NULL),
	("494", "Banco de La Republica Oriental del Uruguay", NULL, NULL),
	("495", "Banco de La Provincia de Buenos Aires", NULL, NULL),
	("505", "Banco Credit Suisse (Brasil) S.A.", NULL, NULL),
	("600", "Banco Luso Brasileiro S.A.", NULL, NULL),
	("604", "Banco Industrial do Brasil S.A.", NULL, NULL),
	("610", "Banco VR S.A.", NULL, NULL),
	("611", "Banco Paulista S.A.", NULL, NULL),
	("612", "Banco Guanabara S.A.", NULL, NULL),
	("613", "Banco Pecúnia S.A.", NULL, NULL),
	("623", "Banco PAN S.A.", NULL, NULL),
	("626", "Banco Ficsa S.A.", NULL, NULL),
	("630", "Banco Intercap S.A.", NULL, NULL),
	("633", "Banco Rendimento S.A.", NULL, NULL),
	("634", "Banco Triângulo S.A.", NULL, NULL),
	("637", "Banco Sofisa S.A.", NULL, NULL),
	("638", "Banco Prosper S.A.", NULL, NULL),
	("641", "Banco Alvorada S.A.", NULL, NULL),
	("643", "Banco Pine S.A.", NULL, NULL),
	("652", "Itaú Unibanco Holding S.A.", NULL, NULL),
	("653", "Banco Indusval S.A.", NULL, NULL),
	("654", "Banco A.J.Renner S.A.", NULL, NULL),
	("655", "Banco Votorantim S.A.", NULL, NULL),
	("658", "Banco Porto Real de Investimentos S.A.", NULL, NULL),
	("707", "Banco Daycoval S.A.", NULL, NULL),
	("712", "Banco Ourinvest S.A.", NULL, NULL),
	("719", "Banif-Banco Internacional do Funchal (Brasil)S.A.", NULL, NULL),
	("720", "Banco Maxinvest S.A.", NULL, NULL),
	("721", "Banco Credibel S.A.", NULL, NULL),
	("724", "Banco Porto Seguro S.A.", NULL, NULL),
	("735", "Banco Neon S.A.", NULL, NULL),
	("739", "Banco Cetelem S.A.", NULL, NULL),
	("741", "Banco Ribeirão Preto S.A.", NULL, NULL),
	("743", "Banco Semear S.A.", NULL, NULL),
	("744", "BankBoston N.A.", NULL, NULL),
	("745", "Banco Citibank S.A.", NULL, NULL),
	("746", "Banco Modal S.A.", NULL, NULL),
	("747", "Banco Rabobank International Brasil S.A.", NULL, NULL),
	("748", "Banco Cooperativo Sicredi S.A.", NULL, NULL),
	("749", "Banco Simples S.A.", NULL, NULL),
	("751", "Scotiabank Brasil S.A. Banco Múltiplo", NULL, NULL),
	("752", "Banco BNP Paribas Brasil S.A.", NULL, NULL),
	("753", "Novo Banco Continental S.A. - Banco Múltiplo", NULL, NULL),
	("754", "Banco Sistema S.A.", NULL, NULL),
	("755", "Bank of America Merrill Lynch Banco Múltiplo S.A.", NULL, NULL),
	("756", "Banco Cooperativo do Brasil S.A. - BANCOOB", NULL, NULL),
	("757", "Banco KEB HANA do Brasil S.A.", NULL, NULL),
	("085-x", "Cooperativa Central de Crédito Urbano-CECRED", NULL, NULL),
	("086-8", "OBOE Crédito Financiamento e Investimento S.A.", NULL, NULL),
	("087-6", "Cooperativa Central de Economia e Crédito Mútuo das Unicreds de Santa Catarina e Paraná", NULL, NULL),
	("089-2", "Cooperativa de Crédito Rural da Região da Mogiana", NULL, NULL),
	("090-2", "Cooperativa Central de Economia e Crédito Mutuo - SICOOB UNIMAIS", NULL, NULL),
	("091-4", "Unicred Central do Rio Grande do Sul", NULL, NULL),
	("098-1", "CREDIALIANÇA COOPERATIVA DE CRÉDITO RURAL", NULL, NULL),
	("114-7", "Central das Cooperativas de Economia e Crédito Mútuo do Estado do Espírito Santo Ltda.", NULL, NULL);

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

INSERT INTO `Integracoes` (`Nome`, `AcessoURL`, `Descricao`, `IconeURL`, `Ativo`, `DataAtualizacao`) VALUES
	('iFood', 'ifood', 'Módulo de integração com o iFood', 'ifood.png', 'N', NOW());

INSERT INTO `Sistema` (`ID`, `PaisID`, `VersaoDB`, `UltimoBackup`, `Computadores`) VALUES
	(1, 1, "1.9.3.5", NOW(), 1);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
