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
namespace MZ\Environment;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa detalhadamente um bem da empresa
 */
class Patrimonio extends SyncModel
{

    /**
     * Estado de conservação do bem
     */
    const ESTADO_NOVO = 'Novo';
    const ESTADO_CONSERVADO = 'Conservado';
    const ESTADO_RUIM = 'Ruim';

    /**
     * Identificador do bem
     */
    private $id;
    /**
     * Empresa a que esse bem pertence
     */
    private $empresa_id;
    /**
     * Fornecedor do bem
     */
    private $fornecedor_id;
    /**
     * Número que identifica o bem
     */
    private $numero;
    /**
     * Descrição ou nome do bem
     */
    private $descricao;
    /**
     * Quantidade do bem com as mesmas características
     */
    private $quantidade;
    /**
     * Altura do bem em metros
     */
    private $altura;
    /**
     * Largura do bem em metros
     */
    private $largura;
    /**
     * Comprimento do bem em metros
     */
    private $comprimento;
    /**
     * Estado de conservação do bem
     */
    private $estado;
    /**
     * Valor de custo do bem
     */
    private $custo;
    /**
     * Valor que o bem vale atualmente
     */
    private $valor;
    /**
     * Informa se o bem está ativo e em uso
     */
    private $ativo;
    /**
     * Caminho relativo da foto do bem
     */
    private $imagem_anexada;
    /**
     * Data de atualização das informações do bem
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Patrimonio
     * @param array $patrimonio All field and values to fill the instance
     */
    public function __construct($patrimonio = [])
    {
        parent::__construct($patrimonio);
    }

    /**
     * Identificador do bem
     * @return int id of Patrimônio
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Patrimônio
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Empresa a que esse bem pertence
     * @return int empresa of Patrimônio
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param int $empresa_id Set empresa for Patrimônio
     * @return self Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Fornecedor do bem
     * @return int fornecedor of Patrimônio
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param int $fornecedor_id Set fornecedor for Patrimônio
     * @return self Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Número que identifica o bem
     * @return string número of Patrimônio
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param string $numero Set número for Patrimônio
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Descrição ou nome do bem
     * @return string descrição of Patrimônio
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Patrimônio
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Quantidade do bem com as mesmas características
     * @return float quantidade of Patrimônio
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Patrimônio
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Altura do bem em metros
     * @return float altura of Patrimônio
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * Set Altura value to new on param
     * @param float $altura Set altura for Patrimônio
     * @return self Self instance
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;
        return $this;
    }

    /**
     * Largura do bem em metros
     * @return float largura of Patrimônio
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * Set Largura value to new on param
     * @param float $largura Set largura for Patrimônio
     * @return self Self instance
     */
    public function setLargura($largura)
    {
        $this->largura = $largura;
        return $this;
    }

    /**
     * Comprimento do bem em metros
     * @return float comprimento of Patrimônio
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }

    /**
     * Set Comprimento value to new on param
     * @param float $comprimento Set comprimento for Patrimônio
     * @return self Self instance
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $comprimento;
        return $this;
    }

    /**
     * Estado de conservação do bem
     * @return string estado of Patrimônio
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Patrimônio
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Valor de custo do bem
     * @return string custo of Patrimônio
     */
    public function getCusto()
    {
        return $this->custo;
    }

    /**
     * Set Custo value to new on param
     * @param string $custo Set custo for Patrimônio
     * @return self Self instance
     */
    public function setCusto($custo)
    {
        $this->custo = $custo;
        return $this;
    }

    /**
     * Valor que o bem vale atualmente
     * @return string valor of Patrimônio
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Patrimônio
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se o bem está ativo e em uso
     * @return string ativo of Patrimônio
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o bem está ativo e em uso
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param string $ativo Set ativo for Patrimônio
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Caminho relativo da foto do bem
     * @return string foto do bem of Patrimônio
     */
    public function getImagemAnexada()
    {
        return $this->imagem_anexada;
    }

