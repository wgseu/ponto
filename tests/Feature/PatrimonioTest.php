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
use App\Models\Patrimonio;
use Illuminate\Validation\ValidationException;

class PatrimonioTest extends TestCase
{
    public function testCreatePatrimonio()
    {
        $headers = PrestadorTest::auth();
        $seed_patrimonio =  factory(Patrimonio::class)->create();
        $response = $this->graphfl('create_patrimonio', [
            'input' => [
                'empresa_id' => $seed_patrimonio->empresa_id,
                'numero' => 'Teste',
                'descricao' => 'Teste',
                'quantidade' => 1.0,
            ]
        ], $headers);

        $found_patrimonio = Patrimonio::findOrFail($response->json('data.CreatePatrimonio.id'));
        $this->assertEquals($seed_patrimonio->empresa_id, $found_patrimonio->empresa_id);
        $this->assertEquals('Teste', $found_patrimonio->numero);
        $this->assertEquals('Teste', $found_patrimonio->descricao);
        $this->assertEquals(1.0, $found_patrimonio->quantidade);
    }

    public function testUpdatePatrimonio()
    {
        $headers = PrestadorTest::auth();
        $patrimonio = factory(Patrimonio::class)->create();
        $this->graphfl('update_patrimonio', [
            'id' => $patrimonio->id,
            'input' => [
                'numero' => 'Atualizou',
                'descricao' => 'Atualizou',
                'quantidade' => 1.0,
            ]
        ], $headers);
        $patrimonio->refresh();
        $this->assertEquals('Atualizou', $patrimonio->numero);
        $this->assertEquals('Atualizou', $patrimonio->descricao);
        $this->assertEquals(1.0, $patrimonio->quantidade);
    }

    public function testDeletePatrimonio()
    {
        $headers = PrestadorTest::auth();
        $patrimonio_to_delete = factory(Patrimonio::class)->create();
        $this->graphfl('delete_patrimonio', ['id' => $patrimonio_to_delete->id], $headers);
        $patrimonio = Patrimonio::find($patrimonio_to_delete->id);
        $this->assertNull($patrimonio);
    }

    public function testFindPatrimonio()
    {
        $headers = PrestadorTest::auth();
        $patrimonio = factory(Patrimonio::class)->create();
        $response = $this->graphfl('query_patrimonio', [ 'id' => $patrimonio->id ], $headers);
        $this->assertEquals($patrimonio->id, $response->json('data.patrimonios.data.0.id'));
    }

    public function testValidadePatrimonioQuantidadeNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->quantidade = -50;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonioAlturaNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->altura = -100;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonioLarguraNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->largura = -150;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonioComprimentoNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->comprimento = -5;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonioCustoNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->custo = -10;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonioValorNegativa()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->valor = -50;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }

    public function testValidadePatrimonio()
    {
        $patrimonio = factory(Patrimonio::class)->create();
        $patrimonio->delete();
        $patrimonio->ativo = false;
        $this->expectException(ValidationException::class);
        $patrimonio->save();
    }
}
