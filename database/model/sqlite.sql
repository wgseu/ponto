-- Creator:       MySQL Workbench 8.0.16/ExportSQLite Plugin 0.1.0
-- Author:        Mazin
-- Caption:       GrandChef Model
-- Project:       GrandChef
-- Changed:       2019-11-07 09:40
-- Created:       2012-09-05 23:08
PRAGMA foreign_keys = OFF;

-- Schema: GrandChef
--   Armazena todas as informações do sistema GrandChef, exceto configurações de janelas, conexão e lembrete de sessão
CREATE TABLE "classificacoes"(
--   Classificação se contas, permite atribuir um grupo de contas[N:Classificação|Classificações][G:a][K:App\Models|Models\][H:Model][L:null][ID:15]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da classificação[G:o]
  "classificacao_id" INTEGER DEFAULT NULL,-- Classificação superior, quando informado, esta classificação será uma subclassificação[N:Classificação superior][G:a][S:S]
  "descricao" VARCHAR(100) NOT NULL,-- Descrição da classificação[N:Descrição][G:a][S]
  "icone_url" VARCHAR(100) DEFAULT NULL,-- Ícone da categoria da conta[N:Ícone][G:o][I:256x256|classificacao|classificacao.png]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao"),
  CONSTRAINT "FK_classificacoes_classificacao_id"
    FOREIGN KEY("classificacao_id")
    REFERENCES "classificacoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "classificacoes.FK_classificacoes_classificacao_id_idx" ON "classificacoes" ("classificacao_id");
CREATE TABLE "sistemas"(
--   Classe que informa detalhes da empresa, parceiro e opções do sistema como a versão do banco de dados e a licença de uso[N:Sistema|Sistemas][G:o][K:App\Models|Models\][H:Model][L:null][ID:72]
  "id" TEXT PRIMARY KEY NOT NULL CHECK("id" IN('1')) DEFAULT '1',-- Identificador único do sistema, valor 1[G:o][F:'1']
  "fuso_horario" VARCHAR(100) DEFAULT NULL,-- Informa qual o fuso horário[G:o][N:Fuso horário]
  "opcoes" TEXT DEFAULT NULL-- Opções gerais do sistema[G:a][N:Opções]
);
CREATE TABLE "sessoes"(
--   Sessão de trabalho do dia, permite que vários caixas sejam abertos utilizando uma mesma sessão[N:Sessão|Sessões][G:a][K:App\Models|Models\][H:Model][L:null][ID:70]
  "ID" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da sessão[G:o]
  "data_inicio" DATETIME NOT NULL,-- Data de início da sessão[N:Data de início][G:a]
  "data_termino" DATETIME DEFAULT NULL,-- Data de fechamento da sessão[N:Data de termíno][G:a]
  "aberta" TEXT NOT NULL CHECK("aberta" IN('Y', 'N')) DEFAULT 'Y'-- Informa se a sessão está aberta[N:Aberta][G:a][F:true]
);
CREATE INDEX "sessoes.aberta_INDEX" ON "sessoes" ("aberta" DESC);
CREATE INDEX "sessoes.data_inicio_INDEX" ON "sessoes" ("data_inicio" DESC);
CREATE INDEX "sessoes.data_termino_INDEX" ON "sessoes" ("data_termino" DESC);
CREATE TABLE "servicos"(
--   Taxas, eventos e serviço cobrado nos pedidos[N:Serviço|Serviços][G:o][K:App\Models|Models\][H:Model][L:null][ID:69]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do serviço[G:o]
  "nome" VARCHAR(50) NOT NULL,-- Nome do serviço, Ex.: Comissão, Entrega, Couvert[N:Nome][G:o]
  "descricao" VARCHAR(100) NOT NULL,-- Descrição do serviço, Ex.: Show de fulano[N:Descrição][G:a][S]
  "detalhes" VARCHAR(200) DEFAULT NULL,-- Detalhes do serviço, Ex.: Com participação especial de fulano[N:Detalhes][G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('evento', 'taxa')),-- Tipo de serviço, Evento: Eventos como show no estabelecimento[N:Tipo][G:o]
  "obrigatorio" TEXT NOT NULL CHECK("obrigatorio" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a taxa é obrigatória[N:Obrigatório][G:o][F:true]
  "data_inicio" DATETIME DEFAULT NULL,-- Data de início do evento[N:Data de início][G:a]
  "data_fim" DATETIME DEFAULT NULL,-- Data final do evento[N:Data final][G:a]
  "tempo_limite" INTEGER DEFAULT NULL,-- Tempo de participação máxima que não será obrigatório adicionar o serviço ao pedido[N:Tempo limite][G:o]
  "valor" DECIMAL NOT NULL DEFAULT 0,-- Valor do serviço[N:Valor][G:o][F:0]
  "individual" TEXT NOT NULL CHECK("individual" IN('Y', 'N')) DEFAULT 'N',-- Informa se a taxa ou serviço é individual para cada pessoa[N:Individual][G:o][F:false]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Banner do evento[N:Imagem][G:a][I:512x256|servico|servico.png]
  "ativo" TEXT NOT NULL CHECK("ativo" IN('Y', 'N')) DEFAULT 'Y'-- Informa se o serviço está ativo[N:Ativo][G:o][F:true]
);
CREATE TABLE "moedas"(
--   Moedas financeiras de um país[N:Moeda|Moedas][G:a][K:App\Models|Models\][H:Model][L:null][ID:49]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da moeda[G:o]
  "nome" VARCHAR(45) NOT NULL,-- Nome da moeda[N:Nome][G:o][S]
  "simbolo" VARCHAR(10) NOT NULL,-- Símbolo da moeda, Ex.: R$, $[N:Símbolo][G:o]
  "codigo" VARCHAR(45) NOT NULL,-- Código internacional da moeda, Ex.: USD, BRL[N:Código][G:o]
  "divisao" INTEGER NOT NULL,-- Informa o número fracionário para determinar a quantidade de casas decimais, Ex: 100 para 0,00. 10 para 0,0[N:Divisão][G:a]
  "fracao" VARCHAR(45) DEFAULT NULL,-- Informa o nome da fração, Ex.: Centavo[N:Nome da fração][G:o]
  "formato" VARCHAR(45) NOT NULL,-- Formado de exibição do valor, Ex: $ %s, para $ 3,00[N:Formato][G:o]
  "conversao" DOUBLE DEFAULT NULL,-- Multiplicador para conversão para a moeda principal[G:a][N:Conversão]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data da última atualização do fator de conversão[G:a][N:Data de atualização]
  "Ativa" TEXT NOT NULL CHECK("Ativa" IN('Y', 'N')) DEFAULT 'N',-- Informa se a moeda é recebida pela empresa, a moeda do país mesmo desativada sempre é aceita[G:a][N:Ativa][F:false]
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo")
);
CREATE TABLE "funcoes"(
--   Função ou atribuição de tarefas à um prestador[N:Função|Funções][G:a][K:App\Models|Models\][H:Model][L:null][ID:36]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da função[G:o]
  "descricao" VARCHAR(45) NOT NULL,-- Descreve o nome da função[N:Descrição][G:a][S]
  "remuneracao" DECIMAL NOT NULL,-- Remuneracao pelas atividades exercidas, não está incluso comissões[N:Remuneração][G:a]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao")
);
CREATE TABLE "operacoes"(
--   Código Fiscal de Operações e Prestações (CFOP)[N:Operação|Operações][G:a][K:App\Models|Models\][H:Model][L:7][ID:53]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da operação[G:o]
  "codigo" INTEGER NOT NULL,-- Código CFOP sem pontuação[G:o][N:Código]
  "descricao" VARCHAR(255) NOT NULL,-- Descrição da operação[G:a][N:Descrição]
  "detalhes" TEXT DEFAULT NULL,-- Detalhes da operação (Opcional)[G:o][N:Detalhes]
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo")
);
CREATE TABLE "comandas"(
--   Comanda individual, permite lançar pedidos em cartões de consumo[N:Comanda|Comandas][G:a][K:App\Models|Models\][H:Model][L:2][ID:17]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Número da comanda[N:Número][G:o]
  "numero" INTEGER NOT NULL,-- Número da comanda[G:o][N:Número]
  "nome" VARCHAR(50) NOT NULL,-- Nome da comanda[N:Nome][G:o][S]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a comanda está diponível para ser usada nas vendas[N:Ativa][G:a][F:true]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "numero_UNIQUE"
    UNIQUE("numero")
);
CREATE TABLE "origens"(
--   Origem da mercadoria[N:Origem|Origens][G:a][K:App\Models|Models\][H:Model][L:7][ID:54]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da origem[G:o]
  "codigo" INTEGER NOT NULL,-- Código da origem da mercadoria[G:o][N:Código]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição da origem da mercadoria[G:a][N:Descrição]
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo")
);
CREATE TABLE "integracoes"(
--   Informa quais integrações estão disponíveis[N:Integração|Integrações][G:a][K:App\Models|Models\][H:Model][L:null][ID:41]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da integração[N:ID][G:o]
  "nome" VARCHAR(45) NOT NULL,-- Nome do módulo de integração[G:o][N:Nome]
  "descricao" VARCHAR(200) DEFAULT NULL,-- Descrição do módulo integrador[G:a][N:Descrição]
  "icone_url" VARCHAR(200) DEFAULT NULL,-- Nome do ícone do módulo integrador[G:o][N:Ícone][I:128x128|integracao|integracao.png]
  "login" VARCHAR(200) DEFAULT NULL,-- Login de acesso à API de sincronização[N:Login][G:o]
  "secret" VARCHAR(200) DEFAULT NULL,-- Chave secreta para acesso à API[G:a][N:Chave secreta]
  "opcoes" TEXT DEFAULT NULL,-- Opções da integração, estados e tokens da loja[G:a][N:Opções]
  "associacoes" TEXT DEFAULT NULL,-- Associações de produtos e cartões[G:a][N:Associações]
  "ativo" TEXT NOT NULL CHECK("ativo" IN('Y', 'N')) DEFAULT 'N',-- Informa de o módulo de integração está habilitado[G:o][N:Habilitado][F:false]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização dos dados do módulo de integração[G:a][N:Data de atualização]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome")
);
CREATE TABLE "regimes"(
--   Regimes tributários[N:Regime|Regimes][G:o][K:App\Models|Models\][H:Model][L:7][ID:66]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do regime tributário[G:o]
  "codigo" INTEGER NOT NULL,-- Código do regime tributário[G:o][N:Código]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição do regime tributário[G:a][N:Descrição]
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo")
);
CREATE TABLE "categorias"(
--   Informa qual a categoria dos produtos e permite a rápida localização dos mesmos[N:Categoria|Categorias][G:a][K:App\Models|Models\][H:Model][L:null][ID:12]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da categoria[G:o]
  "categoria_id" INTEGER DEFAULT NULL,-- Informa a categoria pai da categoria atual, a categoria atual é uma subcategoria[N:Categoria superior][G:a][S:S]
  "descricao" VARCHAR(45) NOT NULL,-- Descrição da categoria. Ex.: Refrigerantes, Salgados[N:Descrição][G:a][S]
  "detalhes" VARCHAR(200) DEFAULT NULL,-- Informa os detalhes gerais dos produtos dessa categoria[G:o][N:Detalhes]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Imagem representativa da categoria[N:Imagem][G:a][I:256x256|categoria|categoria.png]
  "ordem" INTEGER NOT NULL DEFAULT 0,-- Informa a ordem de exibição das categorias nas vendas[G:a][N:Ordem][F:0]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização das informações da categoria[N:Data de atualização][G:a]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que a categoria foi arquivada e não será mais usada[G:a][N:Data de arquivação]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao"),
  CONSTRAINT "FK_categorias_categoria_id"
    FOREIGN KEY("categoria_id")
    REFERENCES "categorias"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "categorias.FK_categorias_categoria_id_idx" ON "categorias" ("categoria_id");
CREATE TABLE "paises"(
--   Informações de um páis com sua moeda e língua nativa[N:País|Paises][G:o][K:App\Models|Models\][H:Model][L:null][ID:57]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do país[G:o]
  "nome" VARCHAR(100) NOT NULL,-- Nome do país[N:Nome][G:o][S]
  "sigla" VARCHAR(10) NOT NULL,-- Abreviação do nome do país[N:Sigla][G:a]
  "codigo" VARCHAR(10) NOT NULL,-- Código do país com 2 letras[G:o][N:Código]
  "moeda_id" INTEGER NOT NULL,-- Informa a moeda principal do país[N:Moeda][G:a]
  "idioma" VARCHAR(10) NOT NULL,-- Idioma nativo do país[N:Código do idioma][G:o]
  "prefixo" VARCHAR(45) DEFAULT NULL,-- Prefixo de telefone para ligações internacionais[G:o][N:Prefixo]
  "entradas" TEXT DEFAULT NULL,-- Frases, nomes de campos e máscaras específicas do país[N:Entrada][G:a]
  "unitario" TEXT NOT NULL CHECK("unitario" IN('Y', 'N')) DEFAULT 'N',-- Informa se o país tem apenas um estado federativo[N:Unitário][G:o][F:false]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "sigla_UNIQUE"
    UNIQUE("sigla"),
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo"),
  CONSTRAINT "FK_paises_moeda_id"
    FOREIGN KEY("moeda_id")
    REFERENCES "moedas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "paises.FK_paises_moeda_id_idx" ON "paises" ("moeda_id");
CREATE TABLE "impostos"(
--   Impostos disponíveis para informar no produto[N:Imposto|Impostos][G:o][K:App\Models|Models\][H:Model][L:7][ID:39]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do imposto[G:o]
  "grupo" TEXT NOT NULL CHECK("grupo" IN('icms', 'pis', 'cofins', 'ipi', 'ii')),-- Grupo do imposto[G:o][N:Grupo]
  "simples" TEXT NOT NULL CHECK("simples" IN('Y', 'N')),-- Informa se o imposto é do simples nacional[G:o][N:Simples nacional]
  "substituicao" TEXT NOT NULL CHECK("substituicao" IN('Y', 'N')),-- Informa se o imposto é por substituição tributária[G:a][N:Substituição tributária]
  "codigo" INTEGER NOT NULL,-- Informa o código do imposto[G:o][N:Código]
  "descricao" VARCHAR(255) NOT NULL,-- Descrição do imposto[G:a][N:Descrição]
  CONSTRAINT "grupo_simples_substituicao_codigo_UNIQUE"
    UNIQUE("grupo","simples","substituicao","codigo")
);
CREATE TABLE "cozinhas"(
--   Categoria de comida servida pelo estabelecimento[G:a][N:Cozinha|Cozinhas][K:App\Models|Models\][H:Model][L:null][ID:22]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da cozinha[G:o]
  "nome" VARCHAR(50) NOT NULL,-- Nome da cozinha, Ex. Japonesa, Brasileira, Italiana, Pizza[G:o][N:Nome]
  "descricao" VARCHAR(255) DEFAULT NULL,-- Descrição da cozinha, o que é servido[G:a][N:Descrição]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome")
);
CREATE TABLE "unidades"(
--   Unidades de medidas aplicadas aos produtos[N:Unidade|Unidades][G:a][K:App\Models|Models\][H:Model][L:null][ID:75]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da unidade[G:o]
  "nome" VARCHAR(45) NOT NULL,-- Nome da unidade de medida, Ex.: Grama, Quilo[N:Nome][G:o][S]
  "descricao" VARCHAR(45) DEFAULT NULL,-- Detalhes sobre a unidade de medida[N:Descrição][G:a]
  "sigla" VARCHAR(10) NOT NULL,-- Sigla da unidade de medida, Ex.: UN, L, g[N:Sigla][G:a]
  CONSTRAINT "sigla_UNIQUE"
    UNIQUE("sigla")
);
CREATE TABLE "modulos"(
--   Módulos do sistema que podem ser desativados/ativados[N:Módulo|Módulos][G:o][K:App\Models|Models\][H:Model][L:null][ID:48]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do módulo[G:o]
  "nome" VARCHAR(50) NOT NULL,-- Nome do módulo, unico em todo o sistema[N:Nome][G:o][S]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição do módulo, informa detalhes sobre a funcionalidade do módulo no sistema[N:Descrição][G:a]
  "habilitado" TEXT NOT NULL CHECK("habilitado" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o módulo do sistema está habilitado[N:Habilitado][G:o][F:true]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome")
);
CREATE TABLE "metricas"(
--   Métricas de avaliação do atendimento e outros serviços do estabelecimento[N:Métrica|Métricas][G:a][K:App\Models|Models\][H:Model][L:null][ID:47]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da métrica[G:o]
  "nome" VARCHAR(100) NOT NULL,-- Nome da métrica[G:o][N:Nome]
  "descricao" VARCHAR(200) DEFAULT NULL,-- Descreve o que deve ser avaliado pelo cliente[G:a][N:Descrição]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('entrega', 'atendimento', 'producao', 'apresentacao')),-- Tipo de métrica que pode ser velocidade de entrega, quantidade no atendimento, sabor da comida e apresentação do prato[G:o][N:Tipo de métrica][E:Entrega|Atendimento|Produção|Apresentação]
  "quantidade" INTEGER NOT NULL,-- Quantidade das últimas avaliações para reavaliação da métrica[G:o][N:Quantidade][F:100]
  "avaliacao" DOUBLE DEFAULT NULL,-- Média das avaliações para o período informado[G:a][N:Avaliação]
  "data_processamento" DATETIME DEFAULT NULL,-- Data do último processamento da avaliação[G:a][N:Data de processamento]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que essa métrica foi arquivada[G:a][N:Data de arquivamento]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome")
);
CREATE TABLE "setores"(
--   Setor de impressão e de estoque[N:Setor|Setores][G:o][K:App\Models|Models\][H:Model][L:null][ID:71]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do setor[G:o]
  "setor_id" INTEGER DEFAULT NULL,-- Informa o setor que abrange esse subsetor[G:o][N:Setor superior]
  "nome" VARCHAR(50) NOT NULL,-- Nome do setor, único em todo o sistema[N:Nome][G:o][S]
  "descricao" VARCHAR(70) DEFAULT NULL,-- Descreve a utilização do setor[N:Descrição][G:a]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "FK_setores_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "setores.FK_setores_setor_id_idx" ON "setores" ("setor_id");
CREATE TABLE "bancos"(
--   Bancos disponíveis no país[N:Banco|Bancos][G:o][K:App\Models|Models\][H:Model][L:null][ID:6]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do banco[G:o]
  "numero" VARCHAR(40) NOT NULL,-- Número do banco[N:Número][G:o]
  "fantasia" VARCHAR(200) NOT NULL,-- Nome fantasia do banco[G:o][N:Fantasia][S]
  "razao_social" VARCHAR(200) NOT NULL,-- Razão social do banco[N:Razão social][G:a][S]
  "agencia_mascara" VARCHAR(45) DEFAULT NULL,-- Mascara para formatação do número da agência[N:Máscara da agência][G:a]
  "conta_mascara" VARCHAR(45) DEFAULT NULL,-- Máscara para formatação do número da conta[N:Máscara da conta][G:a]
  CONSTRAINT "razao_social_UNIQUE"
    UNIQUE("razao_social"),
  CONSTRAINT "numero_UNIQUE"
    UNIQUE("numero"),
  CONSTRAINT "fantasia_UNIQUE"
    UNIQUE("fantasia")
);
CREATE TABLE "clientes"(
--   Informações de cliente físico ou jurídico. Clientes, empresas, funcionários, fornecedores e parceiros são cadastrados aqui[N:Cliente|Clientes][G:o][K:App\Models|Models\][H:Model][L:null][ID:16]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cliente[G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('fisica', 'juridica')) DEFAULT 'fisica',-- Informa o tipo de pessoa, que pode ser física ou jurídica[N:Tipo][G:o][E:Física|Jurídica][S:S][F:self::TIPO_FISICA]
  "empresa_id" INTEGER DEFAULT NULL,-- Informa se esse cliente faz parte da empresa informada[N:Empresa][G:o][S:S]
  "login" VARCHAR(50),-- Nome de usuário utilizado para entrar no sistema, aplicativo ou site[N:Login][G:o]
  "senha" VARCHAR(255) DEFAULT NULL,-- Senha embaralhada do cliente[N:Senha][G:a][P]
  "nome" VARCHAR(100) NOT NULL,-- Primeiro nome da pessoa física ou nome fantasia da empresa[N:Nome][G:o][S]
  "sobrenome" VARCHAR(100) DEFAULT NULL,-- Restante do nome da pessoa física ou Razão social da empresa[N:Sobrenome][G:o]
  "genero" TEXT CHECK("genero" IN('masculino', 'feminino')) DEFAULT NULL,-- Informa o gênero do cliente do tipo pessoa física[N:Gênero][G:o][S:S][R]
  "cpf" VARCHAR(20) DEFAULT NULL,-- Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa Jurídica(CNPJ)[M:999.999.999-99][N:CPF][G:o]
  "rg" VARCHAR(20) DEFAULT NULL,-- Registro Geral(RG) ou Inscrição Estadual (IE)[N:Registro Geral][G:o]
  "im" VARCHAR(20) DEFAULT NULL,-- Inscrição municipal da empresa[N:Inscrição municipal][G:a]
  "email" VARCHAR(100) DEFAULT NULL,-- E-mail do cliente ou da empresa[N:E-mail][G:o]
  "data_nascimento" DATE DEFAULT NULL,-- Data de aniversário ou data de fundação[N:Data de aniversário][G:a]
  "slogan" VARCHAR(100) DEFAULT NULL,-- Slogan ou detalhes do cliente[N:Observação][G:a]
  "status" TEXT NOT NULL CHECK("status" IN('inativo', 'ativo', 'bloqueado')) DEFAULT 'inativo',-- Informa o estado da conta do cliente[G:o][N:Status][F:self::STATUS_INATIVO]
  "secreto" VARCHAR(40) DEFAULT NULL,-- Código secreto para recuperar a conta do cliente[N:Código de recuperação][G:o][D]
  "limite_compra" DECIMAL DEFAULT NULL,-- Limite de compra utilizando a forma de pagamento Conta[N:Limite de compra][G:o]
  "instagram" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do Instagram do cliente[N:Instagram][G:o]
  "facebook_url" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do Facebook do cliente[N:Facebook][G:o]
  "twitter" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do Twitter do cliente[N:Twitter][G:o]
  "linkedin_url" VARCHAR(200) DEFAULT NULL,-- URL para acessar a página do LinkedIn do cliente[N:LinkedIn][G:o]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Foto do cliente ou logo da empresa[I:256x256|cliente|cliente.png][N:Foto][G:a]
  "linguagem" VARCHAR(20) DEFAULT NULL,-- Código da linguagem utilizada pelo cliente para visualizar o aplicativo e o site, Ex: pt-BR[N:Linguagem][G:a]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização das informações do cliente[N:Data de atualização][G:a][D]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro do cliente[N:Data de cadastro][G:a][D]
  CONSTRAINT "email_UNIQUE"
    UNIQUE("email"),
  CONSTRAINT "cpf_UNIQUE"
    UNIQUE("cpf"),
  CONSTRAINT "login_UNIQUE"
    UNIQUE("login"),
  CONSTRAINT "secreto_UNIQUE"
    UNIQUE("secreto"),
  CONSTRAINT "FK_clientes_empresa_id"
    FOREIGN KEY("empresa_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "clientes.FK_clientes_empresa_id_idx" ON "clientes" ("empresa_id");
CREATE INDEX "clientes.nome_INDEX" ON "clientes" ("nome");
CREATE TABLE "telefones"(
--   Telefones dos clientes, apenas o telefone principal deve ser único por cliente[N:Telefone|Telefones][G:o][K:App\Models|Models\][H:Model][L:null][ID:73]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do telefone[G:o]
  "cliente_id" INTEGER NOT NULL,-- Informa o cliente que possui esse número de telefone[G:o][N:Cliente]
  "pais_id" INTEGER NOT NULL,-- Informa o país desse número de telefone[G:o][N:País]
  "numero" VARCHAR(12) NOT NULL,-- Número de telefone com DDD[M:(99) 9999-9999?9][N:Número][G:o]
  "operadora" VARCHAR(45) DEFAULT NULL,-- Informa qual a operadora desse telefone[G:a][N:Operadora]
  "servico" VARCHAR(45) DEFAULT NULL,-- Informa qual serviço está associado à esse número, Ex: WhatsApp[G:o][N:Serviço]
  "principal" TEXT NOT NULL CHECK("principal" IN('Y', 'N')) DEFAULT 'N',-- Informa se o telefone é principal e exclusivo do cliente[G:o][N:Principal][F:false]
  CONSTRAINT "FK_telefones_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_telefones_pais_id"
    FOREIGN KEY("pais_id")
    REFERENCES "paises"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "telefones.FK_telefones_cliente_id_idx" ON "telefones" ("cliente_id");
CREATE INDEX "telefones.numero_INDEX" ON "telefones" ("numero");
CREATE INDEX "telefones.FK_telefones_pais_id_idx" ON "telefones" ("pais_id");
CREATE TABLE "funcionalidades"(
--   Grupo de funcionalidades do sistema[N:Funcionalidade|Funcionalidades][G:a][K:App\Models|Models\][H:Model][L:null][ID:35]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da funcionalidade[G:o]
  "modulo_id" INTEGER DEFAULT NULL,-- Módulo que essa funcionalidade pertence[G:o][N:Módulo]
  "funcionalidade_id" INTEGER DEFAULT NULL,-- Funcionalidade principal[G:a][N:Funcionalidade principal]
  "nome" VARCHAR(64) NOT NULL,-- Nome da funcionalidade, único em todo o sistema[N:Nome][G:o]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição da funcionalidade[N:Descrição][G:a][S]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "FK_funcionalidades_funcionalidade_id"
    FOREIGN KEY("funcionalidade_id")
    REFERENCES "funcionalidades"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_funcionalidades_modulo_id"
    FOREIGN KEY("modulo_id")
    REFERENCES "modulos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "funcionalidades.FK_funcionalidades_funcionalidade_id_idx" ON "funcionalidades" ("funcionalidade_id");
CREATE INDEX "funcionalidades.FK_funcionalidades_modulo_id_idx" ON "funcionalidades" ("modulo_id");
CREATE TABLE "prestadores"(
--   Prestador de serviço que realiza alguma tarefa na empresa[N:Prestador|Prestadores][G:o][K:App\Models|Models\][H:Model][L:null][ID:62]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do prestador[G:o]
  "codigo" VARCHAR(100) NOT NULL,-- Código do prestador, podendo ser de barras[N:Código][G:o]
  "pin" VARCHAR(200) DEFAULT NULL,-- Código pin para acesso rápido[G:o][N:Pin]
  "funcao_id" INTEGER NOT NULL,-- Função do prestada na empresa[N:Função][G:a][S:S]
  "cliente_id" INTEGER NOT NULL,-- Cliente que representa esse prestador, único no cadastro de prestadores[N:Cliente][G:o][S]
  "empresa_id" INTEGER DEFAULT NULL,-- Informa a empresa que gerencia os colaboradores, nulo para a empresa do próprio estabelecimento[G:o][N:Empresa]
  "vinculo" TEXT NOT NULL CHECK("vinculo" IN('funcionario', 'prestador', 'autonomo')) DEFAULT 'funcionario',-- Vínculo empregatício com a empresa, funcionário e autônomo são pessoas físicas, prestador é pessoa jurídica[G:o][N:Vínculo][E:Funcionário|Prestador|Autônomo][F:self::VINCULO_FUNCIONARIO]
  "porcentagem" DOUBLE NOT NULL DEFAULT 0,-- Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.: Comissão de 10% [N:Comissão][G:a][F:0]
  "pontuacao" INTEGER NOT NULL DEFAULT 0,-- Define a distribuição da porcentagem pela parcela de pontos[N:Pontuação][G:a][F:0]
  "remuneracao" DECIMAL NOT NULL DEFAULT 0,-- Remuneracao pelas atividades exercidas, não está incluso comissões[N:Remuneração][G:a][F:0]
  "data_termino" DATETIME DEFAULT NULL,-- Data de término de contrato, informado apenas quando ativo for não[N:Data de término de contrato][G:a][D]
  "data_cadastro" DATETIME NOT NULL,-- Data em que o prestador de serviços foi cadastrado no sistema[N:Data de cadastro][G:a][D]
  CONSTRAINT "cliente_id_UNIQUE"
    UNIQUE("cliente_id"),
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo"),
  CONSTRAINT "FK_prestadores_funcao_id"
    FOREIGN KEY("funcao_id")
    REFERENCES "funcoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_prestadores_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_prestadores_prestador_id"
    FOREIGN KEY("empresa_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "prestadores.FK_prestadores_funcao_id_idx" ON "prestadores" ("funcao_id");
CREATE INDEX "prestadores.FK_prestadores_prestador_id_idx" ON "prestadores" ("empresa_id");
CREATE TABLE "emitentes"(
--   Dados do emitente das notas fiscais[N:Emitente|Emitentes][G:o][K:App\Models|Models\][H:Model][L:7][ID:26]
  "id" TEXT PRIMARY KEY NOT NULL CHECK("id" IN('1')) DEFAULT '1',-- Identificador do emitente, sempre 1[G:o][F:'1']
  "contador_id" INTEGER DEFAULT NULL,-- Contador responsável pela contabilidade da empresa[N:Contador][G:o][S:S]
  "regime_id" INTEGER NOT NULL,-- Regime tributário da empresa[N:Regime tributário][G:o]
  "ambiente" TEXT NOT NULL CHECK("ambiente" IN('homologacao', 'producao')) DEFAULT 'homologacao',-- Ambiente de emissão das notas[N:Ambiente][G:o][E:Homologação|Produção][F:self::AMBIENTE_HOMOLOGACAO]
  "csc_teste" VARCHAR(100) DEFAULT NULL,-- Código de segurança do contribuinte[G:o]
  "csc" VARCHAR(100) DEFAULT NULL,-- Código de segurança do contribuinte[G:o]
  "token_teste" VARCHAR(10) DEFAULT NULL,-- Token do código de segurança do contribuinte[N:Token][G:o]
  "token" VARCHAR(10) DEFAULT NULL,-- Token do código de segurança do contribuinte[N:Token][G:o]
  "ibpt" VARCHAR(100) DEFAULT NULL,-- Token da API do IBPT[N:Token IBPT][G:o]
  "data_expiracao" DATETIME DEFAULT NULL,-- Data de expiração do certificado[N:Data de expiração][G:a]
  CONSTRAINT "FK_emitentes_contador_id"
    FOREIGN KEY("contador_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_emitentes_regime_id"
    FOREIGN KEY("regime_id")
    REFERENCES "regimes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "emitentes.FK_emitentes_contador_id_idx" ON "emitentes" ("contador_id");
CREATE INDEX "emitentes.FK_emitentes_regime_id_idx" ON "emitentes" ("regime_id");
CREATE TABLE "fornecedores"(
--   Fornecedores de produtos[N:Fornecedor|Fornecedores][G:o][K:App\Models|Models\][H:Model][L:null][ID:34]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do fornecedor[G:o]
  "empresa_id" INTEGER NOT NULL,-- Empresa do fornecedor[N:Empresa][G:a][S]
  "prazo_pagamento" INTEGER NOT NULL DEFAULT 0,-- Prazo em dias para pagamento do fornecedor[N:Prazo de pagamento][G:o][F:0]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro do fornecedor[N:Data de cadastro][G:a]
  CONSTRAINT "empresa_id_UNIQUE"
    UNIQUE("empresa_id"),
  CONSTRAINT "FK_fornecedores_empresa_id"
    FOREIGN KEY("empresa_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE TABLE "creditos"(
--   Créditos de clientes[N:Crédito|Créditos][G:o][K:App\Models|Models\][H:Model][L:null][ID:23]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do crédito[G:o]
  "cliente_id" INTEGER NOT NULL,-- Cliente a qual o crédito pertence[N:Cliente][G:o][S:S]
  "valor" DECIMAL NOT NULL,-- Valor do crédito[N:Valor][G:o]
  "detalhes" VARCHAR(255) NOT NULL,-- Detalhes do crédito, justificativa do crédito[N:Detalhes][G:o][S]
  "cancelado" TEXT NOT NULL CHECK("cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o crédito foi cancelado[N:Cancelado][G:o][F:false]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro do crédito[N:Data de cadastro][G:a]
  CONSTRAINT "FK_creditos_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "creditos.FK_creditos_cliente_id_idx" ON "creditos" ("cliente_id");
CREATE TABLE "permissoes"(
--   Informa a listagem de todas as funções do sistema [N:Permissão|Permissões][G:a][K:App\Models|Models\][H:Model][L:null][ID:60]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da permissão[G:o]
  "funcionalidade_id" INTEGER NOT NULL,-- Categoriza um grupo de permissões[N:Funcionalidade][G:a][S:S]
  "nome" VARCHAR(45) NOT NULL,-- Nome da permissão, único no sistema[N:Nome][G:a]
  "descricao" VARCHAR(100) NOT NULL,-- Descreve a permissão[N:Descrição][G:a][S]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "FK_permissoes_funcionalidade_id"
    FOREIGN KEY("funcionalidade_id")
    REFERENCES "funcionalidades"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "permissoes.FK_permissoes_funcionalidade_id_idx" ON "permissoes" ("funcionalidade_id");
CREATE TABLE "auditorias"(
--   Registra todas as atividades importantes do sistema[N:Auditoria|Auditorias][G:a][K:App\Models|Models\][H:Model][L:null][ID:3]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da auditoria[G:o]
  "permissao_id" INTEGER DEFAULT NULL,-- Informa a permissão concedida ou utilizada que permitiu a realização da operação[G:a][N:Permissão]
  "prestador_id" INTEGER NOT NULL,-- Prestador que exerceu a atividade[N:Prestador][G:o][S:S]
  "autorizador_id" INTEGER NOT NULL,-- Prestador que autorizou o acesso ao recurso descrito[N:Autorizador][G:o][S:S]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('financeiro', 'administrativo', 'operacional')),-- Tipo de atividade exercida[N:Tipo][G:o][S:S]
  "prioridade" TEXT NOT NULL CHECK("prioridade" IN('baixa', 'media', 'alta')),-- Prioridade de acesso do recurso[N:Prioridade][G:a][E:Baixa|Média|Alta][S:S]
  "descricao" VARCHAR(255) NOT NULL,-- Descrição da atividade exercida[N:Descrição][G:a][S]
  "autorizacao" VARCHAR(255) DEFAULT NULL,-- Código de autorização necessário para permitir realizar a função descrita[G:a][N:Autorização]
  "data_registro" DATETIME NOT NULL,-- Data e hora do ocorrido[N:Data e hora][G:a]
  CONSTRAINT "FK_auditorias_prestador_id"
    FOREIGN KEY("prestador_id")
    REFERENCES "prestadores"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_auditorias_autorizador_id"
    FOREIGN KEY("autorizador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_auditorias_permissao_id"
    FOREIGN KEY("permissao_id")
    REFERENCES "permissoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "auditorias.FK_auditorias_prestador_id_idx" ON "auditorias" ("prestador_id");
CREATE INDEX "auditorias.data_registro_INDEX" ON "auditorias" ("data_registro" DESC);
CREATE INDEX "auditorias.FK_auditorias_autorizador_id_idx" ON "auditorias" ("autorizador_id");
CREATE INDEX "auditorias.FK_auditorias_permissao_id_idx" ON "auditorias" ("permissao_id");
CREATE TABLE "tributacoes"(
--   Informação tributária dos produtos[N:Tributação|Tributações][G:a][K:App\Models|Models\][H:Model][L:7][ID:74]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da tributação[G:o]
  "ncm" VARCHAR(10) NOT NULL,-- Código NCM (Nomenclatura Comum do Mercosul) do produto[G:o][N:NCM]
  "cest" VARCHAR(20) DEFAULT NULL,-- Código CEST do produto (Opcional)[G:o][N:CEST]
  "origem_id" INTEGER NOT NULL,-- Origem do produto[G:a][N:Origem]
  "operacao_id" INTEGER NOT NULL,-- CFOP do produto[G:o][N:CFOP]
  "imposto_id" INTEGER NOT NULL,-- Imposto do produto[G:o][N:Imposto]
  CONSTRAINT "FK_tributacoes_origem_id"
    FOREIGN KEY("origem_id")
    REFERENCES "origens"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_tributacoes_operacao_id"
    FOREIGN KEY("operacao_id")
    REFERENCES "operacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_tributacoes_imposto_id"
    FOREIGN KEY("imposto_id")
    REFERENCES "impostos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "tributacoes.FK_tributacoes_origem_id_idx" ON "tributacoes" ("origem_id");
CREATE INDEX "tributacoes.FK_tributacoes_operacao_id_idx" ON "tributacoes" ("operacao_id");
CREATE INDEX "tributacoes.FK_tributacoes_imposto_id_idx" ON "tributacoes" ("imposto_id");
CREATE TABLE "horarios"(
--   Informa o horário de funcionamento do estabelecimento[N:Horário|Horários][G:o][K:App\Models|Models\][H:Model][L:null][ID:38]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do horário[G:o]
  "modo" TEXT NOT NULL CHECK("modo" IN('funcionamento', 'operacao', 'entrega')) DEFAULT 'funcionamento',-- Modo de trabalho disponível nesse horário, Funcionamento: horário em que o estabelecimento estará aberto, Operação: quando aceitar novos pedidos locais, Entrega: quando aceita ainda pedidos para entrega[G:o][N:Modo][F:self::MODO_FUNCIONAMENTO][E:Funcionamento|Operação|Entrega]
  "funcao_id" INTEGER DEFAULT NULL,-- Permite informar o horário de acesso ao sistema para realizar essa função[G:a][N:Função]
  "prestador_id" INTEGER DEFAULT NULL,-- Permite informar o horário de prestação de serviço para esse prestador[G:o][N:Prestador]
  "inicio" INTEGER NOT NULL,-- Início do horário de funcionamento em minutos contando a partir de domingo até sábado[N:Início][G:o]
  "fim" INTEGER NOT NULL,-- Horário final de funcionamento do estabelecimento contando em minutos a partir de domingo[N:Fim][G:o]
  "mensagem" VARCHAR(200) DEFAULT NULL,-- Mensagem que será mostrada quando o estabelecimento estiver fechado por algum motivo[G:o][N:Mensagem]
  "entrega_minima" INTEGER DEFAULT NULL,-- Tempo mínimo que leva para entregar nesse horário[G:o][N:Tempo de entrega mínimo]
  "entrega_maxima" INTEGER NOT NULL DEFAULT 0,-- Tempo máximo que leva para entregar nesse horário[G:o][N:Tempo de entrega máximo][F:0]
  "fechado" TEXT NOT NULL CHECK("fechado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o estabelecimento estará fechado nesse horário programado, o início e fim será tempo no formato unix, quando verdadeiro tem prioridade sobre todos os horários[G:o][N:Fechado][F:false]
  CONSTRAINT "FK_horarios_prestador_id"
    FOREIGN KEY("prestador_id")
    REFERENCES "prestadores"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_horarios_funcao_id"
    FOREIGN KEY("funcao_id")
    REFERENCES "funcoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "horarios.FK_horarios_prestador_id_idx" ON "horarios" ("prestador_id");
CREATE INDEX "horarios.FK_horarios_funcao_id_idx" ON "horarios" ("funcao_id");
CREATE TABLE "produtos"(
--   Informações sobre o produto, composição ou pacote[N:Produto|Produtos][G:o][K:App\Models|Models\][H:Model][L:null][ID:63]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código do produto[G:o]
  "codigo" VARCHAR(100) NOT NULL,-- Código do produto podendo ser de barras ou aleatório, deve ser único entre todos os produtos[N:Código][G:o]
  "categoria_id" INTEGER NOT NULL,-- Categoria do produto, permite a rápida localização ao utilizar tablets[N:Categoria][G:a][S:S]
  "unidade_id" INTEGER NOT NULL,-- Informa a unidade do produtos, Ex.: Grama, Litro.[N:Unidade][G:a]
  "setor_estoque_id" INTEGER DEFAULT NULL,-- Informa de qual setor o produto será retirado após a venda[N:Setor de estoque][G:o]
  "setor_preparo_id" INTEGER DEFAULT NULL,-- Informa em qual setor de preparo será enviado o ticket de preparo ou autorização, se nenhum for informado nada será impresso[N:Setor de preparo][G:o]
  "tributacao_id" INTEGER DEFAULT NULL,-- Informações de tributação do produto[G:a][N:Tributação][S:S]
  "descricao" VARCHAR(75) NOT NULL,-- Descrição do produto, Ex.: Refri. Coca Cola 2L.[N:Descrição][G:a][S]
  "abreviacao" VARCHAR(100) DEFAULT NULL,-- Nome abreviado do produto, Ex.: Cebola, Tomate, Queijo[N:Abreviação][G:a]
  "detalhes" VARCHAR(255) DEFAULT NULL,-- Informa detalhes do produto, Ex: Com Cebola, Pimenta, Orégano[N:Detalhes][G:o]
  "quantidade_minima" DOUBLE NOT NULL DEFAULT 0,-- Informa a quantidade limite para que o sistema avise que o produto já está acabando[N:Quantidade limite][G:a][F:0]
  "quantidade_maxima" DOUBLE NOT NULL DEFAULT 0,-- Informa a quantidade máxima do produto no estoque, não proibe, apenas avisa[N:Quantidade máxima][G:a][F:0]
  "preco_venda" DECIMAL NOT NULL DEFAULT 0,-- Preço de venda base desse produto para todos os cardápios[N:Preço de venda][G:o][F:0]
  "custo_medio" DECIMAL DEFAULT NULL,-- Informa o preço médio de compra desse produto[G:o][N:Custo médio]
  "custo_producao" DECIMAL DEFAULT NULL,-- Informa qual o valor para o custo de produção do produto, utilizado quando não há formação de composição do produto[N:Custo de produção][G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('produto', 'composicao', 'pacote')) DEFAULT 'produto',-- Informa qual é o tipo de produto. Produto: Produto normal que possui estoque, Composição: Produto que não possui estoque diretamente, pois é composto de outros produtos ou composições, Pacote: Permite a composição no momento da venda, não possui estoque diretamente[N:Tipo][G:o][E:Produto|Composição|Pacote][F:self::TIPO_PRODUTO]
  "cobrar_servico" TEXT NOT NULL CHECK("cobrar_servico" IN('Y', 'N')) DEFAULT 'Y',-- Informa se deve ser cobrado a taxa de serviço dos garçons sobre este produto[N:Cobrança de serviço][G:a][F:true]
  "divisivel" TEXT NOT NULL CHECK("divisivel" IN('Y', 'N')) DEFAULT 'N',-- Informa se o produto pode ser vendido fracionado[N:Divisível][G:o][F:false]
  "pesavel" TEXT NOT NULL CHECK("pesavel" IN('Y', 'N')) DEFAULT 'N',-- Informa se o peso do produto deve ser obtido de uma balança, obrigatoriamente o produto deve ser divisível[N:Pesável][G:o][F:false]
  "tempo_preparo" INTEGER NOT NULL DEFAULT 0,-- Tempo de preparo em minutos para preparar uma composição, 0 para não informado[N:Tempo de preparo][G:o][F:0]
  "disponivel" TEXT NOT NULL CHECK("disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o produto estará disponível para venda em todos os cardápios[N:Disponível][G:a][F:true]
  "insumo" TEXT NOT NULL CHECK("insumo" IN('Y', 'N')) DEFAULT 'N',-- Informa se o produto é de uso interno e não está disponível para venda[N:Insumo][G:o][F:false]
  "avaliacao" DOUBLE DEFAULT NULL,-- Média das avaliações do último período[G:a][N:Avaliação]
  "estoque" DOUBLE DEFAULT 0,-- Estoque geral do produto[G:o][N:Estoque][F:0]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Imagem do produto[N:Imagem][G:a][I:256x256|produto|produto.png]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização das informações do produto[N:Data de atualização][G:a][D]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que o produto foi arquivado e não será mais usado[G:a][N:Data de arquivação]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao"),
  CONSTRAINT "codigo_UNIQUE"
    UNIQUE("codigo"),
  CONSTRAINT "FK_produtos_categoria_id"
    FOREIGN KEY("categoria_id")
    REFERENCES "categorias"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_produtos_unidade_id"
    FOREIGN KEY("unidade_id")
    REFERENCES "unidades"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_produtos_setor_preparo_id"
    FOREIGN KEY("setor_preparo_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_produtos_setor_estoque_id"
    FOREIGN KEY("setor_estoque_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_produtos_tributacao_id"
    FOREIGN KEY("tributacao_id")
    REFERENCES "tributacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "produtos.FK_produtos_categoria_id_idx" ON "produtos" ("categoria_id");
CREATE INDEX "produtos.FK_produtos_unidade_id_idx" ON "produtos" ("unidade_id");
CREATE INDEX "produtos.FK_produtos_setor_preparo_id_idx" ON "produtos" ("setor_preparo_id");
CREATE INDEX "produtos.FK_produtos_setor_estoque_id_idx" ON "produtos" ("setor_estoque_id");
CREATE INDEX "produtos.FK_produtos_tributacao_id_idx" ON "produtos" ("tributacao_id");
CREATE TABLE "cheques"(
--   Folha de cheque lançado como pagamento[N:Cheque|Cheques][G:o][K:App\Models|Models\][H:Model][L:null][ID:13]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da folha de cheque[G:o]
  "cliente_id" INTEGER NOT NULL,-- Cliente que emitiu o cheque[N:Cliente][G:o][S:S]
  "banco_id" INTEGER NOT NULL,-- Banco do cheque[N:Banco][G:o][S]
  "agencia" VARCHAR(45) NOT NULL,-- Número da agência[N:Agência][G:a]
  "conta" VARCHAR(45) NOT NULL,-- Número da conta do banco descrito no cheque[N:Conta][G:a]
  "numero" VARCHAR(20) NOT NULL,-- Número da folha do cheque[N:Número][G:o][S]
  "valor" DECIMAL NOT NULL,-- Valor na folha do cheque[N:Valor][G:o]
  "vencimento" DATETIME NOT NULL,-- Data de vencimento do cheque[N:Vencimento][G:o]
  "cancelado" TEXT NOT NULL CHECK("cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o cheque e todas as suas folhas estão cancelados[N:Cancelado][G:o][F:false]
  "recolhimento" DATETIME DEFAULT NULL,-- Data de recolhimento do cheque[N:Data de recolhimento][G:a]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro do cheque[N:Data de cadastro][G:a][D]
  CONSTRAINT "FK_cheques_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cheques_banco_id"
    FOREIGN KEY("banco_id")
    REFERENCES "bancos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "cheques.vencimento_INDEX" ON "cheques" ("vencimento");
CREATE INDEX "cheques.FK_cheques_cliente_id_idx" ON "cheques" ("cliente_id");
CREATE INDEX "cheques.FK_cheques_banco_id_idx" ON "cheques" ("banco_id");
CREATE INDEX "cheques.recolhimento_INDEX" ON "cheques" ("recolhimento");
CREATE TABLE "patrimonios"(
--   Informa detalhadamente um bem da empresa[N:Patrimônio|Patrimônios][G:o][K:App\Models|Models\][H:Model][L:null][ID:58]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do bem[G:o]
  "empresa_id" INTEGER NOT NULL,-- Empresa a que esse bem pertence[N:Empresa][G:a][S:S]
  "fornecedor_id" INTEGER DEFAULT NULL,-- Fornecedor do bem[N:Fornecedor][G:o][S:S]
  "numero" VARCHAR(45) NOT NULL,-- Número que identifica o bem[N:Número][G:o]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição ou nome do bem[N:Descrição][G:a][S]
  "quantidade" DOUBLE NOT NULL,-- Quantidade do bem com as mesmas características[N:Quantidade][G:a]
  "altura" DOUBLE NOT NULL DEFAULT 0,-- Altura do bem em metros[N:Altura][G:a][F:0]
  "largura" DOUBLE NOT NULL DEFAULT 0,-- Largura do bem em metros[N:Largura][G:a][F:0]
  "comprimento" DOUBLE NOT NULL DEFAULT 0,-- Comprimento do bem em metros[N:Comprimento][G:o][F:0]
  "estado" TEXT NOT NULL CHECK("estado" IN('novo', 'conservado', 'ruim')) DEFAULT 'novo',-- Estado de conservação do bem[N:Estado][G:o][F:self::ESTADO_NOVO]
  "custo" DECIMAL NOT NULL DEFAULT 0,-- Valor de custo do bem[N:Custo][G:o][F:0]
  "valor" DECIMAL NOT NULL DEFAULT 0,-- Valor que o bem vale atualmente[N:Valor][G:o][F:0]
  "ativo" TEXT NOT NULL CHECK("ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o bem está ativo e em uso[N:Ativo][G:o][F:true]
  "imagem_url" VARCHAR(200) DEFAULT NULL,-- Caminho relativo da foto do bem[N:Foto do bem][G:a][I:512x512|patrimonio|patrimonio.png]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização das informações do bem[N:Data de atualização][G:a][D]
  CONSTRAINT "numero_UNIQUE"
    UNIQUE("numero"),
  CONSTRAINT "FK_patrimonios_fornecedor_id"
    FOREIGN KEY("fornecedor_id")
    REFERENCES "fornecedores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_patrimonios_empresa_id"
    FOREIGN KEY("empresa_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "patrimonios.FK_patrimonios_fornecedor_id_idx" ON "patrimonios" ("fornecedor_id");
CREATE INDEX "patrimonios.FK_patrimonios_empresa_id_idx" ON "patrimonios" ("empresa_id");
CREATE TABLE "observacoes"(
--   Observações e instruções de preparo de produto[N:Observação|Observações][G:a][K:App\Models|Models\][H:Model][L:null][ID:52]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da observação[G:o]
  "produto_id" INTEGER NOT NULL,-- Informa o produto que pode conter essa observação[G:o][N:Produto]
  "grupo" INTEGER NOT NULL DEFAULT 0,-- Informa o grupo de observações obrigatórias, se maior que zero, é obrigatório escolher pelo menos uma opção[F:0][G:o][N:Grupo]
  "descricao" VARCHAR(100) NOT NULL,-- Descrição da observação do produto[G:a][N:Descrição]
  CONSTRAINT "produto_id_descricao_UNIQUE"
    UNIQUE("produto_id","descricao"),
  CONSTRAINT "FK_observacoes_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "carteiras"(
--   Informa uma conta bancária ou uma carteira financeira[N:Carteira|Carteiras][G:a][K:App\Models|Models\][H:Model][L:null][ID:9]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código local da carteira[G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('bancaria', 'financeira', 'credito', 'local')),-- Tipo de carteira, Bancaria: para conta bancária, Financeira: para carteira financeira da empresa ou de sites de pagamentos, Credito: para cartão de crédito e Local: para caixas e cofres locais[N:Tipo][G:o][S:S][E:Bancária|Financeira|Crédito|Local]
  "carteira_id" INTEGER DEFAULT NULL,-- Informa a carteira superior, exemplo: Banco e cartões como subcarteira[G:a][N:Carteira superior]
  "banco_id" INTEGER DEFAULT NULL,-- Código local do banco quando a carteira for bancária[N:Banco][G:o][S:S]
  "descricao" VARCHAR(100) NOT NULL,-- Descrição da carteira, nome dado a carteira cadastrada[N:Descrição][G:a][S]
  "conta" VARCHAR(100) DEFAULT NULL,-- Número da conta bancária ou usuário da conta de acesso da carteira[N:Conta][G:a]
  "agencia" VARCHAR(200) DEFAULT NULL,-- Número da agência da conta bancária ou site da carteira financeira[N:Agência][G:a]
  "transacao" DECIMAL NOT NULL DEFAULT 0,-- Valor cobrado pela operadora de pagamento para cada transação[N:Transação][G:a][F:0]
  "limite" DECIMAL DEFAULT NULL,-- Limite de crédito[G:o][N:Limite de crédito]
  "token" VARCHAR(250) DEFAULT NULL,-- Token para integração de pagamentos[G:o][N:Token]
  "ambiente" TEXT CHECK("ambiente" IN('teste', 'producao')) DEFAULT NULL,-- Ambiente de execução da API usando o token[G:o][N:Ambiente][E:Teste|Produção]
  "logo_url" VARCHAR(100) DEFAULT NULL,-- Logo do gateway de pagamento[N:Logo][G:o][I:256x256|carteira|carteira.png]
  "cor" VARCHAR(20) DEFAULT NULL,-- Cor predominante da marca da instituição[G:a][N:Cor]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a carteira ou conta bancária está ativa[N:Ativa][G:a][F:true]
  "data_desativada" DATETIME DEFAULT NULL,-- Data em que a carteira foi desativada[G:a][N:Data de desativação]
  CONSTRAINT "FK_carteiras_banco_id"
    FOREIGN KEY("banco_id")
    REFERENCES "bancos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_carteiras_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "carteiras.FK_carteiras_banco_id_idx" ON "carteiras" ("banco_id");
CREATE INDEX "carteiras.FK_carteiras_carteira_id_idx" ON "carteiras" ("carteira_id");
CREATE TABLE "estados"(
--   Estado federativo de um país[N:Estado|Estados][G:o][K:App\Models|Models\][H:Model][L:null][ID:29]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do estado[G:o]
  "pais_id" INTEGER NOT NULL,-- País a qual esse estado pertence[N:País][G:o][S:S]
  "nome" VARCHAR(64) NOT NULL,-- Nome do estado[N:Nome][G:o][S]
  "uf" VARCHAR(48) NOT NULL,-- Sigla do estado[N:UF]
  CONSTRAINT "pais_id_nome_UNIQUE"
    UNIQUE("pais_id","nome"),
  CONSTRAINT "pais_id_uf_UNIQUE"
    UNIQUE("pais_id","uf"),
  CONSTRAINT "FK_estados_pais_id"
    FOREIGN KEY("pais_id")
    REFERENCES "paises"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "empresas"(
--   Informações da empresa[N:Empresa|Empresas][G:a][K:App\Models|Models\][H:Model][L:null][ID:27]
  "id" TEXT PRIMARY KEY NOT NULL CHECK("id" IN('1')) DEFAULT '1',-- Identificador único da empresa, valor 1[G:o][F:'1']
  "pais_id" INTEGER DEFAULT NULL,-- País em que a empresa está situada[N:País][G:o]
  "empresa_id" INTEGER DEFAULT NULL,-- Informa a empresa do cadastro de clientes, a empresa deve ser um cliente do tipo pessoa jurídica[N:Empresa][G:a][S:S]
  "parceiro_id" INTEGER DEFAULT NULL,-- Informa quem realiza o suporte do sistema, deve ser um cliente do tipo empresa que possua um acionista como representante[N:Parceiro][G:o][S:S]
  "opcoes" TEXT DEFAULT NULL,-- Opções gerais do sistema como opções de impressão e comportamento[N:Opções do sistema][G:a]
  CONSTRAINT "FK_empresas_empresa_id"
    FOREIGN KEY("empresa_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_empresas_parceiro_id"
    FOREIGN KEY("parceiro_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_empresas_pais_id"
    FOREIGN KEY("pais_id")
    REFERENCES "paises"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "empresas.FK_empresas_empresa_id_idx" ON "empresas" ("empresa_id");
CREATE INDEX "empresas.FK_empresas_parceiro_id_idx" ON "empresas" ("parceiro_id");
CREATE INDEX "empresas.FK_empresas_pais_id_idx" ON "empresas" ("pais_id");
CREATE TABLE "composicoes"(
--   Informa as propriedades da composição de um produto composto[N:Composição|Composições][G:a][K:App\Models|Models\][H:Model][L:null][ID:18]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da composição[G:o]
  "composicao_id" INTEGER NOT NULL,-- Informa a qual produto pertence essa composição, deve sempre ser um produto do tipo Composição[N:Composição][G:a][S:S]
  "produto_id" INTEGER NOT NULL,-- Produto ou composição que faz parte dessa composição, Obs: Não pode ser um pacote[N:Produto da composição][G:o][S]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('composicao', 'opcional', 'adicional')) DEFAULT 'composicao',-- Tipo de composição, Composicao: sempre retira do estoque, Opcional: permite desmarcar na venda, Adicional: permite adicionar na venda[N:Tipo][G:o][E:Composição|Opcional|Adicional][F:self::TIPO_COMPOSICAO]
  "quantidade" DOUBLE NOT NULL,-- Quantidade que será consumida desse produto para cada composição formada[N:Quantidade][G:a]
  "valor" DECIMAL NOT NULL DEFAULT 0,-- Desconto que será realizado ao retirar esse produto da composição no  momento da venda[N:Valor][G:o][F:0]
  "quantidade_maxima" INTEGER NOT NULL DEFAULT 1,-- Define a quantidade máxima que essa composição pode ser vendida repetidamente[N:Quantidade máxima][G:a][F:1]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Indica se a composição está sendo usada atualmente na composição do produto[N:Ativa][G:a][F:true]
  "data_remocao" DATETIME DEFAULT NULL,-- Data em que a composição foi removida e não será mais exibida por padrão[G:a][N:Data de remoção]
  CONSTRAINT "composicao_id_produto_id_tipo_UNIQUE"
    UNIQUE("composicao_id","produto_id","tipo"),
  CONSTRAINT "FK_composicoes_composicao_id"
    FOREIGN KEY("composicao_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_composicoes_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "composicoes.FK_composicoes_produto_id_idx" ON "composicoes" ("produto_id");
CREATE TABLE "mesas"(
--   Mesas para lançamento de pedidos[N:Mesa|Mesas][G:a][K:App\Models|Models\][H:Model][L:1][ID:46]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Número da mesa[N:Número][G:o]
  "setor_id" INTEGER NOT NULL,-- Setor em que a mesa está localizada[G:o][N:Setor]
  "numero" INTEGER NOT NULL,-- Número da mesa[G:o][N:Número]
  "nome" VARCHAR(50) DEFAULT NULL,-- Nome da mesa[N:Nome][G:o][S]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a mesa está disponível para lançamento de pedidos[N:Ativa][G:a][F:true]
  CONSTRAINT "nome_UNIQUE"
    UNIQUE("nome"),
  CONSTRAINT "numero_UNIQUE"
    UNIQUE("numero"),
  CONSTRAINT "FK_mesas_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "mesas.FK_mesas_setor_id_idx" ON "mesas" ("setor_id");
CREATE TABLE "compras"(
--   Compras realizadas em uma lista num determinado fornecedor[N:Compra|Compras][G:a][K:App\Models|Models\][H:Model][L:null][ID:19]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da compra
  "numero" VARCHAR(64) DEFAULT NULL,-- Informa o número fiscal da compra[N:Número da compra][G:o]
  "comprador_id" INTEGER NOT NULL,-- Informa o funcionário que comprou os produtos da lista[N:Comprador][G:o][S:S]
  "fornecedor_id" INTEGER NOT NULL,-- Fornecedor em que os produtos foram compras[N:Fornecedor][G:o][S:S]
  "documento_url" VARCHAR(150) DEFAULT NULL,-- Informa o nome do documento no servidor do sistema[N:Documento][G:o][I:256x256|compra|compra.png]
  "data_compra" DATETIME NOT NULL,-- Informa da data de finalização da compra[N:Data da compra][G:a]
  CONSTRAINT "numero_UNIQUE"
    UNIQUE("numero"),
  CONSTRAINT "FK_compras_fornecedor_id"
    FOREIGN KEY("fornecedor_id")
    REFERENCES "fornecedores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_compras_comprador_id"
    FOREIGN KEY("comprador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "compras.FK_compras_fornecedor_id_idx" ON "compras" ("fornecedor_id");
CREATE INDEX "compras.FK_compras_comprador_id_idx" ON "compras" ("comprador_id");
CREATE TABLE "acessos"(
--   Permite acesso à uma determinada funcionalidade da lista de permissões[N:Acesso|Acessos][G:o][K:App\Models|Models\][H:Model][L:null][ID:1]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do acesso[G:o]
  "funcao_id" INTEGER NOT NULL,-- Função a que a permissão se aplica[N:Função][G:a][S:S]
  "permissao_id" INTEGER NOT NULL,-- Permissão liberada para a função[N:Permissão][G:a][S]
  CONSTRAINT "funcao_id_permissao_id_UNIQUE"
    UNIQUE("funcao_id","permissao_id"),
  CONSTRAINT "FK_acessos_funcao_id"
    FOREIGN KEY("funcao_id")
    REFERENCES "funcoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_acessos_permissao_id"
    FOREIGN KEY("permissao_id")
    REFERENCES "permissoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "acessos.FK_acessos_permissao_id_idx" ON "acessos" ("permissao_id");
CREATE TABLE "viagens"(
--   Registro de viagem de uma entrega ou compra de insumos[N:Viagem|Viagens][G:a][K:App\Models|Models\][H:Model][L:null][ID:76]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da viagem[G:o]
  "responsavel_id" INTEGER NOT NULL,-- Responsável pela entrega ou compra[N:Responsável][G:o][S:S]
  "latitude" DOUBLE DEFAULT NULL,-- Ponto latitudinal para localização do responsável em tempo real[N:Latitude][G:a]
  "longitude" DOUBLE DEFAULT NULL,-- Ponto longitudinal para localização do responsável em tempo real[N:Longitude][G:a]
  "quilometragem" DOUBLE DEFAULT NULL,-- Quilometragem no veículo antes de iniciar a viagem[G:a][N:Quilometragem]
  "distancia" DOUBLE DEFAULT NULL,-- Distância percorrida até chegar de volta ao ponto de partida[G:a][N:Distância]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização da localização do responsável[G:a][N:Data de atualização]
  "data_chegada" DATETIME DEFAULT NULL,-- Data de chegada no estabelecimento[G:a][N:Data de chegada]
  "data_saida" DATETIME NOT NULL,-- Data e hora que o responsável saiu para entregar o pedido ou fazer as compras[N:Data de saida][G:a]
  CONSTRAINT "FK_viagens_responsavel_id"
    FOREIGN KEY("responsavel_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "viagens.FK_viagens_responsavel_id_idx" ON "viagens" ("responsavel_id");
CREATE TABLE "zonas"(
--   Zonas de um bairro[N:Zona|Zonas][G:a][K:App\Models|Models\][H:Model][L:null][ID:77]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da zona[G:o]
  "bairro_id" INTEGER NOT NULL,-- Bairro em que essa zona está localizada[G:o][N:Bairro]
  "nome" VARCHAR(45) NOT NULL,-- Nome da zona, Ex. Sul, Leste, Começo, Fim[G:o][N:Nome]
  "adicional_entrega" DECIMAL NOT NULL,-- Taxa adicional para entrega nessa zona, será somado com a taxa para esse bairro[G:o][N:Adicional de entrega]
  "disponivel" TEXT NOT NULL CHECK("disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a zona está disponível para entrega de pedidos[N:Disponível][G:o][F:true]
  "area" TEXT DEFAULT NULL,-- Área de cobertura para entrega[G:a][N:Área de entrega]
  "entrega_minima" INTEGER DEFAULT NULL,-- Tempo mínimo para entrega nessa zona, sobrescreve o tempo de entrega para o bairro[N:Tempo mínimo de entrega][G:o]
  "entrega_maxima" INTEGER DEFAULT NULL,-- Tempo máximo para entrega nessa zona, sobrescreve o tempo de entrega para o bairro[N:Tempo máximo de entrega][G:o]
  CONSTRAINT "bairro_id_nome_UNIQUE"
    UNIQUE("bairro_id","nome"),
  CONSTRAINT "FK_zonas_bairro_id"
    FOREIGN KEY("bairro_id")
    REFERENCES "bairros"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "contagens"(
--   Guarda a soma do estoque de cada produto por setor[N:Contagem|Contagens][G:a][K:App\Models|Models\][H:Model][L:null][ID:20]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da contagem[G:o]
  "produto_id" INTEGER NOT NULL,-- Produto que possui o estoque acumulado nesse setor[G:o][N:Produto]
  "setor_id" INTEGER NOT NULL,-- Setor em que o produto está localizado[G:o][N:Setor]
  "quantidade" DOUBLE NOT NULL,-- Quantidade do produto nesse setor[G:a][N:Quantidade]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data em que a contagem foi atualizada[G:a][N:Data de atualização]
  CONSTRAINT "produto_id_setor_id_UNIQUE"
    UNIQUE("produto_id","setor_id"),
  CONSTRAINT "FK_contagens_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contagens_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "contagens.FK_contagens_setor_id_idx" ON "contagens" ("setor_id");
CREATE TABLE "resumos"(
--   Resumo de fechamento de caixa, informa o valor contado no fechamento do caixa para cada forma de pagamento[N:Resumo|Resumos][G:o][K:App\Models|Models\][H:Model][L:null][ID:68]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do resumo[G:o]
  "movimentacao_id" INTEGER NOT NULL,-- Movimentação do caixa referente ao resumo[N:Movimentação][G:a][S]
  "forma_id" INTEGER NOT NULL,-- Tipo de pagamento do resumo[N:Tipo][G:o][E:Dinheiro|Cartão de credito|Cartão de débito|Vale|Cheque|Crediário|Saldo][S:S]
  "cartao_id" INTEGER DEFAULT NULL,-- Cartão da forma de pagamento[N:Cartão][G:o]
  "valor" DECIMAL NOT NULL,-- Valor que foi contado ao fechar o caixa[N:Valor][G:o]
  CONSTRAINT "movimentacao_id_forma_id_cartao_id_UNIQUE"
    UNIQUE("movimentacao_id","forma_id","cartao_id"),
  CONSTRAINT "FK_resumos_movimentacao_id"
    FOREIGN KEY("movimentacao_id")
    REFERENCES "movimentacoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_resumos_cartao_id"
    FOREIGN KEY("cartao_id")
    REFERENCES "cartoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_resumos_forma_id"
    FOREIGN KEY("forma_id")
    REFERENCES "formas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "resumos.FK_resumos_cartao_id_idx" ON "resumos" ("cartao_id");
CREATE INDEX "resumos.FK_resumos_forma_id_idx" ON "resumos" ("forma_id");
CREATE TABLE "catalogos"(
--   Informa a lista de produtos disponíveis nos fornecedores[N:Catálogo de produtos|Catálogos de produtos][G:o][K:App\Models|Models\][H:Model][L:null][ID:11]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do catálogo[G:o]
  "produto_id" INTEGER NOT NULL,-- Produto consultado[N:Produto][G:o][S]
  "fornecedor_id" INTEGER NOT NULL,-- Fornecedor que possui o produto à venda[N:Fornecedor][G:o][S:S]
  "preco_compra" DECIMAL NOT NULL,-- Preço a qual o produto foi comprado da última vez[N:Preço de compra][G:o]
  "preco_venda" DECIMAL NOT NULL DEFAULT 0,-- Preço de venda do produto pelo fornecedor na última consulta[N:Preço de venda][G:o][F:0]
  "quantidade_minima" DOUBLE NOT NULL DEFAULT 1,-- Quantidade mínima que o fornecedor vende[N:Quantidade mínima][G:a][F:1]
  "estoque" DOUBLE NOT NULL DEFAULT 0,-- Quantidade em estoque do produto no fornecedor[N:Estoque][G:o][F:0]
  "limitado" TEXT NOT NULL CHECK("limitado" IN('Y', 'N')) DEFAULT 'N',-- Informa se a quantidade de estoque é limitada[N:Limitado][G:o][F:false]
  "conteudo" DOUBLE NOT NULL DEFAULT 1,-- Informa o conteúdo do produto como é comprado, Ex.: 5UN no mesmo pacote[N:Conteúdo][G:o][F:1]
  "data_consulta" DATETIME DEFAULT NULL,-- Última data de consulta do preço do produto[N:Data de consulta][G:a]
  "data_parada" DATETIME DEFAULT NULL,-- Data em que o produto deixou de ser vendido pelo fornecedor[G:a][N:Data de parada]
  CONSTRAINT "fornecedor_id_produto_id_UNIQUE"
    UNIQUE("fornecedor_id","produto_id"),
  CONSTRAINT "FK_catalogos_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_catalogos_fornecedor_id"
    FOREIGN KEY("fornecedor_id")
    REFERENCES "fornecedores"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "catalogos.FK_catalogos_produto_id_idx" ON "catalogos" ("produto_id");
CREATE TABLE "pacotes"(
--   Contém todos as opções para a formação do produto final[N:Pacote|Pacotes][G:o][K:App\Models|Models\][H:Model][L:null][ID:55]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do pacote[G:o]
  "pacote_id" INTEGER NOT NULL,-- Pacote a qual pertence as informações de formação do produto final[N:Pacote][G:o][S:S]
  "grupo_id" INTEGER NOT NULL,-- Grupo de formação, Ex.: Tamanho, Sabores e Complementos.[N:Grupo][G:o][S:S]
  "produto_id" INTEGER DEFAULT NULL,-- Produto selecionável do grupo. Não pode conter propriedade.[N:Produto][G:o][S][S:S]
  "propriedade_id" INTEGER DEFAULT NULL,-- Propriedade selecionável do grupo. Não pode conter produto.[N:Propriedade][G:a]
  "associacao_id" INTEGER DEFAULT NULL,-- Informa a propriedade pai de um complemento, permite atribuir preços diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde Tamanho é pai de Sabor[N:Associação][G:a]
  "quantidade_minima" INTEGER NOT NULL DEFAULT 0,-- Permite definir uma quantidade mínima obrigatória para a venda desse item[N:Quantidade mínima][G:a][F:0]
  "quantidade_maxima" INTEGER NOT NULL DEFAULT 1,-- Define a quantidade máxima que pode ser vendido esse item repetidamente[N:Quantidade máxima][G:a][F:1]
  "acrescimo" DECIMAL NOT NULL,-- Valor acrescentado ao produto quando o item é selecionado[N:Acréscimo][G:o]
  "selecionado" TEXT NOT NULL CHECK("selecionado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o complemento está selecionado por padrão, recomendado apenas para produtos[N:Selecionado][G:o][F:false]
  "disponivel" TEXT NOT NULL CHECK("disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Indica se o pacote estará disponível para venda[N:Disponível][G:o][F:true]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que o pacote foi arquivado e não será mais usado[G:a][N:Data de arquivação]
  CONSTRAINT "FK_pacotes_pacote_id"
    FOREIGN KEY("pacote_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pacotes_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pacotes_grupo_id"
    FOREIGN KEY("grupo_id")
    REFERENCES "grupos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pacotes_associacao_id"
    FOREIGN KEY("associacao_id")
    REFERENCES "pacotes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pacotes_propriedade_id"
    FOREIGN KEY("propriedade_id")
    REFERENCES "propriedades"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "pacotes.FK_pacotes_pacote_id_idx" ON "pacotes" ("pacote_id");
CREATE INDEX "pacotes.FK_pacotes_produto_id_idx" ON "pacotes" ("produto_id");
CREATE INDEX "pacotes.FK_pacotes_grupo_id_idx" ON "pacotes" ("grupo_id");
CREATE INDEX "pacotes.FK_pacotes_associacao_id_idx" ON "pacotes" ("associacao_id");
CREATE INDEX "pacotes.FK_pacotes_propriedade_id_idx" ON "pacotes" ("propriedade_id");
CREATE TABLE "localizacoes"(
--   Endereço detalhado de um cliente[N:Localização|Localizações][G:a][K:App\Models|Models\][H:Model][L:null][ID:45]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do endereço[G:o]
  "cliente_id" INTEGER NOT NULL,-- Cliente a qual esse endereço pertence[N:Cliente][G:o][S:S]
  "bairro_id" INTEGER NOT NULL,-- Bairro do endereço[N:Bairro][G:o][S:S]
  "zona_id" INTEGER DEFAULT NULL,-- Informa a zona do bairro
  "cep" VARCHAR(8) DEFAULT NULL,-- Código dos correios para identificar um logradouro[M:99999-999][N:CEP][G:o]
  "logradouro" VARCHAR(100) NOT NULL,-- Nome da rua ou avenida[N:Logradouro][G:o][S]
  "numero" VARCHAR(20) NOT NULL,-- Número da casa ou do condomínio[N:Número][G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('casa', 'apartamento', 'condominio')) DEFAULT 'casa',-- Tipo de endereço Casa ou Apartamento[N:Tipo][G:o][F:self::TIPO_CASA][E:Casa|Apartamento|Condomínio][F:self::TIPO_CASA]
  "complemento" VARCHAR(100) DEFAULT NULL,-- Complemento do endereço, Ex.: Loteamento Sul[N:Complemento][G:o]
  "condominio" VARCHAR(100) DEFAULT NULL,-- Nome do condomínio[N:Condomínio][G:o]
  "bloco" VARCHAR(20) DEFAULT NULL,-- Número do bloco quando for apartamento[N:Bloco][G:o]
  "apartamento" VARCHAR(20) DEFAULT NULL,-- Número do apartamento[N:Apartamento][G:o]
  "referencia" VARCHAR(200) DEFAULT NULL,-- Ponto de referência para chegar ao local[N:Referência][G:a]
  "latitude" DOUBLE DEFAULT NULL,-- Ponto latitudinal para localização em um mapa[N:Latitude][G:a]
  "longitude" DOUBLE DEFAULT NULL,-- Ponto longitudinal para localização em um mapa[N:Longitude][G:a]
  "apelido" VARCHAR(45) DEFAULT NULL,-- Ex.: Minha Casa, Casa da Amiga[N:Apelido][G:o]
  "data_arquivado" DATETIME DEFAULT NULL,-- Informa a data que essa localização foi removida[G:a][N:Data de arquivamento]
  CONSTRAINT "FK_localizacoes_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_localizacoes_bairro_id"
    FOREIGN KEY("bairro_id")
    REFERENCES "bairros"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_localizacoes_zona_id"
    FOREIGN KEY("zona_id")
    REFERENCES "zonas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "localizacoes.FK_localizacoes_bairro_id_idx" ON "localizacoes" ("bairro_id");
CREATE INDEX "localizacoes.FK_localizacoes_zona_id_idx" ON "localizacoes" ("zona_id");
CREATE TABLE "itens"(
--   Produtos, taxas e serviços do pedido, a alteração do estado permite o controle de produção[N:Item do pedido|Itens do pedido][G:o][U:Item|Itens][K:App\Models|Models\][H:Model][L:null][ID:42]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do item do pedido[G:o]
  "pedido_id" INTEGER NOT NULL,-- Pedido a qual pertence esse item[N:Pedido][G:o][S:S]
  "prestador_id" INTEGER DEFAULT NULL,-- Prestador que lançou esse item no pedido[N:Prestador][G:o][S:S]
  "produto_id" INTEGER,-- Produto vendido[N:Produto][G:o][S][S:S]
  "servico_id" INTEGER DEFAULT NULL,-- Serviço cobrado ou taxa[N:Serviço][G:o][S:S]
  "item_id" INTEGER DEFAULT NULL,-- Pacote em que esse item faz parte[N:Pacote][G:o][S:S]
  "pagamento_id" INTEGER DEFAULT NULL,-- Informa se esse item foi pago e qual foi o lançamento[G:o][N:Pagamento]
  "descricao" VARCHAR(200) DEFAULT NULL,-- Sobrescreve a descrição do produto na exibição[N:Descrição][G:a]
  "composicao" TEXT DEFAULT NULL,-- Informa a composição escolhida[N:Composição][G:a]
  "preco" DECIMAL NOT NULL,-- Preço do produto já com desconto[N:Preço][G:o]
  "quantidade" DOUBLE NOT NULL,-- Quantidade de itens vendidos[N:Quantidade][G:a]
  "subtotal" DECIMAL NOT NULL,-- Subtotal do item sem comissão[G:o][N:Subtotal]
  "comissao" DECIMAL NOT NULL DEFAULT 0,-- Valor total de comissão cobrada nesse item da venda[N:Porcentagem][G:a][F:0]
  "total" DECIMAL NOT NULL,-- Total a pagar do item com a comissão[G:o][N:Total]
  "preco_venda" DECIMAL NOT NULL,-- Preço de normal do produto no momento da venda[N:Preço de venda][G:o]
  "preco_compra" DECIMAL NOT NULL DEFAULT 0,-- Preço de compra do produto calculado automaticamente na hora da venda[N:Preço de compra][G:o][F:0]
  "detalhes" VARCHAR(255) DEFAULT NULL,-- Observações do item pedido, Ex.: bem gelado, mal passado[N:Observações][G:o]
  "estado" TEXT NOT NULL CHECK("estado" IN('adicionado', 'enviado', 'processado', 'pronto', 'disponivel', 'entregue')) DEFAULT 'adicionado',-- Estado de preparo e envio do produto[N:Estado][G:o][E:Adicionado|Enviado|Processado|Pronto|Disponível|Entregue][F:self::ESTADO_ADICIONADO]
  "cancelado" TEXT NOT NULL CHECK("cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o item foi cancelado[N:Cancelado][G:o][F:false]
  "motivo" VARCHAR(200) DEFAULT NULL,-- Informa o motivo do item ser cancelado[N:Motivo][G:o]
  "desperdicado" TEXT NOT NULL CHECK("desperdicado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o item foi cancelado por conta de desperdício[N:Desperdiçado][G:o][F:false]
  "reservado" TEXT NOT NULL CHECK("reservado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o produto foi reservado no estoque[G:o][N:Reservado][F:false]
  "data_processamento" DATETIME DEFAULT NULL,-- Data do processamento do item[N:Data do processamento][G:a]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização do estado do item[N:Data de atualização][G:a]
  "data_lancamento" DATETIME NOT NULL,-- Data e hora da realização do pedido do item[N:Data de lançamento][G:a][D]
  CONSTRAINT "FK_itens_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_itens_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_itens_prestador_id"
    FOREIGN KEY("prestador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_itens_item_id"
    FOREIGN KEY("item_id")
    REFERENCES "itens"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_itens_servico_id"
    FOREIGN KEY("servico_id")
    REFERENCES "servicos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_itens_pagamento_id"
    FOREIGN KEY("pagamento_id")
    REFERENCES "pagamentos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "itens.FK_itens_pedido_id_idx" ON "itens" ("pedido_id");
CREATE INDEX "itens.FK_itens_produto_id_idx" ON "itens" ("produto_id");
CREATE INDEX "itens.FK_itens_prestador_id_idx" ON "itens" ("prestador_id");
CREATE INDEX "itens.FK_itens_item_id_idx" ON "itens" ("item_id");
CREATE INDEX "itens.FK_itens_servico_id_idx" ON "itens" ("servico_id");
CREATE INDEX "itens.FK_itens_pagamento_id_idx" ON "itens" ("pagamento_id");
CREATE TABLE "contas"(
--   Contas a pagar e ou receber[N:Conta|Contas][G:a][K:App\Models|Models\][H:Model][L:null][ID:21]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da conta[G:o]
  "classificacao_id" INTEGER NOT NULL,-- Classificação da conta[N:Classificação][G:a][S:S]
  "funcionario_id" INTEGER NOT NULL,-- Funcionário que lançou a conta[N:Funcionário][G:o][S:S]
  "conta_id" INTEGER DEFAULT NULL,-- Informa a conta principal[G:a][N:Conta principal]
  "agrupamento_id" INTEGER DEFAULT NULL,-- Informa se esta conta foi agrupada e não precisa ser mais paga individualmente, uma conta agrupada é tratada internamente como desativada[G:o][N:Agrupamento]
  "carteira_id" INTEGER DEFAULT NULL,-- Informa a carteira que essa conta será paga automaticamente ou para informar as contas a pagar dessa carteira[G:a][N:Carteira]
  "cliente_id" INTEGER DEFAULT NULL,-- Cliente a qual a conta pertence[N:Cliente][G:o][S:S]
  "pedido_id" INTEGER DEFAULT NULL,-- Pedido da qual essa conta foi gerada[N:Pedido][G:o][S:S]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('receita', 'despesa')) DEFAULT 'despesa',-- Tipo de conta se receita ou despesa[G:o][N:Tipo][E:Receita|Despesa][F:self::TIPO_DESPESA]
  "descricao" VARCHAR(200) NOT NULL,-- Descrição da conta[N:Descrição][G:a][S]
  "valor" DECIMAL NOT NULL,-- Valor da conta[N:Valor][G:o]
  "consolidado" DECIMAL NOT NULL DEFAULT 0,-- Valor pago ou recebido da conta[G:a][N:Valor pago ou recebido][F:0]
  "fonte" TEXT NOT NULL CHECK("fonte" IN('fixa', 'variavel', 'comissao', 'remuneracao')) DEFAULT 'fixa',-- Fonte dos valores, comissão e remuneração se pagar antes do vencimento, o valor será proporcional[G:a][N:Fonte dos valores][E:Fixa|Variável|Comissão|Remuneração][F:self::FONTE_FIXA]
  "numero_parcela" INTEGER NOT NULL DEFAULT 1,-- Informa qual o número da parcela para esta conta[G:o][N:Número da parcela][F:1]
  "parcelas" INTEGER NOT NULL DEFAULT 1,-- Quantidade de parcelas que essa conta terá, zero para conta recorrente e será alterado para 1 quando criar a próxima conta[G:a][N:Parcelas][F:1]
  "frequencia" INTEGER NOT NULL DEFAULT 0,-- Frequência da recorrência em dias ou mês, depende do modo de cobrança[G:a][N:Frequencia][F:0]
  "modo" TEXT NOT NULL CHECK("modo" IN('diario', 'mensal')) DEFAULT 'mensal',-- Modo de cobrança se diário ou mensal, a quantidade é definida em frequencia[G:o][N:Modo][E:Diário|Mensal][F:self::MODO_MENSAL]
  "automatico" TEXT NOT NULL CHECK("automatico" IN('Y', 'N')) DEFAULT 'N',-- Informa se o pagamento será automático após o vencimento, só ocorrerá se tiver saldo na carteira, usado para débito automático[G:o][N:Automático][F:false]
  "acrescimo" DECIMAL NOT NULL DEFAULT 0,-- Acréscimo de valores ao total[N:Acréscimo][G:o][F:0]
  "multa" DECIMAL NOT NULL DEFAULT 0,-- Valor da multa em caso de atraso[N:Multa por atraso][G:a][F:0]
  "juros" DOUBLE NOT NULL DEFAULT 0,-- Juros diário em caso de atraso, valor de 0 a 1, 1 = 100%[N:Juros][G:o][F:0]
  "formula" TEXT NOT NULL CHECK("formula" IN('simples', 'composto')) DEFAULT 'composto',-- Fórmula de juros que será cobrado em caso de atraso[G:o][N:Tipo de juros][E:Simples|Composto][F:self::FORMULA_COMPOSTO]
  "vencimento" DATETIME NOT NULL,-- Data de vencimento da conta[N:Data de vencimento][G:a]
  "numero" VARCHAR(64) DEFAULT NULL,-- Número do documento que gerou a conta[N:Número do documento][G:o]
  "anexo_url" VARCHAR(200) DEFAULT NULL,-- Caminho do anexo da conta[N:Anexo][G:o][I:512x256|conta|conta.png]
  "estado" TEXT NOT NULL CHECK("estado" IN('analise', 'ativa', 'paga', 'cancelada', 'desativada')) DEFAULT 'ativa',-- Informa o estado da conta[N:Estado][G:o][E:Análise|Ativa|Paga|Cancelada|Desativada][F:self::ESTADO_ATIVA]
  "data_calculo" DATETIME DEFAULT NULL,-- Data do último cálculo de acréscimo por atraso de pagamento[N:Data de cálculo][G:a]
  "data_emissao" DATETIME NOT NULL,-- Data de emissão da conta[N:Data de emissão][G:a]
  CONSTRAINT "FK_contas_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_funcionario_id"
    FOREIGN KEY("funcionario_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_classificacao_id"
    FOREIGN KEY("classificacao_id")
    REFERENCES "classificacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_conta_id"
    FOREIGN KEY("conta_id")
    REFERENCES "contas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_contas_agrupamento_id"
    FOREIGN KEY("agrupamento_id")
    REFERENCES "contas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "contas.FK_contas_cliente_id_idx" ON "contas" ("cliente_id");
CREATE INDEX "contas.FK_contas_funcionario_id_idx" ON "contas" ("funcionario_id");
CREATE INDEX "contas.FK_contas_pedido_id_idx" ON "contas" ("pedido_id");
CREATE INDEX "contas.FK_contas_classificacao_id_idx" ON "contas" ("classificacao_id");
CREATE INDEX "contas.FK_contas_conta_id_idx" ON "contas" ("conta_id");
CREATE INDEX "contas.FK_contas_carteira_id_idx" ON "contas" ("carteira_id");
CREATE INDEX "contas.FK_contas_agrupamento_id_idx" ON "contas" ("agrupamento_id");
CREATE INDEX "contas.vencimento_INDEX" ON "contas" ("vencimento" DESC);
CREATE INDEX "contas.data_emissao_INDEX" ON "contas" ("data_emissao" DESC);
CREATE TABLE "dispositivos"(
--   Computadores e tablets com opções de acesso[N:Dispositivo|Dispositivos][G:o][K:App\Models|Models\][H:Model][L:null][ID:25]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do dispositivo[G:o]
  "setor_id" INTEGER DEFAULT NULL,-- Setor em que o dispositivo está instalado/será usado[N:Setor][G:o]
  "caixa_id" INTEGER DEFAULT NULL,-- Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os dispositivos[N:Caixa][G:o]
  "nome" VARCHAR(100) NOT NULL,-- Nome do computador ou tablet em rede, único entre os dispositivos[N:Nome][G:o][S]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('computador', 'tablet', 'navegador')) DEFAULT 'computador',-- Tipo de dispositivo[N:Tipo][G:o][S:S][F:self::TIPO_COMPUTADOR]
  "descricao" VARCHAR(45) DEFAULT NULL,-- Descrição do dispositivo[N:Descrição][G:a]
  "opcoes" TEXT DEFAULT NULL,-- Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros[N:Opções][G:a]
  "serial" VARCHAR(45) NOT NULL,-- Serial do tablet para validação, único entre os dispositivos[N:Serial][G:o]
  "validacao" VARCHAR(40) DEFAULT NULL,-- Validação do dispositivo[N:Validação][G:a]
  CONSTRAINT "caixa_id_UNIQUE"
    UNIQUE("caixa_id"),
  CONSTRAINT "serial_UNIQUE"
    UNIQUE("serial"),
  CONSTRAINT "FK_dispositivos_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_dispositivos_caixa_id"
    FOREIGN KEY("caixa_id")
    REFERENCES "caixas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "dispositivos.FK_dispositivos_setor_id_idx" ON "dispositivos" ("setor_id");
CREATE TABLE "listas"(
--   Lista de compras de produtos[N:Lista de compra|Listas de compras][G:a][K:App\Models|Models\][H:Model][L:null][ID:44]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da lista de compras[G:o]
  "descricao" VARCHAR(100) NOT NULL,-- Nome da lista, pode ser uma data[N:Descrição][G:a][S]
  "estado" TEXT NOT NULL CHECK("estado" IN('analise', 'fechada', 'comprada')) DEFAULT 'analise',-- Estado da lista de compra. Análise: Ainda estão sendo adicionado produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os itens foram comprados[N:Estado][G:o][E:Análise|Fechada|Comprada][F:self::ESTADO_ANALISE]
  "encarregado_id" INTEGER NOT NULL,-- Informa o funcionário encarregado de fazer as compras[N:Encarregado][G:o][S:S]
  "viagem_id" INTEGER DEFAULT NULL,-- Informações da viagem para realizar as compras[G:a][N:Viagem]
  "data_viagem" DATETIME NOT NULL,-- Data e hora para o encarregado ir fazer as compras[N:Data de viagem][G:a]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro da lista[N:Data de cadastro][G:a]
  CONSTRAINT "FK_listas_encarregado_id"
    FOREIGN KEY("encarregado_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_listas_viagem_id"
    FOREIGN KEY("viagem_id")
    REFERENCES "viagens"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "listas.FK_listas_encarregado_id_idx" ON "listas" ("encarregado_id");
CREATE INDEX "listas.FK_listas_viagem_id_idx" ON "listas" ("viagem_id");
CREATE TABLE "avaliacoes"(
--   Avaliação de atendimento e outros serviços do estabelecimento[N:Avaliação|Avaliações][G:a][K:App\Models|Models\][H:Model][L:5][ID:4]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da avaliação[G:o]
  "metrica_id" INTEGER NOT NULL,-- Métrica de avaliação[G:a][N:Métrica]
  "cliente_id" INTEGER DEFAULT NULL,-- Informa o cliente que avaliou esse pedido ou produto, obrigatório quando for avaliação de produto[G:o][N:Cliente]
  "pedido_id" INTEGER DEFAULT NULL,-- Pedido que foi avaliado, quando nulo o produto deve ser informado[G:o][N:Pedido]
  "produto_id" INTEGER DEFAULT NULL,-- Produto que foi avaliado[G:o][N:Produto]
  "estrelas" INTEGER NOT NULL,-- Quantidade de estrelas de 1 a 5[G:a][N:Estrelas]
  "comentario" VARCHAR(255) DEFAULT NULL,-- Comentário da avaliação[G:o][N:Comentário]
  "data_avaliacao" DATETIME NOT NULL,-- Data da avaliação[G:a][N:Data da avaliação]
  CONSTRAINT "FK_avaliacoes_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_avaliacoes_metrica_id"
    FOREIGN KEY("metrica_id")
    REFERENCES "metricas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_avaliacoes_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_avaliacoes_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "avaliacoes.FK_avaliacoes_cliente_id_idx" ON "avaliacoes" ("cliente_id");
CREATE INDEX "avaliacoes.FK_avaliacoes_metrica_id_idx" ON "avaliacoes" ("metrica_id");
CREATE INDEX "avaliacoes.FK_avaliacoes_pedido_id_idx" ON "avaliacoes" ("pedido_id");
CREATE INDEX "avaliacoes.FK_avaliacoes_produto_id_idx" ON "avaliacoes" ("produto_id");
CREATE TABLE "promocoes"(
--   Informa se há descontos nos produtos em determinados dias da semana, o preço pode subir ou descer e ser agendado para ser aplicado[N:Promoção|Promoções][G:a][K:App\Models|Models\][H:Model][L:null][ID:64]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da promoção[G:o]
  "promocao_id" INTEGER DEFAULT NULL,-- Promoção que originou os pontos do cliente/pedido, se informado a promoção será o resgate e somente pontos gerados por ela poderão ser usados[G:a][N:Promoção geradora]
  "categoria_id" INTEGER DEFAULT NULL,-- Permite fazer promoção para qualquer produto dessa categoria[G:a][N:Categoria]
  "produto_id" INTEGER DEFAULT NULL,-- Informa qual o produto participará da promoção de desconto ou terá acréscimo[N:Produto][G:o][S]
  "servico_id" INTEGER DEFAULT NULL,-- Informa se essa promoção será aplicada nesse serviço[G:o][N:Serviço]
  "bairro_id" INTEGER DEFAULT NULL,-- Bairro que essa promoção se aplica, somente serviços[G:o][N:Bairro]
  "zona_id" INTEGER DEFAULT NULL,-- Zona que essa promoção se aplica, somente serviços[G:o][N:Zona]
  "integracao_id" INTEGER DEFAULT NULL,-- Permite alterar o preço do produto para cada integração[G:a][N:Integração]
  "local" TEXT CHECK("local" IN('local', 'mesa', 'comanda', 'balcao', 'entrega', 'online')) DEFAULT NULL,-- Local onde o preço será aplicado[N:Local][G:o][E:Venda local|Mesa|Comanda|Balcão|Entrega|Delivery online][F:self::LOCAL_LOCAL]
  "inicio" INTEGER NOT NULL,-- Momento inicial da semana em minutos que o produto começa a sofrer alteração de preço, em evento será o unix timestamp[N:Momento inicial][G:o]
  "fim" INTEGER NOT NULL,-- Momento final da semana em minutos que o produto volta ao preço normal, em evento será o unix timestamp[N:Momento final][G:o]
  "valor" DECIMAL NOT NULL,-- Acréscimo ou desconto aplicado ao produto ou serviço[N:Valor][G:o]
  "pontos" INTEGER NOT NULL DEFAULT 0,-- Informa quantos pontos será ganho (Positivo) ou descontado (Negativo) na compra desse produto[G:o][N:Pontos][F:0]
  "parcial" TEXT NOT NULL CHECK("parcial" IN('Y', 'N')) DEFAULT 'N',-- Informa se o resgate dos produtos podem ser feitos de forma parcial[G:o][N:Resgate parcial][F:false]
  "proibir" TEXT NOT NULL CHECK("proibir" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve proibir a venda desse produto no período informado[N:Proibir a venda][G:a][F:false]
  "evento" TEXT NOT NULL CHECK("evento" IN('Y', 'N')) DEFAULT 'N',-- Informa se a promoção será aplicada apenas no intervalo de data informado[G:o][N:Evento][F:false]
  "agendamento" TEXT NOT NULL CHECK("agendamento" IN('Y', 'N')) DEFAULT 'N',-- Informa se essa promoção é um agendamento de preço, na data inicial o preço será aplicado, assim como a visibilidade do produto ou serviço será ativada ou desativada de acordo com o proibir[G:o][N:Agendamento][F:false]
  "limitar_vendas" TEXT NOT NULL CHECK("limitar_vendas" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve limitar a quantidade de vendas dessa categoria, produto ou serviço[G:o][N:Limitar vendas][F:false]
  "funcao_vendas" TEXT NOT NULL CHECK("funcao_vendas" IN('menor', 'igual', 'maior')) DEFAULT 'maior',-- Informa a regra para decidir se ainda pode vender com essa promoção[G:a][N:Função de limite por vendas][E:Menor|Igual|Maior][F:self::FUNCAO_VENDAS_MAIOR]
  "vendas_limite" INTEGER NOT NULL DEFAULT 0,-- Quantidade de vendas que essa promoção será programada[G:a][N:Limite de vendas][F:0]
  "limitar_cliente" TEXT NOT NULL CHECK("limitar_cliente" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve limitar a venda desse produto por cliente[G:o][N:Limitar por cliente][F:false]
  "funcao_cliente" TEXT NOT NULL CHECK("funcao_cliente" IN('menor', 'igual', 'maior')) DEFAULT 'maior',-- Informa a regra para decidir se o cliente consegue comprar mais nessa promoção[G:a][N:Função de limite por cliente][E:Menor|Igual|Maior][F:self::FUNCAO_CLIENTE_MAIOR]
  "cliente_limite" DECIMAL NOT NULL DEFAULT 0,-- Quantidade de compras que o cliente será limitado a comprar[G:a][N:Limite de compras por cliente][F:0]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a promoção está ativa[G:a][N:Ativa][F:true]
  "chamada" VARCHAR(200) DEFAULT NULL,-- Chamada para a promoção[G:a][N:Chamada]
  "banner_url" VARCHAR(100) DEFAULT NULL,-- Imagem promocional[N:Banner][G:o][I:512x256|promocao|promocao.png]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que a promoção foi arquivada[G:a][N:Data de arquivamento]
  CONSTRAINT "FK_promocoes_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_servico_id"
    FOREIGN KEY("servico_id")
    REFERENCES "servicos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_bairro_id"
    FOREIGN KEY("bairro_id")
    REFERENCES "bairros"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_zona_id"
    FOREIGN KEY("zona_id")
    REFERENCES "zonas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_integracao_id"
    FOREIGN KEY("integracao_id")
    REFERENCES "integracoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_categoria_id"
    FOREIGN KEY("categoria_id")
    REFERENCES "categorias"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_promocoes_promocao_id"
    FOREIGN KEY("promocao_id")
    REFERENCES "promocoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "promocoes.FK_promocoes_produto_id_idx" ON "promocoes" ("produto_id");
CREATE INDEX "promocoes.FK_promocoes_servico_id_idx" ON "promocoes" ("servico_id");
CREATE INDEX "promocoes.FK_promocoes_bairro_id_idx" ON "promocoes" ("bairro_id");
CREATE INDEX "promocoes.FK_promocoes_zona_id_idx" ON "promocoes" ("zona_id");
CREATE INDEX "promocoes.FK_promocoes_integracao_id_idx" ON "promocoes" ("integracao_id");
CREATE INDEX "promocoes.FK_promocoes_categoria_id_idx" ON "promocoes" ("categoria_id");
CREATE INDEX "promocoes.FK_promocoes_promocao_id_idx" ON "promocoes" ("promocao_id");
CREATE TABLE "associacoes"(
--   Lista de pedidos que não foram integrados ainda e devem ser associados ao sistema[N:Associação|Associações][G:a][K:App\Models|Models\][H:Model][L:5][ID:2]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da associação no banco de dados[G:o]
  "integracao_id" INTEGER NOT NULL,-- Integração a qual essa associação de pedido deve ser realizada[G:a][N:Integração]
  "entrega_id" INTEGER,-- Entrega que foi realizada[N:Entrega][G:o]
  "codigo" VARCHAR(50) NOT NULL,-- Código curto do pedido vindo da plataforma[G:o][N:Código]
  "cliente" VARCHAR(255) NOT NULL,-- Nome do cliente que fez o pedido[G:o][N:Cliente]
  "chave" VARCHAR(100) NOT NULL,-- Dado chave do cliente, esperado telefone, e-mail ou CPF[G:a][N:Chave]
  "pedido" TEXT NOT NULL,-- Pedido no formato JSON para exibição na lista de pedidos e posterior integração[G:o][N:Pedido]
  "endereco" VARCHAR(255),-- Endereço para ser entregue o pedido, nulo para o cliente vir buscar no restaurante[G:o][N:Endereço do cliente]
  "quantidade" DOUBLE NOT NULL,-- Quantidade de produtos no pedido[G:a][N:Quantidade de produtos]
  "servicos" DECIMAL NOT NULL,-- Total dos serviços, geralmente só taxa de entrega[G:o][N:Serviços]
  "produtos" DECIMAL NOT NULL,-- Total dos produtos[G:o][N:Produtos]
  "descontos" DECIMAL NOT NULL,-- Total dos descontos[G:o][N:Descontos]
  "pago" DECIMAL NOT NULL,-- Total que foi pago incluindo o troco[G:o][N:Pago]
  "status" TEXT NOT NULL CHECK("status" IN('agendado', 'aberto', 'entrega', 'concluido', 'cancelado')),-- Status do pedido que não foi integrado ainda[G:o][N:Status][F:self::STATUS_AGENDADO]
  "motivo" VARCHAR(200),-- Informa o motivo do cancelamento[G:o][N:Motivo]
  "mensagem" VARCHAR(255),-- Mensagem de erro que foi gerada ao tentar integrar automaticamente[G:a][N:Mensagem]
  "sincronizado" TEXT NOT NULL CHECK("sincronizado" IN('Y', 'N')),-- Informa se a associação já foi sincronizada com a plataforma[G:o][N:Sincronizado]
  "integrado" TEXT NOT NULL CHECK("integrado" IN('Y', 'N')),-- Informa se a associação já foi integrada no sistema[G:o][N:Integrado]
  "data_confirmacao" DATETIME,-- Data e hora que o pedido foi confirmado e impresso na produção[G:a][N:Data de confirmação]
  "data_pedido" DATETIME NOT NULL,-- Data e hora que o pedido foi criado na plataforma que o gerou[G:a][N:Data do pedido]
  CONSTRAINT "FK_associacoes_integracao_id"
    FOREIGN KEY("integracao_id")
    REFERENCES "integracoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_associacoes_entrega_id"
    FOREIGN KEY("entrega_id")
    REFERENCES "viagens"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "associacoes.FK_associacoes_integracao_id_idx" ON "associacoes" ("integracao_id");
CREATE INDEX "associacoes.FK_associacoes_entrega_id_idx" ON "associacoes" ("entrega_id");
CREATE TABLE "grupos"(
--   Grupos de pacotes, permite criar grupos como Tamanho, Sabores para formações de produtos[N:Grupo|Grupos][G:o][K:App\Models|Models\][H:Model][L:null][ID:37]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do grupo[G:o]
  "produto_id" INTEGER NOT NULL,-- Informa o pacote base da formação[N:Pacote][G:o][S:S]
  "nome" VARCHAR(100) NOT NULL,-- Nome resumido do grupo da formação, Exemplo: Tamanho, Sabores[N:Nome][G:o][S:S]
  "descricao" VARCHAR(100) NOT NULL,-- Descrição do grupo da formação, Exemplo: Escolha o tamanho, Escolha os sabores[N:Descrição][G:a][S]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('inteiro', 'fracionado')) DEFAULT 'inteiro',-- Informa se a formação final será apenas uma unidade ou vários itens[N:Tipo][G:o][F:self::TIPO_INTEIRO]
  "quantidade_minima" INTEGER NOT NULL DEFAULT 1,-- Permite definir uma quantidade mínima obrigatória para continuar com a venda[N:Quantidade mínima][G:a][F:1]
  "quantidade_maxima" INTEGER NOT NULL DEFAULT 0,-- Define a quantidade máxima de itens que podem ser escolhidos[N:Quantidade máxima][G:a][F:0]
  "funcao" TEXT NOT NULL CHECK("funcao" IN('minimo', 'media', 'maximo', 'soma')) DEFAULT 'soma',-- Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor preço, Média:  define o preço do produto como a média dos itens selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma: Soma todos os preços dos produtos selecionados[N:Função de preço][G:a][E:Mínimo|Média|Máximo|Soma][F:self::FUNCAO_SOMA]
  "ordem" INTEGER NOT NULL DEFAULT 0,-- Informa a ordem de exibição dos grupos[G:a][N:Ordem][F:0]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que o grupo foi arquivado e não será mais usado[G:a][N:Data de arquivação]
  CONSTRAINT "FK_grupos_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "grupos.FK_grupos_produto_id_idx" ON "grupos" ("produto_id");
CREATE TABLE "estoques"(
--   Estoque de produtos por setor[N:Estoque|Estoques][G:o][K:App\Models|Models\][H:Model][L:null][ID:30]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da entrada no estoque[G:o]
  "produto_id" INTEGER NOT NULL,-- Produto que entrou no estoque[N:Produto][G:o][S][S:S]
  "requisito_id" INTEGER DEFAULT NULL,-- Informa de qual compra originou essa entrada em estoque[G:o][N:Requisição de Compra]
  "transacao_id" INTEGER DEFAULT NULL,-- Identificador do item que gerou a saída desse produto do estoque[N:Transação][G:a][S:S]
  "fornecedor_id" INTEGER DEFAULT NULL,-- Fornecedor do produto[N:Fornecedor][G:o][S:S]
  "setor_id" INTEGER NOT NULL,-- Setor de onde o produto foi inserido ou retirado[N:Setor][G:o]
  "prestador_id" INTEGER DEFAULT NULL,-- Prestador que inseriu/retirou o produto do estoque[N:Prestador][G:o][S:S]
  "quantidade" DOUBLE NOT NULL,-- Quantidade do mesmo produto inserido no estoque[N:Quantidade][G:a]
  "preco_compra" DECIMAL NOT NULL DEFAULT 0,-- Preço de compra do produto[N:Preço de compra][G:o][F:0]
  "lote" VARCHAR(45) DEFAULT NULL,-- Lote de produção do produto comprado[N:Lote][G:o]
  "fabricacao" DATETIME DEFAULT NULL,-- Data de fabricação do produto[N:Data de fabricação][G:a]
  "vencimento" DATETIME DEFAULT NULL,-- Data de vencimento do produto[N:Data de vencimento][G:a]
  "detalhes" VARCHAR(100) DEFAULT NULL,-- Detalhes da inserção ou retirada do estoque[N:Detalhes][G:o]
  "reservado" TEXT NOT NULL CHECK("reservado" IN('Y', 'N')) DEFAULT 'N',-- Informa se os produtos foram retirados do estoque ou se estão apenas reservados[G:o][N:Reservado][F:false]
  "cancelado" TEXT NOT NULL CHECK("cancelado" IN('Y', 'N')) DEFAULT 'N',-- Informa a entrada ou saída do estoque foi cancelada[N:Cancelado][G:o][F:false]
  "data_movimento" DATETIME NOT NULL,-- Data de entrada ou saída do produto do estoque[N:Data de movimento][G:a][D]
  CONSTRAINT "FK_estoques_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_estoques_transacao_id"
    FOREIGN KEY("transacao_id")
    REFERENCES "itens"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_estoques_fornecedor_id"
    FOREIGN KEY("fornecedor_id")
    REFERENCES "fornecedores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_estoques_prestador_id"
    FOREIGN KEY("prestador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_estoques_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_estoques_requisito_id"
    FOREIGN KEY("requisito_id")
    REFERENCES "requisitos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "estoques.FK_estoques_produto_id_idx" ON "estoques" ("produto_id");
CREATE INDEX "estoques.FK_estoques_transacao_id_idx" ON "estoques" ("transacao_id");
CREATE INDEX "estoques.FK_estoques_fornecedor_id_idx" ON "estoques" ("fornecedor_id");
CREATE INDEX "estoques.FK_estoques_prestador_id_idx" ON "estoques" ("prestador_id");
CREATE INDEX "estoques.FK_estoques_setor_id_idx" ON "estoques" ("setor_id");
CREATE INDEX "estoques.FK_estoques_requisito_id_idx" ON "estoques" ("requisito_id");
CREATE INDEX "estoques.data_movimento_INDEX" ON "estoques" ("data_movimento");
CREATE TABLE "cidades"(
--   Cidade de um estado, contém bairros[N:Cidade|Cidades][G:a][K:App\Models|Models\][H:Model][L:null][ID:14]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código que identifica a cidade[G:o]
  "estado_id" INTEGER NOT NULL,-- Informa a qual estado a cidade pertence[N:Estado][G:o]
  "nome" VARCHAR(100) NOT NULL,-- Nome da cidade, é único para cada estado[S][N:Nome][G:o]
  "cep" VARCHAR(8) DEFAULT NULL,-- Código dos correios para identificação da cidade[M:99999-999][G:o][N:CEP]
  CONSTRAINT "estado_id_nome_UNIQUE"
    UNIQUE("estado_id","nome"),
  CONSTRAINT "cep_UNIQUE"
    UNIQUE("cep"),
  CONSTRAINT "FK_cidades_estado_id"
    FOREIGN KEY("estado_id")
    REFERENCES "estados"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "requisitos"(
--   Informa os produtos da lista de compras[N:Produtos da lista|Produtos das listas][G:o][K:App\Models|Models\][H:Model][L:null][ID:67]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do produto da lista[G:o]
  "lista_id" INTEGER NOT NULL,-- Lista de compra desse produto[N:Lista de compra][G:a]
  "produto_id" INTEGER NOT NULL,-- Produto que deve ser comprado[N:Produto][G:o][S]
  "compra_id" INTEGER DEFAULT NULL,-- Informa em qual fornecedor foi realizado a compra desse produto[G:a][N:Compra]
  "fornecedor_id" INTEGER DEFAULT NULL,-- Fornecedor em que deve ser consultado ou realizado as compras dos produtos, pode ser alterado no momento da compra[N:Fornecedor][G:o]
  "quantidade" DOUBLE NOT NULL DEFAULT 0,-- Quantidade de produtos que deve ser comprado[N:Quantidade][G:a][F:0]
  "comprado" DOUBLE NOT NULL DEFAULT 0,-- Informa quantos produtos já foram comprados[N:Comprado][G:o][F:0]
  "preco_maximo" DECIMAL NOT NULL DEFAULT 0,-- Preço máximo que deve ser pago na compra desse produto[N:Preço máximo][G:o][F:0]
  "preco" DECIMAL NOT NULL DEFAULT 0,-- Preço em que o produto foi comprado da última vez ou o novo preço[N:Preço][G:o][F:0]
  "observacoes" VARCHAR(100) DEFAULT NULL,-- Detalhes na compra desse produto[N:Observações][G:a]
  "data_recolhimento" DATETIME DEFAULT NULL,-- Informa o momento do recolhimento da mercadoria na pratileira[N:Data de recolhimento][G:a]
  CONSTRAINT "FK_requisitos_lista_id"
    FOREIGN KEY("lista_id")
    REFERENCES "listas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_requisitos_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_requisitos_fornecedor_id"
    FOREIGN KEY("fornecedor_id")
    REFERENCES "fornecedores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_requisitos_compra_id"
    FOREIGN KEY("compra_id")
    REFERENCES "compras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "requisitos.FK_requisitos_lista_id_idx" ON "requisitos" ("lista_id");
CREATE INDEX "requisitos.FK_requisitos_produto_id_idx" ON "requisitos" ("produto_id");
CREATE INDEX "requisitos.FK_requisitos_fornecedor_id_idx" ON "requisitos" ("fornecedor_id");
CREATE INDEX "requisitos.FK_requisitos_compra_id_idx" ON "requisitos" ("compra_id");
CREATE TABLE "cardapios"(
--   Cardápios para cada integração ou local de venda[G:o][N:Cardápio|Cardápios][K:App\Models|Models\][H:Model][L:null][ID:8]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cardápio[G:o]
  "produto_id" INTEGER DEFAULT NULL,-- Produto que faz parte desse cardápio[G:o][N:Produto]
  "composicao_id" INTEGER DEFAULT NULL,-- Composição que faz parte desse cardápio[G:a][N:Composição]
  "pacote_id" INTEGER DEFAULT NULL,-- Pacote que faz parte desse cardápio[G:o][N:Pacote]
  "cliente_id" INTEGER DEFAULT NULL,-- Permite exibir um cardápio diferenciado somente para esse cliente[G:o][N:Cliente]
  "integracao_id" INTEGER DEFAULT NULL,-- Permite exibir o cardápio somente nessa integração[G:a][N:Integração]
  "local" TEXT CHECK("local" IN('local', 'mesa', 'comanda', 'balcao', 'entrega', 'online')) DEFAULT NULL,-- O cardápio será exibido para vendas nesse local[N:Local][G:o][E:Venda local|Mesa|Comanda|Balcão|Entrega|Delivery online][F:self::LOCAL_LOCAL]
  "acrescimo" DECIMAL NOT NULL DEFAULT 0,-- Acréscimo ao preço de venda do produto nesse cardápio[N:Acréscimo][G:o][F:0]
  "disponivel" TEXT NOT NULL CHECK("disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o produto estará disponível para venda nesse cardápio[N:Disponível][G:a][F:true]
  CONSTRAINT "FK_cardapios_integracao_id"
    FOREIGN KEY("integracao_id")
    REFERENCES "integracoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cardapios_produto_id"
    FOREIGN KEY("produto_id")
    REFERENCES "produtos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cardapios_composicao_id"
    FOREIGN KEY("composicao_id")
    REFERENCES "composicoes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cardapios_pacote_id"
    FOREIGN KEY("pacote_id")
    REFERENCES "pacotes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cardapios_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "cardapios.item_destino_UNIQUE" ON "cardapios" ("produto_id","composicao_id","pacote_id","cliente_id","integracao_id","local");
CREATE INDEX "cardapios.FK_cardapios_integracao_id_idx" ON "cardapios" ("integracao_id");
CREATE INDEX "cardapios.FK_cardapios_composicao_id_idx" ON "cardapios" ("composicao_id");
CREATE INDEX "cardapios.FK_cardapios_pacote_id_idx" ON "cardapios" ("pacote_id");
CREATE INDEX "cardapios.FK_cardapios_cliente_id_idx" ON "cardapios" ("cliente_id");
CREATE TABLE "propriedades"(
--   Informa tamanhos de pizzas e opções de peso do produto[N:Propriedade|Propriedades][G:a][K:App\Models|Models\][H:Model][L:null][ID:65]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da propriedade[G:o]
  "grupo_id" INTEGER NOT NULL,-- Grupo que possui essa propriedade como item de um pacote[N:Grupo][G:o][S:S]
  "nome" VARCHAR(100) NOT NULL,-- Nome da propriedade, Ex.: Grande, Pequena[N:Nome][G:o][S]
  "abreviacao" VARCHAR(100) DEFAULT NULL,-- Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena, essa abreviação fará parte do nome do produto[N:Abreviação][G:a]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Imagem que representa a propriedade[N:Imagem][G:a][I:256x256|propriedade|propriedade.png]
  "data_atualizacao" DATETIME DEFAULT NULL,-- Data de atualização dos dados ou da imagem da propriedade[N:Data de atualização][G:a]
  CONSTRAINT "grupo_id_nome_UNIQUE"
    UNIQUE("grupo_id","nome"),
  CONSTRAINT "FK_propriedades_grupo_id"
    FOREIGN KEY("grupo_id")
    REFERENCES "grupos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "pagamentos"(
--   Pagamentos de contas e pedidos[N:Pagamento|Pagamentos][G:o][K:App\Models|Models\][H:Model][L:null][ID:56]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do pagamento[G:o]
  "carteira_id" INTEGER NOT NULL,-- Carteira de destino do valor[N:Carteira][G:a]
  "moeda_id" INTEGER NOT NULL,-- Informa em qual moeda está o valor informado[G:a][N:Moeda]
  "pagamento_id" INTEGER DEFAULT NULL,-- Informa o pagamento principal ou primeira parcela, o valor lançado é zero para os pagamentos filhos, restante de antecipação e taxas são filhos do valor antecipado[G:o][N:Pagamento]
  "agrupamento_id" INTEGER DEFAULT NULL,-- Permite antecipar recebimentos de cartões, um pagamento agrupado é internamente tratado como desativado[G:o][N:Agrupamento]
  "movimentacao_id" INTEGER DEFAULT NULL,-- Movimentação do caixa quando for pagamento de pedido ou quando a conta for paga do caixa[N:Movimentação][G:a][S:S]
  "funcionario_id" INTEGER DEFAULT NULL,-- Funcionário que lançou o pagamento no sistema[N:Funcionário][G:o][S:S]
  "forma_id" INTEGER DEFAULT NULL,-- Forma da pagamento do pedido[N:Forma de pagamento][G:a][S]
  "pedido_id" INTEGER DEFAULT NULL,-- Pedido que foi pago[N:Pedido][G:o][S:S]
  "conta_id" INTEGER DEFAULT NULL,-- Conta que foi paga/recebida[N:Conta][G:a][S:S]
  "cartao_id" INTEGER DEFAULT NULL,-- Cartão em que foi pago, para forma de pagamento em cartão[N:Cartão][G:o]
  "cheque_id" INTEGER DEFAULT NULL,-- Cheque em que foi pago[N:Cheque][G:o][S:S]
  "crediario_id" INTEGER DEFAULT NULL,-- Conta que foi utilizada como pagamento do pedido[N:Conta pedido][G:a][S:S]
  "credito_id" INTEGER DEFAULT NULL,-- Crédito que foi utilizado para pagar o pedido[N:Crédito][G:o][S:S]
  "valor" DECIMAL NOT NULL,-- Valor pago ou recebido na moeda informada no momento do recebimento[N:Valor][G:o]
  "numero_parcela" INTEGER NOT NULL DEFAULT 1,-- Informa qual o número da parcela para este pagamento[G:o][N:Número da parcela][F:1]
  "parcelas" INTEGER NOT NULL DEFAULT 1,-- Quantidade de parcelas desse pagamento[G:a][N:Parcelas][F:1]
  "lancado" DECIMAL NOT NULL,-- Valor lançado para pagamento do pedido ou conta na moeda local do país[N:Lancado][G:o]
  "codigo" VARCHAR(100) DEFAULT NULL,-- Código do pagamento, usado em transações online[G:o][N:Código]
  "detalhes" VARCHAR(200) DEFAULT NULL,-- Detalhes do pagamento[N:Detalhes][G:o]
  "estado" TEXT NOT NULL CHECK("estado" IN('aberto', 'aguardando', 'analise', 'pago', 'disputa', 'devolvido', 'cancelado')) DEFAULT 'aberto',-- Informa qual o andamento do processo de pagamento[N:Estado][G:o][F:self::ESTADO_ABERTO][E:Aberto|Aguardando pagamento|Pago|Em disputa|Devolvido|Cancelado][F:self::ESTADO_ABERTO]
  "data_pagamento" DATETIME DEFAULT NULL,-- Data de pagamento[N:Data de pagamento][G:a]
  "data_compensacao" DATETIME DEFAULT NULL,-- Data de compensação do pagamento[N:Data de compensação][G:a]
  "data_lancamento" DATETIME NOT NULL,-- Data e hora do lançamento do pagamento[N:Data de lançamento][G:a]
  CONSTRAINT "FK_pagamentos_funcionario_id"
    FOREIGN KEY("funcionario_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_forma_id"
    FOREIGN KEY("forma_id")
    REFERENCES "formas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_cartao_id"
    FOREIGN KEY("cartao_id")
    REFERENCES "cartoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_crediario_id"
    FOREIGN KEY("crediario_id")
    REFERENCES "contas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_conta_id"
    FOREIGN KEY("conta_id")
    REFERENCES "contas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_movimentacao_id"
    FOREIGN KEY("movimentacao_id")
    REFERENCES "movimentacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_credito_id"
    FOREIGN KEY("credito_id")
    REFERENCES "creditos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_cheque_id"
    FOREIGN KEY("cheque_id")
    REFERENCES "cheques"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_pagamento_id"
    FOREIGN KEY("pagamento_id")
    REFERENCES "pagamentos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_moeda_id"
    FOREIGN KEY("moeda_id")
    REFERENCES "moedas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pagamentos_agrupamento_id"
    FOREIGN KEY("agrupamento_id")
    REFERENCES "pagamentos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "pagamentos.FK_pagamentos_funcionario_id_idx" ON "pagamentos" ("funcionario_id");
CREATE INDEX "pagamentos.FK_pagamentos_forma_id_idx" ON "pagamentos" ("forma_id");
CREATE INDEX "pagamentos.FK_pagamentos_pedido_id_idx" ON "pagamentos" ("pedido_id");
CREATE INDEX "pagamentos.FK_pagamentos_cartao_id_idx" ON "pagamentos" ("cartao_id");
CREATE INDEX "pagamentos.FK_pagamentos_crediario_id_idx" ON "pagamentos" ("crediario_id");
CREATE INDEX "pagamentos.FK_pagamentos_conta_id_idx" ON "pagamentos" ("conta_id");
CREATE INDEX "pagamentos.FK_pagamentos_movimentacao_id_idx" ON "pagamentos" ("movimentacao_id");
CREATE INDEX "pagamentos.FK_pagamentos_credito_id_idx" ON "pagamentos" ("credito_id");
CREATE INDEX "pagamentos.FK_pagamentos_carteira_id_idx" ON "pagamentos" ("carteira_id");
CREATE INDEX "pagamentos.FK_pagamentos_cheque_id_idx" ON "pagamentos" ("cheque_id");
CREATE INDEX "pagamentos.FK_pagamentos_pagamento_id_idx" ON "pagamentos" ("pagamento_id");
CREATE INDEX "pagamentos.FK_pagamentos_moeda_id_idx" ON "pagamentos" ("moeda_id");
CREATE INDEX "pagamentos.FK_pagamentos_agrupamento_id_idx" ON "pagamentos" ("agrupamento_id");
CREATE INDEX "pagamentos.data_compensacao_INDEX" ON "pagamentos" ("data_compensacao" DESC);
CREATE INDEX "pagamentos.data_lancamento_INDEX" ON "pagamentos" ("data_lancamento" DESC);
CREATE INDEX "pagamentos.data_pagamento_INDEX" ON "pagamentos" ("data_pagamento" DESC);
CREATE TABLE "cupons"(
--   Informa os cupons de descontos e seus usos[N:Cupom|Cupons][G:o][K:App\Models|Models\][H:Model][L:null][ID:24]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cupom[G:o]
  "cupom_id" INTEGER DEFAULT NULL,-- Informa de qual cupom foi usado[G:o][N:Cupom]
  "pedido_id" INTEGER DEFAULT NULL,-- Informa qual pedido usou este cupom[G:o][N:Pedido]
  "cliente_id" INTEGER DEFAULT NULL,-- Informa o cliente que possui e pode usar esse cupom[G:o][N:Cliente]
  "codigo" VARCHAR(20) NOT NULL,-- Código para uso do cupom[G:o][N:Código]
  "quantidade" INTEGER NOT NULL,-- Quantidade de cupons disponíveis ou usados[G:a][N:Quantidade]
  "tipo_desconto" TEXT NOT NULL CHECK("tipo_desconto" IN('valor', 'porcentagem')),-- Informa se o desconto será por valor ou porcentagem[G:o][N:Tipo de desconto][E:Valor|Porcentagem]
  "valor" DECIMAL NOT NULL DEFAULT 0,-- Valor do desconto que será aplicado no pedido[G:o][N:Valor do desconto][F:0]
  "porcentagem" DOUBLE NOT NULL DEFAULT 0,-- Porcentagem de desconto do pedido[G:a][N:Porcentagem][F:0]
  "incluir_servicos" TEXT NOT NULL CHECK("incluir_servicos" IN('Y', 'N')),-- Informa se o cupom também se aplica nos serviços[G:o][N:Contemplar serviços]
  "limitar_pedidos" TEXT NOT NULL CHECK("limitar_pedidos" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve limitar o cupom pela quantidade de pedidos válidos do cliente[G:o][N:Limitar por pedidos][F:false]
  "funcao_pedidos" TEXT NOT NULL CHECK("funcao_pedidos" IN('menor', 'igual', 'maior')) DEFAULT 'maior',-- Informa a regra para decidir se a quantidade de pedidos permite usar esse cupom[G:a][N:Função de limite por pedidos][E:Menor|Igual|Maior][F:self::FUNCAO_PEDIDOS_MAIOR]
  "pedidos_limite" INTEGER NOT NULL DEFAULT 0,-- Quantidade de pedidos válidos que permite usar esse cupom[G:a][N:Limite de pedidos][F:0]
  "limitar_valor" TEXT NOT NULL CHECK("limitar_valor" IN('Y', 'N')) DEFAULT 'N',-- Informa se deve limitar o uso do cupom pelo valor do pedido[G:o][N:Limitar pelo valor][F:false]
  "funcao_valor" TEXT NOT NULL CHECK("funcao_valor" IN('menor', 'igual', 'maior')) DEFAULT 'maior',-- Informa a regra para decidir se o valor do pedido permite usar esse cupom[G:a][N:Função de limite por valor][E:Menor|Igual|Maior][F:self::FUNCAO_VALOR_MAIOR]
  "valor_limite" DECIMAL NOT NULL DEFAULT 0,-- Valor do pedido com os serviços que permite usar esse cupom[G:a][N:Limite de valor][F:0]
  "validade" DATETIME NOT NULL,-- Validade do cupom[G:a][N:Validade]
  "data_registro" DATETIME NOT NULL,-- Data de registro do cupom ou do uso[G:a][N:Data de registro]
  CONSTRAINT "FK_cupons_cupom_id"
    FOREIGN KEY("cupom_id")
    REFERENCES "cupons"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cupons_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cupons_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "cupons.FK_cupons_cupom_id_idx" ON "cupons" ("cupom_id");
CREATE INDEX "cupons.FK_cupons_pedido_id_idx" ON "cupons" ("pedido_id");
CREATE INDEX "cupons.FK_cupons_cliente_id_idx" ON "cupons" ("cliente_id");
CREATE TABLE "juncoes"(
--   Junções de mesas, informa quais mesas estão juntas ao pedido[N:Junção|Junções][G:a][K:App\Models|Models\][H:Model][L:1][ID:43]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da junção[G:o]
  "mesa_id" INTEGER NOT NULL,-- Mesa que está junta ao pedido[N:Mesa][G:a][S]
  "pedido_id" INTEGER NOT NULL,-- Pedido a qual a mesa está junta, o pedido deve ser de uma mesa[N:Pedido][G:o][S:S]
  "estado" TEXT NOT NULL CHECK("estado" IN('associado', 'liberado', 'cancelado')) DEFAULT 'associado',-- Estado a junção da mesa. Associado: a mesa está junta ao pedido, Liberado: A mesa está livre, Cancelado: A mesa está liberada [N:Estado][G:o][F:self::ESTADO_ASSOCIADO]
  "data_movimento" DATETIME NOT NULL,-- Data e hora da junção das mesas[N:Data do movimento][G:a]
  CONSTRAINT "FK_juncoes_mesa_id"
    FOREIGN KEY("mesa_id")
    REFERENCES "mesas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_juncoes_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "juncoes.FK_juncoes_pedido_id_idx" ON "juncoes" ("pedido_id");
CREATE INDEX "juncoes.mesa_id_estado_INDEX" ON "juncoes" ("mesa_id","estado");
CREATE TABLE "formas"(
--   Formas de pagamento disponíveis para pedido e contas[N:Forma de pagamento|Formas de pagamento][G:a][K:App\Models|Models\][H:Model][L:null][ID:33]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da forma de pagamento[G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('dinheiro', 'credito', 'debito', 'vale', 'cheque', 'crediario', 'saldo')),-- Tipo de pagamento[N:Tipo][G:o][E:Dinheiro|Cartão de credito|Cartão de débito|Vale|Cheque|Crediário|Saldo][S:S]
  "carteira_id" INTEGER NOT NULL,-- Carteira que será usada para entrada de valores no caixa[N:Carteira de entrada][G:a]
  "descricao" VARCHAR(50) NOT NULL,-- Descrição da forma de pagamento[N:Descrição][G:a][S]
  "min_parcelas" INTEGER NOT NULL DEFAULT 1,-- Quantidade mínima de parcelas[N:Minimo de parcelas][G:a][F:1]
  "max_parcelas" INTEGER NOT NULL DEFAULT 1,-- Quantidade máxima de parcelas[N:Máximo de parcelas][G:o][F:1]
  "parcelas_sem_juros" INTEGER NOT NULL DEFAULT 1,-- Quantidade de parcelas em que não será cobrado juros[N:Parcelas sem juros][G:a][F:1]
  "juros" DOUBLE NOT NULL DEFAULT 0,-- Juros cobrado ao cliente no parcelamento[N:Juros][G:o][F:0]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se a forma de pagamento está ativa[N:Ativa][G:a][F:true]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao"),
  CONSTRAINT "FK_formas_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "formas.FK_formas_carteira_id_idx" ON "formas" ("carteira_id");
CREATE TABLE "eventos"(
--   Eventos de envio das notas[N:Evento|Eventos][G:o][K:App\Models|Models\][H:Model][L:7][ID:31]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do evento[G:o]
  "nota_id" INTEGER NOT NULL,-- Nota a qual o evento foi criado[G:a][N:Nota][S:S]
  "estado" TEXT NOT NULL CHECK("estado" IN('aberto', 'assinado', 'validado', 'pendente', 'processamento', 'denegado', 'cancelado', 'rejeitado', 'contingencia', 'inutilizado', 'autorizado')),-- Estado do evento[G:o][N:Estado][E:Aberto|Assinado|Validado|Pendente|Em processamento|Denegado|Cancelado|Rejeitado|Contingência|Inutilizado|Autorizado]
  "mensagem" TEXT NOT NULL,-- Mensagem do evento, descreve que aconteceu[G:a][N:Mensagem]
  "codigo" VARCHAR(20) NOT NULL,-- Código de status do evento, geralmente código de erro de uma exceção[G:o][N:Código]
  "data_criacao" DATETIME NOT NULL,-- Data de criação do evento[G:a][N:Data de criação]
  CONSTRAINT "FK_eventos_nota_id"
    FOREIGN KEY("nota_id")
    REFERENCES "notas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "eventos.FK_eventos_nota_id_idx" ON "eventos" ("nota_id");
CREATE TABLE "impressoras"(
--   Impressora para impressão de serviços e contas[N:Impressora|Impressoras][G:a][K:App\Models|Models\][H:Model][L:null][ID:40]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da impressora[G:o]
  "dispositivo_id" INTEGER NOT NULL,-- Dispositivo que contém a impressora[N:Dispositivo][G:o]
  "setor_id" INTEGER NOT NULL,-- Setor de impressão[N:Setor de impressão][G:o]
  "nome" VARCHAR(100) NOT NULL,-- Nome da impressora instalada no sistema operacional[N:Nome][G:o]
  "modelo" VARCHAR(45) NOT NULL,-- Informa qual conjunto de comandos deve ser utilizado[N:Driver][G:o]
  "modo" TEXT NOT NULL CHECK("modo" IN('terminal', 'caixa', 'servico', 'estoque')) DEFAULT 'terminal',-- Modo de impressão[N:Modo][G:o][E:Terminal|Caixa|Serviço|Estoque][F:self::MODO_TERMINAL]
  "opcoes" TEXT DEFAULT NULL,-- Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros[N:Opções][G:a]
  "colunas" INTEGER NOT NULL DEFAULT 48,-- Quantidade de colunas do cupom[N:Quantidade de colunas][G:a][F:48]
  "avanco" INTEGER NOT NULL DEFAULT 6,-- Quantidade de linhas para avanço do papel[N:Avanço de papel][G:o][F:6]
  CONSTRAINT "dispositivo_id_setor_id_modo_UNIQUE"
    UNIQUE("dispositivo_id","setor_id","modo"),
  CONSTRAINT "FK_impressoras_dispositivo_id"
    FOREIGN KEY("dispositivo_id")
    REFERENCES "dispositivos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_impressoras_setor_id"
    FOREIGN KEY("setor_id")
    REFERENCES "setores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "impressoras.FK_impressoras_dispositivo_id_idx" ON "impressoras" ("dispositivo_id");
CREATE TABLE "caixas"(
--   Caixas de movimentação financeira[N:Caixa|Caixas][G:o][K:App\Models|Models\][H:Model][L:null][ID:7]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do caixa[G:o]
  "carteira_id" INTEGER NOT NULL,-- Carteira que representa a gaveta de dinheiro do caixa[G:a][N:Carteira do caixa]
  "descricao" VARCHAR(50) NOT NULL,-- Descrição do caixa[N:Descrição][G:a][S]
  "serie" INTEGER NOT NULL DEFAULT 1,-- Série do caixa[N:Série][G:a][F:1]
  "numero_inicial" INTEGER NOT NULL DEFAULT 1,-- Número inicial na geração da nota, será usado quando maior que o último número utilizado[N:Número inicial][G:o][F:1]
  "ativa" TEXT NOT NULL CHECK("ativa" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o caixa está ativo[G:a][N:Ativo][F:true]
  "data_desativada" DATETIME DEFAULT NULL,-- Informa se o caixa está ativo[N:Data da desativação][G:o]
  CONSTRAINT "descricao_UNIQUE"
    UNIQUE("descricao"),
  CONSTRAINT "carteira_id_UNIQUE"
    UNIQUE("carteira_id"),
  CONSTRAINT "FK_caixas_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE TABLE "cartoes"(
--   Cartões utilizados na forma de pagamento em cartão[N:Cartão|Cartões][G:o][K:App\Models|Models\][H:Model][L:null][ID:10]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do cartão[G:o]
  "forma_id" INTEGER NOT NULL,-- Forma de pagamento associada à esse cartão ou vale[G:a][N:Forma de pagamento]
  "carteira_id" INTEGER DEFAULT NULL,-- Carteira de entrada de valores no caixa[N:Carteira de entrada][G:a]
  "bandeira" VARCHAR(50) NOT NULL,-- Nome da bandeira do cartão[N:Bandeira][G:a][S]
  "taxa" DOUBLE NOT NULL DEFAULT 0,-- Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a 100[N:Taxa][G:a][F:0]
  "dias_repasse" INTEGER NOT NULL CHECK("dias_repasse">=0) DEFAULT 30,-- Quantidade de dias para repasse do valor[N:Dias para repasse][G:o][F:30]
  "taxa_antecipacao" DOUBLE NOT NULL DEFAULT 0,-- Taxa em porcentagem para antecipação de recebimento de parcelas[N:Taxa de antecipação][G:a][F:0]
  "imagem_url" VARCHAR(100) DEFAULT NULL,-- Imagem do cartão[N:Imagem][G:a][I:256x256|cartao|cartao.png]
  "ativo" TEXT NOT NULL CHECK("ativo" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o cartão está ativo[N:Ativo][G:o][F:true]
  CONSTRAINT "forma_id_bandeira_UNIQUE"
    UNIQUE("forma_id","bandeira"),
  CONSTRAINT "FK_cartoes_carteira_id"
    FOREIGN KEY("carteira_id")
    REFERENCES "carteiras"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_cartoes_forma_id"
    FOREIGN KEY("forma_id")
    REFERENCES "formas"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "cartoes.FK_cartoes_carteira_id_idx" ON "cartoes" ("carteira_id");
CREATE TABLE "bairros"(
--   Bairro de uma cidade[N:Bairro|Bairros][G:o][K:App\Models|Models\][H:Model][L:null][ID:5]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do bairro[G:o]
  "cidade_id" INTEGER NOT NULL,-- Cidade a qual o bairro pertence[N:Cidade][G:a][S:S]
  "nome" VARCHAR(100) NOT NULL,-- Nome do bairro[N:Nome][G:o][S]
  "valor_entrega" DECIMAL NOT NULL,-- Valor cobrado para entregar um pedido nesse bairro[N:Valor da entrega][G:o]
  "disponivel" TEXT NOT NULL CHECK("disponivel" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o bairro está disponível para entrega de pedidos[N:Disponível][G:o][F:true]
  "mapeado" TEXT NOT NULL CHECK("mapeado" IN('Y', 'N')) DEFAULT 'N',-- Informa se o bairro está mapeado por zonas e se é obrigatório selecionar uma zona[G:o][N:Mapeado][F:false]
  "entrega_minima" INTEGER DEFAULT NULL,-- Tempo mínimo de entrega para esse bairro, sobrescreve o tempo por dia[N:Tempo mínimo de entrega][G:o]
  "entrega_maxima" INTEGER DEFAULT NULL,-- Tempo máximo de entrega para esse bairro, sobrescreve o tempo por dia[N:Tempo máximo de entrega][G:o]
  CONSTRAINT "cidade_id_nome_UNIQUE"
    UNIQUE("cidade_id","nome"),
  CONSTRAINT "FK_bairros_cidade_id"
    FOREIGN KEY("cidade_id")
    REFERENCES "cidades"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE "formacoes"(
--   Informa qual foi a formação que gerou esse produto, assim como quais item foram retirados/adicionados da composição[N:Formação|Formações][G:a][K:App\Models|Models\][H:Model][L:null][ID:32]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da formação[G:o]
  "item_id" INTEGER NOT NULL,-- Informa qual foi o produto vendido para essa formação[N:Item do pedido][G:o][S:S]
  "pacote_id" INTEGER DEFAULT NULL,-- Informa qual pacote foi selecionado no momento da venda[N:Pacote][G:o][S]
  "composicao_id" INTEGER DEFAULT NULL,-- Informa qual composição foi retirada ou adicionada no momento da venda[N:Composição][G:a]
  "quantidade" DOUBLE NOT NULL DEFAULT 1,-- Quantidade de itens selecionados[N:Quantidade][G:a][F:1]
  CONSTRAINT "item_id_pacote_id_composicao_id_UNIQUE"
    UNIQUE("item_id","pacote_id","composicao_id"),
  CONSTRAINT "FK_formacoes_item_id"
    FOREIGN KEY("item_id")
    REFERENCES "itens"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_formacoes_pacote_id"
    FOREIGN KEY("pacote_id")
    REFERENCES "pacotes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_formacoes_composicao_id"
    FOREIGN KEY("composicao_id")
    REFERENCES "composicoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "formacoes.FK_formacoes_pacote_id_idx" ON "formacoes" ("pacote_id");
CREATE INDEX "formacoes.FK_formacoes_composicao_id_idx" ON "formacoes" ("composicao_id");
CREATE TABLE "notas"(
--   Notas fiscais e inutilizações[N:Nota|Notas][G:a][K:App\Models|Models\][H:Model][L:7][ID:51]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da nota[G:o]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('nota', 'inutilizacao')),-- Tipo de registro se nota ou inutilização[G:o][E:Nota|Inutilização][N:Tipo]
  "ambiente" TEXT NOT NULL CHECK("ambiente" IN('homologacao', 'producao')),-- Ambiente em que a nota foi gerada[G:o][N:Ambiente][E:Homologação|Produção]
  "acao" TEXT NOT NULL CHECK("acao" IN('autorizar', 'cancelar', 'inutilizar')),-- Ação que deve ser tomada sobre a nota fiscal[N:Ação][E:Autorizar|Cancelar|Inutilizar]
  "estado" TEXT NOT NULL CHECK("estado" IN('aberto', 'assinado', 'pendente', 'processamento', 'denegado', 'rejeitado', 'cancelado', 'inutilizado', 'autorizado')),-- Estado da nota[G:o][N:Estado][E:Aberto|Assinado|Pendente|Em processamento|Denegado|Rejeitado|Cancelado|Inutilizado|Autorizado]
  "ultimo_evento_id" INTEGER DEFAULT NULL,-- Último evento da nota[G:o][N:Último evento]
  "serie" INTEGER NOT NULL,-- Série da nota[G:a][N:Série]
  "numero_inicial" INTEGER NOT NULL,-- Número inicial da nota[G:o][N:Número]
  "numero_final" INTEGER NOT NULL,-- Número final da nota, igual ao número inicial quando for a nota de um pedido[G:o][N:Número final]
  "sequencia" INTEGER NOT NULL,-- Permite iniciar o número da nota quando alcançar 999.999.999, deve ser incrementado sempre que alcançar[G:a][N:Sequência]
  "chave" VARCHAR(50) DEFAULT NULL,-- Chave da nota fiscal[G:a][N:Chave]
  "recibo" VARCHAR(50) DEFAULT NULL,-- Recibo de envio para consulta posterior[G:o][N:Recibo]
  "protocolo" VARCHAR(80) DEFAULT NULL,-- Protocolo de autorização da nota fiscal[G:o][N:Protocolo]
  "pedido_id" INTEGER DEFAULT NULL,-- Pedido da nota[N:Pedido][S:S]
  "motivo" VARCHAR(255) DEFAULT NULL,-- Motivo do cancelamento, contingência ou inutilização[G:o][N:Motivo]
  "contingencia" TEXT NOT NULL CHECK("contingencia" IN('Y', 'N')),-- Informa se a nota está em contingência[G:a][N:Contingência]
  "consulta_url" VARCHAR(255) DEFAULT NULL,-- URL de consulta da nota fiscal[G:o][N:URL de consulta]
  "qrcode" TEXT DEFAULT NULL,-- Dados do QRCode da nota[G:o][N:QRCode]
  "tributos" DECIMAL DEFAULT NULL,-- Tributos totais da nota[G:o][N:Tributos]
  "detalhes" VARCHAR(255) DEFAULT NULL,-- Informações de interesse do contribuinte[G:a][N:Informações de interesse do contribuinte]
  "corrigido" TEXT NOT NULL CHECK("corrigido" IN('Y', 'N')) DEFAULT 'Y',-- Informa se os erros já foram corrigidos para retomada do processamento[G:o][N:Corrigido][F:true]
  "concluido" TEXT NOT NULL CHECK("concluido" IN('Y', 'N')) DEFAULT 'N',-- Informa se todos os processamentos da nota já foram realizados[G:o][N:Concluído][F:false]
  "data_autorizacao" DATETIME DEFAULT NULL,-- Data de autorização da nota fiscal[G:a][N:Data de autorização]
  "data_emissao" DATETIME NOT NULL,-- Data de emissão da nota[G:a][N:Data de emissão]
  "data_lancamento" DATETIME NOT NULL,-- Data de lançamento da nota no sistema[G:a][N:Data de lançamento]
  "data_arquivado" DATETIME DEFAULT NULL,-- Data em que a nota foi arquivada[G:a][N:Data de arquivamento]
  CONSTRAINT "FK_notas_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_notas_ultimo_evento_id"
    FOREIGN KEY("ultimo_evento_id")
    REFERENCES "eventos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "notas.FK_notas_pedido_id_idx" ON "notas" ("pedido_id");
CREATE INDEX "notas.chave_INDEX" ON "notas" ("chave");
CREATE INDEX "notas.FK_notas_ultimo_evento_id_idx" ON "notas" ("ultimo_evento_id");
CREATE TABLE "enderecos"(
--   Endereços de ruas e avenidas com informação de CEP[N:Endereço|Endereços][G:o][K:App\Models|Models\][H:Model][L:null][ID:28]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador do endereço[G:o]
  "cidade_id" INTEGER NOT NULL,-- Cidade a qual o endereço pertence[N:Cidade][G:a][S:S]
  "bairro_id" INTEGER NOT NULL,-- Bairro a qual o endereço está localizado[N:Bairro][G:o][S:S]
  "logradouro" VARCHAR(200) NOT NULL,-- Nome da rua ou avenida[N:Logradouro][G:o][S]
  "cep" VARCHAR(8) NOT NULL,-- Código dos correios para identificar a rua ou avenida[N:CEP][G:o][M:99999-999]
  CONSTRAINT "cep_UNIQUE"
    UNIQUE("cep"),
  CONSTRAINT "bairro_id_logradouro_UNIQUE"
    UNIQUE("bairro_id","logradouro"),
  CONSTRAINT "FK_enderecos_cidade_id"
    FOREIGN KEY("cidade_id")
    REFERENCES "cidades"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_enderecos_bairro_id"
    FOREIGN KEY("bairro_id")
    REFERENCES "bairros"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX "enderecos.FK_enderecos_cidade_id_idx" ON "enderecos" ("cidade_id");
CREATE TABLE "pedidos"(
--   Informações do pedido de venda[N:Pedido|Pedidos][G:o][K:App\Models|Models\][H:Model][L:null][ID:59]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código do pedido[N:Código][G:o]
  "pedido_id" INTEGER DEFAULT NULL,-- Informa o pedido da mesa / comanda principal quando as mesas / comandas forem agrupadas[G:o][N:Pedido principal]
  "mesa_id" INTEGER DEFAULT NULL,-- Identificador da mesa, único quando o pedido não está fechado[N:Mesa][G:a][S:S]
  "comanda_id" INTEGER DEFAULT NULL,-- Identificador da comanda, único quando o pedido não está fechado[N:Comanda][G:a][S:S]
  "sessao_id" INTEGER DEFAULT NULL,-- Identificador da sessão de vendas[N:Sessão][G:a][S:S]
  "prestador_id" INTEGER DEFAULT NULL,-- Prestador que criou esse pedido[N:Prestador][G:o][S:S]
  "cliente_id" INTEGER DEFAULT NULL,-- Identificador do cliente do pedido[N:Cliente][G:o][S:S]
  "localizacao_id" INTEGER DEFAULT NULL,-- Endereço de entrega do pedido, se não informado na venda entrega, o pedido será para viagem[N:Localização][G:a][S:S]
  "entrega_id" INTEGER DEFAULT NULL,-- Informa em qual entrega esse pedido foi despachado[G:a][N:Entrega]
  "associacao_id" INTEGER DEFAULT NULL,-- Informa se o pedido veio de uma integração e se está associado[G:a][N:Associação]
  "tipo" TEXT NOT NULL CHECK("tipo" IN('mesa', 'comanda', 'balcao', 'entrega')) DEFAULT 'mesa',-- Tipo de venda[N:Tipo][G:o][E:Mesa|Comanda|Balcão|Entrega][S:S][F:self::TIPO_MESA]
  "estado" TEXT NOT NULL CHECK("estado" IN('agendado', 'aberto', 'entrega', 'fechado', 'concluido', 'cancelado')) DEFAULT 'aberto',-- Estado do pedido, Agendado: O pedido deve ser processado na data de agendamento. Aberto: O pedido deve ser processado. Entrega: O pedido saiu para entrega. Fechado: O cliente pediu a conta e está pronto para pagar. Concluído: O pedido foi pago e concluído, Cancelado: O pedido foi cancelado com os itens e pagamentos[N:Estado][G:o][F:self::ESTADO_ABERTO]
  "servicos" DECIMAL NOT NULL DEFAULT 0,-- Valor total dos serviços desse pedido[G:o][N:Total dos serviços][F:0]
  "produtos" DECIMAL NOT NULL DEFAULT 0,-- Valor total dos produtos do pedido sem a comissão[G:o][N:Total dos produtos][F:0]
  "comissao" DECIMAL NOT NULL DEFAULT 0,-- Valor total da comissão desse pedido[G:o][N:Total da comissão][F:0]
  "subtotal" DECIMAL NOT NULL DEFAULT 0,-- Subtotal do pedido sem os descontos[G:o][N:Subtotal][F:0]
  "descontos" DECIMAL NOT NULL DEFAULT 0,-- Total de descontos realizado nesse pedido[G:o][N:Descontos][F:0]
  "total" DECIMAL NOT NULL DEFAULT 0,-- Total do pedido já com descontos[G:o][N:Total][F:0]
  "pago" DECIMAL NOT NULL DEFAULT 0,-- Valor já pago do pedido[G:o][N:Total pago][F:0]
  "troco" DECIMAL NOT NULL DEFAULT 0,-- Troco do cliente[G:o][N:Troco][F:0]
  "lancado" DECIMAL NOT NULL DEFAULT 0,-- Valor lançado para pagar, mas não foi pago ainda[G:o][N:Total lançado][F:0]
  "pessoas" INTEGER NOT NULL DEFAULT 1,-- Informa quantas pessoas estão na mesa[N:Pessoas][G:a][F:1]
  "cpf" VARCHAR(20) DEFAULT NULL,-- CPF/CNPJ na nota[G:o][N:CPF/CNPJ]
  "email" VARCHAR(100) DEFAULT NULL,-- E-mail para envio do XML e Danfe[G:o][N:E-mail]
  "descricao" VARCHAR(255) DEFAULT NULL,-- Detalhes da reserva ou do pedido[N:Descrição][G:a]
  "fechador_id" INTEGER DEFAULT NULL,-- Informa quem fechou o pedido e imprimiu a conta[N:Fechador do pedido][G:o][S:S]
  "data_impressao" DATETIME DEFAULT NULL,-- Data de impressão da conta do cliente[N:Data de impressão][G:a]
  "motivo" VARCHAR(200) DEFAULT NULL,-- Informa o motivo do cancelamento[G:o][N:Motivo]
  "data_entrega" DATETIME DEFAULT NULL,-- Data e hora que o entregador saiu para entregar esse pedido[N:Data de entrega][G:a]
  "data_agendamento" DATETIME DEFAULT NULL,-- Data de agendamento do pedido[N:Data de agendamento][G:a]
  "data_conclusao" DATETIME DEFAULT NULL,-- Data de finalização do pedido[N:Data de conclusão][G:a]
  "data_criacao" DATETIME NOT NULL,-- Data de criação do pedido[N:Data de criação][G:a]
  CONSTRAINT "associacao_id_UNIQUE"
    UNIQUE("associacao_id"),
  CONSTRAINT "FK_pedidos_mesa_id"
    FOREIGN KEY("mesa_id")
    REFERENCES "mesas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_sessao_id"
    FOREIGN KEY("sessao_id")
    REFERENCES "sessoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_prestador_id"
    FOREIGN KEY("prestador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_localizacao_id"
    FOREIGN KEY("localizacao_id")
    REFERENCES "localizacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_comanda_id"
    FOREIGN KEY("comanda_id")
    REFERENCES "comandas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_fechador_id"
    FOREIGN KEY("fechador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_entrega_id"
    FOREIGN KEY("entrega_id")
    REFERENCES "viagens"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pedidos_associacao_id"
    FOREIGN KEY("associacao_id")
    REFERENCES "associacoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "pedidos.FK_pedidos_mesa_id_idx" ON "pedidos" ("mesa_id");
CREATE INDEX "pedidos.FK_pedidos_sessao_id_idx" ON "pedidos" ("sessao_id");
CREATE INDEX "pedidos.FK_pedidos_prestador_id_idx" ON "pedidos" ("prestador_id");
CREATE INDEX "pedidos.FK_pedidos_cliente_id_idx" ON "pedidos" ("cliente_id");
CREATE INDEX "pedidos.tipo_estado_INDEX" ON "pedidos" ("tipo","estado");
CREATE INDEX "pedidos.FK_pedidos_localizacao_id_idx" ON "pedidos" ("localizacao_id");
CREATE INDEX "pedidos.FK_pedidos_comanda_id_idx" ON "pedidos" ("comanda_id");
CREATE INDEX "pedidos.FK_pedidos_fechador_id_idx" ON "pedidos" ("fechador_id");
CREATE INDEX "pedidos.FK_pedidos_entrega_id_idx" ON "pedidos" ("entrega_id");
CREATE INDEX "pedidos.data_criacao_INDEX" ON "pedidos" ("data_criacao" DESC);
CREATE INDEX "pedidos.FK_pedidos_pedido_id_idx" ON "pedidos" ("pedido_id");
CREATE TABLE "pontuacoes"(
--   Informa os pontos ganhos e gastos por compras de produtos promocionais[N:Pontuação|Pontuações][G:a][K:App\Models|Models\][H:Model][L:null][ID:61]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Identificador da pontuação[G:o]
  "promocao_id" INTEGER NOT NULL,-- Informa a promoção que originou os pontos ou que descontou os pontos[G:a][N:Promoção]
  "cliente_id" INTEGER DEFAULT NULL,-- Cliente que possui esses pontos, não informar quando tiver travado por pedido[G:o][N:Cliente]
  "pedido_id" INTEGER DEFAULT NULL,-- Informa se essa pontuação será usada apenas nesse pedido[G:o][N:Pedido]
  "item_id" INTEGER DEFAULT NULL,-- Informa qual venda originou esses pontos, tanto saída como entrada[G:o][N:Item]
  "quantidade" INTEGER NOT NULL,-- Quantidade de pontos ganhos ou gastos[G:a][N:Quantidade]
  "data_cadastro" DATETIME NOT NULL,-- Data de cadastro dos pontos[G:a][N:Data de cadastro]
  CONSTRAINT "FK_pontuacoes_pedido_id"
    FOREIGN KEY("pedido_id")
    REFERENCES "pedidos"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pontuacoes_item_id"
    FOREIGN KEY("item_id")
    REFERENCES "itens"("id")
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pontuacoes_cliente_id"
    FOREIGN KEY("cliente_id")
    REFERENCES "clientes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_pontuacoes_promocao_id"
    FOREIGN KEY("promocao_id")
    REFERENCES "promocoes"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "pontuacoes.FK_pontuacoes_pedido_id_idx" ON "pontuacoes" ("pedido_id");
CREATE INDEX "pontuacoes.FK_pontuacoes_item_id_idx" ON "pontuacoes" ("item_id");
CREATE INDEX "pontuacoes.FK_pontuacoes_cliente_id_idx" ON "pontuacoes" ("cliente_id");
CREATE INDEX "pontuacoes.FK_pontuacoes_promocao_id_idx" ON "pontuacoes" ("promocao_id");
CREATE TABLE "movimentacoes"(
--   Movimentação do caixa, permite abrir diversos caixas na conta de operadores[N:Movimentação|Movimentações][G:a][K:App\Models|Models\][H:Model][L:null][ID:50]
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,-- Código da movimentação do caixa[G:o]
  "sessao_id" INTEGER NOT NULL,-- Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo código da sessão[N:Sessão][G:a][S:S]
  "caixa_id" INTEGER NOT NULL,-- Caixa a qual pertence essa movimentação[N:Caixa][G:o][S]
  "aberta" TEXT NOT NULL CHECK("aberta" IN('Y', 'N')) DEFAULT 'Y',-- Informa se o caixa está aberto[N:Aberta][G:a][F:true]
  "iniciador_id" INTEGER NOT NULL,-- Funcionário que abriu o caixa[N:Funcionário inicializador][G:a][S:S]
  "fechador_id" INTEGER DEFAULT NULL,-- Funcionário que fechou o caixa[N:Funcionário fechador][G:o][S:S]
  "data_fechamento" DATETIME DEFAULT NULL,-- Data de fechamento do caixa[N:Data de fechamento][G:a]
  "data_abertura" DATETIME NOT NULL,-- Data de abertura do caixa[N:Data de abertura][G:a]
  CONSTRAINT "FK_movimentacoes_sessao_id"
    FOREIGN KEY("sessao_id")
    REFERENCES "sessoes"("ID")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_movimentacoes_caixa_id"
    FOREIGN KEY("caixa_id")
    REFERENCES "caixas"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_movimentacoes_iniciador_id"
    FOREIGN KEY("iniciador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT "FK_movimentacoes_fechador_id"
    FOREIGN KEY("fechador_id")
    REFERENCES "prestadores"("id")
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
CREATE INDEX "movimentacoes.FK_movimentacoes_sessao_id_idx" ON "movimentacoes" ("sessao_id");
CREATE INDEX "movimentacoes.FK_movimentacoes_caixa_id_idx" ON "movimentacoes" ("caixa_id");
CREATE INDEX "movimentacoes.FK_movimentacoes_iniciador_id_idx" ON "movimentacoes" ("iniciador_id");
CREATE INDEX "movimentacoes.FK_movimentacoes_fechador_id_idx" ON "movimentacoes" ("fechador_id");
CREATE INDEX "movimentacoes.data_abertura_INDEX" ON "movimentacoes" ("data_abertura");
CREATE INDEX "movimentacoes.data_fechamento_INDEX" ON "movimentacoes" ("data_fechamento");

PRAGMA foreign_keys = ON;
