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
namespace MZ\Invoice;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Session\Caixa;

/**
 * Notas fiscais e inutilizações
 */
class Nota extends SyncModel
{

    /**
     * Tipo de registro se nota ou inutilização
     */
    const TIPO_NOTA = 'Nota';
    const TIPO_INUTILIZACAO = 'Inutilizacao';

    /**
     * Ambiente em que a nota foi gerada
     */
    const AMBIENTE_HOMOLOGACAO = 'Homologacao';
    const AMBIENTE_PRODUCAO = 'Producao';

    /**
     * Ação que deve ser tomada sobre a nota fiscal
     */
    const ACAO_AUTORIZAR = 'Autorizar';
    const ACAO_CANCELAR = 'Cancelar';
    const ACAO_INUTILIZAR = 'Inutilizar';

    /**
     * Estado da nota
     */
    const ESTADO_ABERTO = 'Aberto';
    const ESTADO_ASSINADO = 'Assinado';
    const ESTADO_PENDENTE = 'Pendente';
    const ESTADO_PROCESSAMENTO = 'Processamento';
    const ESTADO_DENEGADO = 'Denegado';
    const ESTADO_REJEITADO = 'Rejeitado';
    const ESTADO_CANCELADO = 'Cancelado';
    const ESTADO_INUTILIZADO = 'Inutilizado';
    const ESTADO_AUTORIZADO = 'Autorizado';

    /**
     * Identificador da nota
     */
    private $id;
    /**
     * Tipo de registro se nota ou inutilização
     */
    private $tipo;
    /**
     * Ambiente em que a nota foi gerada
     */
    private $ambiente;
    /**
     * Ação que deve ser tomada sobre a nota fiscal
     */
    private $acao;
    /**
     * Estado da nota
     */
    private $estado;
    /**
     * Último evento da nota
     */
    private $ultimo_evento_id;
    /**
     * Série da nota
     */
    private $serie;
    /**
     * Número inicial da nota
     */
    private $numero_inicial;
    /**
     * Número final da nota, igual ao número inicial quando for a nota de um
     * pedido
     */
    private $numero_final;
    /**
     * Permite iniciar o número da nota quando alcançar 999.999.999, deve ser
     * incrementado sempre que alcançar
     */
    private $sequencia;
    /**
     * Chave da nota fiscal
     */
    private $chave;
    /**
     * Recibo de envio para consulta posterior
     */
    private $recibo;
    /**
     * Protocolo de autorização da nota fiscal
     */
    private $protocolo;
    /**
     * Pedido da nota
     */
    private $pedido_id;
    /**
     * Motivo do cancelamento, contingência ou inutilização
     */
    private $motivo;
    /**
     * Informa se a nota está em contingência
     */
    private $contingencia;
    /**
     * URL de consulta da nota fiscal
     */
    private $consulta_url;
    /**
     * Dados do QRCode da nota
     */
    private $qrcode;
    /**
     * Tributos totais da nota
     */
    private $tributos;
    /**
     * Informações de interesse do contribuinte
     */
    private $detalhes;
    /**
     * Informa se os erros já foram corrigidos para retomada do processamento
     */
    private $corrigido;
    /**
     * Informa se todos os processamentos da nota já foram realizados
     */
    private $concluido;
    /**
     * Data de autorização da nota fiscal
     */
    private $data_autorizacao;
    /**
     * Data de emissão da nota
     */
    private $data_emissao;
    /**
     * Data de lançamento da nota no sistema
     */
    private $data_lancamento;

    /**
     * Constructor for a new empty instance of Nota
     * @param array $nota All field and values to fill the instance
     */
    public function __construct($nota = [])
    {
        parent::__construct($nota);
    }

