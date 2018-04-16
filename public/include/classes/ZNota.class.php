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
class NotaTipo
{
    const NOTA = 'Nota';
    const INUTILIZACAO = 'Inutilizacao';
}
class NotaAmbiente
{
    const HOMOLOGACAO = 'Homologacao';
    const PRODUCAO = 'Producao';
}
class NotaAcao
{
    const AUTORIZAR = 'Autorizar';
    const CANCELAR = 'Cancelar';
    const INUTILIZAR = 'Inutilizar';
}
class NotaEstado
{
    const ABERTO = 'Aberto';
    const ASSINADO = 'Assinado';
    const PENDENTE = 'Pendente';
    const PROCESSAMENTO = 'Processamento';
    const DENEGADO = 'Denegado';
    const REJEITADO = 'Rejeitado';
    const CANCELADO = 'Cancelado';
    const INUTILIZADO = 'Inutilizado';
    const AUTORIZADO = 'Autorizado';
}

/**
 * Notas fiscais e inutilizações
 */
class ZNota
{
    private $id;
    private $tipo;
    private $ambiente;
    private $acao;
    private $estado;
    private $serie;
    private $numero_inicial;
    private $numero_final;
    private $sequencia;
    private $chave;
    private $recibo;
    private $protocolo;
    private $pedido_id;
    private $motivo;
    private $contingencia;
    private $consulta_url;
    private $qrcode;
    private $tributos;
    private $detalhes;
    private $corrigido;
    private $concluido;
    private $data_autorizacao;
    private $data_emissao;
    private $data_lancamento;

    public function __construct($nota = [])
    {
        $this->fromArray($nota);
    }

    /**
     * Identificador da nota
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
     * Tipo de registro se nota ou inutilização
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
     * Ambiente em que a nota foi gerada
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
    }

    /**
     * Ação que deve ser tomada sobre a nota fiscal
     */
    public function getAcao()
    {
        return $this->acao;
    }

    public function setAcao($acao)
    {
        $this->acao = $acao;
    }

    /**
     * Estado da nota
     */
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Série da nota
     */
    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    /**
     * Número inicial da nota
     */
    public function getNumeroInicial()
    {
        return $this->numero_inicial;
    }

    public function setNumeroInicial($numero_inicial)
    {
        $this->numero_inicial = $numero_inicial;
    }

    /**
     * Número final da nota, igual ao número inicial quando for a nota de um pedido
     */
    public function getNumeroFinal()
    {
        return $this->numero_final;
    }

    public function setNumeroFinal($numero_final)
    {
        $this->numero_final = $numero_final;
    }

    /**
     * Permite iniciar o número da nota quando alcançar 999.999.999, deve ser
     * incrementado sempre que alcançar
     */
    public function getSequencia()
    {
        return $this->sequencia;
    }

    public function setSequencia($sequencia)
    {
        $this->sequencia = $sequencia;
    }

    /**
     * Chave da nota fiscal
     */
    public function getChave()
    {
        return $this->chave;
    }

    public function setChave($chave)
    {
        $this->chave = $chave;
    }

    /**
     * Recibo de envio para consulta posterior
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
    }

    /**
     * Protocolo de autorização da nota fiscal
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;
    }

    /**
     * Pedido da nota
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }

    /**
     * Motivo do cancelamento, contingência ou inutilização
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * Informa se a nota está em contingência
     */
    public function getContingencia()
    {
        return $this->contingencia;
    }

    /**
     * Informa se a nota está em contingência
     */
    public function isContingencia()
    {
        return $this->contingencia == 'Y';
    }

    public function setContingencia($contingencia)
    {
        $this->contingencia = $contingencia;
    }

    /**
     * URL de consulta da nota fiscal
     */
    public function getConsultaURL()
    {
        return $this->consulta_url;
    }

    public function setConsultaURL($consulta_url)
    {
        $this->consulta_url = $consulta_url;
    }

    /**
     * Dados do QRCode da nota
     */
    public function getQRCode()
    {
        return $this->qrcode;
    }

