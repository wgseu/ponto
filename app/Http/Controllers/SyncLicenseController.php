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
        return Log::error('Erro ao sincronizar licença, token inválido');
    }
}
