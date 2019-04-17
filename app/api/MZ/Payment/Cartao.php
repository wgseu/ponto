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
namespace MZ\Payment;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Cartões utilizados na forma de pagamento em cartão
 */
class Cartao extends SyncModel
{

    /**
     * Identificador do cartão
     */
    private $id;
    /**
     * Forma de pagamento associada à esse cartão ou vale
     */
    private $forma_pagto_id;
    /**
     * Carteira de entrada de valores no caixa
     */
    private $carteira_id;
    /**
     * Nome da bandeira do cartão
     */
    private $bandeira;
    /**
     * Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a
     * 100
     */
    private $taxa;
    /**
     * Quantidade de dias para repasse do valor
     */
    private $dias_repasse;
    /**
     * Taxa em porcentagem para antecipação de recebimento de parcelas
     */
    private $taxa_antecipacao;
    /**
     * Imagem do cartão
     */
    private $imagem_url;
    /**
     * Informa se o cartão está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Cartao
     * @param array $cartao All field and values to fill the instance
     */
    public function __construct($cartao = [])
    {
        parent::__construct($cartao);
    }

    /**
     * Identificador do cartão
     * @return int id of Cartão
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Cartão
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Forma de pagamento associada à esse cartão ou vale
     * @return int forma de pagamento of Cartão
     */
    public function getFormaPagtoID()
    {
        return $this->forma_pagto_id;
    }

    /**
     * Set FormaPagtoID value to new on param
     * @param int $forma_pagto_id Set forma de pagamento for Cartão
     * @return self Self instance
     */
    public function setFormaPagtoID($forma_pagto_id)
    {
        $this->forma_pagto_id = $forma_pagto_id;
        return $this;
    }

    /**
     * Carteira de entrada de valores no caixa
     * @return int carteira de entrada of Cartão
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira de entrada for Cartão
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Nome da bandeira do cartão
     * @return string bandeira of Cartão
     */
    public function getBandeira()
    {
        return $this->bandeira;
    }

    /**
     * Set Bandeira value to new on param
     * @param string $bandeira Set bandeira for Cartão
     * @return self Self instance
     */
    public function setBandeira($bandeira)
    {
        $this->bandeira = $bandeira;
        return $this;
    }

    /**
     * Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a
     * 100
     * @return float taxa of Cartão
     */
    public function getTaxa()
    {
        return $this->taxa;
    }

    /**
     * Set Taxa value to new on param
     * @param float $taxa Set taxa for Cartão
     * @return self Self instance
     */
    public function setTaxa($taxa)
    {
        $this->taxa = $taxa;
        return $this;
    }

    /**
     * Quantidade de dias para repasse do valor
     * @return int dias para repasse of Cartão
     */
    public function getDiasRepasse()
    {
        return $this->dias_repasse;
    }

    /**
     * Set DiasRepasse value to new on param
     * @param int $dias_repasse Set dias para repasse for Cartão
     * @return self Self instance
     */
    public function setDiasRepasse($dias_repasse)
    {
        $this->dias_repasse = $dias_repasse;
        return $this;
    }

    /**
     * Taxa em porcentagem para antecipação de recebimento de parcelas
     * @return float taxa de antecipação of Cartão
     */
    public function getTaxaAntecipacao()
    {
        return $this->taxa_antecipacao;
    }

    /**
     * Set TaxaAntecipacao value to new on param
     * @param float $taxa_antecipacao Set taxa de antecipação for Cartão
     * @return self Self instance
     */
    public function setTaxaAntecipacao($taxa_antecipacao)
    {
        $this->taxa_antecipacao = $taxa_antecipacao;
        return $this;
    }

    /**
     * Imagem do cartão
     * @return string imagem of Cartão
     */
    public function getImagemURL()
    {
        return $this->imagem_url;
    }

    /**
     * Set ImagemURL value to new on param
     * @param string $imagem_url Set imagem for Cartão
     * @return self Self instance
     */
    public function setImagemURL($imagem_url)
    {
        $this->imagem_url = $imagem_url;
        return $this;
    }

