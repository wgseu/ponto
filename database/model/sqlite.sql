-- Creator:       MySQL Workbench 6.3.8/ExportSQLite Plugin 0.1.0
-- Author:        Mazin
-- Caption:       GrandChef Model
-- Project:       GrandChef
-- Changed:       2018-05-12 09:52
-- Created:       2012-09-05 23:08
PRAGMA foreign_keys = OFF;

-- Schema: GrandChef
--   Armazena todas as informações do sistema GrandChef, exceto configurações de janelas, conexão e lembrete de sessão
CREATE TABLE "Sessoes"(
--   Sessão de trabalho do dia, permite que vários caixas sejam abertos utilizando uma mesma sessão[N:Sessão|Sessões][G:a][L:AbrirCaixa][K:MZ\Session|MZ\Session\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da sessão[G:o]
  "DataInicio" DATETIME NOT NULL,-- Data de início da sessão[N:Data de início][G:a]
  "DataTermino" DATETIME DEFAULT NULL,-- Data de fechamento da sessão[N:Data de termíno][G:a]
  "Aberta" TEXT NOT NULL CHECK("Aberta" IN('Y', 'N'))-- Informa se a sessão está aberta[N:Aberta][G:a]
);
CREATE INDEX "Sessoes.IDX_Sessoes_Aberta" ON "Sessoes" ("Aberta");
CREATE TABLE "Integracoes"(
--   Informa quais integrações estão disponíveis[N:Integração|Integrações][G:a][L:AlterarConfiguracoes][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da integração[N:ID][G:o]
  "Nome" VARCHAR(45) NOT NULL,-- Nome do módulo de integração[G:o][N:Nome]
  "AcessoURL" VARCHAR(100) NOT NULL,-- Nome da URL de acesso[N:URL][G:a]
  "Descricao" VARCHAR(200) DEFAULT NULL,-- Descrição do módulo integrador[G:a][N:Descrição]
  "IconeURL" VARCHAR(200) DEFAULT NULL,-- Nome do ícone do módulo integrador[G:o][N:Ícone][I:128x128|integracao|integracao.png]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'N',-- Informa de o módulo de integração está habilitado[G:o][N:Habilitado]
  "Token" VARCHAR(200) DEFAULT NULL,-- Token de acesso à API de sincronização[N:Token][G:o]
  "Secret" VARCHAR(200) DEFAULT NULL,-- Chave secreta para acesso à API[G:a][N:Chave secreta]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização dos dados do módulo de integração[G:a][N:Data de atualização]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome"),
  CONSTRAINT "AcessoURL_UNIQUE"
    UNIQUE("AcessoURL")
);
CREATE TABLE "Horarios"(
--   Informa o horário de funcionamento do estabelecimento[N:Horário|Horários][G:o][L:AlterarHorario][K:MZ\Company|MZ\Company\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do horário[G:o]
  "Inicio" INTEGER NOT NULL,-- Início do horário de funcionamento em minutos contando a partir de domingo até sábado[N:Início][G:o]
  "Fim" INTEGER NOT NULL,-- Duração em minutos em que o restaurante ficará aberto contando a partir de domingo[N:Fim][G:o]
  "TempoEntrega" INTEGER DEFAULT NULL-- Tempo médio de entrega em minutos dos pedidos para entrega no dia informado[N:Tempo de entrega][G:o]
);
CREATE TABLE "Funcoes"(
--   Função ou cargo de um funcionário[N:Função|Funções][G:a][L:AlterarConfiguracoes][K:MZ\Employee|MZ\Employee\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da função[G:o]
  "Descricao" VARCHAR(45) NOT NULL,-- Descreve o nome da função[N:Descrição][G:a][S]
  "SalarioBase" DECIMAL NOT NULL,-- Salário base ou mínimo que será acrescentado comissões[N:Salário base][G:o]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao")
);
CREATE TABLE "Origens"(
--   Origem da mercadoria[N:Origem|Origens][G:a][L:CadastroProdutos][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da origem[G:o]
  "Codigo" INTEGER NOT NULL,-- Código da origem da mercadoria[G:o][N:Código]
  "Descricao" VARCHAR(200) NOT NULL,-- Descrição da origem da mercadoria[G:a][N:Descrição]
  CONSTRAINT "Codigo_UNIQUE"
    UNIQUE("Codigo")
);
CREATE TABLE "Servicos"(
--   Taxas, eventos e serviço cobrado nos pedidos[N:Serviço|Serviços][G:o][L:CadastroServicos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do serviço[G:o]
  "Nome" VARCHAR(50) NOT NULL,-- Nome do serviço, Ex.: Comissão, Entrega, Couvert[N:Nome][G:o]
  "Descricao" VARCHAR(100) NOT NULL,-- Descrição do serviço, Ex.: Show de fulano[N:Descrição][G:a][S]
  "Detalhes" VARCHAR(200) DEFAULT NULL,-- Detalhes do serviço, Ex.: Com participação especial de fulano[N:Detalhes][G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Evento', 'Taxa')),-- Tipo de serviço, Evento: Eventos como show no estabelecimento[N:Tipo][G:o]
  "Obrigatorio" TEXT NOT NULL CHECK("Obrigatorio" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a taxa é obrigatória[N:Obrigatório][G:o]
  "DataInicio" DATETIME DEFAULT NULL,-- Data de início do evento[N:Data de início][G:a]
  "DataFim" DATETIME DEFAULT NULL,-- Data final do evento[N:Data final][G:a]
  "Valor" DECIMAL NOT NULL DEFAULT 0,-- Valor do serviço[N:Valor][G:o]
  "Individual" TEXT NOT NULL CHECK("Individual" IN('Y', 'N')) DEFAULT 'N',-- Informa se a taxa ou serviço é individual para cada pessoa[N:Individual][G:o]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y'-- Informa se o serviço está ativo[N:Ativo][G:o]
);
CREATE TABLE "Setores"(
--   Setor de impressão e de estoque[N:Setor|Setores][G:o][L:Estoque][K:MZ\Environment|MZ\Environment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do setor[G:o]
  "Nome" VARCHAR(50) NOT NULL,-- Nome do setor, único em todo o sistema[N:Nome][G:o][S]
  "Descricao" VARCHAR(70) DEFAULT NULL,-- Descreve a utilização do setor[N:Descrição][G:a]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome")
);
CREATE TABLE "Operacoes"(
--   Código Fiscal de Operações e Prestações (CFOP)[N:Operação|Operações][G:a][L:CadastroProdutos][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da operação[G:o]
  "Codigo" INTEGER NOT NULL,-- Código CFOP sem pontuação[G:o][N:Código]
  "Descricao" VARCHAR(255) NOT NULL,-- Descrição da operação[G:a][N:Descrição]
  "Detalhes" TEXT DEFAULT NULL,-- Detalhes da operação (Opcional)[G:o][N:Detalhes]
  CONSTRAINT "Codigo_UNIQUE"
    UNIQUE("Codigo")
);
CREATE TABLE "Caixas"(
--   Caixas de movimentação financeira[N:Caixa|Caixas][G:o][L:CadastroCaixas][K:MZ\Session|MZ\Session\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do caixa[G:o]
  "Descricao" VARCHAR(50) NOT NULL,-- Descrição do caixa[N:Descrição][G:a][S]
  "Serie" INTEGER NOT NULL DEFAULT 1,-- Série do caixa[N:Série][G:a]
  "NumeroInicial" INTEGER NOT NULL DEFAULT 1,-- Número inicial na geração da nota, será usado quando maior que o último número utilizado[N:Número inicial][G:o]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o caixa está ativo[N:Ativo][G:o]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao")
);
CREATE TABLE "Clientes"(
--   Informações de cliente físico ou jurídico. Clientes, empresas, funcionários, fornecedores e parceiros são cadastrados aqui[N:Cliente|Clientes][G:o][L:CadastroClientes][K:MZ\Account|MZ\Account\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cliente[G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Fisica', 'Juridica')) DEFAULT 'Fisica',-- Informa o tipo de pessoa, que pode ser física ou jurídica[N:Tipo][G:o][E:Física|Jurídica][S:S]
  "AcionistaID" INTEGER DEFAULT NULL,-- Informa quem é o acionista principal da empresa, obrigatoriamente o cliente deve ser uma pessoa jurídica e o acionista uma pessoa física[N:Acionista][G:o][S:S]
  "Login" VARCHAR(50),-- Nome de usuário utilizado para entrar no sistema, aplicativo ou site[N:Login][G:o]
  "Senha" VARCHAR(40) DEFAULT NULL,-- Senha embaralhada do cliente ou funcionário[N:Senha][G:a][P]
  "Nome" VARCHAR(100) NOT NULL,-- Primeiro nome da pessoa física ou nome fantasia da empresa[N:Nome][G:o][S]
  "Sobrenome" VARCHAR(100) DEFAULT NULL,-- Restante do nome da pessoa física ou Razão social da empresa[N:Sobrenome][G:o]
  "Genero" TEXT CHECK("Genero" IN('Masculino', 'Feminino')) DEFAULT 'Masculino',-- Informa o gênero do cliente do tipo pessoa física[N:Gênero][G:o][S:S][R]
  "CPF" VARCHAR(20) DEFAULT NULL,-- Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa Jurídica(CNPJ)[M:999.999.999-99][N:CPF][G:o]
  "RG" VARCHAR(20) DEFAULT NULL,-- Registro Geral(RG) ou Inscrição Estadual (IE)[N:Registro Geral][G:o]
  "IM" VARCHAR(20) DEFAULT NULL,-- Inscrição municipal da empresa[N:Inscrição municipal][G:a]
  "Email" VARCHAR(100) DEFAULT NULL,-- E-mail do cliente ou da empresa[N:E-mail][G:o]
  "DataAniversario" DATE DEFAULT NULL,-- Data de aniversário sem o ano ou data de fundação[N:Data de aniversário][G:a]
  "Fone1" VARCHAR(12) DEFAULT NULL,-- Telefone principal do cliente, deve ser único[M:(99) 9999-9999?9][N:Telefone][G:o]
  "Fone2" VARCHAR(12) DEFAULT NULL,-- Telefone opcional[M:(99) 9999-9999?9][N:Celular][G:o]
  "Slogan" VARCHAR(100) DEFAULT NULL,-- Slogan ou detalhes do cliente[N:Observação][G:a]
  "Secreto" VARCHAR(40) DEFAULT NULL,-- Código secreto para recuperar a conta do cliente[N:Código de recuperação][G:o][D]
  "LimiteCompra" DECIMAL DEFAULT NULL,-- Limite de compra utilizando a forma de pagamento Conta[N:Limite de compra][G:o]
  "FacebookURL" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do Facebook do cliente[N:Facebook][G:o]
  "TwitterURL" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do Twitter do cliente[N:Twitter][G:o]
  "LinkedInURL" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do LinkedIn do cliente[N:LinkedIn][G:o]
  "Imagem" MEDIUMBLOB DEFAULT NULL,-- Foto do cliente ou logo da empresa[I:256x256|cliente|cliente.png][N:Foto][G:a]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização das informações do cliente[N:Data de atualização][G:a][D]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro do cliente[N:Data de cadastro][G:a][D]
  CONSTRAINT "Fone1_UNIQUE"
    UNIQUE("Fone1"),
  CONSTRAINT "Email_UNIQUE"
    UNIQUE("Email"),
  CONSTRAINT "CPF_UNIQUE"
    UNIQUE("CPF"),
  CONSTRAINT "Login_UNIQUE"
    UNIQUE("Login"),
  CONSTRAINT "Secreto_UNIQUE"
    UNIQUE("Secreto"),
  CONSTRAINT "FK_Clientes_Clientes_AcionistaID"
    FOREIGN KEY("AcionistaID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Clientes.Nome_INDEX" ON "Clientes" ("Nome");
CREATE INDEX "Clientes.Fone2_INDEX" ON "Clientes" ("Fone2");
CREATE INDEX "Clientes.FK_Clientes_Clientes_AcionistaID_idx" ON "Clientes" ("AcionistaID");
CREATE TABLE "Categorias"(
--   Informa qual a categoria dos produtos e permite a rápida localização dos mesmos[N:Categoria|Categorias][G:a][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da categoria[G:o]
  "CategoriaID" INTEGER DEFAULT NULL,-- Informa a categoria pai da categoria atual, a categoria atual é uma subcategoria[N:Categoria superior][G:a][S:S]
  "Descricao" VARCHAR(45) NOT NULL,-- Descrição da categoria. Ex.: Refrigerantes, Salgados[N:Descrição][G:a][S]
  "Servico" TEXT NOT NULL CHECK("Servico" IN('Y', 'N')),-- Informa se a categoria é destinada para produtos ou serviços[N:Serviço][G:o]
  "Imagem" MEDIUMBLOB DEFAULT NULL,-- Imagem representativa da categoria[N:Imagem][G:a][I:256x256|categoria|categoria.png]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização das informações da categoria[N:Data de atualização][G:a]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao"),
  CONSTRAINT "FK_Categorias_Categorias_CategoriaID"
    FOREIGN KEY("CategoriaID")
    REFERENCES "Categorias"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Categorias.FK_Categorias_Categorias_CategoriaID_idx" ON "Categorias" ("CategoriaID");
CREATE TABLE "Paginas"(
--   Página WEB que contém informações de contato, termos e outras informações da empresa[N:Página|Páginas][G:a][L:AlterarPaginas][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da página[G:o]
  "Nome" VARCHAR(45) NOT NULL,-- Nome da página, único no sistema com o código da linguagem[N:Nome][G:o][S]
  "LinguagemID" INTEGER NOT NULL DEFAULT 0,-- Código da linguagem para exibição no idioma correto, único com o nome[N:Linguagem][G:a]
  "Conteudo" TEXT DEFAULT NULL,-- Conteúdo da página, geralmente texto formatado em HTML[N:Conteúdo][G:o]
  CONSTRAINT "Nome_LinguagemID_UNIQUE"
    UNIQUE("Nome","LinguagemID")
);
CREATE TABLE "Funcionalidades"(
--   Grupo de funcionalidades do sistema[N:Funcionalidade|Funcionalidades][G:a][L:AlterarConfiguracoes][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da funcionalidade[G:o]
  "Nome" VARCHAR(64) NOT NULL,-- Nome da funcionalidade, único em todo o sistema[N:Nome][G:o]
  "Descricao" VARCHAR(100) NOT NULL,-- Descrição da funcionalidade[N:Descrição][G:a][S]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome")
);
CREATE TABLE "Mesas"(
--   Mesas para lançamento de pedidos[N:Mesa|Mesas][G:a][L:CadastroMesas][K:MZ\Environment|MZ\Environment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Número da mesa[N:Número][G:o]
  "Nome" VARCHAR(50) NOT NULL,-- Nome da mesa[N:Nome][G:o][S]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a mesa está disponível para lançamento de pedidos[N:Ativa][G:a]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome")
);
CREATE TABLE "Unidades"(
--   Unidades de medidas aplicadas aos produtos[N:Unidade|Unidades][G:a][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da unidade[G:o]
  "Nome" VARCHAR(45) NOT NULL,-- Nome da unidade de medida, Ex.: Grama, Quilo[N:Nome][G:o][S]
  "Descricao" VARCHAR(45) DEFAULT NULL,-- Detalhes sobre a unidade de medida[N:Descrição][G:a]
  "Sigla" VARCHAR(10) NOT NULL,-- Sigla da unidade de medida, Ex.: UN, L, g[N:Sigla][G:a]
  CONSTRAINT "Sigla_UNIQUE"
    UNIQUE("Sigla")
);
CREATE TABLE "Modulos"(
--   Módulos do sistema que podem ser desativados/ativados[N:Módulo|Módulos][G:o][L:AlterarConfiguracoes][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do módulo[G:o]
  "Nome" VARCHAR(50) NOT NULL,-- Nome do módulo, unico em todo o sistema[N:Nome][G:o][S]
  "Descricao" VARCHAR(200) NOT NULL,-- Descrição do módulo, informa detalhes sobre a funcionalidade do módulo no sistema[N:Descrição][G:a]
  "ImageIndex" INTEGER NOT NULL,-- Índice da imagem que representa o módulo, tamanho 64x64[N:Imagem][G:a]
  "Habilitado" TEXT NOT NULL CHECK("Habilitado" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o módulo do sistema está habilitado[N:Habilitado][G:o]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome")
);
CREATE TABLE "Bancos"(
--   Bancos disponíveis no país[N:Banco|Bancos][G:o][L:CadastroBancos][K:MZ\Wallet|MZ\Wallet\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do banco[G:o]
  "Numero" VARCHAR(40) NOT NULL,-- Número do banco[N:Número][G:o]
  "RazaoSocial" VARCHAR(200) NOT NULL,-- Razão social do banco[N:Razão social][G:a][S]
  "AgenciaMascara" VARCHAR(45) DEFAULT NULL,-- Mascara para formatação do número da agência[N:Máscara da agência][G:a]
  "ContaMascara" VARCHAR(45) DEFAULT NULL,-- Máscara para formatação do número da conta[N:Máscara da conta][G:a]
  CONSTRAINT "RazaoSocial_UNIQUE"
    UNIQUE("RazaoSocial"),
  CONSTRAINT "Numero_UNIQUE"
    UNIQUE("Numero")
);
CREATE TABLE "Regimes"(
--   Regimes tributários[N:Regime|Regimes][G:o][L:AlterarConfiguracoes][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do regime tributário[G:o]
  "Codigo" INTEGER NOT NULL,-- Código do regime tributário[G:o][N:Código]
  "Descricao" VARCHAR(200) NOT NULL,-- Descrição do regime tributário[G:a][N:Descrição]
  CONSTRAINT "Codigo_UNIQUE"
    UNIQUE("Codigo")
);
CREATE TABLE "Classificacoes"(
--   Classificação se contas, permite atribuir um grupo de contas[N:Classificação|Classificações][G:a][L:CadastroContas][K:MZ\Account|MZ\Account\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da classificação[G:o]
  "ClassificacaoID" INTEGER DEFAULT NULL,-- Classificação superior, quando informado, esta classificação será uma subclassificação[N:Classificação superior][G:a][S:S]
  "Descricao" VARCHAR(100) NOT NULL,-- Descrição da classificação[N:Descrição][G:a][S]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao"),
  CONSTRAINT "FK_Classificacoes_ClassificacaoID"
    FOREIGN KEY("ClassificacaoID")
    REFERENCES "Classificacoes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Classificacoes.FK_Classificacoes_ClassificacaoID_idx" ON "Classificacoes" ("ClassificacaoID");
CREATE TABLE "Comandas"(
--   Comanda individual, permite lançar pedidos em cartões de consumo[N:Comanda|Comandas][G:a][L:CadastroComandas][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Número da comanda[N:Número][G:o]
  "Nome" VARCHAR(50) NOT NULL,-- Nome da comanda[N:Nome][G:o][S]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a comanda está diponível para ser usada nas vendas[N:Ativa][G:a]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome")
);
CREATE TABLE "Impostos"(
--   Impostos disponíveis para informar no produto[N:Imposto|Impostos][G:o][L:CadastroProdutos][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do imposto[G:o]
  "Grupo" TEXT NOT NULL CHECK("Grupo" IN('ICMS', 'PIS', 'COFINS', 'IPI', 'II')),-- Grupo do imposto[G:o][N:Grupo]
  "Simples" TEXT NOT NULL CHECK("Simples" IN('Y', 'N')),-- Informa se o imposto é do simples nacional[G:o][N:Simples nacional]
  "Substituicao" TEXT NOT NULL CHECK("Substituicao" IN('Y', 'N')),-- Informa se o imposto é por substituição tributária[G:a][N:Substituição tributária]
  "Codigo" INTEGER NOT NULL,-- Informa o código do imposto[G:o][N:Código]
  "Descricao" VARCHAR(255) NOT NULL,-- Descrição do imposto[G:a][N:Descrição]
  CONSTRAINT "UK_Imposto"
    UNIQUE("Grupo","Simples","Substituicao","Codigo")
);
CREATE TABLE "Moedas"(
--   Moedas financeiras de um país[N:Moeda|Moedas][G:a][L:CadastroMoedas][K:MZ\Wallet|MZ\Wallet\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da moeda[G:o]
  "Nome" VARCHAR(45) NOT NULL,-- Nome da moeda[N:Nome][G:o][S]
  "Simbolo" VARCHAR(10) NOT NULL,-- Símbolo da moeda, Ex.: R$, $[N:Símbolo][G:o]
  "Codigo" VARCHAR(45) DEFAULT NULL,-- Código internacional da moeda, Ex.: USD, BRL[N:Código][G:o]
  "Divisao" INTEGER NOT NULL,-- Informa o número fracionário para determinar a quantidade de casas decimais, Ex: 100 para 0,00. 10 para 0,0[N:Divisão][G:a]
  "Fracao" VARCHAR(45) DEFAULT NULL,-- Informa o nome da fração, Ex.: Centavo[N:Nome da fração][G:o]
  "Formato" VARCHAR(45) NOT NULL-- Formado de exibição do valor, Ex: $ %s, para $ 3,00[N:Formato][G:o]
);
CREATE TABLE "Dispositivos"(
--   Computadores e tablets com opções de acesso[N:Dispositivo|Dispositivos][G:o][L:CadastroComputadores][K:MZ\Device|MZ\Device\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do dispositivo[G:o]
  "SetorID" INTEGER NOT NULL,-- Setor em que o dispositivo está instalado/será usado[N:Setor][G:o]
  "CaixaID" INTEGER DEFAULT NULL,-- Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os dispositivos[N:Caixa][G:o]
  "Nome" VARCHAR(100) NOT NULL,-- Nome do computador ou tablet em rede, único entre os dispositivos[N:Nome][G:o][S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Computador', 'Tablet')) DEFAULT 'Computador',-- Tipo de dispositivo[N:Tipo][G:o][S:S]
  "Descricao" VARCHAR(45) DEFAULT NULL,-- Descrição do dispositivo[N:Descrição][G:a]
  "Opcoes" INTEGER NOT NULL DEFAULT 0,-- Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros[N:Opções][G:a]
  "Serial" VARCHAR(45) DEFAULT NULL,-- Serial do tablet para validação, único entre os dispositivos[N:Serial][G:o]
  "Validacao" VARCHAR(40) DEFAULT NULL,-- Validação do tablet[N:Validação][G:a]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome"),
  CONSTRAINT "CaixaID_UNIQUE"
    UNIQUE("CaixaID"),
  CONSTRAINT "Serial_UNIQUE"
    UNIQUE("Serial"),
  CONSTRAINT "FK_Dispositivos_Setores_SetorID"
    FOREIGN KEY("SetorID")
    REFERENCES "Setores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Dispositivos_Caixas_CaixaID"
    FOREIGN KEY("CaixaID")
    REFERENCES "Caixas"("ID")
    ON DELETE SET NULL
    ON UPDATE CASCADE
);
CREATE INDEX "Dispositivos.FK_Dispositivos_Setores_SetorID_idx" ON "Dispositivos" ("SetorID");
CREATE INDEX "Dispositivos.FK_Dispositivos_Caixas_CaixaID_idx" ON "Dispositivos" ("CaixaID");
CREATE TABLE "Fornecedores"(
--   Fornecedores de produtos[N:Fornecedor|Fornecedores][G:o][L:CadastroFornecedores][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do fornecedor[G:o]
  "EmpresaID" INTEGER NOT NULL,-- Empresa do fornecedor[N:Empresa][G:a][S]
  "PrazoPagamento" INTEGER NOT NULL DEFAULT 0,-- Prazo em dias para pagamento do fornecedor[N:Prazo de pagamento][G:o]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro do fornecedor[N:Data de cadastro][G:a]
  CONSTRAINT "EmpresaID_UNIQUE"
    UNIQUE("EmpresaID"),
  CONSTRAINT "FK_Fornecedores_Clientes_EmpresaID"
    FOREIGN KEY("EmpresaID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Fornecedores.FK_Fornecedores_Clientes_EmpresaID_idx" ON "Fornecedores" ("EmpresaID");
CREATE TABLE "Emitentes"(
--   Dados do emitente das notas fiscais[N:Emitente|Emitentes][G:o][L:AlterarConfiguracoes][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" TEXT PRIMARY KEY NOT NULL CHECK("ID" IN('1')) DEFAULT '1',-- Identificador do emitente, sempre 1[G:o]
  "ContadorID" INTEGER DEFAULT NULL,-- Contador responsável pela contabilidade da empresa[N:Contador][G:o][S:S]
  "RegimeID" INTEGER NOT NULL,-- Regime tributário da empresa[N:Regime tributário][G:o]
  "Ambiente" TEXT NOT NULL CHECK("Ambiente" IN('Homologacao', 'Producao')),-- Ambiente de emissão das notas[N:Ambiente][G:o][E:Homologação|Produção]
  "CSC" VARCHAR(100) NOT NULL,-- Código de segurança do contribuinte[G:o]
  "Token" VARCHAR(10) NOT NULL,-- Token do código de segurança do contribuinte[N:Token][G:o]
  "IBPT" VARCHAR(100) DEFAULT NULL,-- Token da API do IBPT[N:Token IBPT][G:o]
  "ChavePrivada" VARCHAR(100) NOT NULL,-- Nome do arquivo da chave privada[G:a][N:Chave privada]
  "ChavePublica" VARCHAR(100) NOT NULL,-- Nome do arquivo da chave pública[G:a][N:Chave pública]
  "DataExpiracao" DATETIME NOT NULL,-- Data de expiração do certificado[N:Data de expiração][G:a]
  CONSTRAINT "FK_Emitentes_Clientes_ContadorID"
    FOREIGN KEY("ContadorID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Emitentes_Regimes_RegimeID"
    FOREIGN KEY("RegimeID")
    REFERENCES "Regimes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Emitentes.FK_Emitentes_Clientes_ContadorID_idx" ON "Emitentes" ("ContadorID");
CREATE INDEX "Emitentes.FK_Emitentes_Regimes_RegimeID_idx" ON "Emitentes" ("RegimeID");
CREATE TABLE "Permissoes"(
--   Informa a listagem de todas as funções do sistema [N:Permissão|Permissões][G:a][L:AlterarConfiguracoes][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da permissão[G:o]
  "FuncionalidadeID" INTEGER NOT NULL,-- Categoriza um grupo de permissões[N:Funcionalidade][G:a][S:S]
  "Nome" VARCHAR(45) NOT NULL,-- Nome da permissão, único no sistema[N:Nome][G:a]
  "Descricao" VARCHAR(100) NOT NULL,-- Descreve a permissão[N:Descrição][G:a][S]
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome"),
  CONSTRAINT "FK_Permissoes_Funcionalidades_FuncionalidadeID"
    FOREIGN KEY("FuncionalidadeID")
    REFERENCES "Funcionalidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Permissoes.FK_Permissoes_Funcionalidades_FuncionalidadeID_idx" ON "Permissoes" ("FuncionalidadeID");
CREATE TABLE "Funcionarios"(
--   Funcionário que trabalha na empresa e possui uma determinada função[N:Funcionário|Funcionários][G:o][L:CadastroFuncionarios][K:MZ\Employee|MZ\Employee\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código do funcionário[N:Código][G:o]
  "FuncaoID" INTEGER NOT NULL,-- Função do funcionário na empresa[N:Função][G:a][S:S]
  "ClienteID" INTEGER NOT NULL,-- Cliente que representa esse funcionário, único no cadastro de funcionários[N:Cliente][G:o][S]
  "CodigoBarras" VARCHAR(13) DEFAULT NULL,-- Código de barras utilizado pelo funcionário para autorizar uma operação no sistema[N:Código de barras][G:o]
  "Porcentagem" DOUBLE NOT NULL DEFAULT 0,-- Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10% [N:Comissão][G:a]
  "LinguagemID" INTEGER NOT NULL DEFAULT 0,-- Código da linguagem utilizada pelo funcionário para visualizar o programa e o site[N:Linguagem][G:a]
  "Pontuacao" INTEGER NOT NULL DEFAULT 0,-- Define a distribuição da porcentagem pela parcela de pontos[N:Pontuação][G:a]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o funcionário está ativo na empresa[N:Ativo][G:o]
  "DataSaida" DATETIME DEFAULT NULL,-- Data de saída do funcionário, informado apenas quando ativo for não[N:Data de saída][G:a][D]
  "DataCadastro" DATETIME NOT NULL,-- Data em que o funcionário foi cadastrado no sistema[N:Data de cadastro][G:a][D]
  CONSTRAINT "UK_ClienteID"
    UNIQUE("ClienteID"),
  CONSTRAINT "CodigoBarras_UNIQUE"
    UNIQUE("CodigoBarras"),
  CONSTRAINT "FK_Funcionarios_Funcoes_FuncaoID"
    FOREIGN KEY("FuncaoID")
    REFERENCES "Funcoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Funcionarios_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Funcionarios.FK_Funcionarios_Funcoes_FuncaoID_idx" ON "Funcionarios" ("FuncaoID");
CREATE TABLE "Tributacoes"(
--   Informação tributária dos produtos[N:Tributação|Tributações][G:a][L:CadastroProdutos][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da tributação[G:o]
  "NCM" VARCHAR(10) NOT NULL,-- Código NCM (Nomenclatura Comum do Mercosul) do produto[G:o][N:NCM]
  "CEST" VARCHAR(20) DEFAULT NULL,-- Código CEST do produto (Opcional)[G:o][N:CEST]
  "OrigemID" INTEGER NOT NULL,-- Origem do produto[G:a][N:Origem]
  "OperacaoID" INTEGER NOT NULL,-- CFOP do produto[G:o][N:CFOP]
  "ImpostoID" INTEGER NOT NULL,-- Imposto do produto[G:o][N:Imposto]
  CONSTRAINT "FK_Tributacoes_Origens_OrigemID"
    FOREIGN KEY("OrigemID")
    REFERENCES "Origens"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Tributacoes_Operacoes_OperacaoID"
    FOREIGN KEY("OperacaoID")
    REFERENCES "Operacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Tributacoes_Impostos_ImpostoID"
    FOREIGN KEY("ImpostoID")
    REFERENCES "Impostos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Tributacoes.FK_Tributacoes_Origens_OrigemID_idx" ON "Tributacoes" ("OrigemID");
CREATE INDEX "Tributacoes.FK_Tributacoes_Operacoes_OperacaoID_idx" ON "Tributacoes" ("OperacaoID");
CREATE INDEX "Tributacoes.FK_Tributacoes_Impostos_ImpostoID_idx" ON "Tributacoes" ("ImpostoID");
CREATE TABLE "Produtos"(
--   Informações sobre o produto, composição ou pacote[N:Produto|Produtos][G:o][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código do produto[G:o]
  "CodigoBarras" VARCHAR(100) DEFAULT NULL,-- Código de barras do produto, deve ser único entre todos os produtos[N:Código de barras][G:o]
  "CategoriaID" INTEGER NOT NULL,-- Categoria do produto, permite a rápida localização ao utilizar tablets[N:Categoria][G:a][S:S]
  "UnidadeID" INTEGER NOT NULL,-- Informa a unidade do produtos, Ex.: Grama, Litro.[N:Unidade][G:a]
  "SetorEstoqueID" INTEGER DEFAULT NULL,-- Informa de qual setor o produto será retirado após a venda[N:Setor de estoque][G:o]
  "SetorPreparoID" INTEGER DEFAULT NULL,-- Informa em qual setor de preparo será enviado o ticket de preparo ou autorização, se nenhum for informado nada será impresso[N:Setor de preparo][G:o]
  "TributacaoID" INTEGER DEFAULT NULL,-- Informações de tributação do produto[G:a][N:Tributação][S:S]
  "Descricao" VARCHAR(75) NOT NULL,-- Descrição do produto, Ex.: Refri. Coca Cola 2L.[N:Descrição][G:a][S]
  "Abreviacao" VARCHAR(100) DEFAULT NULL,-- Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo[N:Abreviação][G:a]
  "Detalhes" VARCHAR(255) DEFAULT NULL,-- Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano[N:Detalhes][G:o]
  "QuantidadeLimite" DOUBLE NOT NULL,-- Informa a quantidade limite para que o sistema avise que o produto já está acabando[N:Quantidade limite][G:a]
  "QuantidadeMaxima" DOUBLE NOT NULL DEFAULT 0,-- Informa a quantidade máxima do produto no estoque, não proibe, apenas avisa[N:Quantidade máxima][G:a]
  "Conteudo" DOUBLE NOT NULL DEFAULT 1,-- Informa o conteúdo do produto, Ex.: 2000 para 2L de conteúdo, 200 para 200g de peso ou 1 para 1 unidade[N:Conteúdo][G:o]
  "PrecoVenda" DECIMAL NOT NULL,-- Preço de venda ou preço de venda base para pacotes[N:Preço de venda][G:o]
  "CustoProducao" DECIMAL DEFAULT NULL,-- Informa qual o valor para o custo de produção do produto, utilizado quando não há formação de composição do produto[N:Custo de produção][G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Produto', 'Composicao', 'Pacote')) DEFAULT 'Produto',-- Informa qual é o tipo de produto. Produto: Produto normal que possui estoque, Composição: Produto que não possui estoque diretamente, pois é composto de outros produtos ou composições, Pacote: Permite a composição no momento da venda, não possui estoque diretamente[N:Tipo][G:o][E:Produto|Composição|Pacote]
  "CobrarServico" TEXT NOT NULL CHECK("CobrarServico" IN('Y', 'N')) DEFAULT 'Y',-- Informa se deve ser cobrado a taxa de serviço dos garçons sobre este produto[N:Cobrança de serviço][G:a]
  "Divisivel" TEXT NOT NULL CHECK("Divisivel" IN('Y', 'N')) DEFAULT 'N',-- Informa se o produto pode ser vendido fracionado[N:Divisível][G:o]
  "Pesavel" TEXT NOT NULL CHECK("Pesavel" IN('Y', 'N')) DEFAULT 'N',-- Informa se o peso do produto deve ser obtido de uma balança, obrigatoriamente o produto deve ser divisível[N:Pesável][G:o]
  "Perecivel" TEXT NOT NULL CHECK("Perecivel" IN('Y', 'N')) DEFAULT 'N',-- Informa se o produto vence em pouco tempo[N:Perecível][G:o]
  "TempoPreparo" INTEGER NOT NULL DEFAULT 0,-- Tempo de preparo em minutos para preparar uma composição, 0 para não informado[N:Tempo de preparo][G:o]
  "Visivel" TEXT NOT NULL CHECK("Visivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o produto estará disponível para venda[N:Visível][G:o]
  "Imagem" MEDIUMBLOB DEFAULT NULL,-- Imagem do produto[N:Imagem][G:a][I:256x256|produto|produto.png]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização das informações do produto[N:Data de atualização][G:a][D]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao"),
  CONSTRAINT "CodBarras_UNIQUE"
    UNIQUE("CodigoBarras"),
  CONSTRAINT "FK_Produtos_Categorias_CategoriaID"
    FOREIGN KEY("CategoriaID")
    REFERENCES "Categorias"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Produtos_Unidades_UnidadeID"
    FOREIGN KEY("UnidadeID")
    REFERENCES "Unidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Produtos_Setores_SetorPreparoID"
    FOREIGN KEY("SetorPreparoID")
    REFERENCES "Setores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Produtos_Setores_SetorEstoqueID"
    FOREIGN KEY("SetorEstoqueID")
    REFERENCES "Setores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Produtos_Tributacoes_TributacaoID"
    FOREIGN KEY("TributacaoID")
    REFERENCES "Tributacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Produtos.FK_Produtos_Categorias_CategoriaID_idx" ON "Produtos" ("CategoriaID");
CREATE INDEX "Produtos.FK_Produtos_Unidades_UnidadeID_idx" ON "Produtos" ("UnidadeID");
CREATE INDEX "Produtos.FK_Produtos_Setores_SetorPreparoID_idx" ON "Produtos" ("SetorPreparoID");
CREATE INDEX "Produtos.FK_Produtos_Setores_SetorEstoqueID_idx" ON "Produtos" ("SetorEstoqueID");
CREATE INDEX "Produtos.FK_Produtos_Tributacoes_TributacaoID_idx" ON "Produtos" ("TributacaoID");
CREATE TABLE "Composicoes"(
--   Informa as propriedades da composição de um produto composto[N:Composição|Composições][G:a][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da composição[G:o]
  "ComposicaoID" INTEGER NOT NULL,-- Informa a qual produto pertence essa composição, deve sempre ser um produto do tipo Composição[N:Composição][G:a][S:S]
  "ProdutoID" INTEGER NOT NULL,-- Produto ou composição que faz parte dessa composição, Obs: Não pode ser um pacote[N:Produto da composição][G:o][S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Composicao', 'Opcional', 'Adicional')) DEFAULT 'Composicao',-- Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional' permite desmarcar na venda, 'Adicional' permite adicionar na venda[N:Tipo][G:o][E:Composição|Opcional|Adicional]
  "Quantidade" DOUBLE NOT NULL,-- Quantidade que será consumida desse produto para cada composição formada[N:Quantidade][G:a]
  "Valor" DECIMAL NOT NULL DEFAULT 0,-- Desconto que será realizado ao retirar esse produto da composição no  momento da venda[N:Valor][G:o]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'Y',-- Indica se a composição está sendo usada atualmente na composição do produto[N:Ativa][G:a]
  CONSTRAINT "UK_Composicoes_ComposicaoID_ProdutoID_Tipo"
    UNIQUE("ComposicaoID","ProdutoID","Tipo"),
  CONSTRAINT "FK_Composicoes_Produtos_ComposicaoID"
    FOREIGN KEY("ComposicaoID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Composicoes_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Composicoes.FK_Composicoes_Produtos_ComposicaoID_idx" ON "Composicoes" ("ComposicaoID");
CREATE INDEX "Composicoes.FK_Composicoes_Produtos_ProdutoID_idx" ON "Composicoes" ("ProdutoID");
CREATE TABLE "Impressoras"(
--   Impressora para impressão de serviços e contas[N:Impressora|Impressoras][G:a][L:CadastroImpressoras][K:MZ\Device|MZ\Device\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da impressora[G:o]
  "SetorID" INTEGER NOT NULL,-- Setor de impressão[N:Setor de impressão][G:o]
  "DispositivoID" INTEGER DEFAULT NULL,-- Dispositivo que contém a impressora[N:Dispositivo][G:o]
  "Nome" VARCHAR(100) NOT NULL,-- Nome da impressora instalada no windows[N:Nome][G:o]
  "Driver" VARCHAR(45) DEFAULT NULL,-- Informa qual conjunto de comandos deve ser utilizado[N:Driver][G:o]
  "Descricao" VARCHAR(45) NOT NULL DEFAULT '',-- Descrição da impressora[N:Descrição][G:a][S]
  "Modo" TEXT NOT NULL CHECK("Modo" IN('Terminal', 'Caixa', 'Servico', 'Estoque')) DEFAULT 'Terminal',-- Modo de impressão[N:Modo][G:o][E:Terminal|Caixa|Serviço|Estoque]
  "Opcoes" INTEGER NOT NULL DEFAULT 1,-- Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros[N:Opções][G:a]
  "Colunas" INTEGER NOT NULL DEFAULT 48,-- Quantidade de colunas do cupom[N:Quantidade de colunas][G:a]
  "Avanco" INTEGER NOT NULL DEFAULT 6,-- Quantidade de linhas para avanço do papel[N:Avanço de papel][G:o]
  "Comandos" TEXT DEFAULT NULL,-- Comandos para impressão, quando o driver é customizado[N:Comandos][G:o]
  CONSTRAINT "UK_Impresoras_Setor_Dispositivo_Modo"
    UNIQUE("SetorID","DispositivoID","Modo"),
  CONSTRAINT "UK_Impressoras_Dispositivo_Descricao"
    UNIQUE("DispositivoID","Descricao"),
  CONSTRAINT "FK_Impressoras_Dispositivos_DispositivoID"
    FOREIGN KEY("DispositivoID")
    REFERENCES "Dispositivos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Impressoras_Setores_SetorID"
    FOREIGN KEY("SetorID")
    REFERENCES "Setores"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Impressoras.FK_Impressoras_Dispositivos_DispositivoID_idx" ON "Impressoras" ("DispositivoID");
CREATE INDEX "Impressoras.FK_Impressoras_Setores_SetorID_idx" ON "Impressoras" ("SetorID");
CREATE TABLE "Cheques"(
--   Cheques lançados como pagamentos[N:Cheque|Cheques][G:o][L:Pagamento][K:MZ\Payment|MZ\Payment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cheque[G:o]
  "BancoID" INTEGER NOT NULL,-- Banco do cheque[N:Banco][G:o][S]
  "Agencia" VARCHAR(45) NOT NULL,-- Número da agência[N:Agência][G:a]
  "Conta" VARCHAR(45) NOT NULL,-- Número da conta do banco descrito no cheque[N:Conta][G:a]
  "ClienteID" INTEGER NOT NULL,-- Cliente que emitiu o cheque[N:Cliente][G:o][S:S]
  "Parcelas" INTEGER NOT NULL,-- Quantidade de parcelas/folhas de cheque[N:Parcelas][G:a]
  "Total" DECIMAL NOT NULL,-- Total pago em cheque[N:Total][G:o]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o cheque e todas as suas folhas estão cancelados[N:Cancelado][G:o]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro do cheque[N:Data de cadastro][G:a][D]
  CONSTRAINT "FK_Cheques_Bancos_BancoID"
    FOREIGN KEY("BancoID")
    REFERENCES "Bancos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Cheques_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Cheques.FK_Cheques_Bancos_BancoID_idx" ON "Cheques" ("BancoID");
CREATE INDEX "Cheques.FK_Cheques_Clientes_ClienteID_idx" ON "Cheques" ("ClienteID");
CREATE TABLE "Paises"(
--   Informações de um páis com sua moeda e língua nativa[N:País|Paises][G:o][L:CadastroPaises][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do país[G:o]
  "Nome" VARCHAR(100) NOT NULL,-- Nome do país[N:Nome][G:o][S]
  "Sigla" VARCHAR(10) NOT NULL,-- Abreviação do nome do país[N:Sigla][G:a]
  "Codigo" VARCHAR(10) NOT NULL,-- Código do país com 2 letras[G:o][N:Código]
  "MoedaID" INTEGER NOT NULL,-- Informa a moeda principal do país[N:Moeda][G:a]
  "BandeiraIndex" INTEGER NOT NULL,-- Index da imagem da bandeira do país[N:Bandeira][G:a]
  "LinguagemID" INTEGER NOT NULL DEFAULT 0,-- Linguagem nativa do país[N:Linguagem ID][G:a]
  "Entradas" TEXT DEFAULT NULL,-- Frases, nomes de campos e máscaras específicas do país[N:Entrada][G:a]
  "Unitario" TEXT NOT NULL CHECK("Unitario" IN('Y', 'N')) DEFAULT 'N',-- Informa se o país tem apenas um estado federativo[N:Unitário][G:o][F:'N']
  CONSTRAINT "Nome_UNIQUE"
    UNIQUE("Nome"),
  CONSTRAINT "Sigla_UNIQUE"
    UNIQUE("Sigla"),
  CONSTRAINT "Codigo_UNIQUE"
    UNIQUE("Codigo"),
  CONSTRAINT "FK_Paises_Moedas_MoedaID"
    FOREIGN KEY("MoedaID")
    REFERENCES "Moedas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Paises.FK_Paises_Moedas_MoedaID_idx" ON "Paises" ("MoedaID");
CREATE TABLE "Carteiras"(
--   Informa uma conta bancária ou uma carteira financeira[N:Carteira|Carteiras][G:a][L:CadastroCarteiras][K:MZ\Wallet|MZ\Wallet\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código local da carteira[G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Bancaria', 'Financeira')),-- Tipo de carteira, 'Bancaria' para conta bancária e 'Financeira' para carteira financeira da empresa ou de sites de pagamentos[N:Tipo][G:o][S:S][E:Bancária|Financeira]
  "BancoID" INTEGER DEFAULT NULL,-- Código local do banco quando a carteira for bancária[N:Banco][G:o][S:S]
  "Descricao" VARCHAR(100) NOT NULL,-- Descrição da carteira, nome dado a carteira cadastrada[N:Descrição][G:a][S]
  "Conta" VARCHAR(100) DEFAULT NULL,-- Número da conta bancária ou usuário da conta de acesso da carteira[N:Conta][G:a]
  "Agencia" VARCHAR(200) DEFAULT NULL,-- Número da agência da conta bancária ou site da carteira financeira[N:Agência][G:a]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a carteira ou conta bancária está ativa[N:Ativa][G:a]
  CONSTRAINT "FK_Carteiras_Bancos_BancoID"
    FOREIGN KEY("BancoID")
    REFERENCES "Bancos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Carteiras.FK_Carteiras_Bancos_BancoID_idx" ON "Carteiras" ("BancoID");
CREATE TABLE "Listas"(
--   Lista de compras de produtos[N:Lista de compra|Listas de compras][G:a][L:ListaCompras][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da lista de compras[G:o]
  "Descricao" VARCHAR(100) NOT NULL,-- Nome da lista, pode ser uma data[N:Descrição][G:a][S]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Analise', 'Fechada', 'Comprada')) DEFAULT 'Analise',-- Estado da lista de compra. Análise: Ainda estão sendo adicionado produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os itens foram comprados[N:Estado][G:o][E:Análise|Fechada|Comprada]
  "EncarregadoID" INTEGER NOT NULL,-- Informa o funcionário encarregado de fazer as compras[N:Encarregado][G:o][S:S]
  "DataViagem" DATETIME NOT NULL,-- Data e hora para o encarregado ir fazer as compras[N:Data de viagem][G:a]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro da lista[N:Data de cadastro][G:a]
  CONSTRAINT "FK_Listas_Funcionario_EncarregadoID"
    FOREIGN KEY("EncarregadoID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Listas.FK_Listas_Funcionario_EncarregadoID_idx" ON "Listas" ("EncarregadoID");
CREATE TABLE "Compras"(
--   Compras realizadas em uma lista num determinado fornecedor[N:Compra|Compras][G:a][L:ListaCompras][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da compra
  "Numero" VARCHAR(50) DEFAULT NULL,-- Informa o número fiscal da compra[N:Número da compra][G:o]
  "CompradorID" INTEGER NOT NULL,-- Informa o funcionário que comprou os produtos da lista[N:Comprador][G:o][S:S]
  "FornecedorID" INTEGER NOT NULL,-- Fornecedor em que os produtos foram compras[N:Fornecedor][G:o][S:S]
  "DocumentoURL" VARCHAR(150) DEFAULT NULL,-- Informa o nome do documento no servidor do sistema[N:Documento][G:o]
  "DataCompra" DATETIME NOT NULL,-- Informa da data de finalização da compra[N:Data da compra][G:a]
  CONSTRAINT "Numero_UNIQUE"
    UNIQUE("Numero"),
  CONSTRAINT "FK_Compras_Fornecedores_FornecedorID"
    FOREIGN KEY("FornecedorID")
    REFERENCES "Fornecedores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Compras_Funcionarios_CompradorID"
    FOREIGN KEY("CompradorID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Compras.FK_Compras_Fornecedores_FornecedorID_idx" ON "Compras" ("FornecedorID");
CREATE INDEX "Compras.FK_Compras_Funcionarios_CompradorID_idx" ON "Compras" ("CompradorID");
CREATE TABLE "Patrimonios"(
--   Informa detalhadamente um bem da empresa[N:Patrimônio|Patrimônios][G:o][L:CadastroPatrimonio][K:MZ\Environment|MZ\Environment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do bem[G:o]
  "EmpresaID" INTEGER NOT NULL,-- Empresa a que esse bem pertence[N:Empresa][G:a][S:S]
  "FornecedorID" INTEGER DEFAULT NULL,-- Fornecedor do bem[N:Fornecedor][G:o][S:S]
  "Numero" VARCHAR(45) NOT NULL,-- Número que identifica o bem[N:Número][G:o]
  "Descricao" VARCHAR(200) NOT NULL,-- Descrição ou nome do bem[N:Descrição][G:a][S]
  "Quantidade" DOUBLE NOT NULL,-- Quantidade do bem com as mesmas características[N:Quantidade][G:a]
  "Altura" DOUBLE NOT NULL DEFAULT 0,-- Altura do bem em metros[N:Altura][G:a]
  "Largura" DOUBLE NOT NULL DEFAULT 0,-- Largura do bem em metros[N:Largura][G:a]
  "Comprimento" DOUBLE NOT NULL DEFAULT 0,-- Comprimento do bem em metros[N:Comprimento][G:o]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Novo', 'Conservado', 'Ruim')) DEFAULT 'Novo',-- Estado de conservação do bem[N:Estado][G:o]
  "Custo" DECIMAL NOT NULL DEFAULT 0,-- Valor de custo do bem[N:Custo][G:o]
  "Valor" DECIMAL NOT NULL DEFAULT 0,-- Valor que o bem vale atualmente[N:Valor][G:o]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o bem está ativo e em uso[N:Ativo][G:o]
  "ImagemAnexada" VARCHAR(200) DEFAULT NULL,-- Caminho relativo da foto do bem[N:Foto do bem][G:a][I:512x512|patrimonio|patrimonio.png]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização das informações do bem[N:Data de atualização][G:a][D]
  CONSTRAINT "Numero_Estado_UNIQUE"
    UNIQUE("Numero","Estado"),
  CONSTRAINT "FK_Patrimonios_Fornecedores_FornecedorID"
    FOREIGN KEY("FornecedorID")
    REFERENCES "Fornecedores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Patrimonios_Clientes_EmpresaID"
    FOREIGN KEY("EmpresaID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Patrimonios.FK_Patrimonios_Fornecedores_FornecedorID_idx" ON "Patrimonios" ("FornecedorID");
CREATE INDEX "Patrimonios.FK_Patrimonios_Clientes_EmpresaID_idx" ON "Patrimonios" ("EmpresaID");
CREATE TABLE "Acessos"(
--   Permite acesso à uma determinada funcionalidade da lista de permissões[N:Acesso|Acessos][G:o][L:AlterarConfiguracoes][K:MZ\Employee|MZ\Employee\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do acesso[G:o]
  "FuncaoID" INTEGER NOT NULL,-- Função a que a permissão se aplica[N:Função][G:a][S:S]
  "PermissaoID" INTEGER NOT NULL,-- Permissão liberada para a função[N:Permissão][G:a][S]
  CONSTRAINT "UK_Acessos_FuncaoID_PermissaoID"
    UNIQUE("FuncaoID","PermissaoID"),
  CONSTRAINT "FK_Acessos_Funcoes_FuncaoID"
    FOREIGN KEY("FuncaoID")
    REFERENCES "Funcoes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Acessos_Permissoes_PermissaoID"
    FOREIGN KEY("PermissaoID")
    REFERENCES "Permissoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Acessos.FK_Acessos_Funcoes_FuncaoID_idx" ON "Acessos" ("FuncaoID");
CREATE INDEX "Acessos.FK_Acessos_Permissoes_PermissaoID_idx" ON "Acessos" ("PermissaoID");
CREATE TABLE "Movimentacoes"(
--   Movimentação do caixa, permite abrir diversos caixas na conta de operadores[N:Movimentação|Movimentações][G:a][L:AbrirCaixa][K:MZ\Session|MZ\Session\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da movimentação do caixa[G:o]
  "SessaoID" INTEGER NOT NULL,-- Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo código da sessão[N:Sessão][G:a][S:S]
  "CaixaID" INTEGER NOT NULL,-- Caixa a qual pertence essa movimentação[N:Caixa][G:o][S]
  "Aberta" TEXT NOT NULL CHECK("Aberta" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o caixa está aberto[N:Aberta][G:a]
  "FuncionarioAberturaID" INTEGER NOT NULL,-- Funcionário que abriu o caixa[N:Funcionário inicializador][G:a][S:S]
  "DataAbertura" DATETIME NOT NULL,-- Data de abertura do caixa[N:Data de abertura][G:a]
  "FuncionarioFechamentoID" INTEGER DEFAULT NULL,-- Funcionário que fechou o caixa[N:Funcionário fechador][G:o][S:S]
  "DataFechamento" DATETIME DEFAULT NULL,-- Data de fechamento do caixa[N:Data de fechamento][G:a]
  CONSTRAINT "FK_Movimentacoes_Sessoes_SessaoID"
    FOREIGN KEY("SessaoID")
    REFERENCES "Sessoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Movimentacoes_Caixas_CaixaID"
    FOREIGN KEY("CaixaID")
    REFERENCES "Caixas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Movimentacoes_Funcionarios_FuncionarioAberturaID"
    FOREIGN KEY("FuncionarioAberturaID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Movimentacoes_Funcionarios_FuncionarioFechamentoID"
    FOREIGN KEY("FuncionarioFechamentoID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Movimentacoes.FK_Movimentacoes_Sessoes_SessaoID_idx" ON "Movimentacoes" ("SessaoID");
CREATE INDEX "Movimentacoes.FK_Movimentacoes_Caixas_CaixaID_idx" ON "Movimentacoes" ("CaixaID");
CREATE INDEX "Movimentacoes.FK_Movimentacoes_Funcionarios_FuncionarioAberturaID_idx" ON "Movimentacoes" ("FuncionarioAberturaID");
CREATE INDEX "Movimentacoes.FK_Movimentacoes_Funcionarios_FuncionarioFechamentoID_idx" ON "Movimentacoes" ("FuncionarioFechamentoID");
CREATE TABLE "Formas_Pagto"(
--   Formas de pagamento disponíveis para pedido e contas[N:Forma de pagamento|Formas de pagamento][G:a][L:CadastroFormasPagto][K:MZ\Payment|MZ\Payment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da forma de pagamento[G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia')),-- Tipo de pagamento[N:Tipo][G:o][E:Dinheiro|Cartão|Cheque|Conta|Crédito|Transferência][S:S]
  "CarteiraID" INTEGER NOT NULL,-- Carteira que será usada para entrada de valores no caixa[N:Carteira de entrada][G:a]
  "CarteiraPagtoID" INTEGER NOT NULL,-- Carteira de saída de valores do caixa[N:Carteira de saída][G:a]
  "Descricao" VARCHAR(50) NOT NULL,-- Descrição da forma de pagamento[N:Descrição][G:a][S]
  "Parcelado" TEXT NOT NULL CHECK("Parcelado" IN('Y', 'N')),-- Informa se a forma de pagamento permite parcelamento[N:Parcelado][G:o]
  "MinParcelas" INTEGER DEFAULT NULL,-- Quantidade mínima de parcelas[N:Minimo de parcelas][G:a]
  "MaxParcelas" INTEGER DEFAULT NULL,-- Quantidade máxima de parcelas[N:Máximo de parcelas][G:o]
  "ParcelasSemJuros" INTEGER DEFAULT NULL,-- Quantidade de parcelas em que não será cobrado juros[N:Parcelas sem juros][G:a]
  "Juros" DOUBLE DEFAULT NULL,-- Juros cobrado ao cliente no parcelamento[N:Juros][G:o]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a forma de pagamento está ativa[N:Ativa][G:a]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao"),
  CONSTRAINT "FK_Formas_Pagto_Carteiras_CarteiraID"
    FOREIGN KEY("CarteiraID")
    REFERENCES "Carteiras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Formas_Pagto_Carteiras_CarteiraPagtoID"
    FOREIGN KEY("CarteiraPagtoID")
    REFERENCES "Carteiras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Formas_Pagto.FK_Formas_Pagto_Carteiras_CarteiraID_idx" ON "Formas_Pagto" ("CarteiraID");
CREATE INDEX "Formas_Pagto.FK_Formas_Pagto_Carteiras_CarteiraPagtoID_idx" ON "Formas_Pagto" ("CarteiraPagtoID");
CREATE TABLE "Catalogos"(
--   Informa a lista de produtos disponíveis nos fornecedores[N:Catálogo de produtos|Catálogos de produtos][G:o][L:CadastroFornecedores][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do catálogo[G:o]
  "ProdutoID" INTEGER NOT NULL,-- Produto consultado[N:Produto][G:o][S]
  "FornecedorID" INTEGER NOT NULL,-- Fornecedor que possui o produto à venda[N:Fornecedor][G:o][S:S]
  "PrecoCompra" DECIMAL NOT NULL,-- Preço a qual o produto foi comprado da última vez[N:Preço de compra][G:o]
  "PrecoVenda" DECIMAL NOT NULL DEFAULT 0,-- Preço de venda do produto pelo fornecedor na última consulta[N:Preço de venda][G:o]
  "QuantidadeMinima" DOUBLE NOT NULL DEFAULT 1,-- Quantidade mínima que o fornecedor vende[N:Quantidade mínima][G:a]
  "Estoque" DOUBLE NOT NULL DEFAULT 0,-- Quantidade em estoque do produto no fornecedor[N:Estoque][G:o]
  "Limitado" TEXT NOT NULL CHECK("Limitado" IN('Y', 'N')) DEFAULT 'N',-- Informa se a quantidade de estoque é limitada[N:Limitado][G:o]
  "DataConsulta" DATETIME DEFAULT NULL,-- Última data de consulta do preço do produto[N:Data de consulta][G:a]
  CONSTRAINT "FK_Catalogos_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Catalogos_Fornecedores_FornecedorID"
    FOREIGN KEY("FornecedorID")
    REFERENCES "Fornecedores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Catalogos.FK_Catalogos_Produtos_ProdutoID_idx" ON "Catalogos" ("ProdutoID");
CREATE INDEX "Catalogos.FK_Catalogos_Fornecedores_FornecedorID_idx" ON "Catalogos" ("FornecedorID");
CREATE TABLE "Auditoria"(
--   Registra todas as atividades importantes do sistema[N:Auditoria|Auditorias][G:a][L:RelatorioAuditoria][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da auditoria[G:o]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que exerceu a atividade[N:Funcionário][G:o][S:S]
  "AutorizadorID" INTEGER NOT NULL,-- Funcionário que autorizou o acesso ao recurso descrito[N:Autorizador][G:o][S:S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Financeiro', 'Administrativo')),-- Tipo de atividade exercida[N:Tipo][G:o][S:S]
  "Prioridade" TEXT NOT NULL CHECK("Prioridade" IN('Baixa', 'Media', 'Alta')),-- Prioridade de acesso do recurso[N:Prioridade][G:a][E:Baixa|Média|Alta][S:S]
  "Descricao" VARCHAR(255) NOT NULL,-- Descrição da atividade exercida[N:Descrição][G:a][S]
  "DataHora" DATETIME NOT NULL,-- Data e hora do ocorrido[N:Data e hora][G:a]
  CONSTRAINT "FK_Auditoria_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Auditoria_Funcionarios_AutorizadorID"
    FOREIGN KEY("AutorizadorID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Auditoria.FK_Auditoria_Funcionarios_FuncionarioID_idx" ON "Auditoria" ("FuncionarioID");
CREATE INDEX "Auditoria.IDX_Auditoria_Prioridade" ON "Auditoria" ("Prioridade");
CREATE INDEX "Auditoria.IDX_Auditoria_Tipo" ON "Auditoria" ("Tipo");
CREATE INDEX "Auditoria.IDX_Auditoria_DataHora" ON "Auditoria" ("DataHora");
CREATE INDEX "Auditoria.FK_Auditoria_Funcionarios_AutorizadorID_idx" ON "Auditoria" ("AutorizadorID");
CREATE TABLE "Folhas_Cheques"(
--   Folhas de cheque de um pagamento[N:Folha de cheque|Folhas de cheques][G:a][L:Pagamento][K:MZ\Payment|MZ\Payment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da folha de cheque[G:o]
  "ChequeID" INTEGER NOT NULL,-- Cheque a qual pertence esssa folha[N:Cheque][G:o][S:S]
  "Compensacao" VARCHAR(10) NOT NULL,-- Número de compensação do cheque[N:Compensação][G:a]
  "Numero" VARCHAR(20) NOT NULL,-- Número da folha do cheque[N:Número][G:o][S]
  "Valor" DECIMAL NOT NULL,-- Valor na folha do cheque[N:Valor][G:o]
  "Vencimento" DATETIME NOT NULL,-- Data de vencimento do cheque[N:Vencimento][G:o]
  "C1" INTEGER NOT NULL,-- C1 do cheque[N:C1][G:o]
  "C2" INTEGER NOT NULL,-- C2 do cheque[N:C2][G:o]
  "C3" INTEGER NOT NULL,-- C3 do cheque[N:C3][G:o]
  "Serie" VARCHAR(10) DEFAULT NULL,-- Número de série do cheque[N:Série][G:a]
  "Recolhido" TEXT NOT NULL CHECK("Recolhido" IN('Y', 'N')) DEFAULT 'N',-- Informa se o cheque foi recolhido no banco[N:Recolhido][G:o]
  "Recolhimento" DATETIME DEFAULT NULL,-- Data de recolhimento do cheque[N:Data de recolhimento][G:a]
  CONSTRAINT "UK_Folhas_Cheques_ChequeID_Numero"
    UNIQUE("ChequeID","Numero"),
  CONSTRAINT "FK_Folhas_Cheques_Cheques_ChequeID"
    FOREIGN KEY("ChequeID")
    REFERENCES "Cheques"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Folhas_Cheques.FK_Folhas_Cheques_Cheques_ChequeID_idx" ON "Folhas_Cheques" ("ChequeID");
CREATE INDEX "Folhas_Cheques.IDX_Folhas_Cheques_Vencimento_Recolhido" ON "Folhas_Cheques" ("Vencimento","Recolhido");
CREATE TABLE "Promocoes"(
--   Informa se há descontos nos produtos em determinados dias da semana, o preço pode subir ou descer[N:Promoção|Promoções][G:a][L:CadastroProdutos][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da promoção[G:o]
  "ProdutoID" INTEGER NOT NULL,-- Informa qual o produto que possui desconto ou acréscimo[N:Produto][G:o][S]
  "Inicio" INTEGER NOT NULL,-- Dia inicial em que o produto começa a sofrer alteração de preço[N:Dia inicial][G:o]
  "Fim" INTEGER NOT NULL,-- Dia final em que o produto deixará de estar na promoção[N:Dia final][G:o]
  "Valor" DECIMAL NOT NULL,-- Acréscimo ou desconto aplicado ao produto produto[N:Valor][G:o]
  "Proibir" TEXT NOT NULL CHECK("Proibir" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve proibir a venda desse produto no período informado[N:Proibir a venda][G:a]
  CONSTRAINT "FK_Promocoes_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Promocoes.FK_Promocoes_Produtos_ProdutoID_idx" ON "Promocoes" ("ProdutoID");
CREATE TABLE "Creditos"(
--   Créditos de clientes[N:Crédito|Créditos][G:o][L:CadastrarCreditos][K:MZ\Account|MZ\Account\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do crédito[G:o]
  "ClienteID" INTEGER NOT NULL,-- Cliente a qual o crédito pertence[N:Cliente][G:o][S:S]
  "Valor" DECIMAL NOT NULL,-- Valor do crédito[N:Valor][G:o]
  "Detalhes" VARCHAR(255),-- Detalhes do crédito, justificativa do crédito[N:Detalhes][G:o][S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que cadastrou o crédito[N:Funcionário][G:o][S:S]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o crédito foi cancelado[N:Cancelado][G:o]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro do crédito[N:Data de cadastro][G:a]
  CONSTRAINT "FK_Creditos_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Creditos_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Creditos.FK_Creditos_Clientes_ClienteID_idx" ON "Creditos" ("ClienteID");
CREATE INDEX "Creditos.FK_Creditos_Funcionarios_FuncionarioID_idx" ON "Creditos" ("FuncionarioID");
CREATE TABLE "Grupos"(
--   Grupos de pacotes, permite criar grupos como Tamanho, Sabores para formações de produtos[N:Grupo|Grupos][G:o][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do grupo[G:o]
  "ProdutoID" INTEGER NOT NULL,-- Informa o pacote base da formação[N:Pacote][G:o][S:S]
  "Descricao" VARCHAR(100) NOT NULL,-- Descrição do grupo da formação, Exemplo: Tamanho, Sabores[N:Descrição][G:a][S]
  "Multiplo" TEXT NOT NULL CHECK("Multiplo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se é possível selecionar mais de um produto ou opção do produto[N:Múltiplo][G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Inteiro', 'Fracionado')) DEFAULT 'Inteiro',-- Informa se a formação final será apenas uma unidade ou vários itens[N:Tipo][G:o]
  "QuantidadeMinima" INTEGER NOT NULL DEFAULT 1,-- Permite definir uma quantidade mínima obrigatória para continuar com a venda[N:Quantidade mínima][G:a]
  "QuantidadeMaxima" INTEGER NOT NULL DEFAULT 0,-- Define a quantidade máxima de itens que podem ser escolhidos[N:Quantidade máxima][G:a]
  "Funcao" TEXT NOT NULL CHECK("Funcao" IN('Minimo', 'Media', 'Maximo', 'Soma')) DEFAULT 'Soma',-- Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor preço, Média:  define o preço do produto como a média dos itens selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma: Soma todos os preços dos produtos selecionados[N:Função de preço][G:a][E:Mínimo|Média|Máximo|Soma]
  CONSTRAINT "UK_Grupos_Produto_Descricao"
    UNIQUE("ProdutoID","Descricao"),
  CONSTRAINT "FK_Grupos_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Grupos.FK_Grupos_Produtos_ProdutoID_idx" ON "Grupos" ("ProdutoID");
CREATE TABLE "Resumos"(
--   Resumo de fechamento de caixa, informa o valor contado no fechamento do caixa para cada forma de pagamento[N:Resumo|Resumos][G:o][L:ConferirCaixa][K:MZ\Session|MZ\Session\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do resumo[G:o]
  "MovimentacaoID" INTEGER NOT NULL,-- Movimentação do caixa referente ao resumo[N:Movimentação][G:a][S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia')),-- Tipo de pagamento do resumo[N:Tipo][G:o][E:Dinheiro|Cartão|Cheque|Conta|Crédito|Transferência][S:S]
  "CartaoID" INTEGER DEFAULT NULL,-- Cartão da forma de pagamento[N:Cartão][G:o]
  "Valor" DECIMAL NOT NULL,-- Valor que foi contado ao fechar o caixa[N:Valor][G:o]
  CONSTRAINT "UK_Resumos_MovimentacaoID_Tipo_CartaoID"
    UNIQUE("MovimentacaoID","Tipo","CartaoID"),
  CONSTRAINT "FK_Resumos_Movimentacoes_MovimentacaoID"
    FOREIGN KEY("MovimentacaoID")
    REFERENCES "Movimentacoes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Resumos_Cartoes_CartaoID"
    FOREIGN KEY("CartaoID")
    REFERENCES "Cartoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Resumos.FK_Resumos_Movimentacoes_MovimentacaoID_idx" ON "Resumos" ("MovimentacaoID");
CREATE INDEX "Resumos.FK_Resumos_Cartoes_CartaoID_idx" ON "Resumos" ("CartaoID");
CREATE TABLE "Enderecos"(
--   Endereços de ruas e avenidas com informação de CEP[N:Endereço|Endereços][G:o][L:CadastroBairros][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do endereço[G:o]
  "CidadeID" INTEGER NOT NULL,-- Cidade a qual o endereço pertence[N:Cidade][G:a][S:S]
  "BairroID" INTEGER NOT NULL,-- Bairro a qual o endereço está localizado[N:Bairro][G:o][S:S]
  "Logradouro" VARCHAR(200) NOT NULL,-- Nome da rua ou avenida[N:Logradouro][G:o][S]
  "CEP" VARCHAR(8) NOT NULL,-- Código dos correios para identificar a rua ou avenida[N:CEP][G:o][M:99999-999]
  CONSTRAINT "CEP_UNIQUE"
    UNIQUE("CEP"),
  CONSTRAINT "BairroID_Logradouro_UNIQUE"
    UNIQUE("BairroID","Logradouro"),
  CONSTRAINT "FK_Enderecos_Cidades_CidadeID"
    FOREIGN KEY("CidadeID")
    REFERENCES "Cidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Enderecos_Bairros_BairroID"
    FOREIGN KEY("BairroID")
    REFERENCES "Bairros"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Enderecos.FK_Enderecos_Cidades_CidadeID_idx" ON "Enderecos" ("CidadeID");
CREATE INDEX "Enderecos.FK_Enderecos_Bairros_BairroID_idx" ON "Enderecos" ("BairroID");
CREATE TABLE "Cidades"(
--   Cidade de um estado, contém bairros[N:Cidade|Cidades][G:a][L:CadastroCidades][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código que identifica a cidade[G:o]
  "EstadoID" INTEGER NOT NULL,-- Informa a qual estado a cidade pertence[N:Estado][G:o]
  "Nome" VARCHAR(100) NOT NULL,-- Nome da cidade, é único para cada estado[S][N:Nome][G:o]
  "CEP" VARCHAR(8) DEFAULT NULL,-- Código dos correios para identificação da cidade[M:99999-999][G:o][N:CEP]
  CONSTRAINT "EstadoID_Nome_UNIQUE"
    UNIQUE("EstadoID","Nome"),
  CONSTRAINT "CEP_UNIQUE"
    UNIQUE("CEP"),
  CONSTRAINT "FK_Cidades_Estados_EstadoID"
    FOREIGN KEY("EstadoID")
    REFERENCES "Estados"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Cidades.FK_Cidades_Estados_EstadoID_idx" ON "Cidades" ("EstadoID");
CREATE TABLE "Informacoes"(
--   Permite cadastrar informações da tabela nutricional[N:Informação nutricional|Informações nutricionais][G:a][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da informação nutricional[G:o]
  "ProdutoID" INTEGER NOT NULL,-- Produto a que essa tabela de informações nutricionais pertence[N:Produto][G:o][S]
  "UnidadeID" INTEGER NOT NULL,-- Unidade de medida da porção[N:Unidade][G:a]
  "Porcao" DOUBLE NOT NULL,-- Quantidade da porção para base nos valores nutricionais[N:Porção][G:a]
  "Dieta" DOUBLE NOT NULL DEFAULT 2000000,-- Informa a quantidade de referência da dieta geralmente 2000kcal ou 8400kJ[N:Dieta][G:a]
  "Ingredientes" TEXT DEFAULT NULL,-- Informa todos os ingredientes que compõe o produto[N:Ingredientes][G:o]
  CONSTRAINT "ProdutoID_UNIQUE"
    UNIQUE("ProdutoID"),
  CONSTRAINT "FK_Informacoes_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Informacoes_Unidades_UnidadeID"
    FOREIGN KEY("UnidadeID")
    REFERENCES "Unidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Informacoes.FK_Informacoes_Produtos_ProdutoID_idx" ON "Informacoes" ("ProdutoID");
CREATE INDEX "Informacoes.FK_Informacoes_Unidades_UnidadeID_idx" ON "Informacoes" ("UnidadeID");
CREATE TABLE "Requisitos"(
--   Informa os produtos da lista de compras[N:Produtos da lista|Produtos das listas][G:o][L:ListaCompras][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do produto da lista[G:o]
  "ListaID" INTEGER NOT NULL,-- Lista de compra desse produto[N:Lista de compra][G:a][S:S]
  "ProdutoID" INTEGER NOT NULL,-- Produto que deve ser comprado[N:Produto][G:o][S][S:S]
  "CompraID" INTEGER DEFAULT NULL,-- Informa em qual fornecedor foi realizado a compra desse produto[G:a][N:Compra][S:S]
  "FornecedorID" INTEGER DEFAULT NULL,-- Fornecedor em que deve ser consultado ou realizado as compras dos produtos, pode ser alterado no momento da compra[N:Fornecedor][G:o][S:S]
  "Quantidade" DOUBLE NOT NULL DEFAULT 0,-- Quantidade de produtos que deve ser comprado[N:Quantidade][G:a]
  "Comprado" DOUBLE NOT NULL DEFAULT 0,-- Informa quantos produtos já foram comprados[N:Comprado][G:o]
  "PrecoMaximo" DECIMAL NOT NULL,-- Preço máximo que deve ser pago na compra desse produto[N:Preço máximo][G:o]
  "Preco" DECIMAL NOT NULL DEFAULT 0,-- Preço em que o produto foi comprado da última vez ou o novo preço[N:Preço][G:o]
  "Observacoes" VARCHAR(100) DEFAULT NULL,-- Detalhes na compra desse produto[N:Observações][G:a]
  "DataRecolhimento" DATETIME DEFAULT NULL,-- Informa o momento do recolhimento da mercadoria na pratileira[N:Data de recolhimento][G:a]
  CONSTRAINT "FK_Requisitos_Listas_ListaID"
    FOREIGN KEY("ListaID")
    REFERENCES "Listas"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Requisitos_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Requisitos_Fornecedores_FornecedorID"
    FOREIGN KEY("FornecedorID")
    REFERENCES "Fornecedores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Requisitos_Compras_CompraID"
    FOREIGN KEY("CompraID")
    REFERENCES "Compras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Requisitos.FK_Requisitos_Listas_ListaID_idx" ON "Requisitos" ("ListaID");
CREATE INDEX "Requisitos.FK_Requisitos_Produtos_ProdutoID_idx" ON "Requisitos" ("ProdutoID");
CREATE INDEX "Requisitos.FK_Requisitos_Fornecedores_FornecedorID_idx" ON "Requisitos" ("FornecedorID");
CREATE INDEX "Requisitos.FK_Requisitos_Compras_CompraID_idx" ON "Requisitos" ("CompraID");
CREATE TABLE "Pagamentos"(
--   Pagamentos de contas e pedidos[N:Pagamento|Pagamentos][G:o][L:Pagamento][K:MZ\Payment|MZ\Payment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do pagamento[G:o]
  "CarteiraID" INTEGER NOT NULL,-- Carteira de destino do valor[N:Carteira][G:a]
  "MovimentacaoID" INTEGER DEFAULT NULL,-- Movimentação do caixa quando for pagamento de pedido ou quando a conta for paga do caixa[N:Movimentação][G:a][S:S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que lançou o pagamento no sistema[N:Funcionário][G:o][S:S]
  "FormaPagtoID" INTEGER NOT NULL,-- Forma da pagamento do pedido ou conta[N:Forma de pagamento][G:a][S]
  "PedidoID" INTEGER DEFAULT NULL,-- Pedido que foi pago[N:Pedido][G:o][S:S]
  "PagtoContaID" INTEGER DEFAULT NULL,-- Conta que foi paga/recebida[N:Conta][G:a][S:S]
  "CartaoID" INTEGER DEFAULT NULL,-- Cartão em que foi pago, para forma de pagamento em cartão[N:Cartão][G:o]
  "ChequeID" INTEGER DEFAULT NULL,-- Cheque em que foi pago[N:Cheque][G:o][S:S]
  "ContaID" INTEGER DEFAULT NULL,-- Conta que foi utilizada como pagamento do pedido[N:Conta pedido][G:a][S:S]
  "CreditoID" INTEGER DEFAULT NULL,-- Crédito que foi utilizado para pagar o pedido[N:Crédito][G:o][S:S]
  "Total" DECIMAL NOT NULL,-- Total do pagamento, não inclui juros, negativo para trocos e pagamento de contas[N:Total][G:o]
  "Parcelas" INTEGER NOT NULL,-- Quantidade de parcelas quando pagamento parcelado[N:Parcelas][G:a]
  "ValorParcela" DECIMAL NOT NULL,-- Valor da parcela em caso de parcelamento[N:Valor da parcela][G:o]
  "Taxas" DECIMAL NOT NULL DEFAULT 0,-- Total de taxas cobrada por financeiras e outros (Não negativo)[N:Taxas][G:a]
  "Detalhes" VARCHAR(200) DEFAULT NULL,-- Detalhes do pagamento[N:Detalhes][G:o]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o pagamento foi cancelado[N:Cancelado][G:o]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o pagamento está efetivado(Sim) ou apenas foi lançado(Não)[N:Ativo][G:o]
  "DataCompensacao" DATETIME NOT NULL,-- Data de compensação do pagamento[N:Data de compensação][G:a]
  "DataHora" DATETIME NOT NULL,-- Data de hora do lançamento do pagamento[N:Data de hora][G:a]
  CONSTRAINT "FK_Pagamentos_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Formas_Pagto_FormaPagtoID"
    FOREIGN KEY("FormaPagtoID")
    REFERENCES "Formas_Pagto"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Pedidos_PedidoID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Cartoes_CartaoID"
    FOREIGN KEY("CartaoID")
    REFERENCES "Cartoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Cheques_ChequeID"
    FOREIGN KEY("ChequeID")
    REFERENCES "Cheques"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Contas_ContaID"
    FOREIGN KEY("ContaID")
    REFERENCES "Contas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Contas_PagtoContaID"
    FOREIGN KEY("PagtoContaID")
    REFERENCES "Contas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Movimentacoes_MovimentacaoID"
    FOREIGN KEY("MovimentacaoID")
    REFERENCES "Movimentacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Carteiras_CarteiraID"
    FOREIGN KEY("CarteiraID")
    REFERENCES "Carteiras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pagamentos_Creditos_CreditoID"
    FOREIGN KEY("CreditoID")
    REFERENCES "Creditos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Pagamentos.FK_Pagamentos_Funcionarios_FuncionarioID_idx" ON "Pagamentos" ("FuncionarioID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Formas_Pagto_FormaPagtoID_idx" ON "Pagamentos" ("FormaPagtoID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Pedidos_PedidoID_idx" ON "Pagamentos" ("PedidoID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Cartoes_CartaoID_idx" ON "Pagamentos" ("CartaoID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Cheques_ChequeID_idx" ON "Pagamentos" ("ChequeID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Contas_ContaID_idx" ON "Pagamentos" ("ContaID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Contas_PagtoContaID_idx" ON "Pagamentos" ("PagtoContaID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Movimentacoes_MovimentacaoID_idx" ON "Pagamentos" ("MovimentacaoID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Creditos_CreditoID_idx" ON "Pagamentos" ("CreditoID");
CREATE INDEX "Pagamentos.FK_Pagamentos_Carteiras_CarteiraID_idx" ON "Pagamentos" ("CarteiraID");
CREATE TABLE "Estados"(
--   Estado federativo de um país[N:Estado|Estados][G:o][L:CadastroEstados][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do estado[G:o]
  "PaisID" INTEGER NOT NULL,-- País a qual esse estado pertence[N:País][G:o][S:S]
  "Nome" VARCHAR(64) NOT NULL,-- Nome do estado[N:Nome][G:o][S]
  "UF" VARCHAR(48) NOT NULL,-- Sigla do estado[N:UF]
  CONSTRAINT "PaisID_Nome_UNIQUE"
    UNIQUE("PaisID","Nome"),
  CONSTRAINT "PaisID_UF_UNIQUE"
    UNIQUE("PaisID","UF"),
  CONSTRAINT "FK_Estados_Paises_PaisID"
    FOREIGN KEY("PaisID")
    REFERENCES "Paises"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE TABLE "Estoque"(
--   Estoque de produtos por setor[N:Estoque|Estoques][G:o][L:Estoque][K:MZ\Stock|MZ\Stock\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da entrada no estoque[G:o]
  "ProdutoID" INTEGER NOT NULL,-- Produto que entrou no estoque[N:Produto][G:o][S][S:S]
  "TransacaoID" INTEGER DEFAULT NULL,-- Identificador do item que gerou a saída desse produto do estoque[N:Transação][G:a][S:S]
  "EntradaID" INTEGER DEFAULT NULL,-- Informa de qual entrada no estoque essa saída foi retirada, permite estoque FIFO[N:Entrada][G:a][S:S]
  "FornecedorID" INTEGER DEFAULT NULL,-- Fornecedor do produto[N:Fornecedor][G:o][S:S]
  "SetorID" INTEGER NOT NULL,-- Setor de onde o produto foi inserido ou retirado[N:Setor][G:o]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que inseriu/retirou o produto do estoque[N:Funcionário][G:o][S:S]
  "TipoMovimento" TEXT NOT NULL CHECK("TipoMovimento" IN('Entrada', 'Venda', 'Consumo', 'Transferencia')),-- Tipo de movimentação do estoque. Entrada: Entrada de produtos no estoque, Venda: Saída de produtos através de venda, Consumo: Saída de produtos por consumo próprio, Transferência: Indica a transferência de produtos entre setores[N:Tipo de movimento][G:o][E:Entrada|Venda|Consumo|Transferência][S:S]
  "Quantidade" DOUBLE NOT NULL,-- Quantidade do mesmo produto inserido no estoque[N:Quantidade][G:a]
  "PrecoCompra" DECIMAL NOT NULL DEFAULT 0,-- Preço de compra do produto[N:Preço de compra][G:o]
  "Lote" VARCHAR(45) DEFAULT NULL,-- Lote de produção do produto comprado[N:Lote][G:o]
  "DataFabricacao" DATETIME DEFAULT NULL,-- Data de fabricação do produto[N:Data de fabricação][G:a]
  "DataVencimento" DATETIME DEFAULT NULL,-- Data de vencimento do produto[N:Data de vencimento][G:a]
  "Detalhes" VARCHAR(100) DEFAULT NULL,-- Detalhes da inserção ou retirada do estoque[N:Detalhes][G:o]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa a entrada ou saída do estoque foi cancelada[N:Cancelado][G:o]
  "DataMovimento" DATETIME NOT NULL,-- Data de entrada ou saída do produto do estoque[N:Data de movimento][G:a][D]
  CONSTRAINT "FK_Estoque_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Estoque_ProdutosPedidos_TransacaoID"
    FOREIGN KEY("TransacaoID")
    REFERENCES "Produtos_Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Estoque_Fornecedores_FornecedorID"
    FOREIGN KEY("FornecedorID")
    REFERENCES "Fornecedores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Estoque_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Estoque_Setores_SetorID"
    FOREIGN KEY("SetorID")
    REFERENCES "Setores"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Estoque_Estoque_EntradaID"
    FOREIGN KEY("EntradaID")
    REFERENCES "Estoque"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Estoque.FK_Estoque_Produtos_ProdutoID_idx" ON "Estoque" ("ProdutoID");
CREATE INDEX "Estoque.FK_Estoque_ProdutosPedidos_ID_idx" ON "Estoque" ("TransacaoID");
CREATE INDEX "Estoque.FK_Estoque_Fornecedores_FornecedorID_idx" ON "Estoque" ("FornecedorID");
CREATE INDEX "Estoque.FK_Estoque_Funcionarios_FuncionarioID_idx" ON "Estoque" ("FuncionarioID");
CREATE INDEX "Estoque.FK_Estoque_Setores_SetorID_idx" ON "Estoque" ("SetorID");
CREATE INDEX "Estoque.FK_Estoque_Estoque_EntradaID_idx" ON "Estoque" ("EntradaID");
CREATE TABLE "Notas"(
--   Notas fiscais e inutilizações[N:Nota|Notas][G:a][L:RelatorioPedidos][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da nota[G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Nota', 'Inutilizacao')),-- Tipo de registro se nota ou inutilização[G:o][E:Nota|Inutilização][N:Tipo]
  "Ambiente" TEXT NOT NULL CHECK("Ambiente" IN('Homologacao', 'Producao')),-- Ambiente em que a nota foi gerada[G:o][N:Ambiente][E:Homologação|Produção]
  "Acao" TEXT NOT NULL CHECK("Acao" IN('Autorizar', 'Cancelar', 'Inutilizar')),-- Ação que deve ser tomada sobre a nota fiscal[N:Ação][E:Autorizar|Cancelar|Inutilizar]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Aberto', 'Assinado', 'Pendente', 'Processamento', 'Denegado', 'Rejeitado', 'Cancelado', 'Inutilizado', 'Autorizado')),-- Estado da nota[G:o][N:Estado][E:Aberto|Assinado|Pendente|Em processamento|Denegado|Rejeitado|Cancelado|Inutilizado|Autorizado]
  "Serie" INTEGER NOT NULL,-- Série da nota[G:a][N:Série]
  "NumeroInicial" INTEGER NOT NULL,-- Número inicial da nota[G:o][N:Número]
  "NumeroFinal" INTEGER NOT NULL,-- Número final da nota, igual ao número inicial quando for a nota de um pedido[G:o][N:Número inicial]
  "Sequencia" INTEGER NOT NULL,-- Permite iniciar o número da nota quando alcançar 999.999.999, deve ser incrementado sempre que alcançar[G:o][N:Sequencia]
  "Chave" VARCHAR(50) DEFAULT NULL,-- Chave da nota fiscal[G:a][N:Chave]
  "Recibo" VARCHAR(50) DEFAULT NULL,-- Recibo de envio para consulta posterior[G:o][N:Recibo]
  "Protocolo" VARCHAR(80) DEFAULT NULL,-- Protocolo de autorização da nota fiscal[G:o][N:Protocolo]
  "PedidoID" INTEGER DEFAULT NULL,-- Pedido da nota[N:Pedido][S:S]
  "Motivo" VARCHAR(255) DEFAULT NULL,-- Motivo do cancelamento, contingência ou inutilização[G:o][N:Motivo]
  "Contingencia" TEXT NOT NULL CHECK("Contingencia" IN('Y', 'N')),-- Informa se a nota está em contingência[G:a][N:Contingência]
  "ConsultaURL" VARCHAR(255) DEFAULT NULL,-- URL de consulta da nota fiscal[G:o][N:URL de consulta]
  "QRCode" TEXT DEFAULT NULL,-- Dados do QRCode da nota[G:o][N:QRCode]
  "Tributos" DECIMAL DEFAULT NULL,-- Tributos totais da nota[G:o][N:Tributos]
  "Detalhes" VARCHAR(255) DEFAULT NULL,-- Informações de interesse do contribuinte[G:a][N:Informações de interesse do contribuinte]
  "Corrigido" TEXT NOT NULL CHECK("Corrigido" IN('Y', 'N')) DEFAULT 'Y',-- Informa se os erros já foram corrigidos para retomada do processamento[G:o][N:Corrigido]
  "Concluido" TEXT NOT NULL CHECK("Concluido" IN('Y', 'N')) DEFAULT 'N',-- Informa se todos os processamentos da nota já foram realizados[G:o][N:Concluído]
  "DataAutorizacao" DATETIME DEFAULT NULL,-- Data de autorização da nota fiscal[G:a][N:Data de autorização]
  "DataEmissao" DATETIME NOT NULL,-- Data de emissão da nota[G:a][N:Data de emissão]
  "DataLancamento" DATETIME NOT NULL,-- Data de lançamento da nota no sistema[G:a][N:Data de lançamento]
  CONSTRAINT "FK_Notas_Pedidos_PedidoID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Notas.FK_Notas_Pedidos_PedidoID_idx" ON "Notas" ("PedidoID");
CREATE INDEX "Notas.IDX_Chave" ON "Notas" ("Chave");
CREATE TABLE "Eventos"(
--   Eventos de envio das notas[N:Evento|Eventos][G:o][L:RelatorioAuditoria][K:MZ\Invoice|MZ\Invoice\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do evento[G:o]
  "NotaID" INTEGER NOT NULL,-- Nota a qual o evento foi criado[G:a][N:Nota][S:S]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Aberto', 'Assinado', 'Validado', 'Pendente', 'Processamento', 'Denegado', 'Cancelado', 'Rejeitado', 'Contingencia', 'Inutilizado', 'Autorizado')),-- Estado do evento[G:o][N:Estado][E:Aberto|Assinado|Pendente|Em processamento|Denegado|Cancelado|Rejeitado|Contingência|Inutilizado|Autorizado]
  "Mensagem" TEXT NOT NULL,-- Mensagem do evento, descreve que aconteceu[G:a][N:Mensagem]
  "Codigo" VARCHAR(20) NOT NULL,-- Código de status do evento, geralmente código de erro de uma exceção[G:o][N:Código]
  "DataCriacao" DATETIME NOT NULL,-- Data de criação do evento[G:a][N:Data de criação]
  CONSTRAINT "FK_Eventos_Notas_NotaID"
    FOREIGN KEY("NotaID")
    REFERENCES "Notas"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Eventos.FK_Eventos_Notas_NotaID_idx" ON "Eventos" ("NotaID");
CREATE TABLE "Cartoes"(
--   Cartões utilizados na forma de pagamento em cartão[N:Cartão|Cartões][G:o][L:CadastroCartoes][K:MZ\Payment|MZ\Payment\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cartão[G:o]
  "CarteiraID" INTEGER DEFAULT NULL,-- Carteira de entrada de valores no caixa[N:Carteira de entrada][G:a]
  "CarteiraPagtoID" INTEGER DEFAULT NULL,-- Carteira de saída de pagamentos no caixa[N:Carteira de saída][G:a]
  "Descricao" VARCHAR(50) NOT NULL,-- Descrição do cartão[N:Descrição][G:a][S]
  "ImageIndex" INTEGER DEFAULT NULL,-- Índice da imagem do cartão[N:Índice da imagem][G:a]
  "Mensalidade" DECIMAL NOT NULL DEFAULT 0,-- Valor da mensalidade cobrada pela operadora do cartão[N:Mensalidade][G:a]
  "Transacao" DECIMAL NOT NULL DEFAULT 0,-- Valor cobrado pela operadora para cada transação com o cartão[N:Transação][G:a]
  "Taxa" DOUBLE NOT NULL DEFAULT 0,-- Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a 100[N:Taxa][G:a]
  "DiasRepasse" INTEGER NOT NULL CHECK("DiasRepasse">=0) DEFAULT 30,-- Quantidade de dias para repasse do valor[N:Dias para repasse][G:o]
  "Ativo" TEXT NOT NULL CHECK("Ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o cartão está ativo[N:Ativo][G:o]
  CONSTRAINT "Descricao_UNIQUE"
    UNIQUE("Descricao"),
  CONSTRAINT "FK_Cartoes_Carteiras_CarteiraID"
    FOREIGN KEY("CarteiraID")
    REFERENCES "Carteiras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Cartoes_Carteiras_CarteiraPagtoID"
    FOREIGN KEY("CarteiraPagtoID")
    REFERENCES "Carteiras"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Cartoes.FK_Cartoes_Carteiras_CarteiraID_idx" ON "Cartoes" ("CarteiraID");
CREATE INDEX "Cartoes.FK_Cartoes_Carteiras_CarteiraPagtoID_idx" ON "Cartoes" ("CarteiraPagtoID");
CREATE TABLE "Sistema"(
--   Classe que informa detalhes da empresa, parceiro e opções do sistema como a versão do banco de dados e a licença de uso[N:Sistema|Sistemas][G:o][L:AlterarConfiguracoes][K:MZ\System|MZ\System\][H:\MZ\Database\Helper]
  "ID" TEXT PRIMARY KEY NOT NULL CHECK("ID" IN('1')),-- Identificador único do sistema, valor 1[G:o]
  "PaisID" INTEGER DEFAULT NULL,-- País em que o sistema está sendo utilizado[N:País][G:o]
  "EmpresaID" INTEGER DEFAULT NULL,-- Informa qual a empresa que gerencia o sistema, a empresa deve ser um cliente do tipo pessoa jurídica[N:Empresa][G:a][S:S]
  "ParceiroID" INTEGER DEFAULT NULL,-- Informa quem realiza o suporte do sistema, deve ser um cliente do tipo empresa que possua um acionista como representante[N:Parceiro][G:o][S:S]
  "AccessKey" VARCHAR(255) DEFAULT NULL,-- Chave de acesso ao sistema, a chave é atualizada sempre ao utilizar o programa[N:Chave de acesso][G:a]
  "RegistryKey" TEXT DEFAULT NULL,-- Chave de registro, permite licenças do tipo aluguel[N:Chave de registro][G:a]
  "LicenseKey" TEXT DEFAULT NULL,-- Chave da Licença, permite licença do tipo vitalícia[N:Chave de licença][G:a]
  "Computadores" INTEGER DEFAULT NULL,-- Quantidade de computadores permitido para uso em rede[N:Quantidade de computadores][G:a]
  "GUID" VARCHAR(36) DEFAULT NULL,-- Código único da empresa, permite baixar novas licenças automaticamente[N:Identificador da empresa][G:o]
  "Opcoes" TEXT DEFAULT NULL,-- Opções gerais do sistema como opções de impressão[N:Opções do sistema][G:a]
  "UltimoBackup" DATETIME DEFAULT NULL,-- Informa qual foi a data da última realização de backup do banco de dados do sistema[N:Data do último backup][G:a]
  "VersaoDB" VARCHAR(45) NOT NULL,-- Informa qual a versão do banco de dados[N:Versão do banco de dados][G:a][S]
  CONSTRAINT "FK_Sistema_Clientes_EmpresaID"
    FOREIGN KEY("EmpresaID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Sistema_Clientes_ParceiroID"
    FOREIGN KEY("ParceiroID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Sistema_Paises_PaisID"
    FOREIGN KEY("PaisID")
    REFERENCES "Paises"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Sistema.FK_Sistema_Clientes_EmpresaID_idx" ON "Sistema" ("EmpresaID");
CREATE INDEX "Sistema.FK_Sistema_Clientes_ParceiroID_idx" ON "Sistema" ("ParceiroID");
CREATE INDEX "Sistema.FK_Sistema_Paises_PaisID_idx" ON "Sistema" ("PaisID");
CREATE TABLE "Bairros"(
--   Bairro de uma cidade[N:Bairro|Bairros][G:o][L:CadastroBairros][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do bairro[G:o]
  "CidadeID" INTEGER NOT NULL,-- Cidade a qual o bairro pertence[N:Cidade][G:a][S:S]
  "Nome" VARCHAR(100) NOT NULL,-- Nome do bairro[N:Nome][G:o][S]
  "ValorEntrega" DECIMAL NOT NULL,-- Valor cobrado para entregar um pedido nesse bairro[N:Valor da entrega][G:o]
  "Disponivel" TEXT NOT NULL CHECK("Disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o bairro está disponível para entrega de pedidos[N:Disponível][G:o][F:'Y']
  CONSTRAINT "CidadeID_Nome_UNIQUE"
    UNIQUE("CidadeID","Nome"),
  CONSTRAINT "FK_Bairros_Cidades_CidadeID"
    FOREIGN KEY("CidadeID")
    REFERENCES "Cidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Bairros.FK_Bairros_Cidades_CidadeID_idx" ON "Bairros" ("CidadeID");
CREATE TABLE "Formacoes"(
--   Informa qual foi a formação que gerou esse produto, assim como quais item foram retirados/adicionados da composição[N:Formação|Formações][G:a][L:Pagamento][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da formação[G:o]
  "ProdutoPedidoID" INTEGER NOT NULL,-- Informa qual foi o produto vendido para essa formação[N:Item do pedido][G:o][S:S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Pacote', 'Composicao')) DEFAULT 'Pacote',-- Informa qual tipo de formação foi escolhida, Pacote: O produto ou propriedade faz parte de um pacote, Composição: O produto é uma composição e esse item foi retirado ou adicionado na venda[N:Tipo][G:o]
  "PacoteID" INTEGER DEFAULT NULL,-- Informa qual pacote foi selecionado no momento da venda[N:Pacote][G:o][S]
  "ComposicaoID" INTEGER DEFAULT NULL,-- Informa qual composição foi retirada ou adicionada no momento da venda[N:Composição][G:a]
  "Quantidade" DOUBLE NOT NULL DEFAULT 1,-- Quantidade de itens selecionados[N:Quantidade][G:a]
  CONSTRAINT "UK_Formacoes_ProdutoPedidoID_PacoteID"
    UNIQUE("ProdutoPedidoID","PacoteID"),
  CONSTRAINT "FK_Formacoes_ProdPed_ProdutoPedidoID"
    FOREIGN KEY("ProdutoPedidoID")
    REFERENCES "Produtos_Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Formacoes_Pacotes_PacoteID"
    FOREIGN KEY("PacoteID")
    REFERENCES "Pacotes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Formacoes_Composicoes_ComposicaoID"
    FOREIGN KEY("ComposicaoID")
    REFERENCES "Composicoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Formacoes.FK_Formacoes_ProdPed_ProdutoPedidoID_idx" ON "Formacoes" ("ProdutoPedidoID");
CREATE INDEX "Formacoes.FK_Formacoes_Pacotes_PacoteID_idx" ON "Formacoes" ("PacoteID");
CREATE INDEX "Formacoes.FK_Formacoes_Composicoes_ComposicaoID_idx" ON "Formacoes" ("ComposicaoID");
CREATE TABLE "Produtos_Pedidos"(
--   Produtos, taxas e serviços do pedido, a alteração do estado permite o controle de produção[N:Item do pedido|Itens do pedido][G:o][L:Pagamento][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do item do pedido[G:o]
  "PedidoID" INTEGER NOT NULL,-- Pedido a qual pertence esse item[N:Pedido][G:o][S:S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que lançou esse item no pedido[N:Funcionário][G:o][S:S]
  "ProdutoID" INTEGER,-- Produto vendido[N:Produto][G:o][S][S:S]
  "ServicoID" INTEGER DEFAULT NULL,-- Serviço cobrado ou taxa[N:Serviço][G:o][S:S]
  "ProdutoPedidoID" INTEGER DEFAULT NULL,-- Pacote em que esse item faz parte[N:Pacote][G:o][S:S]
  "Descricao" VARCHAR(200) DEFAULT NULL,-- Sobrescreve a descrição do produto na exibição[N:Descrição][G:a]
  "Preco" DECIMAL NOT NULL,-- Preço do produto já com desconto[N:Preço][G:o]
  "Quantidade" DOUBLE NOT NULL,-- Quantidade de itens vendidos[N:Quantidade][G:a]
  "Porcentagem" DOUBLE NOT NULL DEFAULT 0,-- Porcentagem cobrada sobre essa venda, escala de 0 a 100[N:Porcentagem][G:a]
  "PrecoVenda" DECIMAL NOT NULL,-- Preço de normal do produto no momento da venda[N:Preço de venda][G:o]
  "PrecoCompra" DECIMAL NOT NULL DEFAULT 0,-- Preço de compra do produto calculado automaticamente na hora da venda[N:Preço de compra][G:o]
  "Detalhes" VARCHAR(255) DEFAULT NULL,-- Observações do item pedido, Ex.: bem gelado, mal passado[N:Observações][G:o]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Adicionado', 'Enviado', 'Processado', 'Pronto', 'Disponivel', 'Entregue')) DEFAULT 'Adicionado',-- Estado de preparo e envio do produto[N:Estado][G:o][E:Adicionado|Enviado|Processado|Pronto|Disponível|Entregue]
  "Visualizado" TEXT NOT NULL CHECK("Visualizado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o item foi visualizado por alguém[N:Visualizado][G:o]
  "DataVisualizacao" DATETIME DEFAULT NULL,-- Data de visualização do item[N:Data de visualização][G:a]
  "DataAtualizacao" DATETIME DEFAULT NULL,-- Data de atualização do estado do item[N:Data de atualização][G:a]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o item foi cancelado[N:Cancelado][G:o]
  "Motivo" VARCHAR(200) DEFAULT NULL,-- Informa o motivo do item ser cancelado[N:Motivo][G:o]
  "Desperdicado" TEXT NOT NULL CHECK("Desperdicado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o item foi cancelado por conta de desperdício[N:Desperdiçado][G:o]
  "DataHora" DATETIME NOT NULL,-- Data e hora da realização do pedido do item[N:Data e hora][G:a][D]
  CONSTRAINT "FK_ProdPed_Pedidos_PedID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_ProdPed_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_ProdPed_Funcionario_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_ProdPed_ProdPed_ProdutoPedidoID"
    FOREIGN KEY("ProdutoPedidoID")
    REFERENCES "Produtos_Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_ProdPed_Servicos_ServicoID"
    FOREIGN KEY("ServicoID")
    REFERENCES "Servicos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Produtos_Pedidos.FK_ProdPed_Pedidos_PedID_idx" ON "Produtos_Pedidos" ("PedidoID");
CREATE INDEX "Produtos_Pedidos.FK_ProdPed_Produtos_ProdutoID_idx" ON "Produtos_Pedidos" ("ProdutoID");
CREATE INDEX "Produtos_Pedidos.FK_ProdPed_Funcionarios_FuncionarioID_idx" ON "Produtos_Pedidos" ("FuncionarioID");
CREATE INDEX "Produtos_Pedidos.IDX_ProdPed_DataHora" ON "Produtos_Pedidos" ("DataHora");
CREATE INDEX "Produtos_Pedidos.FK_ProdPed_ProdPed_ProdutoPedidoID_idx" ON "Produtos_Pedidos" ("ProdutoPedidoID");
CREATE INDEX "Produtos_Pedidos.FK_ProdPed_Servicos_ServicoID_idx" ON "Produtos_Pedidos" ("ServicoID");
CREATE TABLE "Contas"(
--   Contas a pagar e ou receber[N:Conta|Contas][G:a][L:CadastroContas][K:MZ\Account|MZ\Account\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da conta[G:o]
  "ClassificacaoID" INTEGER NOT NULL,-- Classificação da conta[N:Classificação][G:a][S:S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que lançou a conta[N:Funcionário][G:o][S:S]
  "SubClassificacaoID" INTEGER DEFAULT NULL,-- Subclassificação da conta[N:Subclassificação][G:a][S:S]
  "ClienteID" INTEGER DEFAULT NULL,-- Cliente a qual a conta pertence[N:Cliente][G:o][S:S]
  "PedidoID" INTEGER DEFAULT NULL,-- Pedido da qual essa conta foi gerada[N:Pedido][G:o][S:S]
  "Descricao" VARCHAR(200) NOT NULL,-- Descrição da conta[N:Descrição][G:a][S]
  "Valor" DECIMAL NOT NULL,-- Valor da conta[N:Valor][G:o]
  "Acrescimo" DECIMAL NOT NULL DEFAULT 0,-- Acréscimo de valores ao total[N:Acréscimo][G:o]
  "Multa" DECIMAL NOT NULL DEFAULT 0,-- Multa em valor em caso atraso[N:Multa][G:a]
  "Juros" DOUBLE NOT NULL DEFAULT 0,-- Juros em caso de atraso, valor de 0 a 1, 1 = 100%[N:Juros][G:o]
  "AutoAcrescimo" TEXT NOT NULL CHECK("AutoAcrescimo" IN('Y', 'N')) DEFAULT 'N',-- Calcula o acréscimo automaticamente no pagamento quando a conta está atrasada[N:Acréscimo automático][G:o]
  "Vencimento" DATETIME DEFAULT NULL,-- Data de vencimento da conta[N:Data de vencimento][G:a]
  "DataEmissao" DATETIME DEFAULT NULL,-- Data de emissão da conta[N:Data de emissão][G:a]
  "NumeroDoc" VARCHAR(64) DEFAULT NULL,-- Número do documento que gerou a conta[N:Número do documento][G:o]
  "AnexoCaminho" VARCHAR(200) DEFAULT NULL,-- Caminho do anexo da conta[N:Anexo][G:o]
  "Cancelada" TEXT NOT NULL CHECK("Cancelada" IN('Y', 'N')) DEFAULT 'N',-- Informa se a conta foi cancelada[N:Cancelada][G:a]
  "DataPagamento" DATETIME DEFAULT NULL,-- Data de pagamento que será atribuida ao pagar a conta[N:Data de pagamento][G:a]
  "DataCadastro" DATETIME NOT NULL,-- Data de cadastro da conta[N:Data de cadastro][G:a]
  CONSTRAINT "FK_Contas_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Contas_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Contas_Pedidos_PedidoID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Contas_Classificacoes_ClassificacaoID"
    FOREIGN KEY("ClassificacaoID")
    REFERENCES "Classificacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Contas_Classificacoes_SubClassificacaoID"
    FOREIGN KEY("SubClassificacaoID")
    REFERENCES "Classificacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Contas.FK_Contas_Clientes_ClienteID_idx" ON "Contas" ("ClienteID");
CREATE INDEX "Contas.FK_Contas_Funcionarios_FuncionarioID_idx" ON "Contas" ("FuncionarioID");
CREATE INDEX "Contas.FK_Contas_Pedidos_PedidoID_idx" ON "Contas" ("PedidoID");
CREATE INDEX "Contas.FK_Contas_Classificacoes_ClassificacaoID_idx" ON "Contas" ("ClassificacaoID");
CREATE INDEX "Contas.FK_Contas_Classificacoes_SubClassificacaoID_idx" ON "Contas" ("SubClassificacaoID");
CREATE TABLE "Valores_Nutricionais"(
--   Informa todos os valores nutricionais da tabela nutricional[N:Valor nutricional|Valores nutricionais][G:o][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do valor nutricional[G:o]
  "InformacaoID" INTEGER NOT NULL,-- Informe a que tabela nutricional este valor pertence[N:Informação][G:a][S:S]
  "UnidadeID" INTEGER NOT NULL,-- Unidade de medida do valor nutricional, geralmente grama, exceto para valor energético[N:Unidade][G:a]
  "Nome" VARCHAR(100) NOT NULL,-- Nome do valor nutricional[N:Nome][G:o][S]
  "Quantidade" DOUBLE NOT NULL,-- Quantidade do valor nutricional com base na porção[N:Quantidade][G:a]
  "ValorDiario" DOUBLE DEFAULT NULL,-- Valor diário em %[N:Valor diário][G:o]
  CONSTRAINT "UK_Informacao_Nome"
    UNIQUE("InformacaoID","Nome"),
  CONSTRAINT "FK_Valores_Nutricionais_Informacoes_InformacaoID"
    FOREIGN KEY("InformacaoID")
    REFERENCES "Informacoes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Valores_Nutricionais_Unidades_UnidadeID"
    FOREIGN KEY("UnidadeID")
    REFERENCES "Unidades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Valores_Nutricionais.FK_Valores_Nutricionais_Unidades_UnidadeID_idx" ON "Valores_Nutricionais" ("UnidadeID");
CREATE TABLE "Localizacoes"(
--   Endereço detalhado de um cliente[N:Localização|Localizações][G:a][L:CadastroClientes][K:MZ\Location|MZ\Location\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do endereço[G:o]
  "ClienteID" INTEGER NOT NULL,-- Cliente a qual esse endereço pertence[N:Cliente][G:o][S:S]
  "BairroID" INTEGER NOT NULL,-- Bairro do endereço[N:Bairro][G:o][S:S]
  "CEP" VARCHAR(8) DEFAULT NULL,-- Código dos correios para identificar um logradouro[M:99999-999][N:CEP][G:o]
  "Logradouro" VARCHAR(100) NOT NULL,-- Nome da rua ou avenida[N:Logradouro][G:o][S]
  "Numero" VARCHAR(20) NOT NULL,-- Número da casa ou do condomínio[N:Número][G:o]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Casa', 'Apartamento')) DEFAULT 'Casa',-- Tipo de endereço Casa ou Apartamento[N:Tipo][G:o][F:self::TIPO_CASA]
  "Complemento" VARCHAR(100) DEFAULT NULL,-- Complemento do endereço, Ex.: Loteamento Sul[N:Complemento][G:o]
  "Condominio" VARCHAR(100) DEFAULT NULL,-- Nome do condomínio[N:Condomínio][G:o]
  "Bloco" VARCHAR(20) DEFAULT NULL,-- Número do bloco quando for apartamento[N:Bloco][G:o]
  "Apartamento" VARCHAR(20) DEFAULT NULL,-- Número do apartamento[N:Apartamento][G:o]
  "Referencia" VARCHAR(200) DEFAULT NULL,-- Ponto de referência para chegar ao local[N:Referência][G:a]
  "Latitude" DOUBLE DEFAULT NULL,-- Ponto latitudinal para localização em um mapa[N:Latitude][G:a]
  "Longitude" DOUBLE DEFAULT NULL,-- Ponto longitudinal para localização em um mapa[N:Longitude][G:a]
  "Apelido" VARCHAR(45) DEFAULT NULL,-- Ex.: Minha Casa, Casa da Amiga[N:Apelido][G:o]
  "Mostrar" TEXT NOT NULL CHECK("Mostrar" IN('Y', 'N')) DEFAULT 'Y',-- Permite esconder ou exibir um endereço do cliente[N:Mostrar][G:o][F:'Y']
  CONSTRAINT "UK_Localizacoes_ClienteID_Apelido"
    UNIQUE("ClienteID","Apelido"),
  CONSTRAINT "FK_Localizacoes_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Localizacoes_Bairros"
    FOREIGN KEY("BairroID")
    REFERENCES "Bairros"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Localizacoes.FK_Localizacoes_Clientes_ClienteID_ID_idx" ON "Localizacoes" ("ClienteID");
CREATE INDEX "Localizacoes.FK_Localizacoes_Bairros_idx" ON "Localizacoes" ("BairroID");
CREATE TABLE "Pedidos"(
--   Informações do pedido de venda[N:Pedido|Pedidos][G:o][L:Pagamento][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código do pedido[N:Código][G:o]
  "MesaID" INTEGER DEFAULT NULL,-- Identificador da mesa, único quando o pedido não está fechado[N:Mesa][G:a][S:S]
  "ComandaID" INTEGER DEFAULT NULL,-- Identificador da comanda, único quando o pedido não está fechado[N:Comanda][G:a][S:S]
  "MovimentacaoID" INTEGER DEFAULT NULL,-- Movimentação do caixa quando o pedido é pago total ou parcial, somente um caixa pode receber os pagamentos de um pedido[N:Movimentação][G:a][S:S]
  "SessaoID" INTEGER NOT NULL,-- Identificador da sessão de vendas[N:Sessão][G:a][S:S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que criou esse pedido[N:Funcionário][G:o][S:S]
  "EntregadorID" INTEGER DEFAULT NULL,-- Entregador que fez a entrega do pedido[N:Entregador][G:o][S:S]
  "ClienteID" INTEGER DEFAULT NULL,-- Identificador do cliente do pedido[N:Cliente][G:o][S:S]
  "LocalizacaoID" INTEGER DEFAULT NULL,-- Endereço de entrega do pedido, se não informado na venda entrega, o pedido será para viagem[N:Localização][G:a][S:S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Mesa', 'Comanda', 'Avulso', 'Entrega')) DEFAULT 'Mesa',-- Tipo de venda[N:Tipo][G:o][E:Mesa|Comanda|Balcão|Entrega][S:S]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Finalizado', 'Ativo', 'Agendado', 'Entrega', 'Fechado')) DEFAULT 'Ativo',-- Estado do pedido, Agendado: O pedido deve ser processado na data de agendamento. Ativo: O pedido deve ser processado. Fechado: O cliente pediu a conta e está pronto para pagar. Entrega: O pedido saiu para entrega. Finalizado: O pedido foi pago e concluído[N:Estado][G:o]
  "Pessoas" INTEGER NOT NULL DEFAULT 1,-- Informa quantas pessoas estão na mesa[N:Pessoas][G:a]
  "Descricao" VARCHAR(255) DEFAULT NULL,-- Detalhes da reserva ou do pedido[N:Descrição][G:a]
  "FechadorID" INTEGER DEFAULT NULL,-- Informa quem fechou o pedido e imprimiu a conta[N:Fechador do pedido][G:o][S:S]
  "DataImpressao" DATETIME DEFAULT NULL,-- Data de impressão da conta do cliente[N:Data de impressão][G:a]
  "Cancelado" TEXT NOT NULL CHECK("Cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o pedido foi cancelado[N:Cancelado][G:o]
  "Motivo" VARCHAR(200) DEFAULT NULL,-- Informa o motivo do cancelamento[G:o][N:Motivo]
  "DataCriacao" DATETIME NOT NULL,-- Data de criação do pedido[N:Data de criação][G:a]
  "DataAgendamento" DATETIME DEFAULT NULL,-- Data de agendamento do pedido[N:Data de agendamento][G:a]
  "DataEntrega" DATETIME DEFAULT NULL,-- Data e hora que o entregador saiu para entregar o pedido[N:Data de entrega][G:a]
  "DataConclusao" DATETIME DEFAULT NULL,-- Data de finalização do pedido[N:Data de conclusão][G:a]
  CONSTRAINT "FK_Pedidos_Mesas_MesaID"
    FOREIGN KEY("MesaID")
    REFERENCES "Mesas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Sessoes_SessaoID"
    FOREIGN KEY("SessaoID")
    REFERENCES "Sessoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Clientes_ClienteID"
    FOREIGN KEY("ClienteID")
    REFERENCES "Clientes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Funcionarios_EntregadorID"
    FOREIGN KEY("EntregadorID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Localizacoes_LocalizacaoID"
    FOREIGN KEY("LocalizacaoID")
    REFERENCES "Localizacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Comandas_ComandaID"
    FOREIGN KEY("ComandaID")
    REFERENCES "Comandas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Movimentacoes_MovimentacaoID"
    FOREIGN KEY("MovimentacaoID")
    REFERENCES "Movimentacoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pedidos_Funcionarios_FechadorID"
    FOREIGN KEY("FechadorID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Pedidos.FK_Pedidos_Mesas_MesaID_idx" ON "Pedidos" ("MesaID");
CREATE INDEX "Pedidos.FK_Pedidos_Sessoes_SessaoID_idx" ON "Pedidos" ("SessaoID");
CREATE INDEX "Pedidos.FK_Pedidos_Funcionarios_FuncionarioID_idx" ON "Pedidos" ("FuncionarioID");
CREATE INDEX "Pedidos.IDX_Pedidos_MesaID_Estado" ON "Pedidos" ("MesaID","Estado");
CREATE INDEX "Pedidos.FK_Pedidos_Clientes_ClienteID_idx" ON "Pedidos" ("ClienteID");
CREATE INDEX "Pedidos.IDX_Pedidos_SessaoID_Tipo_Estado" ON "Pedidos" ("SessaoID","Tipo","Estado");
CREATE INDEX "Pedidos.FK_Pedidos_Funcionarios_EntregadorID_idx" ON "Pedidos" ("EntregadorID");
CREATE INDEX "Pedidos.FK_Pedidos_Localizacoes_LocalizacaoID_idx" ON "Pedidos" ("LocalizacaoID");
CREATE INDEX "Pedidos.FK_Pedidos_Comandas_ComandaID_idx" ON "Pedidos" ("ComandaID");
CREATE INDEX "Pedidos.FK_Pedidos_Movimentacoes_MovimentacaoID_idx" ON "Pedidos" ("MovimentacaoID");
CREATE INDEX "Pedidos.FK_Pedidos_Funcionarios_FechadorID_idx" ON "Pedidos" ("FechadorID");
CREATE INDEX "Pedidos.IDX_Pedidos_ComandaID_Estado" ON "Pedidos" ("ComandaID","Estado");
CREATE TABLE "Pacotes"(
--   Contém todos as opções para a formação do produto final[N:Pacote|Pacotes][G:o][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do pacote[G:o]
  "PacoteID" INTEGER NOT NULL,-- Pacote a qual pertence as informações de formação do produto final[N:Pacote][G:o][S:S]
  "GrupoID" INTEGER NOT NULL,-- Grupo de formação, Ex.: Tamanho, Sabores e Complementos.[N:Grupo][G:o][S:S]
  "ProdutoID" INTEGER DEFAULT NULL,-- Produto selecionável do grupo. Não pode conter propriedade.[N:Produto][G:o][S][S:S]
  "PropriedadeID" INTEGER DEFAULT NULL,-- Propriedade selecionável do grupo. Não pode conter produto.[N:Propriedade][G:a]
  "AssociacaoID" INTEGER DEFAULT NULL,-- Informa a propriedade pai de um complemento, permite atribuir preços diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde Tamanho é pai de Sabor[N:Associação][G:a]
  "QuantidadeMinima" INTEGER NOT NULL DEFAULT 0,-- Permite definir uma quantidade mínima obrigatória para a venda desse item[N:Quantidade mínima][G:a][F:0]
  "QuantidadeMaxima" INTEGER NOT NULL DEFAULT 1,-- Define a quantidade máxima que pode ser vendido esse item repetidamente[N:Quantidade máxima][G:a][F:1]
  "Valor" DECIMAL NOT NULL,-- Valor acrescentado ao produto quando o item é selecionado[N:Valor][G:o]
  "Selecionado" TEXT NOT NULL CHECK("Selecionado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o complemento está selecionado por padrão, recomendado apenas para produtos[N:Selecionado][G:o][F:'N']
  "Visivel" TEXT NOT NULL CHECK("Visivel" IN('Y', 'N')) DEFAULT 'Y',-- Indica se o pacote estará disponível para venda[N:Visível][G:o][F:'Y']
  CONSTRAINT "FK_Pacotes_Produtos_PacoteID"
    FOREIGN KEY("PacoteID")
    REFERENCES "Produtos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pacotes_Produtos_ProdutoID"
    FOREIGN KEY("ProdutoID")
    REFERENCES "Produtos"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pacotes_Grupos_GrupoID"
    FOREIGN KEY("GrupoID")
    REFERENCES "Grupos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pacotes_Pacotes_AssociacaoID"
    FOREIGN KEY("AssociacaoID")
    REFERENCES "Pacotes"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Pacotes_Propriedades_PropriedadeID"
    FOREIGN KEY("PropriedadeID")
    REFERENCES "Propriedades"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "Pacotes.FK_Pacotes_Produtos_PacoteID_idx" ON "Pacotes" ("PacoteID");
CREATE INDEX "Pacotes.FK_Pacotes_Produtos_ProdutoID_idx" ON "Pacotes" ("ProdutoID");
CREATE INDEX "Pacotes.FK_Pacotes_Grupos_GrupoID_idx" ON "Pacotes" ("GrupoID");
CREATE INDEX "Pacotes.FK_Pacotes_Pacotes_AssociacaoID_idx" ON "Pacotes" ("AssociacaoID");
CREATE INDEX "Pacotes.FK_Pacotes_Propriedades_PropriedadeID_idx" ON "Pacotes" ("PropriedadeID");
CREATE TABLE "Transferencias"(
--   Informa a transferência de uma mesa / comanda para outra, ou de um produto para outra mesa / comanda[N:Transferência|Transferências][G:a][L:TransferirProdutos][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da transferência[G:o]
  "PedidoID" INTEGER NOT NULL,-- Identificador do pedido de origem[N:Pedido de origem][G:o][S][S:S]
  "DestinoPedidoID" INTEGER NOT NULL,-- Identificador do pedido de destino[N:Pedido de destino][G:o][S:S]
  "Tipo" TEXT NOT NULL CHECK("Tipo" IN('Pedido', 'Produto')),-- Tipo de transferência, se de mesa/comanda ou de produto[N:Tipo][G:o]
  "Modulo" TEXT NOT NULL CHECK("Modulo" IN('Mesa', 'Comanda')),-- Módulo de venda, se mesa ou comanda[N:Módulo][G:o][S:S]
  "MesaID" INTEGER DEFAULT NULL,-- Identificador da mesa de origem[N:Mesa de origem][G:a][S:S]
  "DestinoMesaID" INTEGER DEFAULT NULL,-- Mesa de destino da transferência[N:Mesa de destino][G:a][S:S]
  "ComandaID" INTEGER DEFAULT NULL,-- Comanda de origem da transferência[N:Comanda de origem][G:a][S:S]
  "DestinoComandaID" INTEGER DEFAULT NULL,-- Comanda de destino[N:Comanda de destino][G:a][S:S]
  "ProdutoPedidoID" INTEGER DEFAULT NULL,-- Item que foi transferido[N:Item transferido][G:o][S:S]
  "FuncionarioID" INTEGER NOT NULL,-- Funcionário que transferiu esse pedido/produto[N:Funcionário][G:o][S:S]
  "DataHora" DATETIME NOT NULL,-- Data e hora da transferência[N:Data e hora][G:a][D]
  CONSTRAINT "FK_Transf_Pedidos_PedidoID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Pedidos_DestinoPedidoID"
    FOREIGN KEY("DestinoPedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Mesas_MesaID"
    FOREIGN KEY("MesaID")
    REFERENCES "Mesas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Mesas_DestinoMesaID"
    FOREIGN KEY("DestinoMesaID")
    REFERENCES "Mesas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Funcionarios_FuncionarioID"
    FOREIGN KEY("FuncionarioID")
    REFERENCES "Funcionarios"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Comandas_ComandaID"
    FOREIGN KEY("ComandaID")
    REFERENCES "Comandas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_Comandas_DestinoComandaID"
    FOREIGN KEY("DestinoComandaID")
    REFERENCES "Comandas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Transf_ProdPed_ProdutoPedidoID"
    FOREIGN KEY("ProdutoPedidoID")
    REFERENCES "Produtos_Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Transferencias.FK_Transf_Pedidos_PedidoID_idx" ON "Transferencias" ("PedidoID");
CREATE INDEX "Transferencias.FK_Transf_Pedidos_DestinoPedidoID_idx" ON "Transferencias" ("DestinoPedidoID");
CREATE INDEX "Transferencias.FK_Transf_Mesas_MesaID_idx" ON "Transferencias" ("MesaID");
CREATE INDEX "Transferencias.FK_Transf_Mesas_DestinoMesaID_idx" ON "Transferencias" ("DestinoMesaID");
CREATE INDEX "Transferencias.FK_Transf_Funcionarios_FuncionarioID_idx" ON "Transferencias" ("FuncionarioID");
CREATE INDEX "Transferencias.FK_Transf_Comandas_ComandaID_idx" ON "Transferencias" ("ComandaID");
CREATE INDEX "Transferencias.FK_Transf_Comandas_DestinoComandaID_idx" ON "Transferencias" ("DestinoComandaID");
CREATE INDEX "Transferencias.FK_Transf_ProdPed_ProdutoPedidoID_idx" ON "Transferencias" ("ProdutoPedidoID");
CREATE TABLE "Propriedades"(
--   Informa tamanhos de pizzas e opções de peso do produto[N:Propriedade|Propriedades][G:a][L:CadastroProdutos][K:MZ\Product|MZ\Product\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da propriedade[G:o]
  "GrupoID" INTEGER NOT NULL,-- Grupo que possui essa propriedade como item de um pacote[N:Grupo][G:o][S:S]
  "Nome" VARCHAR(100) NOT NULL,-- Nome da propriedade, Ex.: Grande, Pequena[N:Nome][G:o][S]
  "Abreviacao" VARCHAR(100) DEFAULT NULL,-- Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena, essa abreviação fará parte do nome do produto[N:Abreviação][G:a]
  "Imagem" MEDIUMBLOB DEFAULT NULL,-- Imagem que representa a propriedade[N:Imagem][G:a][I:256x256|propriedade|propriedade.png]
  "DataAtualizacao" DATETIME NOT NULL,-- Data de atualização dos dados ou da imagem da propriedade[N:Data de atualização][G:a]
  CONSTRAINT "GrupoID_Nome_UNIQUE"
    UNIQUE("GrupoID","Nome"),
  CONSTRAINT "FK_Propriedades_Grupos_GrupoID"
    FOREIGN KEY("GrupoID")
    REFERENCES "Grupos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Propriedades.FK_Propriedades_Grupos_GrupoID_idx" ON "Propriedades" ("GrupoID");
CREATE TABLE "Juncoes"(
--   Junções de mesas, informa quais mesas estão juntas ao pedido[N:Junção|Junções][G:a][L:MudarDeMesa][K:MZ\Sale|MZ\Sale\][H:\MZ\Database\Helper]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da junção[G:o]
  "MesaID" INTEGER NOT NULL,-- Mesa que está junta ao pedido[N:Mesa][G:a][S]
  "PedidoID" INTEGER NOT NULL,-- Pedido a qual a mesa está junta, o pedido deve ser de uma mesa[N:Pedido][G:o][S:S]
  "Estado" TEXT NOT NULL CHECK("Estado" IN('Associado', 'Liberado', 'Cancelado')),-- Estado a junção da mesa. Associado: a mesa está junta ao pedido, Liberado: A mesa está livre, Cancelado: A mesa está liberada [N:Estado][G:o]
  "DataMovimento" DATETIME NOT NULL,-- Data e hora da junção das mesas[N:Data do movimento][G:a]
  CONSTRAINT "FK_Juncoes_Mesas_MesaID"
    FOREIGN KEY("MesaID")
    REFERENCES "Mesas"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_Juncoes_Pedidos_PedidoID"
    FOREIGN KEY("PedidoID")
    REFERENCES "Pedidos"("ID")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "Juncoes.FK_Juncoes_Mesas_MesaID_idx" ON "Juncoes" ("MesaID");
CREATE INDEX "Juncoes.FK_Juncoes_Pedidos_PedidoID_idx" ON "Juncoes" ("PedidoID");
CREATE INDEX "Juncoes.MesaEstado_INDEX" ON "Juncoes" ("MesaID","Estado");

PRAGMA foreign_keys = ON;
