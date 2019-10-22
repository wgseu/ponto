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
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEmpresa()
    {
        $headers = PrestadorTest::auth();
        $seed_empresa =  factory(Empresa::class)->create();
        $response = $this->graphfl('create_empresa', [
            'input' => [
            ]
        ], $headers);

        $found_empresa = Empresa::findOrFail($response->json('data.CreateEmpresa.id'));
    }

    public function testUpdateEmpresa()
    {
        $headers = PrestadorTest::auth();
        $empresa = factory(Empresa::class)->create();
        $this->graphfl('update_empresa', [
            'id' => $empresa->id,
            'input' => [
            ]
        ], $headers);
        $empresa->refresh();
    }

    public function testDeleteEmpresa()
    {
        $headers = PrestadorTest::auth();
        $empresa_to_delete = factory(Empresa::class)->create();
        $empresa_to_delete = $this->graphfl('delete_empresa', ['id' => $empresa_to_delete->id], $headers);
        $empresa = Empresa::find($empresa_to_delete->id);
        $this->assertNull($empresa);
    }

    public function testFindEmpresa()
    {
        $headers = PrestadorTest::auth();
        $empresa = factory(Empresa::class)->create();
        $response = $this->graphfl('query_empresa', [ 'id' => $empresa->id ], $headers);
        $this->assertEquals($empresa->id, $response->json('data.empresas.data.0.id'));
    }
}
