<?php

namespace App\Http\Controllers;

class SyncLicenseController extends Controller
{
    public function callCommand($token)
    {
        if (getenv('TRIGGER_LICENSE_TOKEN') == $token) {
            return \Artisan::call('sync:license');
        }
        \Exception('Erro ao sincronizar licença, token inválido', 1);
    }
}
