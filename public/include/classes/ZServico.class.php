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
class ServicoTipo
{
    const EVENTO = 'Evento';
    const TAXA = 'Taxa';
}

/**
 * Taxas, eventos e serviço cobrado nos pedidos
 */
class ZServico
{

    const DESCONTO_ID = 1;
    const ENTREGA_ID = 2;

    private $id;
    private $nome;
    private $descricao;
    private $detalhes;
    private $tipo;
    private $obrigatorio;
    private $data_inicio;
    private $data_fim;
    private $valor;
    private $individual;
    private $ativo;

    public function __construct($servico = array())
    {
        if (is_array($servico)) {
            $this->setID(isset($servico['id'])?$servico['id']:null);
            $this->setNome(isset($servico['nome'])?$servico['nome']:null);
            $this->setDescricao(isset($servico['descricao'])?$servico['descricao']:null);
            $this->setDetalhes(isset($servico['detalhes'])?$servico['detalhes']:null);
            $this->setTipo(isset($servico['tipo'])?$servico['tipo']:null);
            $this->setObrigatorio(isset($servico['obrigatorio'])?$servico['obrigatorio']:null);
            $this->setDataInicio(isset($servico['datainicio'])?$servico['datainicio']:null);
            $this->setDataFim(isset($servico['datafim'])?$servico['datafim']:null);
            $this->setValor(isset($servico['valor'])?$servico['valor']:null);
            $this->setIndividual(isset($servico['individual'])?$servico['individual']:null);
            $this->setAtivo(isset($servico['ativo'])?$servico['ativo']:null);
        }
    }

