<?php

use App\Models\Origem;
use Illuminate\Database\Seeder;

class OrigemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Origem([
            'codigo' => 0,
            'descricao' => 'Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8',
        ]))->save();

        (new Origem([
            'codigo' => 1,
            'descricao' => 'Estrangeira - Importação direta, exceto a indicada no código 6',
        ]))->save();

        (new Origem([
            'codigo' => 2,
            'descricao' => 'Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7',
        ]))->save();

        (new Origem([
            'codigo' => 3,
            'descricao' => 'Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%',
        ]))->save();

        (new Origem([
            'codigo' => 4,
            'descricao' => 'Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei nº 288/67, e as Leis nºs 8.248/91, 8.387/91, 10.176/01 e 11.484/07',
        ]))->save();

        (new Origem([
            'codigo' => 5,
            'descricao' => 'Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%',
        ]))->save();

        (new Origem([
            'codigo' => 6,
            'descricao' => 'Estrangeira - Importação direta, sem similar nacional, constante em lista de Resolução CAMEX e gás natural',
        ]))->save();

        (new Origem([
            'codigo' => 7,
            'descricao' => 'Estrangeira - Adquirida no mercado interno, sem similar nacional, constante em lista de Resolução CAMEX e gás natural',
        ]))->save();

        (new Origem([
            'codigo' => 8,
            'descricao' => 'Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%',
        ]))->save();
    }
}
