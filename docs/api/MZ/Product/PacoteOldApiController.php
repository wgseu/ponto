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
namespace MZ\Product;

/**
 * Allow application to serve system resources
 */
class PacoteOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        $grupo_id = $this->getRequest()->query->getInt('grupo', null);
        if (is_null($grupo_id)) {
            return $this->json()->error('Grupo não informado');
        }
        $limite = $this->getRequest()->query->getInt('limite', null);
        if (!is_null($limite) && $limite < 1) {
            $limite = null;
        }
        $busca = $this->getRequest()->query->get('busca');
        $associacoes = $this->getRequest()->request->get('pacote');
        $condition = [
            'grupoid' => $grupo_id,
            'visivel' => 'Y',
            'search' => $busca
        ];
        if (is_array($associacoes) && count($associacoes) > 0) {
            $condition['associacaoid'] = $associacoes;
        }
        $pacotes = Pacote::rawFindAllEx($condition, [], $limite);
        $items = [];
        foreach ($pacotes as $item) {
            $folder = is_null($item['produtoid']) ? 'propriedade': 'produto';
            $item['quantidade'] = 1;
            $item['imagemurl'] = get_image_url($item['imagemurl'], $folder, null);
            $items[] = $item;
        }
        return $this->json()->success(['pacotes' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_pacote_find',
                'path' => '/app/pacote/listar',
                'method' => 'POST',
                'controller' => 'find',
            ]
        ];
    }
}