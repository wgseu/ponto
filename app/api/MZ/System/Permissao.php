<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\System;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa a listagem de todas as funções do sistema
 */
class Permissao extends SyncModel
{
    const NOME_SISTEMA = 'Sistema'; // Permitir acesso ao sistema
    const NOME_RESTAURACAO = 'Restauracao'; // Permitir restaurar o banco de dados
    const NOME_BACKUP = 'Backup'; // Permitir realização de cópia de segurança do banco de dados
    const NOME_PEDIDOMESA = 'PedidoMesa'; // Permitir realizar pedidos para uma mesa
    const NOME_PAGAMENTO = 'Pagamento'; // Permitir realizar um pagamento e efetuar vendas rápidas
    const NOME_MUDARDEMESA = 'MudarDeMesa'; // Permitir mudar os pedidos de uma mesa para outra
    const NOME_EXCLUIRPEDIDO = 'ExcluirPedido'; // Permitir cancelar produtos de um pedido
    const NOME_RESERVARMESA = 'ReservarMesa'; // Permitir reservar uma mesa
    const NOME_LIBERARMESA = 'LiberarMesa'; // Permitir liberar mesa reservada por outro funcionário
    const NOME_SELECIONARCAIXA = 'SelecionarCaixa'; // Permitir selecionar outro caixa aberto
    const NOME_ABRIRCAIXA = 'AbrirCaixa'; // Permitir abrir o caixa
    const NOME_INSERIRNOCAIXA = 'InserirNoCaixa'; // Permitir inserir dinheiro no caixa
    const NOME_RETIRARDOCAIXA = 'RetirarDoCaixa'; // Permitir retirar dinheiro do caixa
    const NOME_FECHARCAIXA = 'FecharCaixa'; // Permitir fechar qualquer caixa aberto
    const NOME_ALTERARPRECO = 'AlterarPreco'; // Permitir alterar o preço de um produto no momento da venda
    const NOME_MESAS = 'Mesas'; // Permitir acesso à venda para todas as mesas
    const NOME_ESTOQUE = 'Estoque'; // Permitir dar entrada de produtos no estoque
    const NOME_OPCOESIMPRESSAO = 'OpcoesImpressao'; // Permitir alteração das opções de impressão de relatórios
    const NOME_TROCARPLANO = 'TrocarPlano'; // Permitir trocar plano de fundo da aplicação
    const NOME_MODOTELA = 'ModoTela'; // Permitir trocar o modo de tela da aplicação
    const NOME_RELATORIOVENDAS = 'RelatorioVendas'; // Permitir visualizar todas as vendas
    const NOME_RELATORIOVENDEDOR = 'RelatorioVendedor'; // Permitir visualizar o total das vendas de todos vendedores
    const NOME_RELATORIOCAIXA = 'RelatorioCaixa'; // Permitir visualizar o relatório de vendas por caixa
    const NOME_RELATORIOSESSAO = 'RelatorioSessao'; // Permitir visualizar o total de vendas de cada sessão
    const NOME_RELATORIOCONSUMO = 'RelatorioConsumo'; // Permitir a impressão do relatório de consumo da mesa
    const NOME_RELATORIOCOZINHA = 'RelatorioCozinha'; // Permitir a reimpressão dos pedidos enviados para a cozinha
    const NOME_RANKDEVENDAS = 'RankDeVendas'; // Permitir visualizar o ranking dos funcionários nas vendas
    const NOME_CADASTROPRODUTOS = 'CadastroProdutos'; // Permitir cadastrar ou alterar um produto
    const NOME_CADASTROFORNECEDORES = 'CadastroFornecedores'; // Permitir cadastrar ou alterar um fornecedor
    const NOME_CADASTROMESAS = 'CadastroMesas'; // Permitir cadastrar ou alterar uma mesa
    const NOME_CADASTROFUNCIONARIOS = 'CadastroFuncionarios'; // Permitir cadastrar ou alterar um funcionário
    const NOME_CADASTROFORMASPAGTO = 'CadastroFormasPagto'; // Permitir cadastrar ou alterar uma forma de pagamento
    const NOME_CADASTROPROMOCOES = 'CadastroPromocoes'; // Permitir cadastrar ou alterar uma promoção
    const NOME_CADASTROCARTOES = 'CadastroCartoes'; // Permitir cadastrar ou alterar um cartão
    const NOME_CADASTROBANCOS = 'CadastroBancos'; // Permitir cadastrar ou alterar um banco
    const NOME_CADASTROCAIXAS = 'CadastroCaixas'; // Permitir cadastrar ou alterar um caixa
    const NOME_CADASTROTIPOSDECONTAS = 'CadastroTiposDeContas'; // Permitir cadastrar ou alterar um tipo de conta
    const NOME_CADASTROIMPRESSORAS = 'CadastroImpressoras'; // Permitir cadastrar ou alterar uma impressora
    const NOME_CADASTROCOMPUTADORES = 'CadastroComputadores'; // Permitir cadastrar ou alterar um computador
    const NOME_COMPUTADORCAIXA = 'ComputadorCaixa'; // Permitir acessar computadores reservados para o caixa
    const NOME_CADASTROCLIENTES = 'CadastroClientes'; // Permitir cadastrar ou alterar um cliente
    const NOME_ENTREGAPEDIDOS = 'EntregaPedidos'; // Permitir acessar os pedidos de produtos que são para entrega
    const NOME_ALTERARATENDENTE = 'AlterarAtendente'; // Permitir alterar o atendente no momento da venda
    const NOME_PEDIDOCOMANDA = 'PedidoComanda'; // Permitir realizar pedidos para cartões de consumo
    const NOME_CADASTROBAIRROS = 'CadastroBairros'; // Permitir cadastrar ou alterar informações de um bairro
    const NOME_CADASTROCONTAS = 'CadastroContas'; // Permitir cadastrar ou alterar contas a pagar ou a receber
    const NOME_RELATORIOCONTAS = 'RelatorioContas'; // Permitir visualizar relatórios de contas
    const NOME_RELATORIOPRODUTOS = 'RelatorioProdutos'; // Permitir visualizar relatórios de vendas de produtos
    const NOME_RELATORIOCOMPRAS = 'RelatorioCompras'; // Permitir visualizar relatórios de compras de produtos
    const NOME_CADASTROCOMANDAS = 'CadastroComandas'; // Permitir cadastrar ou alterar um número de comanda
    const NOME_CADASTROSERVICOS = 'CadastroServicos'; // Permitir cadastrar ou alterar uma taxa ou evento
    const NOME_REALIZARDESCONTOS = 'RealizarDescontos'; // Permitir realizar desconto nas vendas
    const NOME_COMANDAS = 'Comandas'; // Permitir acesso à venda para todas as comandas
    const NOME_EXCLUIRPEDIDOFINALIZADO = 'ExcluirPedidoFinalizado'; // Permitir excluir um pedido que já foi finalizado
    const NOME_RELATORIOFUNCIONARIOS = 'RelatorioFuncionarios'; // Permitir visualizar relatório de funcionários
    const NOME_RELATORIOCLIENTES = 'RelatorioClientes'; // Permitir visualizar relatório de clientes
    const NOME_REVOGARCOMISSAO = 'RevogarComissao'; // Permitir retirar a comissão de um pedido
    // Permitir selecionar outro entregador na entrega de pedidos para entrega
    const NOME_SELECIONARENTREGADOR = 'SelecionarEntregador';
    const NOME_TRANSFERIRPRODUTOS = 'TransferirProdutos'; // Permitir transferir produtos de uma mesa para outra
    const NOME_RELATORIOPEDIDOS = 'RelatorioPedidos'; // Permitir visualizar relatório de pedidos
    // Permitir alterar informações da empresa e configurações do sistema
    const NOME_ALTERARCONFIGURACOES = 'AlterarConfiguracoes';
    const NOME_LISTACOMPRAS = 'ListaCompras'; // Permitir cadastrar lista de compras de produtos
    const NOME_RELATORIOMENSAL = 'RelatorioMensal'; // Permitir visualizar e emitir relatórios de vendas mensais
    const NOME_CADASTROCIDADES = 'CadastroCidades'; // Permitir cadastrar ou alterar as cidades dos estados
    const NOME_RETIRARDOESTOQUE = 'RetirarDoEstoque'; // Permitir retirar produtos do estoque
    const NOME_RELATORIOBAIRROS = 'RelatorioBairros'; // Permitir visualizar relatórios de bairros
    const NOME_ALTERARHORARIO = 'AlterarHorario'; // Permitir alterar o horário de funcionamento do estabelecimento
    const NOME_CADASTRARCREDITOS = 'CadastrarCreditos'; // Permitir cadastrar e alterar créditos de clientes
    const NOME_ALTERARSTATUS = 'AlterarStatus'; // Permitir alterar os estados de preparo dos produtos
    const NOME_RELATORIOENTREGA = 'RelatorioEntrega'; // Permitir visualizar relatório de entrega por entregador
    const NOME_RELATORIOFORNECEDORES = 'RelatorioFornecedores'; // Permitir visualizar relatório de fornecedores
    const NOME_MUDARDECOMANDA = 'MudarDeComanda'; // Permitir mudar os pedidos de uma comanda para comanda
    const NOME_RELATORIOAUDITORIA = 'RelatorioAuditoria'; // Permitir visualizar o relatório de auditoria
    const NOME_RELATORIOCONSUMIDOR = 'RelatorioConsumidor'; // Permitir visualizar o relatório de vendas por cliente
    const NOME_RELATORIOCREDITOS = 'RelatorioCreditos'; // Permitir visualizar o relatório de créditos de clientes
    const NOME_CADASTROCARTEIRAS = 'CadastroCarteiras'; // Permitir cadastrar carteiras e contas bancárias
    const NOME_RELATORIOFLUXO = 'RelatorioFluxo'; // Permitir visualizar o relatório de fluxo de caixa
    const NOME_TRANSFERIRVALORES = 'TransferirValores'; // Permitir transferir dinheiro de um caixa para outro
    // Permitir cadastrar e atualizar a quantidade de bens de uma empresa
    const NOME_CADASTROPATRIMONIO = 'CadastroPatrimonio';
    const NOME_RELATORIOPATRIMONIO = 'RelatorioPatrimonio'; // Permitir visualizar a lista de bens de uma empresa
    const NOME_RELATORIOCARTEIRAS = 'RelatorioCarteiras'; // Permitir visualizar o relatório de carteiras
    const NOME_RELATORIOCHEQUES = 'RelatorioCheques'; // Permitir visualizar o relatório de cheques
    const NOME_PAGAMENTOCONTA = 'PagamentoConta'; // Permitir pagar um pedido na forma de pagamento Conta
    const NOME_CADASTROPAISES = 'CadastroPaises'; // Permitir cadastrar ou alterar paises
    const NOME_CADASTROESTADOS = 'CadastroEstados'; // Permitir cadastrar ou alterar os estados de um país
    const NOME_CADASTROMOEDAS = 'CadastroMoedas'; // Permitir cadastrar ou alterar os tipos de moedas
    const NOME_ALTERARPAGINAS = 'AlterarPaginas'; // Permitir alterar as páginas do site da empresa
    const NOME_ALTERARENTREGADOR = 'AlterarEntregador'; // Permitir alterar o entregador após enviar os pedidos
    const NOME_RELATORIOBALANCO = 'RelatorioBalanco'; // Permitir visualizar o relatório de balanço de contas
    // Permitir transformar um pedido de entrega para viagem e vice versa
    const NOME_TRANSFORMARENTREGA = 'TransformarEntrega';
    const NOME_CONFERIRCAIXA = 'ConferirCaixa'; // Permitir alterar os valores de conferência de um caixa
    const NOME_CONTAVIAGEM = 'ContaViagem'; // Permitir imprimir conta de pedidos para viagem
    const NOME_ENTREGAADICIONAR = 'EntregaAdicionar'; // Permitir adicionar produtos na tela de entrega
    const NOME_ENTREGARPEDIDOS = 'EntregarPedidos'; // Permitir realizar entrega de pedidos
    const NOME_INFORMARDESPERDICIO = 'InformarDesperdicio'; // Permitir informar um desperdício ao cancelar um produto

