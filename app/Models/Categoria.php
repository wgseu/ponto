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
 * Informa qual a categoria dos produtos e permite a rápida localização dos
 * mesmos
 */
class Categoria extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    public const UPDATED_AT = 'data_atualizacao';
    public const DELETED_AT = 'data_arquivado';
    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categorias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categoria_id',
        'descricao',
        'detalhes',
        'imagem_url',
        'ordem',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ordem' => 0,
    ];

    /**
     * Informa a categoria pai da categoria atual, a categoria atual é uma
     * subcategoria
     */
    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    public function validate()
    {
    }
}
