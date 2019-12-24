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
use Illuminate\Database\Eloquent\Model;

/**
 * Conferência diária de produto em cada setor
 */
class Conferencia extends Model
{
    use ModelEvents;

    public const UPDATED_AT = null;
    public const CREATED_AT = 'data_conferencia';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conferencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'produto_id',
        'setor_id',
        'conferido',
    ];

    /**
     * Funcionário que está realizando a conferẽncia do estoque
     */
    public function funcionario()
    {
        return $this->belongsTo(Prestador::class, 'funcionario_id');
    }

    /**
     * Produto que está sendo conferido nesse setor
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Setor em que o produto está localizado
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }
}
