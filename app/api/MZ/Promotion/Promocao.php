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
namespace MZ\Promotion;

use MZ\Util\Mask;
use MZ\Util\Date;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer e ser agendado para ser aplicado
 */
class Promocao extends SyncModel
{

    /**
     * Identificador da promoção
     */
    private $id;
    /**
     * Permite fazer promoção para qualquer produto dessa categoria
     */
    private $categoria_id;
    /**
     * Informa qual o produto participará da promoção de desconto ou terá
     * acréscimo
     */
    private $produto_id;
    /**
     * Informa se essa promoção será aplicada nesse serviço
     */
    private $servico_id;
    /**
     * Bairro que essa promoção se aplica, somente serviços
     */
    private $bairro_id;
    /**
     * Zona que essa promoção se aplica, somente serviços
     */
    private $zona_id;
    /**
     * Permite alterar o preço do produto para cada integração
     */
    private $integracao_id;
    /**
     * Momento inicial da semana em minutos que o produto começa a sofrer
     * alteração de preço, em evento será o unix timestamp
     */
    private $inicio;
    /**
     * Momento final da semana em minutos que o produto volta ao preço normal,
     * em evento será o unix timestamp
     */
    private $fim;
    /**
     * Acréscimo ou desconto aplicado ao produto ou serviço
     */
    private $valor;
    /**
     * Informa quantos pontos será ganho (Positivo) ou descontado (Negativo) na
     * compra desse produto
     */
    private $pontos;
    /**
     * Informa se o resgate dos produtos podem ser feitos de forma parcial
     */
    private $parcial;
    /**
     * Informa se deve proibir a venda desse produto no período informado
     */
    private $proibir;
    /**
     * Informa se a promoção será aplicada apenas no intervalo de data
     * informado
     */
    private $evento;
    /**
     * Informa se essa promoção é um agendamento de preço, na data inicial o
     * preço será aplicado, assim como a visibilidade do produto ou serviço
     * será ativada ou desativada de acordo com o proibir
     */
    private $agendamento;
    /**
     * Chamada para a promoção
     */
    private $chamada;
    /**
     * Imagem promocional
     */
    private $banner_url;

    /**
     * Constructor for a new empty instance of Promocao
     * @param array $promocao All field and values to fill the instance
     */
    public function __construct($promocao = [])
    {
        parent::__construct($promocao);
    }

    /**
     * Identificador da promoção
     * @return int id of Promoção
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Promoção
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Permite fazer promoção para qualquer produto dessa categoria
     * @return int categoria of Promoção
     */
    public function getCategoriaID()
    {
        return $this->categoria_id;
    }

    /**
     * Set CategoriaID value to new on param
     * @param int $categoria_id Set categoria for Promoção
     * @return self Self instance
     */
    public function setCategoriaID($categoria_id)
    {
        $this->categoria_id = $categoria_id;
        return $this;
    }

    /**
     * Informa qual o produto participará da promoção de desconto ou terá
     * acréscimo
     * @return int produto of Promoção
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Promoção
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Informa se essa promoção será aplicada nesse serviço
     * @return int serviço of Promoção
     */
    public function getServicoID()
    {
        return $this->servico_id;
    }

    /**
     * Set ServicoID value to new on param
     * @param int $servico_id Set serviço for Promoção
     * @return self Self instance
     */
    public function setServicoID($servico_id)
    {
        $this->servico_id = $servico_id;
        return $this;
    }

    /**
     * Bairro que essa promoção se aplica, somente serviços
     * @return int bairro of Promoção
     */
    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * Set BairroID value to new on param
     * @param int $bairro_id Set bairro for Promoção
     * @return self Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    /**
     * Zona que essa promoção se aplica, somente serviços
     * @return int zona of Promoção
     */
    public function getZonaID()
    {
        return $this->zona_id;
    }

    /**
     * Set ZonaID value to new on param
     * @param int $zona_id Set zona for Promoção
     * @return self Self instance
     */
    public function setZonaID($zona_id)
    {
        $this->zona_id = $zona_id;
        return $this;
    }

    /**
     * Permite alterar o preço do produto para cada integração
     * @return int integração of Promoção
     */
    public function getIntegracaoID()
    {
        return $this->integracao_id;
    }

    /**
     * Set IntegracaoID value to new on param
     * @param int $integracao_id Set integração for Promoção
     * @return self Self instance
     */
    public function setIntegracaoID($integracao_id)
    {
        $this->integracao_id = $integracao_id;
        return $this;
    }

