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
        for ($i = 1; $i < 8; $i++) {
            $inicio = Date::make($i, '10:30');
            $fim = Date::make($i, '13:00');
            (new Horario([
                'cozinha_id' => $cozinha->id,
                'inicio' => $inicio,
                'fim' => $fim,
            ]))->save();
        }

        for ($i = 1; $i < 8; $i++) {
            $inicio = Date::make($i, '18:00');
            $fim = Date::make($i, '23:00');
            (new Horario([
                'cozinha_id' => $cozinha->id,
                'inicio' => $inicio,
                'fim' => $fim,
            ]))->save();
        }
    }
}
