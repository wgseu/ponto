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
 * Registra todas as atividades importantes do sistema
 */
class Auditoria extends Model
{
    /**
     * Tipo de atividade exercida
     */
    const TIPO_FINANCEIRO = 'financeiro';
    const TIPO_ADMINISTRATIVO = 'administrativo';
    const TIPO_OPERACIONAL = 'operacional';

    /**
     * Prioridade de acesso do recurso
     */
    const PRIORIDADE_BAIXA = 'baixa';
    const PRIORIDADE_MEDIA = 'media';
    const PRIORIDADE_ALTA = 'alta';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auditorias';

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
        'permissao_id',
        'prestador_id',
        'autorizador_id',
        'tipo',
        'prioridade',
        'descricao',
        'autorizacao',
        'data_registro',
    ];

    /**
     * Informa a permissão concedida ou utilizada que permitiu a realização da
     * operação
     */
    public function permissao()
    {
        return $this->belongsTo('App\Models\Permissao', 'permissao_id');
    }

    /**
     * Prestador que exerceu a atividade
     */
    public function prestador()
    {
        return $this->belongsTo('App\Models\Prestador', 'prestador_id');
    }

    /**
     * Prestador que autorizou o acesso ao recurso descrito
     */
    public function autorizador()
    {
        return $this->belongsTo('App\Models\Prestador', 'autorizador_id');
    }
}
