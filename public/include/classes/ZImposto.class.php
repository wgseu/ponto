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
class ImpostoGrupo
{
    const ICMS = 'ICMS';
    const PIS = 'PIS';
    const COFINS = 'COFINS';
    const IPI = 'IPI';
    const II = 'II';
}

/**
 * Impostos disponíveis para informar no produto
 */
class ZImposto
{
    private $id;
    private $grupo;
    private $simples;
    private $substituicao;
    private $codigo;
    private $descricao;

    public function __construct($imposto = [])
    {
        $this->fromArray($imposto);
    }

    /**
     * Identificador do imposto
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
     * Grupo do imposto
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * Informa se o imposto é do simples nacional
     */
    public function getSimples()
    {
        return $this->simples;
    }

    /**
     * Informa se o imposto é do simples nacional
     */
    public function isSimples()
    {
        return $this->simples == 'Y';
    }

    public function setSimples($simples)
    {
        $this->simples = $simples;
    }

    /**
     * Informa se o imposto é por substituição tributária
     */
    public function getSubstituicao()
    {
        return $this->substituicao;
    }

    /**
     * Informa se o imposto é por substituição tributária
     */
    public function isSubstituicao()
    {
        return $this->substituicao == 'Y';
    }

    public function setSubstituicao($substituicao)
    {
        $this->substituicao = $substituicao;
    }

    /**
     * Informa o código do imposto
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Descrição do imposto
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function toArray()
    {
        $imposto = [];
        $imposto['id'] = $this->getID();
        $imposto['grupo'] = $this->getGrupo();
        $imposto['simples'] = $this->getSimples();
        $imposto['substituicao'] = $this->getSubstituicao();
        $imposto['codigo'] = $this->getCodigo();
        $imposto['descricao'] = $this->getDescricao();
        return $imposto;
    }

    public function fromArray($imposto = [])
    {
        if (!is_array($imposto)) {
            return $this;
        }
        $this->setID(isset($imposto['id'])?$imposto['id']:null);
        $this->setGrupo(isset($imposto['grupo'])?$imposto['grupo']:null);
        $this->setSimples(isset($imposto['simples'])?$imposto['simples']:null);
        $this->setSubstituicao(isset($imposto['substituicao'])?$imposto['substituicao']:null);
        $this->setCodigo(isset($imposto['codigo'])?$imposto['codigo']:null);
        $this->setDescricao(isset($imposto['descricao'])?$imposto['descricao']:null);
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Impostos')
                         ->where(['id' => $id]);
        return new Imposto($query->fetch());
    }

    public static function getPeloGrupoSimplesSubstituicaoCodigo($grupo, $simples, $substituicao, $codigo)
    {
        $query = \DB::$pdo->from('Impostos')
                         ->where(['grupo' => $grupo, 'simples' => $simples, 'substituicao' => $substituicao, 'codigo' => $codigo]);
        return new Imposto($query->fetch());
    }

    private static function validarCampos(&$imposto)
    {
        $erros = [];
        $imposto['grupo'] = strval($imposto['grupo']);
        if (!in_array($imposto['grupo'], ['ICMS', 'PIS', 'COFINS', 'IPI', 'II'])) {
            $erros['grupo'] = 'O grupo informado não é válido';
        }
        $imposto['simples'] = strval($imposto['simples']);
        if (strlen($imposto['simples']) == 0) {
            $imposto['simples'] = 'N';
        } elseif (!in_array($imposto['simples'], ['Y', 'N'])) {
            $erros['simples'] = 'O simples nacional informado não é válido';
        }
        $imposto['substituicao'] = strval($imposto['substituicao']);
        if (strlen($imposto['substituicao']) == 0) {
            $imposto['substituicao'] = 'N';
        } elseif (!in_array($imposto['substituicao'], ['Y', 'N'])) {
            $erros['substituicao'] = 'A substituição tributária informada não é válida';
        }
        if (!is_numeric($imposto['codigo'])) {
            $erros['codigo'] = 'O código não foi informado';
        }
        $imposto['descricao'] = strip_tags(trim($imposto['descricao']));
        if (strlen($imposto['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O id informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'UK_Imposto') !== false) {
            throw new ValidationException(['codigo' => 'O código informado já está cadastrado']);
        }
    }

    public static function cadastrar($imposto)
    {
        $_imposto = $imposto->toArray();
        self::validarCampos($_imposto);
        try {
            $_imposto['id'] = \DB::$pdo->insertInto('Impostos')->values($_imposto)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_imposto['id']);
    }

    public static function atualizar($imposto)
    {
        $_imposto = $imposto->toArray();
        if (!$_imposto['id']) {
            throw new ValidationException(['id' => 'O id do imposto não foi informado']);
        }
        self::validarCampos($_imposto);
        $campos = [
            'grupo',
            'simples',
            'substituicao',
            'codigo',
            'descricao',
        ];
        try {
            $query = \DB::$pdo->update('Impostos');
            $query = $query->set(array_intersect_key($_imposto, array_flip($campos)));
            $query = $query->where('id', $_imposto['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_imposto['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir o imposto, o id do imposto não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Impostos')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch()
    {
        return   \DB::$pdo->from('Impostos')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_impostos = $query->fetchAll();
        $impostos = [];
        foreach ($_impostos as $imposto) {
            $impostos[] = new Imposto($imposto);
        }
        return $impostos;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }
}
