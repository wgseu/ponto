<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\Util;

use MZ\Account\Cliente;

/**
 * Filter values to secure save on database
 */
class Gender
{
    /**
     * Detect gender from name
     * @param  string $name nome of person
     * @return string gender identifier
     */
    public static function detect($name)
    {
        if (\preg_match('/(?:a|ne|mem|lem|de|te|ly|ny|lu|eth|en)$/i', $name)) {
            return Cliente::GENERO_FEMININO;
        }
        if (\preg_match('/(?:o|os|on|me|ur|el|us|x)$/i', $name)) {
            return Cliente::GENERO_MASCULINO;
        }
        # não detectado
        return Cliente::GENERO_MASCULINO;
    }
}
