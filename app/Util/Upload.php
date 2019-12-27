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

namespace App\Util;

use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Storage;

/**
 * Filter values to secure save on database
 */
class Upload
{
    /**
     * Envia a imagem para o storage e retorna o path
     *
     * @param string $value
     * @param string $base_path
     * @param mixed $options
     * @return string
     */
    public static function send($value, $base_path, $options = [])
    {
        if (preg_match('/^data:\w+\/[\w-]+;base64,/', $value, $matches)) {
            $value = substr($value, strlen($matches[0]));
        }
        $content = base64_decode($value, true);
        $finfo = new \finfo(FILEINFO_EXTENSION);
        $mime = $finfo->buffer($content, FILEINFO_MIME_TYPE);
        if (
            !preg_match('/^image\/(jpeg|png)$/', $mime, $ext) &&
            !preg_match('/^text\/(xml)$/', $mime, $ext) &&
            !preg_match('/^application\/(pdf)$/', $mime, $ext)
        ) {
            throw new ValidationException(['imagem' => __('messages.invalid_picture')]);
        }
        $extension = $ext[1] == 'jpeg' ? 'jpg' : $ext[1];
        do {
            $name = uniqid() . '.' . $extension;
            $path = "$base_path/${name}";
        } while (Storage::exists($path));
        Storage::put($path, $content, $options);
        return $path;
    }

    /**
     * Obtém um recurso em base 64 da pasta resources
     *
     * @param string $path
     * @return string base64 do conteúdo do arquivo
     */
    public static function getResource($path)
    {
        return base64_encode(file_get_contents(resource_path($path)));
    }
}
