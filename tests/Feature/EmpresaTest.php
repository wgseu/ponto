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
use Tests\TestCase;
use App\Models\Empresa;

class EmpresaTest extends TestCase
{
    public function testUpdateEmpresa()
    {
        $headers = PrestadorTest::auth();
        $empresa = factory(Empresa::class)->create();
        $parceiro = factory(Cliente::class)->create();
        $this->graphfl('update_empresa', [
            'id' => $empresa->id,
            'input' => [
                'parceiro_id' => $parceiro->id,
            ]
        ], $headers);
        $empresa->refresh();
        $this->assertEquals($parceiro->id, $empresa->parceiro_id);
    }

    public function testFindEmpresa()
    {
        $headers = PrestadorTest::auth();
        $empresa = factory(Empresa::class)->create();
        $response = $this->graphfl('query_empresa', [ 'id' => $empresa->id ], $headers);
        $this->assertEquals($empresa->id, $response->json('data.empresas.data.0.id'));
    }
}
