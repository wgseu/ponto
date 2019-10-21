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

use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Notas fiscais e inutilizações
 */
class Nota extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Tipo de registro se nota ou inutilização
     */
    public const TIPO_NOTA = 'nota';
    public const TIPO_INUTILIZACAO = 'inutilizacao';

    /**
     * Ambiente em que a nota foi gerada
     */
    public const AMBIENTE_HOMOLOGACAO = 'homologacao';
    public const AMBIENTE_PRODUCAO = 'producao';

    /**
     * Ação que deve ser tomada sobre a nota fiscal
     */
    public const ACAO_AUTORIZAR = 'autorizar';
    public const ACAO_CANCELAR = 'cancelar';
    public const ACAO_INUTILIZAR = 'inutilizar';

    /**
     * Estado da nota
     */
    public const ESTADO_ABERTO = 'aberto';
    public const ESTADO_ASSINADO = 'assinado';
    public const ESTADO_PENDENTE = 'pendente';
    public const ESTADO_PROCESSAMENTO = 'processamento';
    public const ESTADO_DENEGADO = 'denegado';
    public const ESTADO_REJEITADO = 'rejeitado';
    public const ESTADO_CANCELADO = 'cancelado';
    public const ESTADO_INUTILIZADO = 'inutilizado';
    public const ESTADO_AUTORIZADO = 'autorizado';

    public const CREATED_AT = 'data_lancamento';
    public const DELETED_AT = 'data_arquivado';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notas';

    /**
     * The attributes that are mass assignable.
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

    public function validate()
    {
    }
}
