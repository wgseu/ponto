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
$[table.if(package)]
namespace $[Table.package];
$[table.end]

use Illuminate\Database\Eloquent\Model;

$[table.if(comment)]
/**
$[table.each(comment)]
 * $[Table.comment]
$[table.end]
 */
$[table.end]
class $[Table.norm]$[table.if(inherited)] extends $[table.inherited]$[table.end]

{
$[field.each(enum)]
$[field.if(first)]
$[field.else]

$[field.end]
$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     */
$[field.end]
$[field.each(option)]
    const $[FIELD.unix]_$[FIELD.option.norm] = '$[field.option]';
$[field.end]
$[field.end]
$[field.exists(enum)]

$[field.end]
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '$[table]';

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
$[field.each(all)]
$[field.if(primary)]
$[field.else]
        '$[field]',
$[field.end]
$[field.end]
    ];
$[field.exists(default)]

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
$[field.each(default)]
        '$[field]' => $[fIeld.info],
$[field.end]
    ];
$[field.end]
$[field.each(reference)]

$[field.if(comment)]
    /**
$[field.each(comment)]
     * $[Field.comment]
$[field.end]
     */
$[field.end]
    public function $[fIeld.noid]()
    {
        return $this->belongsTo('$[Reference.package]\$[Reference.norm]', '$[field]');
    }
$[field.end]
}
