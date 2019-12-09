<?php

use App\Models\Classificacao;
use Illuminate\Database\Seeder;

class ClassificacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Classificacao([
            'descricao' => __('messages.salary'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.provider'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.tax'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.lease'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.water'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.light'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.telephone'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.gas'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.internet'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.system'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.cleaning_materials'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.office_supplies'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.insurance'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.publicity'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.maintenance_renovation_repairs'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.services'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.fuels'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.travels'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.advances'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.vehicles'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.machines'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.furniture_utensils'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.uniform_equipments'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.accounting'),
        ]))->save();

        (new Classificacao([
            'descricao' => __('messages.commission'),
        ]))->save();

        (new Classificacao([
            'classificacao_id' =>  Classificacao::where('descricao', __('messages.system'))->first()->id,
            'descricao' => 'GrandChef',
        ]))->save();
    }
}
