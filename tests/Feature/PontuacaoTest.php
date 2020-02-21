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

namespace Tests\Feature;

use App\Models\Item;
use Tests\TestCase;
use App\Models\Pontuacao;
use App\Exceptions\ValidationException;
use App\Models\Pedido;

class PontuacaoTest extends TestCase
{
    public function testCreatePontuacao()
    {
        $pontuacao = factory(Pontuacao::class)->create();
        $found_pontuacao = Pontuacao::findOrFail($pontuacao->id);
        $this->assertEquals($pontuacao->id, $found_pontuacao->promocao_id);
        $this->assertEquals($pontuacao->quantidade, $found_pontuacao->quantidade);
    }

    public function testUpdatePontuacao()
    {
        $pontuacao = factory(Pontuacao::class)->create();
        $pontuacao->update([
            'quantidade' => 99,
        ]);
        $pontuacao->refresh();
        $this->assertEquals(99, $pontuacao->quantidade);
    }

    public function testDeletePontuacao()
    {
        $pontuacao = factory(Pontuacao::class)->create();
        $pontuacao->delete();
        $found_pontuacao = Pontuacao::find($pontuacao->id);
        $this->assertNull($found_pontuacao);
    }

    public function testPontuacaoItemPedidoNull()
    {
        $item = factory(Item::class)->make([
            'pedido_id' => factory(Pedido::class)->create()->id
        ]);
        $item->save();
        $this->expectException(ValidationException::class);
        factory(Pontuacao::class)->create([
            'item_id' => $item->id,
        ]);
    }
}
