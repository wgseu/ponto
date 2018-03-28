<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class PermissaoNome
{
    const SISTEMA = 'Sistema'; // Permitir acesso ao sistema
    const RESTAURACAO = 'Restauracao'; // Permitir restaurar o banco de dados
    const BACKUP = 'Backup'; // Permitir realização de cópia de segurança do banco de dados
    const PEDIDOMESA = 'PedidoMesa'; // Permitir realizar pedidos para uma mesa
    const PAGAMENTO = 'Pagamento'; // Permitir realizar um pagamento e efetuar vendas rápidas
    const MUDARDEMESA = 'MudarDeMesa'; // Permitir mudar os pedidos de uma mesa para outra
    const EXCLUIRPEDIDO = 'ExcluirPedido'; // Permitir cancelar produtos de um pedido
    const RESERVARMESA = 'ReservarMesa'; // Permitir reservar uma mesa
    const LIBERARMESA = 'LiberarMesa'; // Permitir liberar mesa reservada por outro funcionário
    const SELECIONARCAIXA = 'SelecionarCaixa'; // Permitir selecionar outro caixa aberto
    const ABRIRCAIXA = 'AbrirCaixa'; // Permitir abrir o caixa
    const INSERIRNOCAIXA = 'InserirNoCaixa'; // Permitir inserir dinheiro no caixa
    const RETIRARDOCAIXA = 'RetirarDoCaixa'; // Permitir retirar dinheiro do caixa
    const FECHARCAIXA = 'FecharCaixa'; // Permitir fechar qualquer caixa aberto
    const ALTERARPRECO = 'AlterarPreco'; // Permitir alterar o preço de um produto no momento da venda
    const MESAS = 'Mesas'; // Permitir acesso à venda para todas as mesas
    const ESTOQUE = 'Estoque'; // Permitir dar entrada de produtos no estoque
    const OPCOESIMPRESSAO = 'OpcoesImpressao'; // Permitir alteração das opções de impressão de relatórios
    const TROCARPLANO = 'TrocarPlano'; // Permitir trocar plano de fundo da aplicação
    const MODOTELA = 'ModoTela'; // Permitir trocar o modo de tela da aplicação
    const RELATORIOVENDAS = 'RelatorioVendas'; // Permitir visualizar todas as vendas
    const RELATORIOVENDEDOR = 'RelatorioVendedor'; // Permitir visualizar o total das vendas de todos vendedores
    const RELATORIOCAIXA = 'RelatorioCaixa'; // Permitir visualizar o relatório de vendas por caixa
    const RELATORIOSESSAO = 'RelatorioSessao'; // Permitir visualizar o total de vendas de cada sessão
    const RELATORIOCONSUMO = 'RelatorioConsumo'; // Permitir a impressão do relatório de consumo da mesa
    const RELATORIOCOZINHA = 'RelatorioCozinha'; // Permitir a reimpressão dos pedidos enviados para a cozinha
    const RANKDEVENDAS = 'RankDeVendas'; // Permitir visualizar o ranking dos funcionários nas vendas
    const CADASTROPRODUTOS = 'CadastroProdutos'; // Permitir cadastrar ou alterar um produto
    const CADASTROFORNECEDORES = 'CadastroFornecedores'; // Permitir cadastrar ou alterar um fornecedor
    const CADASTROMESAS = 'CadastroMesas'; // Permitir cadastrar ou alterar uma mesa
    const CADASTROFUNCIONARIOS = 'CadastroFuncionarios'; // Permitir cadastrar ou alterar um funcionário
    const CADASTROFORMASPAGTO = 'CadastroFormasPagto'; // Permitir cadastrar ou alterar uma forma de pagamento
    const CADASTROPROMOCOES = 'CadastroPromocoes'; // Permitir cadastrar ou alterar uma promoção
    const CADASTROCARTOES = 'CadastroCartoes'; // Permitir cadastrar ou alterar um cartão
    const CADASTROBANCOS = 'CadastroBancos'; // Permitir cadastrar ou alterar um banco
    const CADASTROCAIXAS = 'CadastroCaixas'; // Permitir cadastrar ou alterar um caixa
    const CADASTROTIPOSDECONTAS = 'CadastroTiposDeContas'; // Permitir cadastrar ou alterar um tipo de conta
    const CADASTROIMPRESSORAS = 'CadastroImpressoras'; // Permitir cadastrar ou alterar uma impressora
    const CADASTROCOMPUTADORES = 'CadastroComputadores'; // Permitir cadastrar ou alterar um computador
    const COMPUTADORCAIXA = 'ComputadorCaixa'; // Permitir acessar computadores reservados para o caixa
    const CADASTROCLIENTES = 'CadastroClientes'; // Permitir cadastrar ou alterar um cliente
    const ENTREGAPEDIDOS = 'EntregaPedidos'; // Permitir acessar os pedidos de produtos que são para entrega
    const ALTERARATENDENTE = 'AlterarAtendente'; // Permitir alterar o atendente no momento da venda
    const PEDIDOCOMANDA = 'PedidoComanda'; // Permitir realizar pedidos para cartões de consumo
    const CADASTROBAIRROS = 'CadastroBairros'; // Permitir cadastrar ou alterar informações de um bairro
    const CADASTROCONTAS = 'CadastroContas'; // Permitir cadastrar ou alterar contas a pagar ou a receber
    const RELATORIOCONTAS = 'RelatorioContas'; // Permitir visualizar relatórios de contas
    const RELATORIOPRODUTOS = 'RelatorioProdutos'; // Permitir visualizar relatórios de vendas de produtos
    const RELATORIOCOMPRAS = 'RelatorioCompras'; // Permitir visualizar relatórios de compras de produtos
    const CADASTROCOMANDAS = 'CadastroComandas'; // Permitir cadastrar ou alterar um número de comanda
    const CADASTROSERVICOS = 'CadastroServicos'; // Permitir cadastrar ou alterar uma taxa ou evento
    const REALIZARDESCONTOS = 'RealizarDescontos'; // Permitir realizar desconto nas vendas
    const COMANDAS = 'Comandas'; // Permitir acesso à venda para todas as comandas
    const EXCLUIRPEDIDOFINALIZADO = 'ExcluirPedidoFinalizado'; // Permitir excluir um pedido que já foi finalizado
    const RELATORIOFUNCIONARIOS = 'RelatorioFuncionarios'; // Permitir visualizar relatório de funcionários
    const RELATORIOCLIENTES = 'RelatorioClientes'; // Permitir visualizar relatório de clientes
    const REVOGARCOMISSAO = 'RevogarComissao'; // Permitir retirar a comissão de um pedido
    const SELECIONARENTREGADOR = 'SelecionarEntregador'; // Permitir selecionar outro entregador na entrega de pedidos para entrega
    const TRANSFERIRPRODUTOS = 'TransferirProdutos'; // Permitir transferir produtos de uma mesa para outra
    const RELATORIOPEDIDOS = 'RelatorioPedidos'; // Permitir visualizar relatório de pedidos
    const ALTERARCONFIGURACOES = 'AlterarConfiguracoes'; // Permitir alterar informações da empresa e configurações do sistema
    const LISTACOMPRAS = 'ListaCompras'; // Permitir cadastrar lista de compras de produtos
    const RELATORIOMENSAL = 'RelatorioMensal'; // Permitir visualizar e emitir relatórios de vendas mensais
    const CADASTROCIDADES = 'CadastroCidades'; // Permitir cadastrar ou alterar as cidades dos estados
    const RETIRARDOESTOQUE = 'RetirarDoEstoque'; // Permitir retirar produtos do estoque
    const RELATORIOBAIRROS = 'RelatorioBairros'; // Permitir visualizar relatórios de bairros
    const ALTERARHORARIO = 'AlterarHorario'; // Permitir alterar o horário de funcionamento do estabelecimento
    const CADASTRARCREDITOS = 'CadastrarCreditos'; // Permitir cadastrar e alterar créditos de clientes
    const ALTERARSTATUS = 'AlterarStatus'; // Permitir alterar os estados de preparo dos produtos
    const RELATORIOENTREGA = 'RelatorioEntrega'; // Permitir visualizar relatório de entrega por entregador
    const RELATORIOFORNECEDORES = 'RelatorioFornecedores'; // Permitir visualizar relatório de fornecedores
    const MUDARDECOMANDA = 'MudarDeComanda'; // Permitir mudar os pedidos de uma comanda para comanda
    const RELATORIOAUDITORIA = 'RelatorioAuditoria'; // Permitir visualizar o relatório de auditoria
    const RELATORIOCONSUMIDOR = 'RelatorioConsumidor'; // Permitir visualizar o relatório de vendas por cliente
    const RELATORIOCREDITOS = 'RelatorioCreditos'; // Permitir visualizar o relatório de créditos de clientes
    const CADASTROCARTEIRAS = 'CadastroCarteiras'; // Permitir cadastrar carteiras e contas bancárias
    const RELATORIOFLUXO = 'RelatorioFluxo'; // Permitir visualizar o relatório de fluxo de caixa
    const TRANSFERIRVALORES = 'TransferirValores'; // Permitir transferir dinheiro de um caixa para outro
    const CADASTROPATRIMONIO = 'CadastroPatrimonio'; // Permitir cadastrar e atualizar a quantidade de bens de uma empresa
    const RELATORIOPATRIMONIO = 'RelatorioPatrimonio'; // Permitir visualizar a lista de bens de uma empresa
    const RELATORIOCARTEIRAS = 'RelatorioCarteiras'; // Permitir visualizar o relatório de carteiras
    const RELATORIOCHEQUES = 'RelatorioCheques'; // Permitir visualizar o relatório de cheques
    const PAGAMENTOCONTA = 'PagamentoConta'; // Permitir pagar um pedido na forma de pagamento Conta
    const CADASTROPAISES = 'CadastroPaises'; // Permitir cadastrar ou alterar paises
    const CADASTROESTADOS = 'CadastroEstados'; // Permitir cadastrar ou alterar os estados de um país
    const CADASTROMOEDAS = 'CadastroMoedas'; // Permitir cadastrar ou alterar os tipos de moedas
    const ALTERARPAGINAS = 'AlterarPaginas'; // Permitir alterar as páginas do site da empresa
    const ALTERARENTREGADOR = 'AlterarEntregador'; // Permitir alterar o entregador após enviar os pedidos
    const RELATORIOBALANCO = 'RelatorioBalanco'; // Permitir visualizar o relatório de balanço de contas
    const TRANSFORMARENTREGA = 'TransformarEntrega'; // Permitir transformar um pedido de entrega para viagem e vice versa
    const CONFERIRCAIXA = 'ConferirCaixa'; // Permitir alterar os valores de conferência de um caixa
    const CONTAVIAGEM = 'ContaViagem'; // Permitir imprimir conta de pedidos para viagem
    const ENTREGAADICIONAR = 'EntregaAdicionar'; // Permitir adicionar produtos na tela de entrega
    const ENTREGARPEDIDOS = 'EntregarPedidos'; // Permitir realizar entrega de pedidos
    const INFORMARDESPERDICIO = 'InformarDesperdicio'; // Permitir informar um desperdício ao cancelar um produto
}

