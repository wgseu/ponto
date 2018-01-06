<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php'); // main app file

need_permission(PermissaoNome::ALTERARCONFIGURACOES, true);

try {
    $outputs = array();
    $products = array();
    $produtos = ZProduto::getTodos();
    foreach ($produtos as $produto) {
        if (is_null($produto->getImagem())) {
            continue;
        }
        $imagem = ZProduto::getImagemPeloID($produto->getID());
        $imagebytes = $imagem['imagem'];
        $name = $produto->getDescricao().'.png';
        $name = iconv("UTF-8//IGNORE", "WINDOWS-1252//IGNORE", $name);
        $type = 'produto';
        $dir = IMG_ROOT . '/' . $type . '/';
        $name = generate_file_name($dir, '.png', $name, true);
        $path = $dir . $name;
        file_put_contents($path, $imagebytes);
        $name = iconv("WINDOWS-1252//IGNORE", "UTF-8//IGNORE", $name);
        $imagemurl = get_image_url($name, $type, null);
        $products[] = array('id' => $produto->getID(), 'imagemurl' => $imagemurl);
    }
    $outputs['produtos'] = $products;
    json(null, $outputs);
} catch (Exception $e) {
    json($e->getMessage());
}
