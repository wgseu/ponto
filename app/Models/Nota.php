<?php
/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
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
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Notas fiscais e inutilizações
 */
class Nota extends Model
{
    /**
     * Tipo de registro se nota ou inutilização
     */
    const TIPO_NOTA = 'nota';
    const TIPO_INUTILIZACAO = 'inutilizacao';

    /**
     * Ambiente em que a nota foi gerada
     */
    const AMBIENTE_HOMOLOGACAO = 'homologacao';
    const AMBIENTE_PRODUCAO = 'producao';

    /**
     * Ação que deve ser tomada sobre a nota fiscal
     */
    const ACAO_AUTORIZAR = 'autorizar';
    const ACAO_CANCELAR = 'cancelar';
    const ACAO_INUTILIZAR = 'inutilizar';

    /**
     * Estado da nota
     */
    const ESTADO_ABERTO = 'aberto';
    const ESTADO_ASSINADO = 'assinado';
    const ESTADO_PENDENTE = 'pendente';
    const ESTADO_PROCESSAMENTO = 'processamento';
    const ESTADO_DENEGADO = 'denegado';
    const ESTADO_REJEITADO = 'rejeitado';
    const ESTADO_CANCELADO = 'cancelado';
    const ESTADO_INUTILIZADO = 'inutilizado';
    const ESTADO_AUTORIZADO = 'autorizado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notas';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $fillable = [
        'tipo',
        'ambiente',
        'acao',
        'estado',
        'ultimo_evento_id',
        'serie',
        'numero_inicial',
        'numero_final',
        'sequencia',
        'chave',
        'recibo',
        'protocolo',
        'pedido_id',
        'motivo',
        'contingencia',
        'consulta_url',
        'qrcode',
        'tributos',
        'detalhes',
        'corrigido',
        'concluido',
        'data_autorizacao',
        'data_emissao',
        'data_lancamento',
        'data_arquivado',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'corrigido' => true,
        'concluido' => false,
    ];

    /**
     * Último evento da nota
     */
    public function ultimoEvento()
    {
        return $this->belongsTo('App\Models\Evento', 'ultimo_evento_id');
    }

    /**
     * Pedido da nota
     */
    public function pedido()
    {
        return $this->belongsTo('App\Models\Pedido', 'pedido_id');
    }
}
