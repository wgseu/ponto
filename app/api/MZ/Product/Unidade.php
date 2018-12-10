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
namespace MZ\Product;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Unidades de medidas aplicadas aos produtos
 */
class Unidade extends SyncModel
{
    const SIGLA_UNITARIA = 'UN';

    /**
     * Identificador da unidade
     */
    private $id;
    /**
     * Nome da unidade de medida, Ex.: Grama, Quilo
     */
    private $nome;
    /**
     * Detalhes sobre a unidade de medida
     */
    private $descricao;
    /**
     * Sigla da unidade de medida, Ex.: UN, L, g
     */
    private $sigla;

    /**
     * Constructor for a new empty instance of Unidade
     * @param array $unidade All field and values to fill the instance
     */
    public function __construct($unidade = [])
    {
        parent::__construct($unidade);
    }

    /**
     * Identificador da unidade
     * @return int id of Unidade
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Unidade
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da unidade de medida, Ex.: Grama, Quilo
     * @return string nome of Unidade
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Unidade
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Detalhes sobre a unidade de medida
     * @return string descrição of Unidade
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Unidade
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Sigla da unidade de medida, Ex.: UN, L, g
     * @return string sigla of Unidade
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set Sigla value to new on param
     * @param string $sigla Set sigla for Unidade
     * @return self Self instance
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $unidade = parent::toArray($recursive);
        $unidade['id'] = $this->getID();
        $unidade['nome'] = $this->getNome();
        $unidade['descricao'] = $this->getDescricao();
        $unidade['sigla'] = $this->getSigla();
        return $unidade;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $unidade Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($unidade = [])
    {
        if ($unidade instanceof self) {
            $unidade = $unidade->toArray();
        } elseif (!is_array($unidade)) {
            $unidade = [];
        }
        parent::fromArray($unidade);
        if (!isset($unidade['id'])) {
            $this->setID(null);
        } else {
            $this->setID($unidade['id']);
        }
        if (!isset($unidade['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($unidade['nome']);
        }
        if (!array_key_exists('descricao', $unidade)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($unidade['descricao']);
        }
        if (!isset($unidade['sigla'])) {
            $this->setSigla(null);
        } else {
            $this->setSigla($unidade['sigla']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $unidade = parent::publish($requester);
        return $unidade;
    }

    private function processaUnidade($quantidade, $conteudo)
    {
        $unidade = $this->getSigla();
        $grandezas = [
            -24 => 'y',
            -21 => 'z',
            -18 => 'a',
            -15 => 'f',
            -12 => 'p',
            -9  => 'n',
            -6  => 'µ',
            -3  => 'm',
            -2  => 'c',
            -1  => 'd',
             0  => '',
             1  => 'da',
             2  => 'h',
             3  => 'k',
             6  => 'M',
             9  => 'G',
             12 => 'T',
             15 => 'P',
             18 => 'E',
             21 => 'Z',
             24 => 'Y'
        ];
        $index = intval(log10($conteudo));
        $remain = $conteudo / pow(10, $index);
        if (!array_key_exists($index, $grandezas)) {
            throw new \Exception('Não existe grandeza para o conteudo '.$conteudo.' da unidade '.$unidade, 404);
        }
        $unidade = $grandezas[$index].$unidade;
        return [
            'unidade' => $unidade,
            'quantidade' => $quantidade * $remain
        ];
    }

    public function processaSigla($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['unidade'];
    }

    public function processaQuantidade($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['quantidade'];
    }

    public function formatar($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return strval($data['quantidade']) . ' ' . $data['unidade'];
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setSigla(Filter::string($this->getSigla()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Unidade in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('unidade.nome_cannot_empty');
        }
        if (is_null($this->getSigla())) {
            $errors['sigla'] = _t('unidade.sigla_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Sigla', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'sigla' => _t(
                    'unidade.sigla_used',
                    $this->getSigla()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Sigla
     * @return self Self filled instance or empty when not found
     */
    public function loadBySigla()
    {
        return $this->load([
            'sigla' => strval($this->getSigla()),
        ]);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '(u.nome LIKE ? OR u.descricao LIKE ?)';
            $condition[$field] = ['%'.$search.'%', '%'.$search.'%'];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'u.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Unidades u');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('u.nome ASC');
        $query = $query->orderBy('u.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Sigla
     * @param string $sigla sigla to find Unidade
     * @return self A filled instance or empty when not found
     */
    public static function findBySigla($sigla)
    {
        $result = new self();
        $result->setSigla($sigla);
        return $result->loadBySigla();
    }
}
