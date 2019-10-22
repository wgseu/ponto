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
use App\Models\Modulo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuloTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateModulo()
    {
        $headers = PrestadorTest::auth();
        $seed_modulo =  factory(Modulo::class)->create();
        $response = $this->graphfl('create_modulo', [
            'input' => [
                'nome' => 'Teste',
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_modulo = Modulo::findOrFail($response->json('data.CreateModulo.id'));
        $this->assertEquals('Teste', $found_modulo->nome);
        $this->assertEquals('Teste', $found_modulo->descricao);
    }

    public function testUpdateModulo()
    {
        $headers = PrestadorTest::auth();
        $modulo = factory(Modulo::class)->create();
        $this->graphfl('update_modulo', [
            'id' => $modulo->id,
            'input' => [
                'nome' => 'Atualizou',
                'descricao' => 'Atualizou',
            ]
        ], $headers);
        $modulo->refresh();
        $this->assertEquals('Atualizou', $modulo->nome);
        $this->assertEquals('Atualizou', $modulo->descricao);
    }

    public function testDeleteModulo()
    {
        $headers = PrestadorTest::auth();
        $modulo_to_delete = factory(Modulo::class)->create();
        $modulo_to_delete = $this->graphfl('delete_modulo', ['id' => $modulo_to_delete->id], $headers);
        $modulo = Modulo::find($modulo_to_delete->id);
        $this->assertNull($modulo);
    }

    public function testFindModulo()
    {
        $headers = PrestadorTest::auth();
        $modulo = factory(Modulo::class)->create();
        $response = $this->graphfl('query_modulo', [ 'id' => $modulo->id ], $headers);
        $this->assertEquals($modulo->id, $response->json('data.modulos.data.0.id'));
    }
}
