<?php

namespace App\Models;

use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Bairro de uma cidade
 */
class Colaborador extends Model implements ValidateInterface
{
    use ModelEvents;

    public const STATUS_TRABALHO = 'trabalho';
    public const STATUS_FERIAS = 'ferias';
    public const STATUS_ENCOSTADO = 'encostado';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colaboradores';

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
        'empresa_id',
        'nome',
        'sobrenome',
        'email',
        'senha',
        'carga_horaria',
        'status',
        'acumulado',
        'ativo'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'ativo' => false,
    ];

    /**
     * Cidade a qual o bairro pertence
     */
    public function cidade()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

}
