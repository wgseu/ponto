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
use App\Models\Pais;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaisTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePais()
    {
        $headers = PrestadorTest::auth();
        $seed_pais =  factory(Pais::class)->create();
        $response = $this->graphfl('create_pais', [
            'input' => [
                'nome' => 'Korea',
                'sigla' => 'KOR',
                'codigo' => 'KO',
                'idioma' => 'Koreano',
                'moeda_id' => $seed_pais->moeda_id,
            ]
        ], $headers);

        $found_pais = Pais::findOrFail($response->json('data.CreatePais.id'));
        $this->assertEquals('Korea', $found_pais->nome);
        $this->assertEquals('KOR', $found_pais->sigla);
        $this->assertEquals('KO', $found_pais->codigo);
        $this->assertEquals($seed_pais->moeda_id, $found_pais->moeda_id);
        $this->assertEquals('Koreano', $found_pais->idioma);
    }

    public function testUpdatePais()
    {
        $headers = PrestadorTest::auth();
        $pais = factory(Pais::class)->create();
        $this->graphfl('update_pais', [
            'id' => $pais->id,
            'input' => [
                'nome' => 'Russia',
                'sigla' => 'RUS',
                'codigo' => 'RS',
                'idioma' => 'RU',
            ]
        ], $headers);
        $pais->refresh();
        $this->assertEquals('Russia', $pais->nome);
        $this->assertEquals('RUS', $pais->sigla);
        $this->assertEquals('RS', $pais->codigo);
        $this->assertEquals('RU', $pais->idioma);
    }

    public function testDeletePais()
    {
        $headers = PrestadorTest::auth();
        $pais_to_delete = factory(Pais::class)->create();
        $pais_to_delete = $this->graphfl('delete_pais', ['id' => $pais_to_delete->id], $headers);
        $pais = Pais::find($pais_to_delete->id);
        $this->assertNull($pais);
    }

    public function testFindPais()
    {
        $headers = PrestadorTest::auth();
        $pais = factory(Pais::class)->create();
        $response = $this->graphfl('query_pais', [ 'id' => $pais->id ], $headers);
        $this->assertEquals($pais->id, $response->json('data.paises.data.0.id'));
    }
}