    public function setQRCode($qrcode)
    {
        $this->qrcode = $qrcode;
    }

    /**
     * Tributos totais da nota
     */
    public function getTributos()
    {
        return $this->tributos;
    }

    public function setTributos($tributos)
    {
        $this->tributos = $tributos;
    }

    /**
     * Informações de interesse do contribuinte
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
     * Informa se os erros já foram corrigidos para retomada do processamento
     */
    public function getCorrigido()
    {
        return $this->corrigido;
    }

    /**
     * Informa se os erros já foram corrigidos para retomada do processamento
     */
    public function isCorrigido()
    {
        return $this->corrigido == 'Y';
    }

    public function setCorrigido($corrigido)
    {
        $this->corrigido = $corrigido;
    }

    /**
     * Informa se todos os processamentos da nota já foram realizados
     */
    public function getConcluido()
    {
        return $this->concluido;
    }

    /**
     * Informa se todos os processamentos da nota já foram realizados
     */
    public function isConcluido()
    {
        return $this->concluido == 'Y';
    }

    public function setConcluido($concluido)
    {
        $this->concluido = $concluido;
    }

    /**
     * Data de autorização da nota fiscal
     */
    public function getDataAutorizacao()
    {
        return $this->data_autorizacao;
    }

    public function setDataAutorizacao($data_autorizacao)
    {
        $this->data_autorizacao = $data_autorizacao;
    }

    /**
     * Data de emissão da nota
     */
    public function getDataEmissao()
    {
        return $this->data_emissao;
    }

    public function setDataEmissao($data_emissao)
    {
        $this->data_emissao = $data_emissao;
    }

    /**
     * Data de lançamento da nota no sistema
     */
    public function getDataLancamento()
    {
        return $this->data_lancamento;
    }

    public function setDataLancamento($data_lancamento)
    {
        $this->data_lancamento = $data_lancamento;
    }

