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
use App\Models\Fornecedor;

class FornecedorTest extends TestCase
{
    public function testCreateFornecedor()
    {
        $headers = PrestadorTest::auth();
        $seed_fornecedor =  factory(Cliente::class)->create();
        $response = $this->graphfl('create_fornecedor', [
            'input' => [
                'empresa_id' => $seed_fornecedor->id,
            ]
        ], $headers);

        $found_fornecedor = Fornecedor::findOrFail($response->json('data.CreateFornecedor.id'));
        $this->assertEquals($seed_fornecedor->id, $found_fornecedor->empresa_id);
    }

    public function testUpdateFornecedor()
    {
        $headers = PrestadorTest::auth();
        $fornecedor = factory(Fornecedor::class)->create();
        $empresa = factory(Cliente::class)->create();
        $this->graphfl('update_fornecedor', [
            'id' => $fornecedor->id,
            'input' => [
                'empresa_id' => $empresa->id
            ]
        ], $headers);
        $fornecedor->refresh();
        $this->assertEquals($empresa->id, $fornecedor->empresa_id);
    }

    public function testDeleteFornecedor()
    {
        $headers = PrestadorTest::auth();
        $fornecedor_to_delete = factory(Fornecedor::class)->create();
        $this->graphfl('delete_fornecedor', ['id' => $fornecedor_to_delete->id], $headers);
        $fornecedor = Fornecedor::find($fornecedor_to_delete->id);
        $this->assertNull($fornecedor);
    }

    public function testFindFornecedor()
    {
        $headers = PrestadorTest::auth();
        $fornecedor = factory(Fornecedor::class)->create();
        $response = $this->graphfl('query_fornecedor', [ 'id' => $fornecedor->id ], $headers);
        $this->assertEquals($fornecedor->id, $response->json('data.fornecedores.data.0.id'));
    }
}