    /**
     * Identificador da permissão
     */
    private $id;
    /**
     * Categoriza um grupo de permissões
     */
    private $funcionalidade_id;
    /**
     * Nome da permissão, único no sistema
     */
    private $nome;
    /**
     * Descreve a permissão
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Permissao
     * @param array $permissao All field and values to fill the instance
     */
    public function __construct($permissao = [])
    {
        parent::__construct($permissao);
    }

    /**
     * Identificador da permissão
     * @return mixed ID of Permissao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Permissao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Categoriza um grupo de permissões
     * @return mixed Funcionalidade of Permissao
     */
    public function getFuncionalidadeID()
    {
        return $this->funcionalidade_id;
    }

    /**
     * Set FuncionalidadeID value to new on param
     * @param  mixed $funcionalidade_id new value for FuncionalidadeID
     * @return Permissao Self instance
     */
    public function setFuncionalidadeID($funcionalidade_id)
    {
        $this->funcionalidade_id = $funcionalidade_id;
        return $this;
    }

    /**
     * Nome da permissão, único no sistema
     * @return mixed Nome of Permissao
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Permissao Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descreve a permissão
     * @return mixed Descrição of Permissao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Permissao Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $permissao = parent::toArray($recursive);
        $permissao['id'] = $this->getID();
        $permissao['funcionalidadeid'] = $this->getFuncionalidadeID();
        $permissao['nome'] = $this->getNome();
        $permissao['descricao'] = $this->getDescricao();
        return $permissao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $permissao Associated key -> value to assign into this instance
     * @return Permissao Self instance
     */
    public function fromArray($permissao = [])
    {
        if ($permissao instanceof Permissao) {
            $permissao = $permissao->toArray();
        } elseif (!is_array($permissao)) {
            $permissao = [];
        }
        parent::fromArray($permissao);
        if (!isset($permissao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($permissao['id']);
        }
        if (!isset($permissao['funcionalidadeid'])) {
            $this->setFuncionalidadeID(null);
        } else {
            $this->setFuncionalidadeID($permissao['funcionalidadeid']);
        }
        if (!isset($permissao['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($permissao['nome']);
        }
        if (!isset($permissao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($permissao['descricao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $permissao = parent::publish();
        return $permissao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Permissao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setFuncionalidadeID(Filter::number($this->getFuncionalidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Permissao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Permissao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncionalidadeID())) {
            $errors['funcionalidadeid'] = 'A funcionalidade não pode ser vazia';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'A nome não pode ser vazia';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => sprintf(
                    'A nome "%s" já está cadastrada',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Permissão into the database and fill instance from database
     * @return Permissao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Permissoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Permissão with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Permissao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da permissão não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Permissoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da permissão não foi informado');
        }
        $result = DB::deleteFrom('Permissoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Permissao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Permissão
     * @return Permissao Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Categoriza um grupo de permissões
     * @return \MZ\System\Funcionalidade The object fetched from database
     */
    public function findFuncionalidadeID()
    {
        return \MZ\System\Funcionalidade::findByID($this->getFuncionalidadeID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $permissao = new Permissao();
        $allowed = Filter::concatKeys('p.', $permissao->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'p.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Permissoes p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.funcionalidadeid ASC');
        $query = $query->orderBy('p.descricao ASC');
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Permissao A filled Permissão or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Permissao($row);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Permissão
     * @return Permissao A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        return $result->loadByNome($nome);
    }

    /**
     * Find all Permissão
     * @param  array  $condition Condition to get all Permissão
     * @param  array  $order     Order Permissão
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Permissao
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Permissao($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
