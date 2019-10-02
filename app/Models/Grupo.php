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
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para
 * formações de produtos
 */
class Grupo extends Model implements ValidateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    const TIPO_INTEIRO = 'inteiro';
    const TIPO_FRACIONADO = 'fracionado';

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor
     * preço, Média:  define o preço do produto como a média dos itens
     * selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma:
     * Soma todos os preços dos produtos selecionados
     */
    const FUNCAO_MINIMO = 'minimo';
    const FUNCAO_MEDIA = 'media';
    const FUNCAO_MAXIMO = 'maximo';
    const FUNCAO_SOMA = 'soma';

    const DELETED_AT = 'data_arquivado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupos';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'produto_id',
        'nome',
        'descricao',
        'tipo',
        'quantidade_minima',
        'quantidade_maxima',
        'funcao',
        'ordem',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'tipo' => self::TIPO_INTEIRO,
        'quantidade_minima' => 1,
        'quantidade_maxima' => 0,
        'funcao' => self::FUNCAO_SOMA,
        'ordem' => 0,
    ];

    /**
     * Informa o pacote base da formação
     */
    public function produto()
    {
        return $this->belongsTo('App\Models\Produto', 'produto_id');
    }

    public function validate()
    {
    }
}
