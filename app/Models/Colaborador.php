<?php

namespace App\Models;

use App\Concerns\ModelEvents;
use App\Interfaces\ValidateInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Bairro de uma cidade
 */
class Colaborador extends Model
{
    use ModelEvents;

    public const STATUS_TRABALHO = 'trabalho';
    public const STATUS_FERIAS = 'ferias';
    public const STATUS_ENCOSTADO = 'encostado';

    public const SENHA_TEMPORARIA = '1q2w3e4r5t6y@';

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

    protected $attributes = [
        'senha' => self::SENHA_TEMPORARIA
    ];

        /**
     * Sempre que alterar a senha, roda a encriptação
     *
     * @param string $senha
     * @return void
     */
    public function setSenhaAttribute($senha)
    {
        if (!empty($senha)) {
            $this->attributes['senha'] = bcrypt($senha);
        }
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * Empresa em que o coladorador pertence
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

}
