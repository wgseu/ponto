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
use App\Interfaces\ValidateUpdateInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para
 * formações de produtos
 */
class Grupo extends Model implements ValidateInterface, ValidateUpdateInterface
{
    use ModelEvents;
    use SoftDeletes;

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    public const TIPO_INTEIRO = 'inteiro';
    public const TIPO_FRACIONADO = 'fracionado';

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor
     * preço, Média:  define o preço do produto como a média dos itens
     * selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma:
     * Soma todos os preços dos produtos selecionados
     */
    public const FUNCAO_MINIMO = 'minimo';
    public const FUNCAO_MEDIA = 'media';
    public const FUNCAO_MAXIMO = 'maximo';
    public const FUNCAO_SOMA = 'soma';

    public const DELETED_AT = 'data_arquivado';

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
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Regras:
     * Os grupos são formados apenas por pacotes,
     * A quantidade minima não pode ser maior que a maxima a menos que a maxima seja zero,
     * Se a quantidade maxima for zero não a liminte para produtos do grupo,
     * A quantidade minima e maxima não pode ser negativas,
     * Depois de casdastrado não pode alterar o produto do grupo,
     * A quantidade maxima do grupo não pode ser inferior a quantidade maxima do pacote.
     * Se o grupo está associado a um pacote a alteração da ordem não pode ser superior a ordem do grupo associado;
     */
    public function validate($old)
    {
        $errors = [];
        $produto = $this->produto;
        if ($produto->tipo != Produto::TIPO_PACOTE) {
            $errors['produto_id'] = __('messages.produto_required_pacote');
        }
        if ($this->quantidade_minima > $this->quantidade_maxima && $this->quantidade_maxima != 0) {
            $errors['quantidade_minima'] = __('messages.quantidade_minima_cannot_greater_maxima');
        }
        if ($this->quantidade_minima < 0) {
            $errors['quantidade_minima'] = __('messages.quantidade_minima_cannot_negative');
        }
        if ($this->quantidade_maxima < 0) {
            $errors['quantidade_maxima'] = __('messages.quantidade_maxima_cannot_negative');
        }
        return $errors;
    }

    public function onUpdate($old)
    {
        $errors = [];
        $ordem = self::select('1')
            ->from('pacotes as p')
            ->leftJoin('pacotes as a', 'a.id', '=', 'p.associacao_id')
            ->leftJoin('grupos', 'grupos.id', '=', 'p.grupo_id')
            ->where('a.id', '<>', null)
            ->where('a.grupo_id', '=', $this->id)
            ->where(function ($query) {
                $query->where('grupos.ordem', '<', $this->ordem)
                ->orWhere(function ($query) {
                    $query->where('grupos.ordem', '=', $this->ordem)
                    ->where('grupos.id', '<=', $this->id);
                });
            })
            ->count();

        if ($ordem > 0) {
            $errors['produto_id'] = __('messages.update_ordem_invalid');
        }
        if ($old->produto_id != $this->produto_id) {
            $errors['produto_id'] = __('messages.produto_cannot_update');
        }
        if (
            $old->quantidade_maxima != $this->quantidade_maxima
            && $this->tipo == Grupo::TIPO_INTEIRO
            && $this->quantidade_maxima != 0
        ) {
            $pacoteMaximo = Pacote::where('grupo_id', $this->id)->max('quantidade_maxima');
            if (!is_null($pacoteMaximo) && $pacoteMaximo > $this->quantidade_maxima) {
                $errors['produto_id'] = __('messages.grupo_cannot_update_quantidade_maxima_less_group');
            }
        }
        return $errors;
    }
}