/**
 * Informa a listagem de todas as funções do sistema
 */
class ZPermissao
{
    private $id;
    private $funcionalidade_id;
    private $nome;
    private $descricao;

    public function __construct($permissao = [])
    {
        if (is_array($permissao)) {
            $this->setID(isset($permissao['id'])?$permissao['id']:null);
            $this->setFuncionalidadeID(isset($permissao['funcionalidadeid'])?$permissao['funcionalidadeid']:null);
            $this->setNome(isset($permissao['nome'])?$permissao['nome']:null);
            $this->setDescricao(isset($permissao['descricao'])?$permissao['descricao']:null);
        }
    }

    /**
     * Identificador da permissão
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador da permissão
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Categoriza um grupo de permissões
     */
    public function getFuncionalidadeID()
    {
        return $this->funcionalidade_id;
    }

    /**
     * Categoriza um grupo de permissões
     */
    public function setFuncionalidadeID($funcionalidade_id)
    {
        $this->funcionalidade_id = $funcionalidade_id;
    }

    /**
     * Nome da permissão, único no sistema
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Nome da permissão, único no sistema
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Descreve a permissão
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descreve a permissão
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
    public function toArray()
    {
        $permissao = [];
        $permissao['id'] = $this->getID();
        $permissao['funcionalidadeid'] = $this->getFuncionalidadeID();
        $permissao['nome'] = $this->getNome();
        $permissao['descricao'] = $this->getDescricao();
        return $permissao;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Permissoes')
                         ->where(['id' => $id]);
        return new Permissao($query->fetch());
    }

    public static function getPeloNome($nome)
    {
        $query = \DB::$pdo->from('Permissoes')
                         ->where(['nome' => $nome]);
        return new Permissao($query->fetch());
    }

    private static function validarCampos(&$permissao)
    {
        $erros = [];
        if (!is_numeric($permissao['funcionalidadeid'])) {
            $erros['funcionalidadeid'] = 'A funcionalidade não foi informada';
        }
        $permissao['nome'] = strip_tags(trim($permissao['nome']));
        if (strlen($permissao['nome']) == 0) {
            $erros['nome'] = 'A nome não pode ser vazia';
        }
        $permissao['descricao'] = strip_tags(trim($permissao['descricao']));
        if (strlen($permissao['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            throw new ValidationException(['nome' => 'O Nome informado já está cadastrado']);
        }
    }

    private static function initSearch($busca)
    {
        $query = \DB::$pdo->from('Permissoes')
                         ->orderBy('funcionalidadeid ASC, descricao ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodas($busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_permissaos = $query->fetchAll();
        $permissaos = [];
        foreach ($_permissaos as $permissao) {
            $permissaos[] = new Permissao($permissao);
        }
        return $permissaos;
    }

    public static function getCount($busca)
    {
        $query = self::initSearch($busca);
        return $query->count();
    }

    private static function initSearchDaFuncionalidadeID($funcionalidade_id)
    {
        return   \DB::$pdo->from('Permissoes')
                         ->where(['funcionalidadeid' => $funcionalidade_id])
                         ->orderBy('id ASC');
    }

    public static function getTodasDaFuncionalidadeID($funcionalidade_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaFuncionalidadeID($funcionalidade_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_permissaos = $query->fetchAll();
        $permissaos = [];
        foreach ($_permissaos as $permissao) {
            $permissaos[] = new Permissao($permissao);
        }
        return $permissaos;
    }

    public static function getCountDaFuncionalidadeID($funcionalidade_id)
    {
        $query = self::initSearchDaFuncionalidadeID($funcionalidade_id);
        return $query->count();
    }
}
