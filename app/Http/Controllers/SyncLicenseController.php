<?php

namespace App\Http\Controllers;

use App\Exceptions\Exception;
use Illuminate\Support\Facades\Artisan;

class SyncLicenseController extends Controller
{
    public function callCommand($token)
    {
        if (getenv('TRIGGER_LICENSE_TOKEN') != $token) {
            throw new Exception('Erro ao sincronizar licença, token inválido', 401);
        }
        return Artisan::call('sync:license');
    }
}
