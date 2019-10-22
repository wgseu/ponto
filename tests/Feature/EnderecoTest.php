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
use App\Models\Endereco;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnderecoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEndereco()
    {
        $headers = PrestadorTest::auth();
        $seed_endereco =  factory(Endereco::class)->create();
        $response = $this->graphfl('create_endereco', [
            'input' => [
                'cidade_id' => $seed_endereco->cidade_id,
                'bairro_id' => $seed_endereco->bairro_id,
                'logradouro' => 'Teste',
                'cep' => 'Teste',
            ]
        ], $headers);

        $found_endereco = Endereco::findOrFail($response->json('data.CreateEndereco.id'));
        $this->assertEquals($seed_endereco->cidade_id, $found_endereco->cidade_id);
        $this->assertEquals($seed_endereco->bairro_id, $found_endereco->bairro_id);
        $this->assertEquals('Teste', $found_endereco->logradouro);
        $this->assertEquals('Teste', $found_endereco->cep);
    }

    public function testUpdateEndereco()
    {
        $headers = PrestadorTest::auth();
        $endereco = factory(Endereco::class)->create();
        $this->graphfl('update_endereco', [
            'id' => $endereco->id,
            'input' => [
                'logradouro' => 'Atualizou',
                'cep' => 'Atualizou',
            ]
        ], $headers);
        $endereco->refresh();
        $this->assertEquals('Atualizou', $endereco->logradouro);
        $this->assertEquals('Atualizou', $endereco->cep);
    }

    public function testDeleteEndereco()
    {
        $headers = PrestadorTest::auth();
        $endereco_to_delete = factory(Endereco::class)->create();
        $endereco_to_delete = $this->graphfl('delete_endereco', ['id' => $endereco_to_delete->id], $headers);
        $endereco = Endereco::find($endereco_to_delete->id);
        $this->assertNull($endereco);
    }

    public function testFindEndereco()
    {
        $headers = PrestadorTest::auth();
        $endereco = factory(Endereco::class)->create();
        $response = $this->graphfl('query_endereco', [ 'id' => $endereco->id ], $headers);
        $this->assertEquals($endereco->id, $response->json('data.enderecos.data.0.id'));
    }
}
