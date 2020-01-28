<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SyncLicenseController extends Controller
{
    public function callCommand($token)
    {
        if (getenv('TRIGGER_LICENSE_TOKEN') == $token) {
            return \Artisan::call('sync:license');
        }
        throw new Exception('Erro ao sincronizar licença, token inválido', 1);
    }
}
