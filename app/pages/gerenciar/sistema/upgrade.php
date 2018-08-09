<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

use MZ\System\Permissao;
use MZ\Product\Produto;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);

try {
    $outputs = [];
    $products = [];
    $produtos = Produto::findAll();
    foreach ($produtos as $produto) {
        if (is_null($produto->getImagem())) {
            continue;
        }
        $produto->loadImagem();
        $imagebytes = $produto->getImagem();
        $name = $produto->getDescricao().'.png';
        $name = iconv("UTF-8//IGNORE", "WINDOWS-1252//IGNORE", $name);
        $type = 'produto';
        $dir = $app->getPath('image') . '/' . $type . '/';
        $name = generate_file_name($dir, '.png', $name, true);
        $path = $dir . $name;
        file_put_contents($path, $imagebytes);
        $name = iconv("WINDOWS-1252//IGNORE", "UTF-8//IGNORE", $name);
        $imagemurl = get_image_url($name, $type, null);
        $products[] = ['id' => $produto->getID(), 'imagemurl' => $imagemurl];
    }
    $outputs['produtos'] = $products;
    json(null, $outputs);
} catch (\Exception $e) {
    json($e->getMessage());
}