    /**
     * Informa se o cartão está ativo
     * @return string ativo of Cartão
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o cartão está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param string $ativo Set ativo for Cartão
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $cartao = parent::toArray($recursive);
        $cartao['id'] = $this->getID();
        $cartao['formapagtoid'] = $this->getFormaPagtoID();
        $cartao['carteiraid'] = $this->getCarteiraID();
        $cartao['bandeira'] = $this->getBandeira();
        $cartao['taxa'] = $this->getTaxa();
        $cartao['diasrepasse'] = $this->getDiasRepasse();
        $cartao['taxaantecipacao'] = $this->getTaxaAntecipacao();
        $cartao['imagemurl'] = $this->getImagemURL();
        $cartao['ativo'] = $this->getAtivo();
        return $cartao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $cartao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($cartao = [])
    {
        if ($cartao instanceof self) {
            $cartao = $cartao->toArray();
        } elseif (!is_array($cartao)) {
            $cartao = [];
        }
        parent::fromArray($cartao);
        if (!isset($cartao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($cartao['id']);
        }
        if (!isset($cartao['formapagtoid'])) {
            $this->setFormaPagtoID(null);
        } else {
            $this->setFormaPagtoID($cartao['formapagtoid']);
        }
        if (!array_key_exists('carteiraid', $cartao)) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($cartao['carteiraid']);
        }
        if (!isset($cartao['bandeira'])) {
            $this->setBandeira(null);
        } else {
            $this->setBandeira($cartao['bandeira']);
        }
        if (!isset($cartao['taxa'])) {
            $this->setTaxa(0);
        } else {
            $this->setTaxa($cartao['taxa']);
        }
        if (!isset($cartao['diasrepasse'])) {
            $this->setDiasRepasse(0);
        } else {
            $this->setDiasRepasse($cartao['diasrepasse']);
        }
        if (!isset($cartao['taxaantecipacao'])) {
            $this->setTaxaAntecipacao(0);
        } else {
            $this->setTaxaAntecipacao($cartao['taxaantecipacao']);
        }
        if (!array_key_exists('imagemurl', $cartao)) {
            $this->setImagemURL(null);
        } else {
            $this->setImagemURL($cartao['imagemurl']);
        }
        if (!isset($cartao['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($cartao['ativo']);
        }
        return $this;
    }

    /**
     * Get relative imagem path or default imagem
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for cartão imagem
     */
    public function makeImagemURL($default = false, $default_name = 'cartao.png')
    {
        $imagem_url = $this->getImagemURL();
        if ($default) {
            $imagem_url = null;
        }
        return get_image_url($imagem_url, 'cartao', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $cartao = parent::publish($requester);
        $cartao['imagemurl'] = $this->makeImagemURL(false, null);
        return $cartao;
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
        $this->setFormaPagtoID(Filter::number($this->getFormaPagtoID()));
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setBandeira(Filter::string($this->getBandeira()));
        $this->setTaxa(Filter::float($this->getTaxa(), $localized));
        $this->setDiasRepasse(Filter::number($this->getDiasRepasse()));
        $this->setTaxaAntecipacao(Filter::float($this->getTaxaAntecipacao(), $localized));
        $imagem_url = upload_image('raw_imagemurl', 'cartao');
        if (is_null($imagem_url) && trim($this->getImagemURL()) != '') {
            $this->setImagemURL($original->getImagemURL());
        } else {
            $this->setImagemURL($imagem_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemURL()) && $dependency->getImagemURL() != $this->getImagemURL()) {
            @unlink(get_image_path($this->getImagemURL(), 'cartao'));
        }
        $this->setImagemURL($dependency->getImagemURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Cartao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFormaPagtoID())) {
            $errors['formapagtoid'] = _t('cartao.forma_pagto_id_cannot_empty');
        }
        if (is_null($this->getBandeira())) {
            $errors['bandeira'] = _t('cartao.bandeira_cannot_empty');
        }
        if (is_null($this->getTaxa())) {
            $errors['taxa'] = _t('cartao.taxa_cannot_empty');
        } elseif ($this->getTaxa() < 0) {
            $errors['taxa'] = 'A taxa não pode ser negativa';
        }
        if (is_null($this->getDiasRepasse())) {
            $errors['diasrepasse'] = _t('cartao.dias_repasse_cannot_empty');
        } elseif ($this->getDiasRepasse() < 0) {
            $errors['diasrepasse'] = 'Os dias para repasse não pode ser negativo';
        }
        if (is_null($this->getTaxaAntecipacao())) {
            $errors['taxaantecipacao'] = _t('cartao.taxa_antecipacao_cannot_empty');
        } elseif ($this->getTaxa() < 0) {
            $errors['taxaantecipacao'] = _t('cartao.taxa_antecipacao_cannot_negative');
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('cartao.ativo_invalid');
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
        if (contains(['FormaPagtoID', 'Bandeira', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'formapagtoid' => _t(
                    'cartao.forma_pagto_id_used',
                    $this->getFormaPagtoID()
                ),
                'bandeira' => _t(
                    'cartao.bandeira_used',
                    $this->getBandeira()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, FormaPagtoID, Bandeira
     * @return self Self filled instance or empty when not found
     */
    public function loadByFormaPagtoIDBandeira()
    {
        return $this->load([
            'formapagtoid' => intval($this->getFormaPagtoID()),
            'bandeira' => strval($this->getBandeira()),
        ]);
    }

    /**
     * Forma de pagamento associada à esse cartão ou vale
     * @return \MZ\Payment\FormaPagto The object fetched from database
     */
    public function findFormaPagtoID()
    {
        return \MZ\Payment\FormaPagto::findByID($this->getFormaPagtoID());
    }

    /**
     * Carteira de entrada de valores no caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
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
            $field = 'c.bandeira LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Cartoes c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.ativo ASC');
        $query = $query->orderBy('c.bandeira ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, FormaPagtoID, Bandeira
     * @param int $forma_pagto_id forma de pagamento to find Cartão
     * @param string $bandeira bandeira to find Cartão
     * @return self A filled instance or empty when not found
     */
    public static function findByFormaPagtoIDBandeira($forma_pagto_id, $bandeira)
    {
        $result = new self();
        $result->setFormaPagtoID($forma_pagto_id);
        $result->setBandeira($bandeira);
        return $result->loadByFormaPagtoIDBandeira();
    }
}