    /**
     * Momento inicial da semana em minutos que o produto começa a sofrer
     * alteração de preço, em evento será o unix timestamp
     * @return int momento inicial of Promoção
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set Inicio value to new on param
     * @param int $inicio Set momento inicial for Promoção
     * @return self Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Momento final da semana em minutos que o produto volta ao preço normal,
     * em evento será o unix timestamp
     * @return int momento final of Promoção
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set Fim value to new on param
     * @param int $fim Set momento final for Promoção
     * @return self Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Acréscimo ou desconto aplicado ao produto ou serviço
     * @return string valor of Promoção
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Promoção
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa quantos pontos será ganho (Positivo) ou descontado (Negativo) na
     * compra desse produto
     * @return int pontos of Promoção
     */
    public function getPontos()
    {
        return $this->pontos;
    }

    /**
     * Set Pontos value to new on param
     * @param int $pontos Set pontos for Promoção
     * @return self Self instance
     */
    public function setPontos($pontos)
    {
        $this->pontos = $pontos;
        return $this;
    }

    /**
     * Informa se o resgate dos produtos podem ser feitos de forma parcial
     * @return string resgate parcial of Promoção
     */
    public function getParcial()
    {
        return $this->parcial;
    }

    /**
     * Informa se o resgate dos produtos podem ser feitos de forma parcial
     * @return boolean Check if o of Parcial is selected or checked
     */
    public function isParcial()
    {
        return $this->parcial == 'Y';
    }

