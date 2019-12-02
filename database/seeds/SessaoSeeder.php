<?php

use App\Models\Sessao;
use App\Models\Horario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SessaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $horario = Horario::loadByAvailable();
        (new Sessao([
            'cozinha_id' => $horario->cozinha_id,
            'data_inicio' => Carbon::now(),
        ]))->save();
    }
}
