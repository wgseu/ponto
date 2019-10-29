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

use App\Models\Bairro;
use App\Models\Cliente;
use Tests\TestCase;
use App\Models\Localizacao;
use App\Models\Zona;
use Illuminate\Validation\ValidationException;

class LocalizacaoTest extends TestCase
{
    public function testCreateLocalizacao()
    {
        $headers = PrestadorTest::auth();
        $seed_localizacao =  factory(Localizacao::class)->create();
        $response = $this->graphfl('create_localizacao', [
            'input' => [
                'cliente_id' => $seed_localizacao->cliente_id,
                'bairro_id' => $seed_localizacao->bairro_id,
                'logradouro' => 'Teste',
                'numero' => 'Teste',
            ]
        ], $headers);

        $found_localizacao = Localizacao::findOrFail($response->json('data.CreateLocalizacao.id'));
        $this->assertEquals($seed_localizacao->cliente_id, $found_localizacao->cliente_id);
        $this->assertEquals($seed_localizacao->bairro_id, $found_localizacao->bairro_id);
        $this->assertEquals('Teste', $found_localizacao->logradouro);
        $this->assertEquals('Teste', $found_localizacao->numero);
    }

    public function testUpdateLocalizacao()
    {
        $headers = PrestadorTest::auth();
        $localizacao = factory(Localizacao::class)->create();
        $this->graphfl('update_localizacao', [
            'id' => $localizacao->id,
            'input' => [
                'logradouro' => 'Atualizou',
                'numero' => 'Atualizou',
            ]
        ], $headers);
        $localizacao->refresh();
        $this->assertEquals('Atualizou', $localizacao->logradouro);
        $this->assertEquals('Atualizou', $localizacao->numero);
    }

    public function testDeleteLocalizacao()
    {
        $headers = PrestadorTest::auth();
        $localizacao_to_delete = factory(Localizacao::class)->create();
        $this->graphfl('delete_localizacao', ['id' => $localizacao_to_delete->id], $headers);
        $localizacao_to_delete->refresh();
        $this->assertTrue($localizacao_to_delete->trashed());
        $this->assertNotNull($localizacao_to_delete->data_arquivado);
    }

    public function testFindLocalizacao()
    {
        $headers = PrestadorTest::auth();
        $localizacao = factory(Localizacao::class)->create();
        $response = $this->graphfl('query_localizacao', [ 'id' => $localizacao->id ], $headers);
        $this->assertEquals($localizacao->id, $response->json('data.localizacoes.data.0.id'));
    }

    public function testValidateCondominioTipoObrigatorio()
    {
        $localizacao = factory(Localizacao::class)->create();
        $localizacao->tipo = Localizacao::TIPO_CONDOMINIO;
        $this->expectException(ValidationException::class);
        $localizacao->save();
    }

    public function testValidateApartamentoTipoObrigatorio()
    {
        $localizacao = factory(Localizacao::class)->create();
        $localizacao->tipo = Localizacao::TIPO_APARTAMENTO;
        $this->expectException(ValidationException::class);
        $localizacao->save();
    }

    public function testBelongToCliente()
    {
        $localizacao = factory(Localizacao::class)->create();
        $expected = Cliente::find($localizacao->cliente_id);
        $result = $localizacao->cliente;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToBairro()
    {
        $localizacao = factory(Localizacao::class)->create();
        $expected = Bairro::find($localizacao->bairro_id);
        $result = $localizacao->bairro;
        $this->assertEquals($expected, $result);
    }

    public function testBelongToZona()
    {
        $zona = factory(Zona::class)->create();
        $localizacao = factory(Localizacao::class)->create();
        $localizacao->zona_id = $zona->id;
        $localizacao->save();
        $expected = Zona::find($localizacao->zona_id);
        $result = $localizacao->zona;
        $this->assertEquals($expected, $result);
    }
}