    /**
     * Identificador do serviço
     */
    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Nome do serviço, Ex.: Comissão, Entrega, Couvert
     */
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Descrição do serviço, Ex.: Show de fulano
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Detalhes do serviço, Ex.: Com participação especial de fulano
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
    }

    /**
     * Tipo de serviço, Evento: Eventos como show no estabelecimento
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Informa se a taxa é obrigatória
     */
    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * Informa se a taxa é obrigatória
     */
    public function isObrigatorio()
    {
        return $this->obrigatorio == 'Y';
    }

    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;
    }

    /**
     * Data de início do evento
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
    }

    /**
     * Data final do evento
     */
    public function getDataFim()
    {
        return $this->data_fim;
    }

    public function setDataFim($data_fim)
    {
        $this->data_fim = $data_fim;
    }

    /**
     * Valor do serviço
     */
    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Informa se a taxa ou serviço é individual para cada pessoa
     */
    public function isIndividual()
    {
        return $this->individual == 'Y';
    }

    public function setIndividual($individual)
    {
        $this->individual = $individual;
    }

    /**
     * Informa se o serviço está ativo
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o serviço está ativo
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function toArray()
    {
        $servico = array();
        $servico['id'] = $this->getID();
        $servico['nome'] = $this->getNome();
        $servico['descricao'] = $this->getDescricao();
        $servico['detalhes'] = $this->getDetalhes();
        $servico['tipo'] = $this->getTipo();
        $servico['obrigatorio'] = $this->getObrigatorio();
        $servico['datainicio'] = $this->getDataInicio();
        $servico['datafim'] = $this->getDataFim();
        $servico['valor'] = $this->getValor();
        $servico['individual'] = $this->getIndividual();
        $servico['ativo'] = $this->getAtivo();
        return $servico;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Servicos')
                         ->where(array('id' => $id));
        return new ZServico($query->fetch());
    }

    private static function validarCampos(&$servico)
    {
        $erros = array();
        $servico['nome'] = strip_tags(trim($servico['nome']));
        if (strlen($servico['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $servico['descricao'] = strip_tags(trim($servico['descricao']));
        if (strlen($servico['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        $servico['detalhes'] = strip_tags(trim($servico['detalhes']));
        if (strlen($servico['detalhes']) == 0) {
            $servico['detalhes'] = null;
        }
        $servico['tipo'] = strval($servico['tipo']);
        if (!in_array($servico['tipo'], array('Evento', 'Taxa'))) {
            $erros['tipo'] = 'O tipo informado não é válido';
        }
        $servico['obrigatorio'] = trim($servico['obrigatorio']);
        if (strlen($servico['obrigatorio']) == 0) {
            $servico['obrigatorio'] = 'N';
        } elseif (!in_array($servico['obrigatorio'], array('Y', 'N'))) {
            $erros['obrigatorio'] = 'O obrigatório informado não é válido';
        }
        if ($servico['tipo'] == ServicoTipo::EVENTO) {
            $datainicio = strtotime($servico['datainicio']);
            if ($datainicio === false) {
                $erros['datainicio'] = 'A data de ínicio do evento é inválida';
            } else {
                $servico['datainicio'] = date('Y-m-d H:i:s', $datainicio);
            }
            $datafim = strtotime($servico['datafim']);
            if ($datafim === false) {
                $erros['datafim'] = 'A data final do evento é inválida';
            } else {
                $servico['datafim'] = date('Y-m-d H:i:s', $datafim);
            }
        } else {
            $servico['datainicio'] = null;
            $servico['datafim'] = null;
        }
        if (!is_numeric($servico['valor'])) {
            $erros['valor'] = 'O valor não foi informado';
        } else {
            $servico['valor'] = floatval($servico['valor']);
            if ($servico['valor'] < 0) {
                $erros['valor'] = 'O valor não pode ser negativo';
            } elseif (is_equal($servico['valor'], 0)) {
                $erros['valor'] = 'O valor não pode ser nulo';
            }
        }
        $servico['individual'] = trim($servico['individual']);
        if (strlen($servico['individual']) == 0) {
            $servico['individual'] = 'N';
        } elseif (!in_array($servico['individual'], array('Y', 'N'))) {
            $erros['individual'] = 'O individual informado não é válido';
        }
        $servico['ativo'] = trim($servico['ativo']);
        if (strlen($servico['ativo']) == 0) {
            $servico['ativo'] = 'N';
        } elseif (!in_array($servico['ativo'], array('Y', 'N'))) {
            $erros['ativo'] = 'O ativo informado não é válido';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
    }

    public static function cadastrar($servico)
    {
        $_servico = $servico->toArray();
        self::validarCampos($_servico);
        try {
            $_servico['id'] = DB::$pdo->insertInto('Servicos')->values($_servico)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_servico['id']);
    }

    public static function atualizar($servico)
    {
        $_servico = $servico->toArray();
        if (!$_servico['id']) {
            throw new ValidationException(array('id' => 'O id do servico não foi informado'));
        }
        if ($_servico['id'] >= self::DESCONTO_ID && $_servico['id'] <= self::ENTREGA_ID) {
            throw new Exception('Não é possível alterar esse serviço, o serviço é utilizado internamente pelo sistema');
        }
        self::validarCampos($_servico);
        $campos = array(
            'nome',
            'descricao',
            'detalhes',
            'tipo',
            'obrigatorio',
            'datainicio',
            'datafim',
            'valor',
            'individual',
            'ativo',
        );
        try {
            $query = DB::$pdo->update('Servicos');
            $query = $query->set(array_intersect_key($_servico, array_flip($campos)));
            $query = $query->where('id', $_servico['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_servico['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o servico, o id do serviço não foi informado');
        }
        if ($id >= self::DESCONTO_ID && $id <= self::ENTREGA_ID) {
            throw new Exception('Não é possível excluir esse serviço, o serviço é utilizado internamente pelo sistema');
        }
        $query = DB::$pdo->deleteFrom('Servicos')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $tipo)
    {
        $query = DB::$pdo->from('Servicos')
                         ->orderBy('id ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('CONCAT(nome, " ", descricao, " ", COALESCE(detalhes, "")) LIKE ?', '%'.$busca.'%');
        }
        $tipo = trim($tipo);
        if ($tipo != '') {
            $query = $query->where('tipo', $tipo);
        }
        return $query;
    }

    public static function getTodos($busca = null, $tipo = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $tipo);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_servicos = $query->fetchAll();
        $servicos = array();
        foreach ($_servicos as $servico) {
            $servicos[] = new ZServico($servico);
        }
        return $servicos;
    }

    public static function getCount($busca = null, $tipo = null)
    {
        $query = self::initSearch($busca, $tipo);
        return $query->count();
    }
}
