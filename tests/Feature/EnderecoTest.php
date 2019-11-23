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

use App\Exceptions\ValidationException;
use App\Models\Bairro;
use App\Models\Cidade;
use Tests\TestCase;
use App\Models\Endereco;

class EnderecoTest extends TestCase
{
    public function testCreateEndereco()
    {
        $headers = PrestadorTest::authOwner();
        $seed_endereco =  factory(Endereco::class)->create();
        $response = $this->graphfl('create_endereco', [
            'input' => [
                'cidade_id' => $seed_endereco->cidade_id,
                'bairro_id' => $seed_endereco->bairro_id,
                'logradouro' => 'Teste',
                'cep' => '87878000',
            ]
        ], $headers);

        $found_endereco = Endereco::findOrFail($response->json('data.CreateEndereco.id'));
        $this->assertEquals($seed_endereco->cidade_id, $found_endereco->cidade_id);
        $this->assertEquals($seed_endereco->bairro_id, $found_endereco->bairro_id);
        $this->assertEquals('Teste', $found_endereco->logradouro);
        $this->assertEquals('87878000', $found_endereco->cep);
    }

    public function testUpdateEndereco()
    {
        $headers = PrestadorTest::authOwner();
        $endereco = factory(Endereco::class)->create();
        $this->graphfl('update_endereco', [
            'id' => $endereco->id,
            'input' => [
                'logradouro' => 'Atualizou',
                'cep' => '85440000',

            ]
        ], $headers);
        $endereco->refresh();
        $this->assertEquals('Atualizou', $endereco->logradouro);
        $this->assertEquals('85440000', $endereco->cep);
    }

    public function testDeleteEndereco()
    {
        $headers = PrestadorTest::authOwner();
        $endereco_to_delete = factory(Endereco::class)->create();
        $this->graphfl('delete_endereco', ['id' => $endereco_to_delete->id], $headers);
        $endereco = Endereco::find($endereco_to_delete->id);
        $this->assertNull($endereco);
    }

    public function testFindEndereco()
    {
        $headers = PrestadorTest::authOwner();
        $endereco = factory(Endereco::class)->create();
        $response = $this->graphfl('query_endereco', [ 'id' => $endereco->id ], $headers);
        $this->assertEquals($endereco->id, $response->json('data.enderecos.data.0.id'));

        $found_endereco = Endereco::findOrFail($response->json('data.enderecos.data.0.id'));
        $expectedBairro = Bairro::find($response->json('data.enderecos.data.0.bairro_id'));
        $resultBairro = $found_endereco->bairro;
        $this->assertEquals($expectedBairro, $resultBairro);
    
        $expectedCidade = Cidade::find($response->json('data.enderecos.data.0.cidade_id'));
        $resultCidade = $found_endereco->cidade;
        $this->assertEquals($expectedCidade, $resultCidade);
    }

    public function testValidCepEndereco()
    {
        $this->expectException(ValidationException::class);
        factory(Endereco::class)->create(['cep' => '8875 0a0']);
    }

    public function testValidCepEnderecoNulo()
    {
        $this->expectException(ValidationException::class);
        factory(Endereco::class)->create(['cep' => '']);
    }
}
