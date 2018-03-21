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
/**
 * Informa tamanhos de pizzas e opções de peso do produto
 */
class ZPropriedade
{
    private $id;
    private $grupo_id;
    private $nome;
    private $abreviacao;
    private $imagem;
    private $data_atualizacao;

    public function __construct($propriedade = [])
    {
        if (is_array($propriedade)) {
            $this->setID(isset($propriedade['id'])?$propriedade['id']:null);
            $this->setGrupoID(isset($propriedade['grupoid'])?$propriedade['grupoid']:null);
            $this->setNome(isset($propriedade['nome'])?$propriedade['nome']:null);
            $this->setAbreviacao(isset($propriedade['abreviacao'])?$propriedade['abreviacao']:null);
            $this->setImagem(isset($propriedade['imagem'])?$propriedade['imagem']:null);
            $this->setDataAtualizacao(isset($propriedade['dataatualizacao'])?$propriedade['dataatualizacao']:null);
        }
    }

    /**
     * Identificador da propriedade
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
     * Grupo que possui essa propriedade como item de um pacote
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
    }

    /**
     * Nome da propriedade, Ex.: Grande, Pequena
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
     * Abreviação do nome da propriedade, Ex.: G para Grande, P para Pequena, essa
     * abreviação fará parte do nome do produto
     */
    public function getAbreviacao()
    {
        return $this->abreviacao;
    }

    public function setAbreviacao($abreviacao)
    {
        $this->abreviacao = $abreviacao;
    }

    /**
     * Imagem que representa a propriedade
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * Data de atualização dos dados ou da imagem da propriedade
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
    }

    public function toArray()
    {
        $propriedade = [];
        $propriedade['id'] = $this->getID();
        $propriedade['grupoid'] = $this->getGrupoID();
        $propriedade['nome'] = $this->getNome();
        $propriedade['abreviacao'] = $this->getAbreviacao();
        $propriedade['imagem'] = $this->getImagem();
        $propriedade['dataatualizacao'] = $this->getDataAtualizacao();
        return $propriedade;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Propriedades')
                         ->where(['id' => $id]);
        return new ZPropriedade($query->fetch());
    }

    public static function getPeloGrupoIDNome($grupo_id, $nome)
    {
        $query = DB::$pdo->from('Propriedades')
                         ->where(['grupoid' => $grupo_id, 'nome' => $nome]);
        return new ZPropriedade($query->fetch());
    }

    private static function validarCampos(&$propriedade)
    {
        $erros = [];
        if (!is_numeric($propriedade['grupoid'])) {
            $erros['grupoid'] = 'O grupo não foi informado';
        }
        $propriedade['nome'] = strip_tags(trim($propriedade['nome']));
        if (strlen($propriedade['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $propriedade['abreviacao'] = strip_tags(trim($propriedade['abreviacao']));
        if (strlen($propriedade['abreviacao']) == 0) {
            $propriedade['abreviacao'] = null;
        }
        if ($propriedade['imagem'] === '') {
            $propriedade['imagem'] = null;
        }
        $propriedade['dataatualizacao'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'GrupoID_Nome_UNIQUE') !== false) {
            throw new ValidationException(['nome' => 'O nome informado já está cadastrado']);
        }
    }

    public static function cadastrar($propriedade)
    {
        $_propriedade = $propriedade->toArray();
        self::validarCampos($_propriedade);
        try {
            $_propriedade['id'] = DB::$pdo->insertInto('Propriedades')->values($_propriedade)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_propriedade['id']);
    }

    public static function atualizar($propriedade)
    {
        $_propriedade = $propriedade->toArray();
        if (!$_propriedade['id']) {
            throw new ValidationException(['id' => 'O id da propriedade não foi informado']);
        }
        self::validarCampos($_propriedade);
        $campos = [
            'grupoid',
            'nome',
            'abreviacao',
            'dataatualizacao',
        ];
        if ($_propriedade['imagem'] !== true) {
            $campos[] = 'imagem';
        }
        try {
            $query = DB::$pdo->update('Propriedades');
            $query = $query->set(array_intersect_key($_propriedade, array_flip($campos)));
            $query = $query->where('id', $_propriedade['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_propriedade['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a propriedade, o id da propriedade não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Propriedades')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch()
    {
        return   DB::$pdo->from('Propriedades')
                         ->orderBy('id ASC');
    }

    public static function getTodas($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_propriedades = $query->fetchAll();
        $propriedades = [];
        foreach ($_propriedades as $propriedade) {
            $propriedades[] = new ZPropriedade($propriedade);
        }
        return $propriedades;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoGrupoID($grupo_id)
    {
        return   DB::$pdo->from('Propriedades')
                         ->where(['grupoid' => $grupo_id])
                         ->orderBy('id ASC');
    }

    public static function getTodasDoGrupoID($grupo_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoGrupoID($grupo_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_propriedades = $query->fetchAll();
        $propriedades = [];
        foreach ($_propriedades as $propriedade) {
            $propriedades[] = new ZPropriedade($propriedade);
        }
        return $propriedades;
    }

    public static function getCountDoGrupoID($grupo_id)
    {
        $query = self::initSearchDoGrupoID($grupo_id);
        return $query->count();
    }
}
