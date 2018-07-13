-- Database generated with pgModeler (PostgreSQL Database Modeler).
-- pgModeler  version: 0.8.2
-- PostgreSQL version: 9.5
-- Project Site: pgmodeler.com.br
-- Model Author: ---


-- Database creation must be done outside an multicommand file.
-- These commands were put in this file only for convenience.
-- -- object: "GrandChef" | type: DATABASE --
-- -- DROP DATABASE IF EXISTS "GrandChef";
-- CREATE DATABASE "GrandChef"
-- ;
-- -- ddl-end --
-- 

-- object: public.costumer | type: TABLE --
-- DROP TABLE IF EXISTS public.costumer CASCADE;
CREATE TABLE public.costumer(
	id bigserial NOT NULL,
	CONSTRAINT costumer_pk PRIMARY KEY (id)

);
-- ddl-end --
ALTER TABLE public.costumer OWNER TO postgres;
-- ddl-end --

-- object: public.address | type: TABLE --
-- DROP TABLE IF EXISTS public.address CASCADE;
CREATE TABLE public.address(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.address OWNER TO postgres;
-- ddl-end --

-- object: public.product | type: TABLE --
-- DROP TABLE IF EXISTS public.product CASCADE;
CREATE TABLE public.product(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.product OWNER TO postgres;
-- ddl-end --

-- object: public.item | type: TABLE --
-- DROP TABLE IF EXISTS public.item CASCADE;
CREATE TABLE public.item(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.item OWNER TO postgres;
-- ddl-end --

-- object: public.district | type: TABLE --
-- DROP TABLE IF EXISTS public.district CASCADE;
CREATE TABLE public.district(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.district OWNER TO postgres;
-- ddl-end --

-- object: public.phone | type: TABLE --
-- DROP TABLE IF EXISTS public.phone CASCADE;
CREATE TABLE public.phone(
	id bigserial NOT NULL,
	CONSTRAINT phone_pk PRIMARY KEY (id)

);
-- ddl-end --
COMMENT ON TABLE public.phone IS 'Informa o telefone, país e cliente proprietário desse telefone';
-- ddl-end --
ALTER TABLE public.phone OWNER TO postgres;
-- ddl-end --

-- object: public.system | type: TABLE --
-- DROP TABLE IF EXISTS public.system CASCADE;
CREATE TABLE public.system(
	id boolean NOT NULL DEFAULT TRUE,
	CONSTRAINT system_pk PRIMARY KEY (id),
	CONSTRAINT system_upk CHECK (id)

);
-- ddl-end --
ALTER TABLE public.system OWNER TO postgres;
-- ddl-end --

-- object: public.sector | type: TABLE --
-- DROP TABLE IF EXISTS public.sector CASCADE;
CREATE TABLE public.sector(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.sector OWNER TO postgres;
-- ddl-end --

-- object: public.employee | type: TABLE --
-- DROP TABLE IF EXISTS public.employee CASCADE;
CREATE TABLE public.employee(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.employee OWNER TO postgres;
-- ddl-end --

-- object: public.device | type: TABLE --
-- DROP TABLE IF EXISTS public.device CASCADE;
CREATE TABLE public.device(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.device OWNER TO postgres;
-- ddl-end --

-- object: public.role | type: TABLE --
-- DROP TABLE IF EXISTS public.role CASCADE;
CREATE TABLE public.role(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.role OWNER TO postgres;
-- ddl-end --

-- object: public.country | type: TABLE --
-- DROP TABLE IF EXISTS public.country CASCADE;
CREATE TABLE public.country(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.country OWNER TO postgres;
-- ddl-end --

-- object: public.city | type: TABLE --
-- DROP TABLE IF EXISTS public.city CASCADE;
CREATE TABLE public.city(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.city OWNER TO postgres;
-- ddl-end --

-- object: public.state | type: TABLE --
-- DROP TABLE IF EXISTS public.state CASCADE;
CREATE TABLE public.state(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.state OWNER TO postgres;
-- ddl-end --

-- object: public.audit | type: TABLE --
-- DROP TABLE IF EXISTS public.audit CASCADE;
CREATE TABLE public.audit(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.audit OWNER TO postgres;
-- ddl-end --

-- object: public.printer | type: TABLE --
-- DROP TABLE IF EXISTS public.printer CASCADE;
CREATE TABLE public.printer(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.printer OWNER TO postgres;
-- ddl-end --

-- object: public.feature | type: TABLE --
-- DROP TABLE IF EXISTS public.feature CASCADE;
CREATE TABLE public.feature(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.feature IS 'Categoria de recursos dispóníveis no sistema';
-- ddl-end --
ALTER TABLE public.feature OWNER TO postgres;
-- ddl-end --

-- object: public.access | type: TABLE --
-- DROP TABLE IF EXISTS public.access CASCADE;
CREATE TABLE public.access(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.access IS 'Acesso concedido à função para a permissão requerida';
-- ddl-end --
ALTER TABLE public.access OWNER TO postgres;
-- ddl-end --

-- object: public.permission | type: TABLE --
-- DROP TABLE IF EXISTS public.permission CASCADE;
CREATE TABLE public.permission(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.permission IS 'Permissão requerida para acessar parte do sistema';
-- ddl-end --
ALTER TABLE public.permission OWNER TO postgres;
-- ddl-end --

-- object: public.page | type: TABLE --
-- DROP TABLE IF EXISTS public.page CASCADE;
CREATE TABLE public.page(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.page OWNER TO postgres;
-- ddl-end --

-- object: public.integration | type: TABLE --
-- DROP TABLE IF EXISTS public.integration CASCADE;
CREATE TABLE public.integration(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.integration IS 'Inoformações sobre os módulos de integração com outros sistemas';
-- ddl-end --
ALTER TABLE public.integration OWNER TO postgres;
-- ddl-end --

-- object: public.hour | type: TABLE --
-- DROP TABLE IF EXISTS public.hour CASCADE;
CREATE TABLE public.hour(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.hour IS 'Delimitação de intervalos de horas usado pelo quadro de horas';
-- ddl-end --
ALTER TABLE public.hour OWNER TO postgres;
-- ddl-end --

-- object: public.module | type: TABLE --
-- DROP TABLE IF EXISTS public.module CASCADE;
CREATE TABLE public.module(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.module IS 'Módulos de vendas e operações disponíveis no sistema';
-- ddl-end --
ALTER TABLE public.module OWNER TO postgres;
-- ddl-end --

-- object: public.patrimony | type: TABLE --
-- DROP TABLE IF EXISTS public.patrimony CASCADE;
CREATE TABLE public.patrimony(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.patrimony OWNER TO postgres;
-- ddl-end --

-- object: public.category | type: TABLE --
-- DROP TABLE IF EXISTS public.category CASCADE;
CREATE TABLE public.category(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.category OWNER TO postgres;
-- ddl-end --

-- object: public.formation | type: TABLE --
-- DROP TABLE IF EXISTS public.formation CASCADE;
CREATE TABLE public.formation(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.formation OWNER TO postgres;
-- ddl-end --

-- object: public."table" | type: TABLE --
-- DROP TABLE IF EXISTS public."table" CASCADE;
CREATE TABLE public."table"(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public."table" OWNER TO postgres;
-- ddl-end --

-- object: public.payment | type: TABLE --
-- DROP TABLE IF EXISTS public.payment CASCADE;
CREATE TABLE public.payment(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.payment OWNER TO postgres;
-- ddl-end --

-- object: public."check" | type: TABLE --
-- DROP TABLE IF EXISTS public."check" CASCADE;
CREATE TABLE public."check"(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public."check" OWNER TO postgres;
-- ddl-end --

-- object: public.bill | type: TABLE --
-- DROP TABLE IF EXISTS public.bill CASCADE;
CREATE TABLE public.bill(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.bill OWNER TO postgres;
-- ddl-end --

-- object: public.register | type: TABLE --
-- DROP TABLE IF EXISTS public.register CASCADE;
CREATE TABLE public.register(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.register IS 'Caixa registradora';
-- ddl-end --
ALTER TABLE public.register OWNER TO postgres;
-- ddl-end --

-- object: public.moviment | type: TABLE --
-- DROP TABLE IF EXISTS public.moviment CASCADE;
CREATE TABLE public.moviment(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.moviment OWNER TO postgres;
-- ddl-end --

-- object: public.section | type: TABLE --
-- DROP TABLE IF EXISTS public.section CASCADE;
CREATE TABLE public.section(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.section OWNER TO postgres;
-- ddl-end --

-- object: public.wallet | type: TABLE --
-- DROP TABLE IF EXISTS public.wallet CASCADE;
CREATE TABLE public.wallet(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.wallet OWNER TO postgres;
-- ddl-end --

-- object: public.summary | type: TABLE --
-- DROP TABLE IF EXISTS public.summary CASCADE;
CREATE TABLE public.summary(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.summary IS 'Registra uma conferência de fechamento de caixa';
-- ddl-end --
ALTER TABLE public.summary OWNER TO postgres;
-- ddl-end --

-- object: public.bank | type: TABLE --
-- DROP TABLE IF EXISTS public.bank CASCADE;
CREATE TABLE public.bank(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.bank OWNER TO postgres;
-- ddl-end --

-- object: public.currency | type: TABLE --
-- DROP TABLE IF EXISTS public.currency CASCADE;
CREATE TABLE public.currency(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.currency OWNER TO postgres;
-- ddl-end --

-- object: public.method | type: TABLE --
-- DROP TABLE IF EXISTS public.method CASCADE;
CREATE TABLE public.method(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.method OWNER TO postgres;
-- ddl-end --

-- object: public.card | type: TABLE --
-- DROP TABLE IF EXISTS public.card CASCADE;
CREATE TABLE public.card(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.card OWNER TO postgres;
-- ddl-end --

-- object: public.credit | type: TABLE --
-- DROP TABLE IF EXISTS public.credit CASCADE;
CREATE TABLE public.credit(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.credit OWNER TO postgres;
-- ddl-end --

-- object: public.package | type: TABLE --
-- DROP TABLE IF EXISTS public.package CASCADE;
CREATE TABLE public.package(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.package OWNER TO postgres;
-- ddl-end --

-- object: public.property | type: TABLE --
-- DROP TABLE IF EXISTS public.property CASCADE;
CREATE TABLE public.property(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.property OWNER TO postgres;
-- ddl-end --

-- object: public.catalog | type: TABLE --
-- DROP TABLE IF EXISTS public.catalog CASCADE;
CREATE TABLE public.catalog(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.catalog IS 'Catálogo de preços de produtos de um fornecedor';
-- ddl-end --
ALTER TABLE public.catalog OWNER TO postgres;
-- ddl-end --

-- object: public.list | type: TABLE --
-- DROP TABLE IF EXISTS public.list CASCADE;
CREATE TABLE public.list(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.list IS 'Lista de compras de produtos de insumos para suprir o estabelecimento';
-- ddl-end --
ALTER TABLE public.list OWNER TO postgres;
-- ddl-end --

-- object: public.supplier | type: TABLE --
-- DROP TABLE IF EXISTS public.supplier CASCADE;
CREATE TABLE public.supplier(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.supplier OWNER TO postgres;
-- ddl-end --

-- object: public.transfer | type: TABLE --
-- DROP TABLE IF EXISTS public.transfer CASCADE;
CREATE TABLE public.transfer(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.transfer OWNER TO postgres;
-- ddl-end --

-- object: public.stock | type: TABLE --
-- DROP TABLE IF EXISTS public.stock CASCADE;
CREATE TABLE public.stock(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.stock OWNER TO postgres;
-- ddl-end --

-- object: public.purchase | type: TABLE --
-- DROP TABLE IF EXISTS public.purchase CASCADE;
CREATE TABLE public.purchase(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.purchase IS 'Informa um produto da lista que deve ser comprado em um fornecedor';
-- ddl-end --
ALTER TABLE public.purchase OWNER TO postgres;
-- ddl-end --

-- object: public.composition | type: TABLE --
-- DROP TABLE IF EXISTS public.composition CASCADE;
CREATE TABLE public.composition(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.composition OWNER TO postgres;
-- ddl-end --

-- object: public.unit | type: TABLE --
-- DROP TABLE IF EXISTS public.unit CASCADE;
CREATE TABLE public.unit(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.unit OWNER TO postgres;
-- ddl-end --

-- object: public."group" | type: TABLE --
-- DROP TABLE IF EXISTS public."group" CASCADE;
CREATE TABLE public."group"(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public."group" OWNER TO postgres;
-- ddl-end --

-- object: public.quotation | type: TABLE --
-- DROP TABLE IF EXISTS public.quotation CASCADE;
CREATE TABLE public.quotation(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.quotation OWNER TO postgres;
-- ddl-end --

-- object: public."order" | type: TABLE --
-- DROP TABLE IF EXISTS public."order" CASCADE;
CREATE TABLE public."order"(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public."order" OWNER TO postgres;
-- ddl-end --

-- object: public.promotion | type: TABLE --
-- DROP TABLE IF EXISTS public.promotion CASCADE;
CREATE TABLE public.promotion(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.promotion OWNER TO postgres;
-- ddl-end --

-- object: public.nutritional | type: TABLE --
-- DROP TABLE IF EXISTS public.nutritional CASCADE;
CREATE TABLE public.nutritional(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.nutritional OWNER TO postgres;
-- ddl-end --

-- object: public.rating | type: TABLE --
-- DROP TABLE IF EXISTS public.rating CASCADE;
CREATE TABLE public.rating(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.rating OWNER TO postgres;
-- ddl-end --

-- object: public.comment | type: TABLE --
-- DROP TABLE IF EXISTS public.comment CASCADE;
CREATE TABLE public.comment(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.comment OWNER TO postgres;
-- ddl-end --

-- object: public.receipt | type: TABLE --
-- DROP TABLE IF EXISTS public.receipt CASCADE;
CREATE TABLE public.receipt(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.receipt OWNER TO postgres;
-- ddl-end --

-- object: public.issuer | type: TABLE --
-- DROP TABLE IF EXISTS public.issuer CASCADE;
CREATE TABLE public.issuer(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.issuer OWNER TO postgres;
-- ddl-end --

-- object: public.event | type: TABLE --
-- DROP TABLE IF EXISTS public.event CASCADE;
CREATE TABLE public.event(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.event OWNER TO postgres;
-- ddl-end --

-- object: public.tax | type: TABLE --
-- DROP TABLE IF EXISTS public.tax CASCADE;
CREATE TABLE public.tax(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.tax OWNER TO postgres;
-- ddl-end --

-- object: public.origin | type: TABLE --
-- DROP TABLE IF EXISTS public.origin CASCADE;
CREATE TABLE public.origin(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.origin OWNER TO postgres;
-- ddl-end --

-- object: public.operation | type: TABLE --
-- DROP TABLE IF EXISTS public.operation CASCADE;
CREATE TABLE public.operation(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.operation OWNER TO postgres;
-- ddl-end --

-- object: public.regime | type: TABLE --
-- DROP TABLE IF EXISTS public.regime CASCADE;
CREATE TABLE public.regime(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.regime OWNER TO postgres;
-- ddl-end --

-- object: public.tribute | type: TABLE --
-- DROP TABLE IF EXISTS public.tribute CASCADE;
CREATE TABLE public.tribute(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.tribute OWNER TO postgres;
-- ddl-end --

-- object: public.server | type: TABLE --
-- DROP TABLE IF EXISTS public.server CASCADE;
CREATE TABLE public.server(
	id bigserial NOT NULL,
	guid varchar(48) NOT NULL,
	"offset" bigint,
	token text,
	synced timestamp,
	active boolean NOT NULL,
	CONSTRAINT server_pk PRIMARY KEY (id)

);
-- ddl-end --
COMMENT ON TABLE public.server IS 'Registra os servidores de sincronização';
-- ddl-end --
COMMENT ON COLUMN public.server.id IS 'Identificador auto gerado para cada servidor cadastrado';
-- ddl-end --
COMMENT ON COLUMN public.server.guid IS 'Número universal que identifica um único servidor de sincronização';
-- ddl-end --
COMMENT ON COLUMN public.server."offset" IS 'Guarda até qual registro foi importado os dados do servidor remoto';
-- ddl-end --
COMMENT ON COLUMN public.server.token IS 'Token de acesso que deve ser enviado para o servidor de sincronização para o mesmo aceitar os dados';
-- ddl-end --
COMMENT ON COLUMN public.server.synced IS 'Data em que o servidor sincronizou dados pela última vez';
-- ddl-end --
COMMENT ON COLUMN public.server.active IS 'Informa se o servidor está ativo e permite sincronização';
-- ddl-end --
ALTER TABLE public.server OWNER TO postgres;
-- ddl-end --

-- object: public.mapper | type: TABLE --
-- DROP TABLE IF EXISTS public.mapper CASCADE;
CREATE TABLE public.mapper(
	id bigserial NOT NULL,
	server_id bigint NOT NULL,
	"table" varchar(64) NOT NULL,
	"from" bigint NOT NULL,
	"to" bigint NOT NULL,
	CONSTRAINT mapper_pk PRIMARY KEY (id)

);
-- ddl-end --
COMMENT ON TABLE public.mapper IS 'Mapeia os ids vindo de outros servidores para os dados desse servidor';
-- ddl-end --
COMMENT ON COLUMN public.mapper.id IS 'Identificador auto gerado';
-- ddl-end --
COMMENT ON COLUMN public.mapper.server_id IS 'Informa de qual servidor foi criado esse mapeamento';
-- ddl-end --
COMMENT ON COLUMN public.mapper."table" IS 'Nome da tabela envolvida na associação';
-- ddl-end --
COMMENT ON COLUMN public.mapper."from" IS 'Informa o id vindo de fora por outros servidores';
-- ddl-end --
COMMENT ON COLUMN public.mapper."to" IS 'Informa qual o id local associado para permitir operações';
-- ddl-end --
ALTER TABLE public.mapper OWNER TO postgres;
-- ddl-end --

-- object: public.logevent | type: TYPE --
-- DROP TYPE IF EXISTS public.logevent CASCADE;
CREATE TYPE public.logevent AS
 ENUM ('insert','update','delete');
-- ddl-end --
ALTER TYPE public.logevent OWNER TO postgres;
-- ddl-end --

-- object: public.logger | type: TABLE --
-- DROP TABLE IF EXISTS public.logger CASCADE;
CREATE TABLE public.logger(
	id bigserial NOT NULL,
	server_id bigint NOT NULL,
	"table" varchar(64) NOT NULL,
	"row" bigint NOT NULL,
	event public.logevent NOT NULL,
	date timestamp NOT NULL,

);
-- ddl-end --
ALTER TABLE public.logger OWNER TO postgres;
-- ddl-end --

-- object: public.translation | type: TABLE --
-- DROP TABLE IF EXISTS public.translation CASCADE;
CREATE TABLE public.translation(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.translation IS 'Informa as traduções de cada texto inserido no banco de dados do sistema';
-- ddl-end --
ALTER TABLE public.translation OWNER TO postgres;
-- ddl-end --

-- object: public.language | type: TABLE --
-- DROP TABLE IF EXISTS public.language CASCADE;
CREATE TABLE public.language(
	id serial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.language OWNER TO postgres;
-- ddl-end --

-- object: public.balance | type: TABLE --
-- DROP TABLE IF EXISTS public.balance CASCADE;
CREATE TABLE public.balance(
	id bigserial NOT NULL,

);
-- ddl-end --
ALTER TABLE public.balance OWNER TO postgres;
-- ddl-end --

-- object: public.conference | type: TABLE --
-- DROP TABLE IF EXISTS public.conference CASCADE;
CREATE TABLE public.conference(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.conference IS 'Registra uma conferência de estoque';
-- ddl-end --
ALTER TABLE public.conference OWNER TO postgres;
-- ddl-end --

-- object: public.journey | type: TABLE --
-- DROP TABLE IF EXISTS public.journey CASCADE;
CREATE TABLE public.journey(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.journey IS 'Quadro de horas que poderá ser utilizado como horário de funcionamento';
-- ddl-end --
ALTER TABLE public.journey OWNER TO postgres;
-- ddl-end --

-- object: public.cuisine | type: TABLE --
-- DROP TABLE IF EXISTS public.cuisine CASCADE;
CREATE TABLE public.cuisine(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.cuisine IS 'Informa com quais cozinhas o estabelecimento trabalha';
-- ddl-end --
ALTER TABLE public.cuisine OWNER TO postgres;
-- ddl-end --

-- object: public.availability | type: TABLE --
-- DROP TABLE IF EXISTS public.availability CASCADE;
CREATE TABLE public.availability(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.availability IS 'Informa a disponibilidade e preço de um produto nos módulos de venda ou de integração';
-- ddl-end --
ALTER TABLE public.availability OWNER TO postgres;
-- ddl-end --

-- object: public.attendance | type: TABLE --
-- DROP TABLE IF EXISTS public.attendance CASCADE;
CREATE TABLE public.attendance(
	id serial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.attendance IS 'Tipo de atendimento para avaliação, Ex.: Entrega, Comida, Serviço';
-- ddl-end --
ALTER TABLE public.attendance OWNER TO postgres;
-- ddl-end --

-- object: public.favorite | type: TABLE --
-- DROP TABLE IF EXISTS public.favorite CASCADE;
CREATE TABLE public.favorite(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.favorite IS 'Informa os produtos favoritos do cliente';
-- ddl-end --
ALTER TABLE public.favorite OWNER TO postgres;
-- ddl-end --

-- object: public.score | type: TABLE --
-- DROP TABLE IF EXISTS public.score CASCADE;
CREATE TABLE public.score(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.score IS 'Informa os pontos ganhos e gastos por um cliente ao comprar produtos';
-- ddl-end --
ALTER TABLE public.score OWNER TO postgres;
-- ddl-end --

-- object: public.status | type: TABLE --
-- DROP TABLE IF EXISTS public.status CASCADE;
CREATE TABLE public.status(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.status IS 'Informa o estado do pedido, quando foi confirmado, se já foi entregue, etc.';
-- ddl-end --
ALTER TABLE public.status OWNER TO postgres;
-- ddl-end --

-- object: public.production | type: TABLE --
-- DROP TABLE IF EXISTS public.production CASCADE;
CREATE TABLE public.production(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.production IS 'Informa os estados de produção do itens';
-- ddl-end --
ALTER TABLE public.production OWNER TO postgres;
-- ddl-end --

-- object: public.tracking | type: TABLE --
-- DROP TABLE IF EXISTS public.tracking CASCADE;
CREATE TABLE public.tracking(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.tracking IS 'Informa a rota do entregador ao fazer uma entrega de um ou mais pedidos';
-- ddl-end --
ALTER TABLE public.tracking OWNER TO postgres;
-- ddl-end --

-- object: public.delivery | type: TABLE --
-- DROP TABLE IF EXISTS public.delivery CASCADE;
CREATE TABLE public.delivery(
	id bigserial NOT NULL,

);
-- ddl-end --
COMMENT ON TABLE public.delivery IS 'Informa uma viagem de um entregador, os pedidos serão atribuídos para essa entrega';
-- ddl-end --
ALTER TABLE public.delivery OWNER TO postgres;
-- ddl-end --

-- object: mapper_server_id_table_from_idx | type: INDEX --
-- DROP INDEX IF EXISTS public.mapper_server_id_table_from_idx CASCADE;
CREATE INDEX mapper_server_id_table_from_idx ON public.mapper
	USING btree
	(
	  server_id ASC NULLS LAST,
	  "table" ASC NULLS LAST,
	  "from" DESC NULLS LAST
	);
-- ddl-end --

-- object: server_guid_uk | type: INDEX --
-- DROP INDEX IF EXISTS public.server_guid_uk CASCADE;
CREATE UNIQUE INDEX server_guid_uk ON public.server
	USING btree
	(
	  guid ASC NULLS LAST
	);
-- ddl-end --

-- object: public.label | type: TABLE --
-- DROP TABLE IF EXISTS public.label CASCADE;
CREATE TABLE public.label(
	id serial NOT NULL,
	CONSTRAINT label_pk PRIMARY KEY (id)

);
-- ddl-end --
COMMENT ON TABLE public.label IS 'Registra os tipos de telefones, Ex.: WhatsApp, Home, Work, Custom';
-- ddl-end --
ALTER TABLE public.label OWNER TO postgres;
-- ddl-end --

-- object: public.timezone | type: TABLE --
-- DROP TABLE IF EXISTS public.timezone CASCADE;
CREATE TABLE public.timezone(
	id serial NOT NULL,
	CONSTRAINT timezone_pk PRIMARY KEY (id)

);
-- ddl-end --
COMMENT ON TABLE public.timezone IS 'Informa a geo localização para cálculo de fuso horário';
-- ddl-end --
COMMENT ON COLUMN public.timezone.id IS 'Identificador de banco de dados';
-- ddl-end --
ALTER TABLE public.timezone OWNER TO postgres;
-- ddl-end --

-- object: mapper_server_fk | type: CONSTRAINT --
-- ALTER TABLE public.mapper DROP CONSTRAINT IF EXISTS mapper_server_fk CASCADE;
ALTER TABLE public.mapper ADD CONSTRAINT mapper_server_fk FOREIGN KEY (server_id)
REFERENCES public.server (id) MATCH SIMPLE
ON DELETE RESTRICT ON UPDATE RESTRICT;
-- ddl-end --


