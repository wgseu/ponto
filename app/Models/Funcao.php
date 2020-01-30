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
use Illuminate\Database\Query\Builder;

/**
 * Função ou atribuição de tarefas à um prestador
 */
class Funcao extends Model implements ValidateInterface
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'funcoes';

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
        'descricao',
        'remuneracao',
    ];

    /**
     * Mapa com permissões
     *
     * @var int[]
     */
    private $permissoesCache = null;


    public function acessos()
    {
        return $this->hasMany(Acesso::class, 'funcao_id');
    }

    /**
     * Retorna a lista de nome das permissões da função
     *
     * @return string[]
     */
    public function getPermissoesAttribute()
    {
        return $this->permissoes()->pluck('nome');
    }

    /**
     * Lista de permissões dessa função
     *
     * @return Builder
     */
    public function permissoes()
    {
        return Permissao::leftJoin('acessos', 'acessos.permissao_id', '=', 'permissoes.id')
            ->where('acessos.funcao_id', $this->id);
    }

    /**
     * Verifica se a função tem acesso para a permissão informada
     *
     * @param string $permissao
     * @return boolean
     */
    public function hasPermissionTo(string $permissao)
    {
        if (is_null($this->permissoesCache)) {
            $this->permissoesCache = array_flip($this->permissoes);
        }
        return array_key_exists($permissao, $this->permissoesCache);
    }

    /**
     * Regras:
     * A remuração não pode ser negativa;
     */
    public function validate($old)
    {
        $errors = [];
        if ($this->remuneracao < 0) {
            $errors['remuneracao'] = __('messages.remuneracao_cannot_negative');
        }
        return $errors;
    }
}