    /**
     * Set Parcial value to new on param
     * @param string $parcial Set resgate parcial for Promoção
     * @return self Self instance
     */
    public function setParcial($parcial)
    {
        $this->parcial = $parcial;
        return $this;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return string proibir a venda of Promoção
     */
    public function getProibir()
    {
        return $this->proibir;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return boolean Check if a of Proibir is selected or checked
     */
    public function isProibir()
    {
        return $this->proibir == 'Y';
    }

    /**
     * Set Proibir value to new on param
     * @param string $proibir Set proibir a venda for Promoção
     * @return self Self instance
     */
    public function setProibir($proibir)
    {
        $this->proibir = $proibir;
        return $this;
    }

    /**
     * Informa se a promoção será aplicada apenas no intervalo de data
     * informado
     * @return string evento of Promoção
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Informa se a promoção será aplicada apenas no intervalo de data
     * informado
     * @return boolean Check if o of Evento is selected or checked
     */
    public function isEvento()
    {
        return $this->evento == 'Y';
    }

    /**
     * Set Evento value to new on param
     * @param string $evento Set evento for Promoção
     * @return self Self instance
     */
    public function setEvento($evento)
    {
        $this->evento = $evento;
        return $this;
    }

    /**
     * Informa se essa promoção é um agendamento de preço, na data inicial o
     * preço será aplicado, assim como a visibilidade do produto ou serviço
     * será ativada ou desativada de acordo com o proibir
     * @return string agendamento of Promoção
     */
    public function getAgendamento()
    {
        return $this->agendamento;
    }

    /**
     * Informa se essa promoção é um agendamento de preço, na data inicial o
     * preço será aplicado, assim como a visibilidade do produto ou serviço
     * será ativada ou desativada de acordo com o proibir
     * @return boolean Check if o of Agendamento is selected or checked
     */
    public function isAgendamento()
    {
        return $this->agendamento == 'Y';
    }

    /**
     * Set Agendamento value to new on param
     * @param string $agendamento Set agendamento for Promoção
     * @return self Self instance
     */
    public function setAgendamento($agendamento)
    {
        $this->agendamento = $agendamento;
        return $this;
    }

    /**
     * Chamada para a promoção
     * @return string chamada of Promoção
     */
    public function getChamada()
    {
        return $this->chamada;
    }

    /**
     * Set Chamada value to new on param
     * @param string $chamada Set chamada for Promoção
     * @return self Self instance
     */
    public function setChamada($chamada)
    {
        $this->chamada = $chamada;
        return $this;
    }

    /**
     * Imagem promocional
     * @return string banner of Promoção
     */
    public function getBannerURL()
    {
        return $this->banner_url;
    }

    /**
     * Set BannerURL value to new on param
     * @param string $banner_url Set banner for Promoção
     * @return self Self instance
     */
    public function setBannerURL($banner_url)
    {
        $this->banner_url = $banner_url;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $promocao = parent::toArray($recursive);
        $promocao['id'] = $this->getID();
        $promocao['categoriaid'] = $this->getCategoriaID();
        $promocao['produtoid'] = $this->getProdutoID();
        $promocao['servicoid'] = $this->getServicoID();
        $promocao['bairroid'] = $this->getBairroID();
        $promocao['zonaid'] = $this->getZonaID();
        $promocao['integracaoid'] = $this->getIntegracaoID();
        $promocao['inicio'] = $this->getInicio();
        $promocao['fim'] = $this->getFim();
        $promocao['valor'] = $this->getValor();
        $promocao['pontos'] = $this->getPontos();
        $promocao['parcial'] = $this->getParcial();
        $promocao['proibir'] = $this->getProibir();
        $promocao['evento'] = $this->getEvento();
        $promocao['agendamento'] = $this->getAgendamento();
        $promocao['chamada'] = $this->getChamada();
        $promocao['bannerurl'] = $this->getBannerURL();
        return $promocao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $promocao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($promocao = [])
    {
        if ($promocao instanceof self) {
            $promocao = $promocao->toArray();
        } elseif (!is_array($promocao)) {
            $promocao = [];
        }
        parent::fromArray($promocao);
        if (!isset($promocao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($promocao['id']);
        }
        if (!array_key_exists('categoriaid', $promocao)) {
            $this->setCategoriaID(null);
        } else {
            $this->setCategoriaID($promocao['categoriaid']);
        }
        if (!array_key_exists('produtoid', $promocao)) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($promocao['produtoid']);
        }
        if (!array_key_exists('servicoid', $promocao)) {
            $this->setServicoID(null);
        } else {
            $this->setServicoID($promocao['servicoid']);
        }
        if (!array_key_exists('bairroid', $promocao)) {
            $this->setBairroID(null);
        } else {
            $this->setBairroID($promocao['bairroid']);
        }
        if (!array_key_exists('zonaid', $promocao)) {
            $this->setZonaID(null);
        } else {
            $this->setZonaID($promocao['zonaid']);
        }
        if (!array_key_exists('integracaoid', $promocao)) {
            $this->setIntegracaoID(null);
        } else {
            $this->setIntegracaoID($promocao['integracaoid']);
        }
        if (!isset($promocao['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($promocao['inicio']);
        }
        if (!isset($promocao['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($promocao['fim']);
        }
        if (!isset($promocao['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($promocao['valor']);
        }
        if (!isset($promocao['pontos'])) {
            $this->setPontos(null);
        } else {
            $this->setPontos($promocao['pontos']);
        }
        if (!isset($promocao['parcial'])) {
            $this->setParcial('N');
        } else {
            $this->setParcial($promocao['parcial']);
        }
        if (!isset($promocao['proibir'])) {
            $this->setProibir('N');
        } else {
            $this->setProibir($promocao['proibir']);
        }
        if (!isset($promocao['evento'])) {
            $this->setEvento('N');
        } else {
            $this->setEvento($promocao['evento']);
        }
        if (!isset($promocao['agendamento'])) {
            $this->setAgendamento('N');
        } else {
            $this->setAgendamento($promocao['agendamento']);
        }
        if (!array_key_exists('chamada', $promocao)) {
            $this->setChamada(null);
        } else {
            $this->setChamada($promocao['chamada']);
        }
        if (!array_key_exists('bannerurl', $promocao)) {
            $this->setBannerURL(null);
        } else {
            $this->setBannerURL($promocao['bannerurl']);
        }
        return $this;
    }

    /**
     * Get relative banner path or default banner
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for promoção banner
     */
    public function makeBannerURL($default = false, $default_name = 'promocao.png')
    {
        $banner_url = $this->getBannerURL();
        if ($default) {
            $banner_url = null;
        }
        return get_image_url($banner_url, 'promocao', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $promocao = parent::publish();
        $promocao['bannerurl'] = $this->makeBannerURL(false, null);
        return $promocao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCategoriaID(Filter::number($this->getCategoriaID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setServicoID(Filter::number($this->getServicoID()));
        $this->setBairroID(Filter::number($this->getBairroID()));
        $this->setZonaID(Filter::number($this->getZonaID()));
        $this->setIntegracaoID(Filter::number($this->getIntegracaoID()));
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setPontos(Filter::number($this->getPontos()));
        $this->setChamada(Filter::string($this->getChamada()));
        $banner_url = upload_image('raw_bannerurl', 'promocao');
        if (is_null($banner_url) && trim($this->getBannerURL()) != '') {
            $this->setBannerURL($original->getBannerURL());
        } else {
            $this->setBannerURL($banner_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getBannerURL()) && $dependency->getBannerURL() != $this->getBannerURL()) {
            @unlink(get_image_path($this->getBannerURL(), 'promocao'));
        }
        $this->setBannerURL($dependency->getBannerURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Promocao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getInicio())) {
            $errors['inicio'] = _t('promocao.inicio_cannot_empty');
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = _t('promocao.fim_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('promocao.valor_cannot_empty');
        }
        if (is_null($this->getPontos())) {
            $errors['pontos'] = _t('promocao.pontos_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getParcial())) {
            $errors['parcial'] = _t('promocao.parcial_invalid');
        }
        if (!Validator::checkBoolean($this->getProibir())) {
            $errors['proibir'] = _t('promocao.proibir_invalid');
        }
        if (!Validator::checkBoolean($this->getEvento())) {
            $errors['evento'] = _t('promocao.evento_invalid');
        }
        if (!Validator::checkBoolean($this->getAgendamento())) {
            $errors['agendamento'] = _t('promocao.agendamento_invalid');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Promoção into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Promocoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Promoção with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('promocao.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Promocoes')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('promocao.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Promocoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load current promotion for this product
     * @return Promocao Self instance filled or empty
     */
    public function loadByProdutoID()
    {
        if ($this->getProdutoID() === null) {
            return $this->fromArray([]);
        }
        $week_offset = Date::weekOffset();
        return $this->load([
            'produtoid' => $this->getProdutoID(),
            'ate_inicio' => $week_offset,
            'apartir_fim' => $week_offset
        ]);
    }

    /**
     * Permite fazer promoção para qualquer produto dessa categoria
     * @return \MZ\Product\Categoria The object fetched from database
     */
    public function findCategoriaID()
    {
        if (is_null($this->getCategoriaID())) {
            return new \MZ\Product\Categoria();
        }
        return \MZ\Product\Categoria::findByID($this->getCategoriaID());
    }

    /**
     * Informa qual o produto participará da promoção de desconto ou terá
     * acréscimo
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        if (is_null($this->getProdutoID())) {
            return new \MZ\Product\Produto();
        }
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Informa se essa promoção será aplicada nesse serviço
     * @return \MZ\Product\Servico The object fetched from database
     */
    public function findServicoID()
    {
        if (is_null($this->getServicoID())) {
            return new \MZ\Product\Servico();
        }
        return \MZ\Product\Servico::findByID($this->getServicoID());
    }

    /**
     * Bairro que essa promoção se aplica, somente serviços
     * @return \MZ\Location\Bairro The object fetched from database
     */
    public function findBairroID()
    {
        if (is_null($this->getBairroID())) {
            return new \MZ\Location\Bairro();
        }
        return \MZ\Location\Bairro::findByID($this->getBairroID());
    }

    /**
     * Zona que essa promoção se aplica, somente serviços
     * @return \MZ\Location\Zona The object fetched from database
     */
    public function findZonaID()
    {
        if (is_null($this->getZonaID())) {
            return new \MZ\Location\Zona();
        }
        return \MZ\Location\Zona::findByID($this->getZonaID());
    }

    /**
     * Permite alterar o preço do produto para cada integração
     * @return \MZ\System\Integracao The object fetched from database
     */
    public function findIntegracaoID()
    {
        if (is_null($this->getIntegracaoID())) {
            return new \MZ\System\Integracao();
        }
        return \MZ\System\Integracao::findByID($this->getIntegracaoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $promocao = new self();
        $allowed = Filter::concatKeys('p.', $promocao->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['ate_inicio'])) {
            $field = 'p.inicio <= ?';
            $condition[$field] = $condition['ate_inicio'];
            $allowed[$field] = true;
            unset($condition['ate_inicio']);
        }
        if (isset($condition['apartir_fim'])) {
            $field = 'p.fim >= ?';
            $condition[$field] = $condition['apartir_fim'];
            $allowed[$field] = true;
            unset($condition['apartir_fim']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
    {
        $query = DB::from('Promocoes p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Promoção or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Promoção or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('promocao.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Promoção
     * @param array  $condition Condition to get all Promoção
     * @param array  $order     Order Promoção
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Promocao
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
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
