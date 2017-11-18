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
class ZCategoria
{

    private $id;
    private $categoria_id;
    private $descricao;
    private $servico;
    private $imagem;
    private $data_atualizacao;

    public function __construct($categoria = array())
    {
        if (is_array($categoria)) {
            $this->setID(isset($categoria['id'])?$categoria['id']:null);
            $this->setCategoriaID(isset($categoria['categoriaid'])?$categoria['categoriaid']:null);
            $this->setDescricao(isset($categoria['descricao'])?$categoria['descricao']:null);
            $this->setServico(isset($categoria['servico'])?$categoria['servico']:null);
            $this->setImagem(isset($categoria['imagem'])?$categoria['imagem']:null);
            $this->setDataAtualizacao(isset($categoria['dataatualizacao'])?$categoria['dataatualizacao']:null);
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma sub-categoria
     */
    public function getCategoriaID()
    {
        return $this->categoria_id;
    }

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma sub-categoria
     */
    public function setCategoriaID($categoria_id)
    {
        $this->categoria_id = $categoria_id;
    }

    /**
     * Descrição da categoria. Ex.: Refrigerantes, Salgados
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descrição da categoria. Ex.: Refrigerantes, Salgados
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     */
    public function isServico()
    {
        return $this->servico == 'Y';
    }

    /**
     * Informa se a categoria é destinada para produtos ou serviços
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
    }

    /**
     * Imagem representativa da categoria
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * Imagem representativa da categoria
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * Data de atualização das informações da categoria
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Data de atualização das informações da categoria
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
    }

    public function toArray()
    {
        $categoria = array();
        $categoria['id'] = $this->getID();
        $categoria['categoriaid'] = $this->getCategoriaID();
        $categoria['descricao'] = $this->getDescricao();
        $categoria['servico'] = $this->getServico();
        $categoria['imagem'] = $this->getImagem();
        $categoria['dataatualizacao'] = $this->getDataAtualizacao();
        return $categoria;
    }

    private static function initGet()
    {
        return DB::$pdo->from('Categorias c')
                         ->select(null)
                         ->select('c.id')
                         ->select('c.categoriaid')
                         ->select('c.descricao')
                         ->select('c.servico')
                         ->select('IF(IsNull(c.imagem), NULL, CONCAT(c.id, ".png")) as imagem')
                         ->select('c.dataatualizacao');
    }

    public static function getPeloID($categoria_id)
    {
        $query = self::initGet()->where(array('c.id' => $categoria_id));
        return new ZCategoria($query->fetch());
    }

    public static function getImagemPeloID($categoria_id, $dataSomente = false)
    {
        $query = DB::$pdo->from('Categorias c')
                         ->select(null)
                         ->select('c.dataatualizacao')
                         ->where(array('c.id' => $categoria_id));
        if (!$dataSomente) {
            $query = $query->select('c.imagem');
        }
        return $query->fetch();
    }

    private static function validarCampos(&$categoria)
    {
        $erros = array();
        $categoria['categoriaid'] = trim($categoria['categoriaid']);
        if (strlen($categoria['categoriaid']) == 0) {
            $categoria['categoriaid'] = null;
        } elseif (!is_numeric($categoria['categoriaid'])) {
            $erros['categoriaid'] = 'A categoria superior é inválida';
        } else {
            $_categoria = self::getPeloID($categoria['categoriaid']);
            if (is_null($_categoria->getID())) {
                $erros['categoriaid'] = 'A categoria informada não existe';
            } elseif (!is_null($_categoria->getCategoriaID())) {
                $erros['categoriaid'] = 'A categoria informada já é uma subcategoria';
            } elseif ($_categoria->getID() == $categoria['id']) {
                $erros['categoriaid'] = 'A categoria superior não pode ser a própria categoria';
            }
        }
        $categoria['descricao'] = strip_tags(trim($categoria['descricao']));
        if (strlen($categoria['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        $categoria['servico'] = strval($categoria['servico']);
        if (strlen($categoria['servico']) == 0) {
            $categoria['servico'] = 'N';
        } elseif (!in_array($categoria['servico'], array('Y', 'N'))) {
            $erros['servico'] = 'O serviço informado não é válido';
        }
        if ($categoria['imagem'] === '') {
            $categoria['imagem'] = null;
        }
        $categoria['dataatualizacao'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            throw new ValidationException(array('descricao' => 'A descrição informada já está cadastrada'));
        }
    }

    public static function cadastrar($categoria)
    {
        $_categoria = $categoria->toArray();
        self::validarCampos($_categoria);
        try {
            $_categoria['id'] = DB::$pdo->insertInto('Categorias')->values($_categoria)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_categoria['id']);
    }

    public static function atualizar($categoria)
    {
        $_categoria = $categoria->toArray();
        if (!$_categoria['id']) {
            throw new ValidationException(array('id' => 'O id da categoria não foi informado'));
        }
        self::validarCampos($_categoria);
        $campos = array(
            'categoriaid',
            'descricao',
            'servico',
            'dataatualizacao',
        );
        if ($_categoria['imagem'] !== true) {
            $campos[] = 'imagem';
        }
        try {
            $query = DB::$pdo->update('Categorias');
            $query = $query->set(array_intersect_key($_categoria, array_flip($campos)));
            $query = $query->where('id', $_categoria['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_categoria['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a categoria, o id da categoria não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Categorias')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    public static function initSearch($todas, $superiores, $busca)
    {
        $query = self::initGet()
            ->leftJoin('Produtos p ON p.categoriaid = c.id AND p.visivel = "Y"')
            ->leftJoin(
                'Produtos_Pedidos pp ON pp.produtoid = p.id AND pp.datahora > DATE_SUB(NOW(), INTERVAL 1 MONTH)'
            )
            ->groupBy('c.id');
        if (!$todas) {
            $query = $query->orderBy('SUM(pp.quantidade) DESC')
                           ->having('COUNT(p.id) > 0');
        }
        if ($superiores && is_numeric($superiores)) {
            $query = $query->where('c.categoriaid', intval($superiores));
        } elseif ($superiores) {
            $query = $query->where('c.categoriaid', null);
        }
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('c.descricao LIKE ?', '%'.$busca.'%');
        }
        $query = $query->orderBy('c.descricao ASC');
        return $query;
    }

    public static function getTodas(
        $todas = false,
        $superiores = false,
        $busca = null,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch($todas, $superiores, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_categorias = $query->fetchAll();
        $categorias = array();
        foreach ($_categorias as $categoria) {
            $categorias[] = new ZCategoria($categoria);
        }
        return $categorias;
    }

    public static function getCount($tudo = false, $superiores = false, $busca = null)
    {
        $query = self::initSearch($tudo, $superiores, $busca);
        $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT c.id)');
        return (int) $query->fetchColumn();
    }
}