    /**
     * Set ImagemAnexada value to new on param
     * @param string $imagem_anexada Set foto do bem for Patrimônio
     * @return self Self instance
     */
    public function setImagemAnexada($imagem_anexada)
    {
        $this->imagem_anexada = $imagem_anexada;
        return $this;
    }

    /**
     * Data de atualização das informações do bem
     * @return string data de atualização of Patrimônio
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Patrimônio
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $patrimonio = parent::toArray($recursive);
        $patrimonio['id'] = $this->getID();
        $patrimonio['empresaid'] = $this->getEmpresaID();
        $patrimonio['fornecedorid'] = $this->getFornecedorID();
        $patrimonio['numero'] = $this->getNumero();
        $patrimonio['descricao'] = $this->getDescricao();
        $patrimonio['quantidade'] = $this->getQuantidade();
        $patrimonio['altura'] = $this->getAltura();
        $patrimonio['largura'] = $this->getLargura();
        $patrimonio['comprimento'] = $this->getComprimento();
        $patrimonio['estado'] = $this->getEstado();
        $patrimonio['custo'] = $this->getCusto();
        $patrimonio['valor'] = $this->getValor();
        $patrimonio['ativo'] = $this->getAtivo();
        $patrimonio['imagemanexada'] = $this->getImagemAnexada();
        $patrimonio['dataatualizacao'] = $this->getDataAtualizacao();
        return $patrimonio;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $patrimonio Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($patrimonio = [])
    {
        if ($patrimonio instanceof self) {
            $patrimonio = $patrimonio->toArray();
        } elseif (!is_array($patrimonio)) {
            $patrimonio = [];
        }
        parent::fromArray($patrimonio);
        if (!isset($patrimonio['id'])) {
            $this->setID(null);
        } else {
            $this->setID($patrimonio['id']);
        }
        if (!isset($patrimonio['empresaid'])) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($patrimonio['empresaid']);
        }
        if (!array_key_exists('fornecedorid', $patrimonio)) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($patrimonio['fornecedorid']);
        }
        if (!isset($patrimonio['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($patrimonio['numero']);
        }
        if (!isset($patrimonio['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($patrimonio['descricao']);
        }
        if (!isset($patrimonio['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($patrimonio['quantidade']);
        }
        if (!isset($patrimonio['altura'])) {
            $this->setAltura(null);
        } else {
            $this->setAltura($patrimonio['altura']);
        }
        if (!isset($patrimonio['largura'])) {
            $this->setLargura(null);
        } else {
            $this->setLargura($patrimonio['largura']);
        }
        if (!isset($patrimonio['comprimento'])) {
            $this->setComprimento(null);
        } else {
            $this->setComprimento($patrimonio['comprimento']);
        }
        if (!isset($patrimonio['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($patrimonio['estado']);
        }
        if (!isset($patrimonio['custo'])) {
            $this->setCusto(null);
        } else {
            $this->setCusto($patrimonio['custo']);
        }
        if (!isset($patrimonio['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($patrimonio['valor']);
        }
        if (!isset($patrimonio['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($patrimonio['ativo']);
        }
        if (!array_key_exists('imagemanexada', $patrimonio)) {
            $this->setImagemAnexada(null);
        } else {
            $this->setImagemAnexada($patrimonio['imagemanexada']);
        }
        if (!isset($patrimonio['dataatualizacao'])) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($patrimonio['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Get relative foto do bem path or default foto do bem
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for patrimônio foto do bem
     */
    public function makeImagemAnexada($default = false, $default_name = 'patrimonio.png')
    {
        $imagem_anexada = $this->getImagemAnexada();
        if ($default) {
            $imagem_anexada = null;
        }
        return get_image_url($imagem_anexada, 'patrimonio', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $patrimonio = parent::publish($requester);
        $patrimonio['imagemanexada'] = $this->makeImagemAnexada(false, null);
        return $patrimonio;
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
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setAltura(Filter::float($this->getAltura(), $localized));
        $this->setLargura(Filter::float($this->getLargura(), $localized));
        $this->setComprimento(Filter::float($this->getComprimento(), $localized));
        $this->setCusto(Filter::money($this->getCusto(), $localized));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $imagem_anexada = upload_image('raw_imagemanexada', 'patrimonio');
        if (is_null($imagem_anexada) && trim($this->getImagemAnexada()) != '') {
            $this->setImagemAnexada($original->getImagemAnexada());
        } else {
            $this->setImagemAnexada($imagem_anexada);
        }
        $this->setDataAtualizacao(DB::now());
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemAnexada()) && $dependency->getImagemAnexada() != $this->getImagemAnexada()) {
            @unlink(get_image_path($this->getImagemAnexada(), 'patrimonio'));
        }
        $this->setImagemAnexada($dependency->getImagemAnexada());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Patrimonio in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getEmpresaID())) {
            $errors['empresaid'] = _t('patrimonio.empresa_id_cannot_empty');
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('patrimonio.numero_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('patrimonio.descricao_cannot_empty');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('patrimonio.quantidade_cannot_empty');
        } elseif ($this->getQuantidade() < 1) {
            $errors['quantidade'] = 'A quantidade não pode ser nula ou negativa';
        }
        if (is_null($this->getAltura())) {
            $errors['altura'] = _t('patrimonio.altura_cannot_empty');
        } elseif ($this->getAltura() < 0) {
            $errors['altura'] = 'A altura não pode ser nula ou negativa';
        }
        if (is_null($this->getLargura())) {
            $errors['largura'] = _t('patrimonio.largura_cannot_empty');
        } elseif ($this->getLargura() < 0) {
            $errors['largura'] = 'A largura não pode ser nula ou negativa';
        }
        if (is_null($this->getComprimento())) {
            $errors['comprimento'] = _t('patrimonio.comprimento_cannot_empty');
        } elseif ($this->getComprimento() < 0) {
            $errors['comprimento'] = 'O comprimento não pode ser nulo ou negativo';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('patrimonio.estado_invalid');
        }
        if (is_null($this->getCusto())) {
            $errors['custo'] = _t('patrimonio.custo_cannot_empty');
        } elseif ($this->getCusto() < 0) {
            $errors['custo'] = 'O custo não pode ser nulo ou negativo';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('patrimonio.valor_cannot_empty');
        } elseif ($this->getValor() < 0) {
            $errors['valor'] = 'O valor não pode ser negativo';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('patrimonio.ativo_invalid');
        }
        $this->setDataAtualizacao(DB::now());
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
        if (contains(['Numero', 'Estado', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'numero' => _t(
                    'patrimonio.numero_used',
                    $this->getNumero()
                ),
                'estado' => _t(
                    'patrimonio.estado_used',
                    $this->getEstado()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Numero, Estado
     * @return self Self filled instance or empty when not found
     */
    public function loadByNumeroEstado()
    {
        return $this->load([
            'numero' => strval($this->getNumero()),
            'estado' => strval($this->getEstado()),
        ]);
    }

    /**
     * Empresa a que esse bem pertence
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }

    /**
     * Fornecedor do bem
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        if (is_null($this->getFornecedorID())) {
            return new \MZ\Stock\Fornecedor();
        }
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Gets textual and translated Estado for Patrimonio
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_NOVO => _t('patrimonio.estado_novo'),
            self::ESTADO_CONSERVADO => _t('patrimonio.estado_conservado'),
            self::ESTADO_RUIM => _t('patrimonio.estado_ruim'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
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
            $field = 'p.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Patrimonios p');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.descricao ASC');
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Numero, Estado
     * @param string $numero número to find Patrimônio
     * @param string $estado estado to find Patrimônio
     * @return self A filled instance or empty when not found
     */
    public static function findByNumeroEstado($numero, $estado)
    {
        $result = new self();
        $result->setNumero($numero);
        $result->setEstado($estado);
        return $result->loadByNumeroEstado();
    }
}
