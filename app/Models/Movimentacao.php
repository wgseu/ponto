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
 * Movimentação do caixa, permite abrir diversos caixas na conta de
 * operadores
 */
class Movimentacao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movimentacoes';

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
        'sessao_id',
        'caixa_id',
        'aberta',
        'iniciador_id',
        'fechador_id',
        'data_fechamento',
        'data_abertura',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'aberta' => true,
    ];

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     */
    public function sessao()
    {
        return $this->belongsTo('App\Models\Sessao', 'sessao_id');
    }

    /**
     * Caixa a qual pertence essa movimentação
     */
    public function caixa()
    {
        return $this->belongsTo('App\Models\Caixa', 'caixa_id');
    }

    /**
     * Funcionário que abriu o caixa
     */
    public function iniciador()
    {
        return $this->belongsTo('App\Models\Prestador', 'iniciador_id');
    }

    /**
     * Funcionário que fechou o caixa
     */
    public function fechador()
    {
        return $this->belongsTo('App\Models\Prestador', 'fechador_id');
    }
}