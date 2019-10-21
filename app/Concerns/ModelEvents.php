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

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\ValidateInterface;
use App\Interfaces\ValidateInsertInterface;
use App\Interfaces\ValidateUpdateInterface;

/**
 * Informações sobre o produto, composição ou pacote
 */
trait ModelEvents
{
    /**
     * @inheritDoc
     *
     * @param Builder  $query
     * @return bool
     */
    protected function performInsert(Builder $query)
    {
        if ($this instanceof ValidateInterface) {
            $this->validate();
        }
        if ($this instanceof ValidateInsertInterface) {
            $this->onInsert();
        }
        return parent::performInsert($query);
    }

    /**
     * @inheritDoc
     *
     * @param Builder  $query
     * @return bool
     */
    protected function performUpdate(Builder $query)
    {
        if ($this instanceof ValidateInterface) {
            $this->validate();
        }
        if ($this instanceof ValidateUpdateInterface) {
            $this->onUpdate();
        }
        return parent::performUpdate($query);
    }
}
