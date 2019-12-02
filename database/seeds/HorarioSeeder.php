<?php

use App\Util\Date;
use App\Models\Cozinha;
use App\Models\Horario;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cozinha = Cozinha::where('nome', __('messages.restaurant'))->first();
        $inicio = Date::make(Date::SUNDAY, '10:30');
        $fim = Date::make(Date::SUNDAY, '13:00');
        (new Horario([
            'cozinha_id' => $cozinha->id,
            'inicio' => $inicio,
            'fim' => $fim,
        ]))->save();

        $inicio = Date::make(Date::SATURDAY, '18:00');
        $fim = Date::make(Date::SATURDAY, '23:00');
        (new Horario([
            'cozinha_id' => $cozinha->id,
            'inicio' => $inicio,
            'fim' => $fim,
        ]))->save();
    }
}
