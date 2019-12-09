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

use Tests\TestCase;
use App\Models\Sessao;
use App\Models\Movimentacao;
use Illuminate\Support\Carbon;
use App\Exceptions\ValidationException;

class SessaoTest extends TestCase
{
    public function testFindSessao()
    {
        $headers = PrestadorTest::authOwner();
        $sessao = factory(Sessao::class)->create();
        $response = $this->graphfl('query_sessao', [ 'id' => $sessao->id ], $headers);
        $this->assertEquals($sessao->id, $response->json('data.sessoes.data.0.id'));
    }

    public function testMovimentacaoOpen()
    {
        $movimentacao = factory(Movimentacao::class)->create();
        $sessao = $movimentacao->sessao;
        $sessao->data_termino = Carbon::now();
        $sessao->aberta = false;
        $this->expectException(ValidationException::class);
        $sessao->save();
    }
}
