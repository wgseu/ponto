<?php

namespace Tests\Feature;

use App\Exceptions\ValidationException;
use App\Models\Classificacao;
use Tests\TestCase;

class ClassificacaoTest extends TestCase
{
    public function testCreateClassificacao()
    {
        $headers = PrestadorTest::authOwner();
        $seed_classificacao =  factory(Classificacao::class)->create();
        $response = $this->graphfl('create_classificacao', [
            'input' => [
                'descricao' => 'Teste',
            ]
        ], $headers);

        $found_classificacao = Classificacao::findOrFail($response->json('data.CreateClassificacao.id'));
        $this->assertEquals('Teste', $found_classificacao->descricao);
    }


    public function testFindClassificacao()
    {
        $headers = PrestadorTest::authOwner();
        $classificacao = factory(Classificacao::class)->create();
        $response = $this->graphfl('query_classificacao', [
            'id' => $classificacao->id,
        ], $headers);

        $this->assertEquals(
            $classificacao->descricao,
            $response->json('data.classificacoes.data.0.descricao')
        );
    }

    public function testUpdateClassificacao()
    {
        $headers = PrestadorTest::authOwner();
        $classificacao = factory(Classificacao::class)->create();
        $this->graphfl('update_classificacao', [
            'id' => $classificacao->id,
            'input' => [
                'descricao' => 'Diárias',
            ]
        ], $headers);
        $classificacao->refresh();
        $this->assertEquals(
            'Diárias',
            $classificacao->descricao
        );
    }
    
    public function testDeleteClassificacao()
    {
        $headers = PrestadorTest::authOwner();
        $classificacao_to_delete = factory(Classificacao::class)->create();
        $this->graphfl('delete_classificacao', ['id' => $classificacao_to_delete->id], $headers);
        $classificacao = Classificacao::find($classificacao_to_delete->id);
        $this->assertNull($classificacao);
    }

    public function testValidateClassificacaoCreateSubclassificacaoDeSubclassificacao()
    {
        $classificacaoPai = factory(Classificacao::class)->create();
        $subclassificacao = factory(Classificacao::class)->create(['classificacao_id' => $classificacaoPai->id]);
        $this->expectException(ValidationException::class);
        factory(Classificacao::class)->create(['classificacao_id' => $subclassificacao->id]);
    }

    public function testValidateClassificacaoUpdateSubclassificacaoElaMesma()
    {
        $classificacao = factory(Classificacao::class)->create();
        $classificacao->classificacao_id = $classificacao->id;
        $this->expectException(ValidationException::class);
        $classificacao->save();
    }

    public function testValidateClassificacaoUpdateClassificacaoPai()
    {
        $classificacaoPai = factory(Classificacao::class)->create();
        $subclassificacao = factory(Classificacao::class)->create(['classificacao_id' => $classificacaoPai->id]);
        $classificacao = factory(Classificacao::class)->create();
        $classificacaoPai->classificacao_id = $classificacao->id;
        $this->expectException(ValidationException::class);
        $classificacaoPai->save();
    }
}
