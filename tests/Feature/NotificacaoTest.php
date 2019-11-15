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
use App\Models\Notificacao;

class NotificacaoTest extends TestCase
{
    public function testMarkRead()
    {
        $notificacao = factory(Notificacao::class)->create();
        $headers = ClienteTest::auth($notificacao->destinatario);
        $this->assertNull($notificacao->data_visualizacao);
        $this->graphfl('update_notificacao', [
            'id' => $notificacao->id,
            'visualizado' => true,
        ], $headers);
        $notificacao->refresh();
        $this->assertNotNull($notificacao->data_visualizacao);
    }

    public function testMarkReadByOther()
    {
        $headers = PrestadorTest::auth();
        $notificacao = factory(Notificacao::class)->create();
        $this->assertNull($notificacao->data_visualizacao);
        $this->expectException('\Exception');
        $this->graphfl('update_notificacao', [
            'id' => $notificacao->id,
            'visualizado' => true,
        ], $headers);
    }

    public function testFindAll()
    {
        $notificacao = factory(Notificacao::class)->create();
        factory(Notificacao::class)->create();
        $user = $notificacao->destinatario;
        $headers = ClienteTest::auth($user);
        $response = $this->graphfl('query_notificacao', [ 'destinatario_id' => $user->id ], $headers);
        $this->assertEquals($notificacao->mensagem, $response->json('data.notificacoes.data.0.mensagem'));
    }
}
