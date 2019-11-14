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

use App\Models\Cliente;
use App\Models\Empresa;
use Tests\TestCase;
use App\Models\Sistema;

class SistemaTest extends TestCase
{
    public function testUpdateSistema()
    {
        $headers = PrestadorTest::auth();
        $sistema = Sistema::find('1');
        $data = [
            'auto_sair' => true,
        ];
        $this->graphfl('update_sistema', [
            'input' => [
                'opcoes' => json_encode($data)
            ]
        ], $headers);
        $sistema->refresh();
        $sistema->loadOptions();
        $this->assertTrue($sistema->options->getValue('auto_sair'));
    }

    public function testFindSistema()
    {
        $headers = PrestadorTest::auth();
        $empresa = factory(Cliente::class)->create(['tipo' => Cliente::TIPO_JURIDICA]);
        Empresa::find('1')->update(['empresa_id' => $empresa->id]);
        $response = $this->graphfl('query_sistema', [], $headers);
        $this->assertArrayHasKey('empresa', $response->json('data.sistema'));
        $this->assertArrayHasKey('fuso_horario', $response->json('data.sistema'));
        $this->assertArrayHasKey('opcoes', $response->json('data.sistema'));
    }
}