    public function toArray()
    {
        $nota = [];
        $nota['id'] = $this->getID();
        $nota['tipo'] = $this->getTipo();
        $nota['ambiente'] = $this->getAmbiente();
        $nota['acao'] = $this->getAcao();
        $nota['estado'] = $this->getEstado();
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

    public function fromArray($nota = [])
    {
        if (!is_array($nota)) {
            return $this;
        }
        $this->setID(isset($nota['id'])?$nota['id']:null);
        $this->setTipo(isset($nota['tipo'])?$nota['tipo']:null);
        $this->setAmbiente(isset($nota['ambiente'])?$nota['ambiente']:null);
        $this->setAcao(isset($nota['acao'])?$nota['acao']:null);
        $this->setEstado(isset($nota['estado'])?$nota['estado']:null);
        $this->setSerie(isset($nota['serie'])?$nota['serie']:null);
        $this->setNumeroInicial(isset($nota['numeroinicial'])?$nota['numeroinicial']:null);
        $this->setNumeroFinal(isset($nota['numerofinal'])?$nota['numerofinal']:null);
        $this->setSequencia(isset($nota['sequencia'])?$nota['sequencia']:null);
        $this->setChave(isset($nota['chave'])?$nota['chave']:null);
        $this->setRecibo(isset($nota['recibo'])?$nota['recibo']:null);
        $this->setProtocolo(isset($nota['protocolo'])?$nota['protocolo']:null);
        $this->setPedidoID(isset($nota['pedidoid'])?$nota['pedidoid']:null);
        $this->setMotivo(isset($nota['motivo'])?$nota['motivo']:null);
        $this->setContingencia(isset($nota['contingencia'])?$nota['contingencia']:null);
        $this->setConsultaURL(isset($nota['consultaurl'])?$nota['consultaurl']:null);
        $this->setQRCode(isset($nota['qrcode'])?$nota['qrcode']:null);
        $this->setTributos(isset($nota['tributos'])?$nota['tributos']:null);
        $this->setDetalhes(isset($nota['detalhes'])?$nota['detalhes']:null);
        $this->setCorrigido(isset($nota['corrigido'])?$nota['corrigido']:null);
        $this->setConcluido(isset($nota['concluido'])?$nota['concluido']:null);
        $this->setDataAutorizacao(isset($nota['dataautorizacao'])?$nota['dataautorizacao']:null);
        $this->setDataEmissao(isset($nota['dataemissao'])?$nota['dataemissao']:null);
        $this->setDataLancamento(isset($nota['datalancamento'])?$nota['datalancamento']:null);
    }

    public function isAutorizada()
    {
        $result = ($this->getTipo() == Nota::TIPO_NOTA) && ($this->getAcao() == Nota::ACAO_AUTORIZAR);
        if (!$result) {
            return $result;
        }
        $result = $this->isCorrigido() && $this->isConcluido() && ($this->getEstado() == Nota::ESTADO_AUTORIZADO);
        if ($result) {
            return $result;
        }
        $result = $this->isContingencia() && $this->isCorrigido() && ($this->getEstado() == Nota::ESTADO_ASSINADO);
        return $result;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Notas')
                         ->where(['id' => $id]);
        return new Nota($query->fetch());
    }

    public static function getPelaChave($chave, $todos = false)
    {
        $query = \DB::$pdo->from('Notas')
                         ->where(['chave' => $chave])
                         ->orderBy('concluido ASC, corrigido DESC, datalancamento DESC')
                         ->limit(1);
        if (!$todos) {
            $query = $query->where('concluido', 'N');
        }
        return new Nota($query->fetch());
    }

    public static function getPeloPedidoID($pedido_id, $todos = false)
    {
        $query = \DB::$pdo->from('Notas')
                         ->where('pedidoid', intval($pedido_id))
                         ->orderBy('concluido ASC, corrigido DESC, datalancamento DESC')
                         ->limit(1);
        if (!$todos) {
            $query = $query->where('concluido', 'N');
        }
        return new Nota($query->fetch());
    }

    public static function getValida($pedido_id)
    {
        $query = \DB::$pdo->from('Notas')
                         ->where('pedidoid', intval($pedido_id))
                         ->where('tipo', Nota::TIPO_NOTA)
                         ->where('acao', Nota::ACAO_AUTORIZAR)
                         ->orderBy('sequencia DESC, numerofinal DESC')
                         ->limit(1);
        return new Nota($query->fetch());
    }

    private static function validarCampos(&$nota)
    {
        $erros = [];
        $nota['tipo'] = strval($nota['tipo']);
        if (!in_array($nota['tipo'], ['Nota', 'Inutilizacao'])) {
            $erros['tipo'] = 'O tipo informado não é válido';
        }
        $nota['ambiente'] = strval($nota['ambiente']);
        if (!in_array($nota['ambiente'], ['Homologacao', 'Producao'])) {
            $erros['ambiente'] = 'O ambiente informado não é válido';
        }
        $nota['acao'] = strval($nota['acao']);
        if (!in_array($nota['acao'], ['Autorizar', 'Cancelar', 'Inutilizar'])) {
            $erros['acao'] = 'A ação informada não é válida';
        }
        $nota['estado'] = strval($nota['estado']);
        if (!in_array($nota['estado'], ['Aberto', 'Assinado', 'Pendente', 'Processamento', 'Denegado', 'Rejeitado', 'Cancelado', 'Inutilizado', 'Autorizado'])) {
            $erros['estado'] = 'O estado informado não é válido';
        }
        if (!is_numeric($nota['serie'])) {
            $erros['serie'] = 'A série não foi informada';
        }
        if (!is_numeric($nota['numeroinicial'])) {
            $erros['numeroinicial'] = 'O número não foi informado';
        }
        if (!is_numeric($nota['numerofinal'])) {
            $erros['numerofinal'] = 'O número inicial não foi informado';
        }
        if (!is_numeric($nota['sequencia'])) {
            $erros['sequencia'] = 'O sequencia não foi informado';
        }
        $nota['chave'] = strip_tags(trim($nota['chave']));
        if (strlen($nota['chave']) == 0) {
            $nota['chave'] = null;
        }
        $nota['recibo'] = strip_tags(trim($nota['recibo']));
        if (strlen($nota['recibo']) == 0) {
            $nota['recibo'] = null;
        }
        $nota['protocolo'] = strip_tags(trim($nota['protocolo']));
        if (strlen($nota['protocolo']) == 0) {
            $nota['protocolo'] = null;
        }
        $nota['pedidoid'] = trim($nota['pedidoid']);
        if (strlen($nota['pedidoid']) == 0) {
            $nota['pedidoid'] = null;
        } elseif (!is_numeric($nota['pedidoid'])) {
            $erros['pedidoid'] = 'O pedido não foi informado';
        }
        $nota['motivo'] = strip_tags(trim($nota['motivo']));
        if (strlen($nota['motivo']) == 0) {
            $nota['motivo'] = null;
        }
        $nota['contingencia'] = strval($nota['contingencia']);
        if (strlen($nota['contingencia']) == 0) {
            $nota['contingencia'] = 'N';
        } elseif (!in_array($nota['contingencia'], ['Y', 'N'])) {
            $erros['contingencia'] = 'A contingência informada não é válida';
        }
        $nota['consultaurl'] = strip_tags(trim($nota['consultaurl']));
        if (strlen($nota['consultaurl']) == 0) {
            $nota['consultaurl'] = null;
        }
        $nota['qrcode'] = strip_tags(trim($nota['qrcode']));
        if (strlen($nota['qrcode']) == 0) {
            $nota['qrcode'] = null;
        }
        $nota['tributos'] = trim($nota['tributos']);
        if (strlen($nota['tributos']) == 0) {
            $nota['tributos'] = null;
        } elseif (!is_numeric($nota['tributos'])) {
            $erros['tributos'] = 'O tributos não foi informado';
        }
        $nota['detalhes'] = strip_tags(trim($nota['detalhes']));
        if (strlen($nota['detalhes']) == 0) {
            $nota['detalhes'] = null;
        }
        $nota['corrigido'] = trim($nota['corrigido']);
        if (strlen($nota['corrigido']) == 0) {
            $nota['corrigido'] = 'N';
        } elseif (!in_array($nota['corrigido'], ['Y', 'N'])) {
            $erros['corrigido'] = 'O corrigido informado não é válido';
        }
        $nota['concluido'] = trim($nota['concluido']);
        if (strlen($nota['concluido']) == 0) {
            $nota['concluido'] = 'N';
        } elseif (!in_array($nota['concluido'], ['Y', 'N'])) {
            $erros['concluido'] = 'O concluído informado não é válido';
        }
        $dataautorizacao = null;
        if (is_numeric($nota['dataautorizacao'])) {
            $dataautorizacao = date('Y-m-d H:i:s', $nota['dataautorizacao']);
        } elseif (strtotime($nota['dataautorizacao']) !== false) {
            $dataautorizacao = $nota['dataautorizacao'];
        }
        $nota['dataautorizacao'] = $dataautorizacao;
        if (is_numeric($nota['dataemissao'])) {
            $dataemissao = date('Y-m-d H:i:s', $nota['dataemissao']);
        } elseif (strtotime($nota['dataemissao']) !== false) {
            $dataemissao = $nota['dataemissao'];
        } else {
            $dataemissao = date('Y-m-d H:i:s');
        }
        $nota['dataemissao'] = $dataemissao;
        if (is_numeric($nota['datalancamento'])) {
            $datalancamento = date('Y-m-d H:i:s', $nota['datalancamento']);
        } elseif (strtotime($nota['datalancamento']) !== false) {
            $datalancamento = $nota['datalancamento'];
        } else {
            $datalancamento = date('Y-m-d H:i:s');
        }
        $nota['datalancamento'] = $datalancamento;
        if (!empty($erros)) {
            throw new \MZ\Exception\ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new \MZ\Exception\ValidationException(['id' => 'O id informado já está cadastrado']);
        }
    }

    public static function cadastrar($nota)
    {
        $_nota = $nota->toArray();
        self::validarCampos($_nota);
        try {
            $_nota['id'] = \DB::$pdo->insertInto('Notas')->values($_nota)->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_nota['id']);
    }

    public static function atualizar($nota)
    {
        $_nota = $nota->toArray();
        if (!$_nota['id']) {
            throw new \MZ\Exception\ValidationException(['id' => 'O id da nota não foi informado']);
        }
        self::validarCampos($_nota);
        $campos = [
            'tipo',
            'ambiente',
            'acao',
            'estado',
            'serie',
            'numeroinicial',
            'numerofinal',
            'sequencia',
            'chave',
            'recibo',
            'protocolo',
            'pedidoid',
            'motivo',
            'contingencia',
            'consultaurl',
            'qrcode',
            'tributos',
            'detalhes',
            'corrigido',
            'concluido',
            'dataautorizacao',
            'dataemissao',
            'datalancamento',
        ];
        try {
            $query = \DB::$pdo->update('Notas');
            $query = $query->set(array_intersect_key($_nota, array_flip($campos)));
            $query = $query->where('id', $_nota['id']);
            $query->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_nota['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir a nota, o id da nota não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Notas')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    public static function criarProxima($base)
    {
        // procura o último número da nota e a última sequencia de repetições
        $query = \DB::$pdo->from('Notas')
                         ->where('ambiente', $base->getAmbiente())
                         ->where('serie', $base->getSerie())
                         ->orderBy('sequencia DESC, numerofinal DESC')
                         ->limit(1);
        $ultima = new Nota($query->fetch());
        if (!$ultima->exists()) {
            // não existe nenhuma nota ou inutilização para essa série ou ambiente
            $ultima->fromArray($base->toArray()); // copia a série e ambiente
            // inicia a numeração e sequência
            $ultima->setNumeroFinal(0);
            $ultima->setSequencia(1);
        }
        // retira os dados da última nota
        $ultima->setID(null);
        $ultima->setTipo(Nota::TIPO_NOTA);
        $ultima->setAcao(Nota::ACAO_AUTORIZAR);
        $ultima->setEstado(Nota::ESTADO_ABERTO);
        $ultima->setChave(null);
        $ultima->setRecibo(null);
        $ultima->setProtocolo(null);
        $ultima->setMotivo(null);
        $ultima->setContingencia('N');
        $ultima->setConsultaURL(null);
        $ultima->setQRCode(null);
        $ultima->setTributos(null);
        $ultima->setDetalhes(null);
        $ultima->setCorrigido('Y');
        $ultima->setConcluido('N');
        $ultima->setDataEmissao(null);
        $ultima->setDataAutorizacao(null);
        $ultima->setDataLancamento(null);
        // utiliza o pedido da base
        $ultima->setPedidoID($base->getPedidoID());
        // verifica se o número inicial do caixa deve ser o próximo
        $caixa = Caixa::findBySerie($ultima->getSerie());
        if ($caixa->getNumeroInicial() > $ultima->getNumeroFinal()) {
            $ultima->setNumeroFinal($caixa->getNumeroInicial() - 1);
        }
        // verifica se alcançou o último número da nota permitido na SEFAZ
        if ($ultima->getNumeroFinal() == 999999999) {
            // inicia uma nova sequência
            $ultima->setSequencia($ultima->getSequencia() + 1);
            $ultima->setNumeroFinal(0);
            Caixa::resetBySerie($ultima->getSerie());
        }
        // incrementa para o próximo número
        $ultima->setNumeroFinal($ultima->getNumeroFinal() + 1);
        $ultima->setNumeroInicial($ultima->getNumeroFinal());
        // cadastra a nota como aberta
        return self::cadastrar($ultima);
    }

    public function getCaminhoXml()
    {
        $xmlfile = NFeDB::getCaminhoXmlAtual($this);
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

    public static function zip($notas)
    {
        $files = [];
        foreach ($notas as $_nota) {
            // Notas abertas não possuem arquivo XML
            if ($_nota->getEstado() == Nota::ESTADO_ABERTO) {
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

    private static function initSearch(
        $busca,
        $estado,
        $acao,
        $ambiente,
        $serie,
        $pedido_id,
        $tipo,
        $contingencia,
        $emissao_inicio,
        $emissao_fim,
        $lancamento_inicio,
        $lancamento_fim
    ) {
        $query = \DB::$pdo->from('Notas')
                         ->orderBy('id ASC');
        $chave = null;
        $protocolo = null;
        $numero_inicial = null;
        $numero_final = null;
        $parts = explode('-', $busca);
        if ($parts !== false && count($parts) > 0 && count($parts) <= 2) {
            if (is_number($parts[0])) {
                if (strlen($parts[0]) == 44) {
                    $chave = $parts[0];
                } elseif (strlen($parts[0]) > 9) {
                    $protocolo = $parts[0];
                } elseif (strlen($parts[0]) > 0) {
                    $numero_inicial = intval($parts[0]);
                    if (count($parts) == 2 && strlen($parts[1]) <= 9 && is_number($parts[1])) {
                        $numero_final = intval($parts[1]);
                    }
                }
            }
        }
        $num = preg_replace('-', '', $busca);
        if (!is_number($num) && trim($busca) != '') {
            $busca = '%'.preg_replace(' ', '%', trim($busca)).'%';
            $query = $query->where('motivo LIKE ?', $busca);
        }
        if (!is_null($chave)) {
            $query = $query->where('chave', $chave);
        }
        if (!is_null($protocolo)) {
            $query = $query->where('protocolo', $protocolo);
        }
        if (is_numeric($numero_inicial) && is_numeric($numero_final)) {
            $query = $query->where('numeroinicial BETWEEN ? AND ?', intval($numero_inicial), intval($numero_final));
        } elseif (is_numeric($numero_inicial)) {
            $query = $query->where('numeroinicial', intval($numero_inicial));
        } elseif (is_numeric($numero_final)) {
            $query = $query->where('numerofinal', intval($numero_final));
        }
        if (is_numeric($serie)) {
            $query = $query->where('serie', intval($serie));
        }
        if (is_numeric($pedido_id)) {
            $query = $query->where('pedidoid', intval($pedido_id));
        }
        if (trim($estado) != '') {
            $query = $query->where('estado', strval($estado));
        }
        if (trim($acao) != '') {
            $query = $query->where('acao', strval($acao));
        }
        if (trim($ambiente) != '') {
            $query = $query->where('ambiente', strval($ambiente));
        }
        if (trim($tipo) != '') {
            $query = $query->where('tipo', strval($tipo));
        }
        if (in_array($contingencia, ['Y', 'N'])) {
            $query = $query->where('contingencia', strval($contingencia));
        }
        if ($emissao_inicio !== false) {
            $query = $query->where('dataemissao >= ?', date('Y-m-d', $emissao_inicio));
        }
        if ($emissao_fim !== false) {
            $query = $query->where('dataemissao <= ?', date('Y-m-d 23:59:59', $emissao_fim));
        }
        if ($lancamento_inicio !== false) {
            $query = $query->where('datalancamento >= ?', date('Y-m-d', $lancamento_inicio));
        }
        if ($lancamento_fim !== false) {
            $query = $query->where('datalancamento <= ?', date('Y-m-d 23:59:59', $lancamento_fim));
        }
        return $query;
    }

    public static function getTodas(
        $busca = null,
        $estado = null,
        $acao = null,
        $ambiente = null,
        $serie = null,
        $pedido_id = null,
        $tipo = null,
        $contingencia = null,
        $emissao_inicio = false,
        $emissao_fim = false,
        $lancamento_inicio = false,
        $lancamento_fim = false,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch(
            $busca,
            $estado,
            $acao,
            $ambiente,
            $serie,
            $pedido_id,
            $tipo,
            $contingencia,
            $emissao_inicio,
            $emissao_fim,
            $lancamento_inicio,
            $lancamento_fim
        );
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_notas = $query->fetchAll();
        $notas = [];
        foreach ($_notas as $nota) {
            $notas[] = new Nota($nota);
        }
        return $notas;
    }

    public static function getCount(
        $busca = null,
        $estado = null,
        $acao = null,
        $ambiente = null,
        $serie = null,
        $pedido_id = null,
        $tipo = null,
        $contingencia = null,
        $emissao_inicio = false,
        $emissao_fim = false,
        $lancamento_inicio = false,
        $lancamento_fim = false
    ) {
        $query = self::initSearch(
            $busca,
            $estado,
            $acao,
            $ambiente,
            $serie,
            $pedido_id,
            $tipo,
            $contingencia,
            $emissao_inicio,
            $emissao_fim,
            $lancamento_inicio,
            $lancamento_fim
        );
        return $query->count();
    }

    private static function initSearchAbertas()
    {
        return   \DB::$pdo->from('Notas')
                         ->where('tipo', Nota::TIPO_NOTA)
                         ->where('acao', Nota::ACAO_AUTORIZAR)
                         ->where('estado <> ?', Nota::ESTADO_PROCESSAMENTO)
                         ->where('(estado <> ? OR contingencia = ?)', NotaEstado::PENDENTE, 'N')
                         ->where('corrigido', 'Y')
                         ->where('concluido', 'N')
                         ->orderBy('id DESC');
    }

    public static function getAbertas($inicio = null, $quantidade = null)
    {
        $query = self::initSearchAbertas();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_notas = $query->fetchAll();
        $notas = [];
        foreach ($_notas as $nota) {
            $notas[] = new Nota($nota);
        }
        return $notas;
    }

    public static function getCountAbertas()
    {
        $query = self::initSearchAbertas();
        return $query->count();
    }

    private static function initSearchPendentes()
    {
        return   \DB::$pdo->from('Notas')
                         ->where('tipo', Nota::TIPO_NOTA)
                         ->where('acao', Nota::ACAO_AUTORIZAR)
                         ->where('estado', Nota::ESTADO_PROCESSAMENTO)
                         ->where('corrigido', 'Y')
                         ->where('concluido', 'N')
                         ->orderBy('id DESC');
    }

    public static function getPendentes($inicio = null, $quantidade = null)
    {
        $query = self::initSearchPendentes();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_notas = $query->fetchAll();
        $notas = [];
        foreach ($_notas as $nota) {
            $notas[] = new Nota($nota);
        }
        return $notas;
    }

    public static function getCountPendentes()
    {
        $query = self::initSearchPendentes();
        return $query->count();
    }

    private static function initSearchTarefas()
    {
        return   \DB::$pdo->from('Notas')
                         ->where('(acao <> ? OR (contingencia = ? AND estado = ?))', NotaAcao::AUTORIZAR, 'Y', Nota::ESTADO_PENDENTE)
                         ->where('corrigido', 'Y')
                         ->where('concluido', 'N')
                         ->orderBy('id DESC');
    }

    public static function getTarefas($inicio = null, $quantidade = null)
    {
        $query = self::initSearchTarefas();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_notas = $query->fetchAll();
        $notas = [];
        foreach ($_notas as $nota) {
            $notas[] = new Nota($nota);
        }
        return $notas;
    }

    public static function getCountTarefas()
    {
        $query = self::initSearchTarefas();
        return $query->count();
    }

    private static function initSearchDoPedidoID($pedido_id)
    {
        return   \DB::$pdo->from('Notas')
                         ->where(['pedidoid' => $pedido_id])
                         ->orderBy('id ASC');
    }

    public static function getTodasDoPedidoID($pedido_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoPedidoID($pedido_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_notas = $query->fetchAll();
        $notas = [];
        foreach ($_notas as $nota) {
            $notas[] = new Nota($nota);
        }
        return $notas;
    }

    public static function getCountDoPedidoID($pedido_id)
    {
        $query = self::initSearchDoPedidoID($pedido_id);
        return $query->count();
    }
}
