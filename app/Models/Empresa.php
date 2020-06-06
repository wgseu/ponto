<?php

namespace App\Models;

use App\Concerns\ModelEvents;
use Illuminate\Database\Eloquent\Model;

/**
 * Informações da empresa
 */
class Empresa extends Model
{
    use ModelEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empresas';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fantasia',
        'razao_social',
        'email',
        'senha',
        'data_criacao',
        'cnpj',
        'fone1',
        'fone2',
        'imagem_url',
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
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getImagemUrlAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function setImagemUrlAttribute($value)
    {
        if (!is_null($value)) {
            $value = is_null($this->imagem_url) ? null : $this->attributes['imagem_url'];
        }
        $this->attributes['imagem_url'] = $value;
    }

    public function setImagemAttribute($value)
    {
        if (isset($value)) {
            $this->attributes['imagem_url'] = Image::upload($value, 'users');
        }
    }
}
