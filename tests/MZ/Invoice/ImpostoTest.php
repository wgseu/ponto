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
namespace MZ\Invoice;

use MZ\Exception\ValidationException;

class ImpostoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid imposto
     * @param string $descricao Imposto descrição
     * @return Imposto
     */
    public static function build($descricao = null)
    {
        $last = Imposto::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $imposto = new Imposto();
        $imposto->setGrupo(Imposto::GRUPO_ICMS);
        $imposto->setSimples('Y');
        $imposto->setSubstituicao('Y');
        $imposto->setCodigo(12+$id);
        $imposto->setDescricao('Descrição do imposto');
        return $imposto;
    }

    /**
     * Create a imposto on database
     * @param string $descricao Imposto descrição
     * @return Imposto
     */
    public static function create($descricao = null)
    {
        $imposto = self::build($descricao);
        $imposto->insert();
        return $imposto;
    }

    public function testFromArray()
    {
        $old_imposto = new Imposto([
            'id' => 123,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => 'Y',
            'substituicao' => 'Y',
            'codigo' => 123,
            'descricao' => 'Imposto',
        ]);
        $imposto = new Imposto();
        $imposto->fromArray($old_imposto);
        $this->assertEquals($imposto, $old_imposto);
        $imposto->fromArray(null);
        $this->assertEquals($imposto, new Imposto());
    }

    public function testFilter()
    {
        $old_imposto = new Imposto([
            'id' => 1234,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => 'Y',
            'substituicao' => 'Y',
            'codigo' => 1234,
            'descricao' => 'Imposto filter',
        ]);
        $imposto = new Imposto([
            'id' => 321,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => 'Y',
            'substituicao' => 'Y',
            'codigo' => '1.234',
            'descricao' => ' Imposto <script>filter</script> ',
        ]);
        $imposto->filter($old_imposto, app()->auth->provider, true);
        $this->assertEquals($old_imposto, $imposto);
    }

    public function testPublish()
    {
        $imposto = new Imposto();
        $values = $imposto->publish(app()->auth->provider);
        $allowed = [
            'id',
            'grupo',
            'simples',
            'substituicao',
            'codigo',
            'descricao',
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testInsert()
    {
        $imposto = self::build();
        $imposto->setGrupo('Invalido');
        $imposto->setSimples('T');
        $imposto->setSubstituicao('T');
        $imposto->setCodigo(null);
        $imposto->setDescricao(null);

        try {
            $imposto->insert();
            $this->fail('Não deveria ter cadastrado o imposto');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                [
                    'grupo',
                    'simples',
                    'substituicao',
                    'codigo',
                    'descricao',
                ],
                array_keys($e->getErrors())
            );
        }
        $imposto->setGrupo(Imposto::GRUPO_ICMS);
        $imposto->setSimples('Y');
        $imposto->setSubstituicao('Y');
        $imposto->setCodigo(123);
        $imposto->setDescricao('Imposto to insert');
        $imposto->insert();

        //isSImples
        $this->assertTrue($imposto->isSimples());
        //isSubstituicao
        $this->assertTrue($imposto->isSubstituicao());
    }

    public function testTranslate()
    {
        $imposto = self::create();
        try {
            $imposto->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['grupo', 'simples', 'substituicao', 'codigo'],
                array_keys($e->getErrors())
            );
        }
    }

    public function testGetGrupoOptions()
    {
        $imposto = new Imposto(['grupo' => Imposto::GRUPO_ICMS]);
        $options = Imposto::getGrupoOptions();
        $this->assertEquals(Imposto::getGrupoOptions($imposto->getGrupo()), $options[$imposto->getGrupo()]);
    }

    public function testUpdate()
    {
        $imposto = new Imposto();
        $imposto->setGrupo(Imposto::GRUPO_II);
        $imposto->setSimples('N');
        $imposto->setSubstituicao('N');
        $imposto->setCodigo(1234);
        $imposto->setDescricao('Imposto to update');
        $imposto->insert();
        $imposto->setGrupo(Imposto::GRUPO_ICMS);
        $imposto->setSimples('N');
        $imposto->setSubstituicao('N');
        $imposto->setCodigo(456);
        $imposto->setDescricao('Imposto updated');
        $imposto->update();
        $found_imposto = Imposto::findByID($imposto->getID());
        $this->assertEquals($imposto, $found_imposto);
        $imposto->setID('');
        $this->expectException('\Exception');
        $imposto->update();
    }

    public function testDelete()
    {
        $imposto = new Imposto();
        $imposto->setGrupo(Imposto::GRUPO_IPI);
        $imposto->setSimples('Y');
        $imposto->setSubstituicao('Y');
        $imposto->setCodigo(12345);
        $imposto->setDescricao('Imposto to delete');
        $imposto->insert();
        $imposto->delete();
        $imposto->clean(new Imposto());
        $found_imposto = Imposto::findByID($imposto->getID());
        $this->assertEquals(new Imposto(), $found_imposto);
        $imposto->setID('');
        $this->expectException('\Exception');
        $imposto->delete();
    }

    public function testFind()
    {
        $imposto = new Imposto();
        $imposto->setGrupo(Imposto::GRUPO_COFINS);
        $imposto->setSimples('Y');
        $imposto->setSubstituicao('Y');
        $imposto->setCodigo(123456);
        $imposto->setDescricao('Imposto find');
        $imposto->insert();
        $found_imposto = Imposto::find(['id' => $imposto->getID()]);
        $this->assertEquals($imposto, $found_imposto);
        $found_imposto = Imposto::findByID($imposto->getID());
        $this->assertEquals($imposto, $found_imposto);
        $found_imposto = Imposto::findByGrupoSimplesSubstituicaoCodigo($imposto->getGrupo(), $imposto->getSimples(), $imposto->getSubstituicao(), $imposto->getCodigo());
        $this->assertEquals($imposto, $found_imposto);

        $imposto_sec = new Imposto();
        $imposto_sec->setGrupo(Imposto::GRUPO_PIS);
        $imposto_sec->setSimples('Y');
        $imposto_sec->setSubstituicao('Y');
        $imposto_sec->setCodigo(123654);
        $imposto_sec->setDescricao('Imposto find second');
        $imposto_sec->insert();

        $impostos = Imposto::findAll(['search' => 'Imposto find'], [], 2, 0);
        $this->assertEquals([$imposto, $imposto_sec], $impostos);

        $count = Imposto::count(['search' => 'Imposto find']);
        $this->assertEquals(2, $count);
    }
}