    /**
     * Identificador da nota
     * @return int id of Nota
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Nota
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Tipo de registro se nota ou inutilização
     * @return string tipo of Nota
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Nota
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Ambiente em que a nota foi gerada
     * @return string ambiente of Nota
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set Ambiente value to new on param
     * @param string $ambiente Set ambiente for Nota
     * @return self Self instance
     */
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
        return $this;
    }

    /**
     * Ação que deve ser tomada sobre a nota fiscal
     * @return string ação of Nota
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * Set Acao value to new on param
     * @param string $acao Set ação for Nota
     * @return self Self instance
     */
    public function setAcao($acao)
    {
        $this->acao = $acao;
        return $this;
    }

    /**
     * Estado da nota
     * @return string estado of Nota
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Nota
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Último evento da nota
     * @return int último evento of Nota
     */
    public function getUltimoEventoID()
    {
        return $this->ultimo_evento_id;
    }

    /**
     * Set UltimoEventoID value to new on param
     * @param int $ultimo_evento_id Set último evento for Nota
     * @return self Self instance
     */
    public function setUltimoEventoID($ultimo_evento_id)
    {
        $this->ultimo_evento_id = $ultimo_evento_id;
        return $this;
    }

    /**
     * Série da nota
     * @return int série of Nota
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set Serie value to new on param
     * @param int $serie Set série for Nota
     * @return self Self instance
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * Número inicial da nota
     * @return int número of Nota
     */
    public function getNumeroInicial()
    {
        return $this->numero_inicial;
    }

    /**
     * Set NumeroInicial value to new on param
     * @param int $numero_inicial Set número for Nota
     * @return self Self instance
     */
    public function setNumeroInicial($numero_inicial)
    {
        $this->numero_inicial = $numero_inicial;
        return $this;
    }

    /**
     * Número final da nota, igual ao número inicial quando for a nota de um
     * pedido
     * @return int número final of Nota
     */
    public function getNumeroFinal()
    {
        return $this->numero_final;
    }

    /**
     * Set NumeroFinal value to new on param
     * @param int $numero_final Set número final for Nota
     * @return self Self instance
     */
    public function setNumeroFinal($numero_final)
    {
        $this->numero_final = $numero_final;
        return $this;
    }

    /**
     * Permite iniciar o número da nota quando alcançar 999.999.999, deve ser
     * incrementado sempre que alcançar
     * @return int sequência of Nota
     */
    public function getSequencia()
    {
        return $this->sequencia;
    }

    /**
     * Set Sequencia value to new on param
     * @param int $sequencia Set sequência for Nota
     * @return self Self instance
     */
    public function setSequencia($sequencia)
    {
        $this->sequencia = $sequencia;
        return $this;
    }

    /**
     * Chave da nota fiscal
     * @return string chave of Nota
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * Set Chave value to new on param
     * @param string $chave Set chave for Nota
     * @return self Self instance
     */
    public function setChave($chave)
    {
        $this->chave = $chave;
        return $this;
    }

    /**
     * Recibo de envio para consulta posterior
     * @return string recibo of Nota
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    /**
     * Set Recibo value to new on param
     * @param string $recibo Set recibo for Nota
     * @return self Self instance
     */
    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
        return $this;
    }

    /**
     * Protocolo de autorização da nota fiscal
     * @return string protocolo of Nota
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    /**
     * Set Protocolo value to new on param
     * @param string $protocolo Set protocolo for Nota
     * @return self Self instance
     */
    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;
        return $this;
    }

    /**
     * Pedido da nota
     * @return int pedido of Nota
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido for Nota
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Motivo do cancelamento, contingência ou inutilização
     * @return string motivo of Nota
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set Motivo value to new on param
     * @param string $motivo Set motivo for Nota
     * @return self Self instance
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
        return $this;
    }

    /**
     * Informa se a nota está em contingência
     * @return string contingência of Nota
     */
    public function getContingencia()
    {
        return $this->contingencia;
    }

    /**
     * Informa se a nota está em contingência
     * @return boolean Check if a of Contingencia is selected or checked
     */
    public function isContingencia()
    {
        return $this->contingencia == 'Y';
    }

    /**
     * Set Contingencia value to new on param
     * @param string $contingencia Set contingência for Nota
     * @return self Self instance
     */
    public function setContingencia($contingencia)
    {
        $this->contingencia = $contingencia;
        return $this;
    }

    /**
     * URL de consulta da nota fiscal
     * @return string url de consulta of Nota
     */
    public function getConsultaURL()
    {
        return $this->consulta_url;
    }

    /**
     * Set ConsultaURL value to new on param
     * @param string $consulta_url Set url de consulta for Nota
     * @return self Self instance
     */
    public function setConsultaURL($consulta_url)
    {
        $this->consulta_url = $consulta_url;
        return $this;
    }

    /**
     * Dados do QRCode da nota
     * @return string qrcode of Nota
     */
    public function getQRCode()
    {
        return $this->qrcode;
    }

    /**
     * Set QRCode value to new on param
     * @param string $qrcode Set qrcode for Nota
     * @return self Self instance
     */
    public function setQRCode($qrcode)
    {
        $this->qrcode = $qrcode;
        return $this;
    }

    /**
     * Tributos totais da nota
     * @return string tributos of Nota
     */
    public function getTributos()
    {
        return $this->tributos;
    }

    /**
     * Set Tributos value to new on param
     * @param string $tributos Set tributos for Nota
     * @return self Self instance
     */
    public function setTributos($tributos)
    {
        $this->tributos = $tributos;
        return $this;
    }

    /**
     * Informações de interesse do contribuinte
     * @return string informações de interesse do contribuinte of Nota
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set informações de interesse do contribuinte for Nota
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa se os erros já foram corrigidos para retomada do processamento
     * @return string corrigido of Nota
     */
    public function getCorrigido()
    {
        return $this->corrigido;
    }

    /**
     * Informa se os erros já foram corrigidos para retomada do processamento
     * @return boolean Check if o of Corrigido is selected or checked
     */
    public function isCorrigido()
    {
        return $this->corrigido == 'Y';
    }

    /**
     * Set Corrigido value to new on param
     * @param string $corrigido Set corrigido for Nota
     * @return self Self instance
     */
    public function setCorrigido($corrigido)
    {
        $this->corrigido = $corrigido;
        return $this;
    }

    /**
     * Informa se todos os processamentos da nota já foram realizados
     * @return string concluído of Nota
     */
    public function getConcluido()
    {
        return $this->concluido;
    }

    /**
     * Informa se todos os processamentos da nota já foram realizados
     * @return boolean Check if o of Concluido is selected or checked
     */
    public function isConcluido()
    {
        return $this->concluido == 'Y';
    }

    /**
     * Set Concluido value to new on param
     * @param string $concluido Set concluído for Nota
     * @return self Self instance
     */
    public function setConcluido($concluido)
    {
        $this->concluido = $concluido;
        return $this;
    }

    /**
     * Data de autorização da nota fiscal
     * @return string data de autorização of Nota
     */
    public function getDataAutorizacao()
    {
        return $this->data_autorizacao;
    }

    /**
     * Set DataAutorizacao value to new on param
     * @param string $data_autorizacao Set data de autorização for Nota
     * @return self Self instance
     */
    public function setDataAutorizacao($data_autorizacao)
    {
        $this->data_autorizacao = $data_autorizacao;
        return $this;
    }

    /**
     * Data de emissão da nota
     * @return string data de emissão of Nota
     */
    public function getDataEmissao()
    {
        return $this->data_emissao;
    }

    /**
     * Set DataEmissao value to new on param
     * @param string $data_emissao Set data de emissão for Nota
     * @return self Self instance
     */
    public function setDataEmissao($data_emissao)
    {
        $this->data_emissao = $data_emissao;
        return $this;
    }

    /**
     * Data de lançamento da nota no sistema
     * @return string data de lançamento of Nota
     */
    public function getDataLancamento()
    {
        return $this->data_lancamento;
    }

    /**
     * Set DataLancamento value to new on param
     * @param string $data_lancamento Set data de lançamento for Nota
     * @return self Self instance
     */
    public function setDataLancamento($data_lancamento)
    {
        $this->data_lancamento = $data_lancamento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $nota = parent::toArray($recursive);
        $nota['id'] = $this->getID();
        $nota['tipo'] = $this->getTipo();
        $nota['ambiente'] = $this->getAmbiente();
        $nota['acao'] = $this->getAcao();
        $nota['estado'] = $this->getEstado();
        $nota['ultimoeventoid'] = $this->getUltimoEventoID();
        $nota['serie'] = $this->getSerie();
        $nota['numeroinicial'] = $this->getNumeroInicial();
        $nota['numerofinal'] = $this->getNumeroFinal();
        $nota['sequencia'] = $this->getSequencia();
        $nota['chave'] = $this->getChave();
        $nota['recibo'] = $this->getRecibo();
        $nota['protocolo'] = $this->getProtocolo();
        $nota['pedidoid'] = $this->getPedidoID();
        $nota['motivo'] = $this->getMotivo();
        $nota['contingencia'] = $this->getContingencia();
        $nota['consultaurl'] = $this->getConsultaURL();
        $nota['qrcode'] = $this->getQRCode();
        $nota['tributos'] = $this->getTributos();
        $nota['detalhes'] = $this->getDetalhes();
        $nota['corrigido'] = $this->getCorrigido();
        $nota['concluido'] = $this->getConcluido();
        $nota['dataautorizacao'] = $this->getDataAutorizacao();
        $nota['dataemissao'] = $this->getDataEmissao();
        $nota['datalancamento'] = $this->getDataLancamento();
        return $nota;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $nota Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($nota = [])
    {
        if ($nota instanceof self) {
            $nota = $nota->toArray();
        } elseif (!is_array($nota)) {
            $nota = [];
        }
        parent::fromArray($nota);
        if (!isset($nota['id'])) {
            $this->setID(null);
        } else {
            $this->setID($nota['id']);
        }
        if (!isset($nota['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($nota['tipo']);
        }
        if (!isset($nota['ambiente'])) {
            $this->setAmbiente(null);
        } else {
            $this->setAmbiente($nota['ambiente']);
        }
        if (!isset($nota['acao'])) {
            $this->setAcao(null);
        } else {
            $this->setAcao($nota['acao']);
        }
        if (!isset($nota['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($nota['estado']);
        }
        if (!array_key_exists('ultimoeventoid', $nota)) {
            $this->setUltimoEventoID(null);
        } else {
            $this->setUltimoEventoID($nota['ultimoeventoid']);
        }
        if (!isset($nota['serie'])) {
            $this->setSerie(null);
        } else {
            $this->setSerie($nota['serie']);
        }
        if (!isset($nota['numeroinicial'])) {
            $this->setNumeroInicial(null);
        } else {
            $this->setNumeroInicial($nota['numeroinicial']);
        }
        if (!isset($nota['numerofinal'])) {
            $this->setNumeroFinal(null);
        } else {
            $this->setNumeroFinal($nota['numerofinal']);
        }
        if (!isset($nota['sequencia'])) {
            $this->setSequencia(null);
        } else {
            $this->setSequencia($nota['sequencia']);
        }
        if (!array_key_exists('chave', $nota)) {
            $this->setChave(null);
        } else {
            $this->setChave($nota['chave']);
        }
        if (!array_key_exists('recibo', $nota)) {
            $this->setRecibo(null);
        } else {
            $this->setRecibo($nota['recibo']);
        }
        if (!array_key_exists('protocolo', $nota)) {
            $this->setProtocolo(null);
        } else {
            $this->setProtocolo($nota['protocolo']);
        }
        if (!array_key_exists('pedidoid', $nota)) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($nota['pedidoid']);
        }
        if (!array_key_exists('motivo', $nota)) {
            $this->setMotivo(null);
        } else {
            $this->setMotivo($nota['motivo']);
        }
        if (!isset($nota['contingencia'])) {
            $this->setContingencia('N');
        } else {
            $this->setContingencia($nota['contingencia']);
        }
        if (!array_key_exists('consultaurl', $nota)) {
            $this->setConsultaURL(null);
        } else {
            $this->setConsultaURL($nota['consultaurl']);
        }
        if (!array_key_exists('qrcode', $nota)) {
            $this->setQRCode(null);
        } else {
            $this->setQRCode($nota['qrcode']);
        }
        if (!array_key_exists('tributos', $nota)) {
            $this->setTributos(null);
        } else {
            $this->setTributos($nota['tributos']);
        }
        if (!array_key_exists('detalhes', $nota)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($nota['detalhes']);
        }
        if (!isset($nota['corrigido'])) {
            $this->setCorrigido('N');
        } else {
            $this->setCorrigido($nota['corrigido']);
        }
        if (!isset($nota['concluido'])) {
            $this->setConcluido('N');
        } else {
            $this->setConcluido($nota['concluido']);
        }
        if (!array_key_exists('dataautorizacao', $nota)) {
            $this->setDataAutorizacao(null);
        } else {
            $this->setDataAutorizacao($nota['dataautorizacao']);
        }
        if (!isset($nota['dataemissao'])) {
            $this->setDataEmissao(DB::now());
        } else {
            $this->setDataEmissao($nota['dataemissao']);
        }
        if (!isset($nota['datalancamento'])) {
            $this->setDataLancamento(DB::now());
        } else {
            $this->setDataLancamento($nota['datalancamento']);
        }
        return $this;
    }

    public function isAutorizada()
    {
        $result = ($this->getTipo() == self::TIPO_NOTA) && ($this->getAcao() == self::ACAO_AUTORIZAR);
        if (!$result) {
            return $result;
        }
        $result = $this->isCorrigido() && $this->isConcluido() && ($this->getEstado() == self::ESTADO_AUTORIZADO);
        if ($result) {
            return $result;
        }
        $result = $this->isContingencia() && $this->isCorrigido() && ($this->getEstado() == self::ESTADO_ASSINADO);
        return $result;
    }

    public function getCaminhoXml()
    {
        $xmlfile = \MZ\Invoice\NFeDB::getCaminhoXmlAtual($this);
        if (is_array($xmlfile)) {
            $files = $xmlfile;
        } else {
            $files = ['nota' => $xmlfile];
        }
        foreach ($files as $name => $path) {
            if (!file_exists($path)) {
                $msg = 'Não existe XML para a(o) ' . $name . ' de número "' . $this->getNumeroInicial() . '"';
                throw new \Exception($msg, 404);
            }
        }
        return $xmlfile;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $nota = parent::publish();
        return $nota;
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
        $this->setUltimoEventoID(Filter::number($this->getUltimoEventoID()));
        $this->setSerie(Filter::number($this->getSerie()));
        $this->setNumeroInicial(Filter::number($this->getNumeroInicial()));
        $this->setNumeroFinal(Filter::number($this->getNumeroFinal()));
        $this->setSequencia(Filter::number($this->getSequencia()));
        $this->setChave(Filter::string($this->getChave()));
        $this->setRecibo(Filter::string($this->getRecibo()));
        $this->setProtocolo(Filter::string($this->getProtocolo()));
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setMotivo(Filter::string($this->getMotivo()));
        $this->setConsultaURL(Filter::string($this->getConsultaURL()));
        $this->setQRCode(Filter::text($this->getQRCode()));
        $this->setTributos(Filter::money($this->getTributos(), $localized));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataAutorizacao(Filter::datetime($this->getDataAutorizacao()));
        $this->setDataEmissao(Filter::datetime($this->getDataEmissao()));
        $this->setDataLancamento(Filter::datetime($this->getDataLancamento()));
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
     * @return array All field of Nota in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('nota.tipo_invalid');
        }
        if (!Validator::checkInSet($this->getAmbiente(), self::getAmbienteOptions())) {
            $errors['ambiente'] = _t('nota.ambiente_invalid');
        }
        if (!Validator::checkInSet($this->getAcao(), self::getAcaoOptions())) {
            $errors['acao'] = _t('nota.acao_invalid');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('nota.estado_invalid');
        }
        if (is_null($this->getSerie())) {
            $errors['serie'] = _t('nota.serie_cannot_empty');
        }
        if (is_null($this->getNumeroInicial())) {
            $errors['numeroinicial'] = _t('nota.numero_inicial_cannot_empty');
        }
        if (is_null($this->getNumeroFinal())) {
            $errors['numerofinal'] = _t('nota.numero_final_cannot_empty');
        }
        if (is_null($this->getSequencia())) {
            $errors['sequencia'] = _t('nota.sequencia_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getContingencia())) {
            $errors['contingencia'] = _t('nota.contingencia_invalid');
        }
        if (!Validator::checkBoolean($this->getCorrigido())) {
            $errors['corrigido'] = _t('nota.corrigido_invalid');
        }
        if (!Validator::checkBoolean($this->getConcluido())) {
            $errors['concluido'] = _t('nota.concluido_invalid');
        }
        if (is_null($this->getDataEmissao())) {
            $errors['dataemissao'] = _t('nota.data_emissao_cannot_empty');
        }
        if (is_null($this->getDataLancamento())) {
            $errors['datalancamento'] = 'A data de lançamento não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Nota into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Notas')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Nota with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('nota.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Notas')
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
                ['id' => _t('nota.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Notas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Cria a próxima nota para o caixa informado
     * @return self
     */
    public function criarProxima()
    {
        // procura o último número da nota e a última sequencia de repetições
        $nota = self::find(
            ['ambiente' => $this->getAmbiente(), 'serie' => $this->getSerie()],
            ['sequencia' => -1, 'numerofinal' => -1]
        );
        if (!$nota->exists()) {
            // não existe nenhuma nota ou inutilização para essa série ou ambiente
            $nota->fromArray($this->toArray()); // copia a série e ambiente
            // inicia a numeração e sequência
            $nota->setNumeroFinal(0);
            $nota->setSequencia(1);
        }
        // retira os dados da última nota
        $nota->setID(null);
        $nota->setTipo(self::TIPO_NOTA);
        $nota->setAcao(self::ACAO_AUTORIZAR);
        $nota->setEstado(self::ESTADO_ABERTO);
        $nota->setChave(null);
        $nota->setRecibo(null);
        $nota->setProtocolo(null);
        $nota->setMotivo(null);
        $nota->setContingencia('N');
        $nota->setConsultaURL(null);
        $nota->setQRCode(null);
        $nota->setTributos(null);
        $nota->setDetalhes(null);
        $nota->setCorrigido('Y');
        $nota->setConcluido('N');
        $nota->setDataAutorizacao(null);
        $nota->setDataEmissao(DB::now());
        $nota->setDataLancamento(DB::now());
        // utiliza o pedido da base
        $nota->setPedidoID($this->getPedidoID());
        // verifica se o número inicial do caixa deve ser o próximo
        $caixa = Caixa::findBySerie($nota->getSerie());
        if ($caixa->getNumeroInicial() > $nota->getNumeroFinal()) {
            $nota->setNumeroFinal($caixa->getNumeroInicial() - 1);
        }
        // verifica se alcançou o último número da nota permitido na SEFAZ
        if ($nota->getNumeroFinal() == 999999999) {
            // inicia uma nova sequência
            $nota->setSequencia($nota->getSequencia() + 1);
            $nota->setNumeroFinal(0);
            Caixa::resetBySerie($nota->getSerie());
        }
        // incrementa para o próximo número
        $nota->setNumeroFinal($nota->getNumeroFinal() + 1);
        $nota->setNumeroInicial($nota->getNumeroFinal());
        // cadastra a nota como aberta
        return $nota->insert();
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
     * Último evento da nota
     * @return \MZ\Invoice\Evento The object fetched from database
     */
    public function findUltimoEventoID()
    {
        if (is_null($this->getUltimoEventoID())) {
            return new \MZ\Invoice\Evento();
        }
        return \MZ\Invoice\Evento::findByID($this->getUltimoEventoID());
    }

    /**
     * Pedido da nota
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        if (is_null($this->getPedidoID())) {
            return new \MZ\Sale\Pedido();
        }
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Gets textual and translated Tipo for Nota
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_NOTA => _t('nota.tipo_nota'),
            self::TIPO_INUTILIZACAO => _t('nota.tipo_inutilizacao'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Ambiente for Nota
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getAmbienteOptions($index = null)
    {
        $options = [
            self::AMBIENTE_HOMOLOGACAO => _t('nota.ambiente_homologacao'),
            self::AMBIENTE_PRODUCAO => _t('nota.ambiente_producao'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Acao for Nota
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getAcaoOptions($index = null)
    {
        $options = [
            self::ACAO_AUTORIZAR => _t('nota.acao_autorizar'),
            self::ACAO_CANCELAR => _t('nota.acao_cancelar'),
            self::ACAO_INUTILIZAR => _t('nota.acao_inutilizar'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Estado for Nota
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ABERTO => _t('nota.estado_aberto'),
            self::ESTADO_ASSINADO => _t('nota.estado_assinado'),
            self::ESTADO_PENDENTE => _t('nota.estado_pendente'),
            self::ESTADO_PROCESSAMENTO => _t('nota.estado_processamento'),
            self::ESTADO_DENEGADO => _t('nota.estado_denegado'),
            self::ESTADO_REJEITADO => _t('nota.estado_rejeitado'),
            self::ESTADO_CANCELADO => _t('nota.estado_cancelado'),
            self::ESTADO_INUTILIZADO => _t('nota.estado_inutilizado'),
            self::ESTADO_AUTORIZADO => _t('nota.estado_autorizado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $nota = new self();
        $allowed = Filter::concatKeys('n.', $nota->toArray());
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
        return Filter::orderBy($order, $allowed, 'n.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            $parts = explode('-', $search);
            $motivo = false;
            if ($parts !== false &&
                count($parts) > 0 &&
                count($parts) <= 2 &&
                Validator::checkDigits($parts[0])
            ) {
                if (strlen($parts[0]) == 44) {
                    $chave = $parts[0];
                    $condition['chave'] = $chave;
                } elseif (strlen($parts[0]) > 9) {
                    $protocolo = $parts[0];
                    $condition['protocolo'] = $protocolo;
                } elseif (strlen($parts[0]) > 0) {
                    $numero_inicial = intval($parts[0]);
                    if (count($parts) == 2 && strlen($parts[1]) <= 9 && Validator::checkDigits($parts[1])) {
                        $numero_final = intval($parts[1]);
                        $field = '(n.numeroinicial BETWEEN ? AND ?)';
                        $condition[$field] = [$numero_inicial, $numero_final];
                        $allowed[$field] = true;
                    } else {
                        $condition['numeroinicial'] = $numero_inicial;
                    }
                } else {
                    $motivo = true;
                }
            } else {
                $motivo = true;
            }
            if ($motivo) {
                $search = '%'.preg_replace(' ', '%', $search).'%';
                $field = 'n.motivo LIKE ?';
                $condition[$field] = [$search, $search];
                $allowed[$field] = true;
            }
        }
        if (isset($condition['apartir_emissao'])) {
            $field = 'n.dataemissao >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_emissao'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_emissao'])) {
            $field = 'n.dataemissao <= ?';
            $condition[$field] = Filter::datetime($condition['ate_emissao'], '23:59:59');
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_lancamento'])) {
            $field = 'n.datalancamento >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_lancamento'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_lancamento'])) {
            $field = 'n.datalancamento <= ?';
            $condition[$field] = Filter::datetime($condition['ate_lancamento'], '23:59:59');
            $allowed[$field] = true;
        }
        if (isset($condition['!estado'])) {
            $field = 'n.estado <> ?';
            $condition[$field] = $condition['!estado'];
            $allowed[$field] = true;
        }
        if (isset($condition['disponivel'])) {
            $field = '(n.estado <> ? OR n.contingencia = ?)';
            $condition[$field] = [self::ESTADO_PENDENTE, 'N'];
            $allowed[$field] = true;
        }
        if (isset($condition['tarefas'])) {
            $field = '(n.acao <> ? OR (n.contingencia = ? AND n.estado = ?))';
            $condition[$field] = [self::ACAO_AUTORIZAR, 'Y', self::ESTADO_PENDENTE];
            $allowed[$field] = true;
        }
        return Filter::keys($condition, $allowed, 'n.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Notas n');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('n.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Nota or empty instance
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
     * @return self A filled Nota or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('nota.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find invoice by key
     * @param  string $chave key to find Nota
     * @param  boolean $todos find all invoice finished or not
     * @return Nota A filled instance or empty when not found
     */
    public static function findByChave($chave, $todos = false)
    {
        $condition = ['chave' => $chave];
        if (!$todos) {
            $condition['concluido'] = 'N';
        }
        $order = ['concluido' => -1, 'corrigido' => 1, 'datalancamento' => -1];
        return self::find($condition, $order);
    }

    /**
     * Find invoice by order id
     * @param  string $pedido_id order id to find Nota
     * @param  boolean $todos find all invoice finished or not
     * @return Nota A filled instance or empty when not found
     */
    public static function findByPedidoID($pedido_id, $todos = false)
    {
        $condition = ['pedidoid' => intval($pedido_id)];
        if (!$todos) {
            $condition['concluido'] = 'N';
        }
        $order = ['concluido' => -1, 'corrigido' => 1, 'datalancamento' => -1];
        return self::find($condition, $order);
    }

    /**
     * Find valid invoice by order id
     * @param  string $pedido_id order id to find Nota
     * @return Nota A filled instance or empty when not found
     */
    public static function findValida($pedido_id)
    {
        $condition = [
            'pedidoid' => intval($pedido_id),
            'tipo' => self::TIPO_NOTA,
            'acao' => self::ACAO_AUTORIZAR
        ];
        $order = ['sequencia' => -1, 'numerofinal' => -1];
        return self::find($condition, $order);
    }

    public static function zip($notas)
    {
        $files = [];
        foreach ($notas as $_nota) {
            // Notas abertas não possuem arquivo XML
            if ($_nota->getEstado() == self::ESTADO_ABERTO) {
                continue;
            }
            // a nota cancelada tem 2 XML
            $xmlfile = $_nota->getCaminhoXml();
            if (is_array($xmlfile)) {
                $_files = $xmlfile;
            } else {
                $_files = ['nota' => $xmlfile];
            }
            foreach ($_files as $xmlfile) {
                $xmlname = basename($xmlfile);
                $directory = basename(dirname($xmlfile));
                $files[$directory . '/' . $xmlname] = $xmlfile;
            }
        }
        $zipfile = tempnam(sys_get_temp_dir(), 'xml');
        create_zip($files, $zipfile, true);
        return $zipfile;
    }

    /**
     * Find all Nota
     * @param array  $condition Condition to get all Nota
     * @param array  $order     Order Nota
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Nota
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
     * Find all open invoice
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all open invoice
     */
    public static function findAllOpen($limit = null, $offset = null)
    {
        $condition = [
            'tipo' => self::TIPO_NOTA,
            'acao' => self::ACAO_AUTORIZAR,
            '!estado' => self::ESTADO_PROCESSAMENTO,
            'disponivel' => 'Y',
            'corrigido' => 'Y',
            'concluido' => 'N'
        ];
        return self::findAll($condition, [], $limit, $offset);
    }

    /**
     * Find all pending invoice
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all pending invoice
     */
    public static function findAllPending($limit = null, $offset = null)
    {
        $condition = [
            'tipo' => self::TIPO_NOTA,
            'acao' => self::ACAO_AUTORIZAR,
            'estado' => self::ESTADO_PROCESSAMENTO,
            'disponivel' => 'Y',
            'corrigido' => 'Y',
            'concluido' => 'N'
        ];
        return self::findAll($condition, [], $limit, $offset);
    }

    /**
     * Find all invoice tasks
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all invoice tasks
     */
    public static function findAllTasks($limit = null, $offset = null)
    {
        $condition = [
            'tarefas' => 'Y',
            'corrigido' => 'Y',
            'concluido' => 'N'
        ];
        return self::findAll($condition, [], $limit, $offset);
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
