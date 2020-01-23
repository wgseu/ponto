<?php

namespace App\Console\Commands;

use App\Models\Sistema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:license';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize the license between site and api';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $uri = getenv('MAIN_SITE_URI');
        $prefix = getenv('DOMAIN_PREFIX');
        $token = getenv('TRIGGER_LICENSE_TOKEN');
        $request = $client->get("$uri/api/licenca?prefixo=$prefix&token=$token");
        $response = json_decode($request->getBody()->getContents());
        try {
            $options = app('settings');
            if (!is_null($options->getEntry('license', 'expires'))) {
                return Log::error('A empresa ' . $response->fantasia . ' já possui licença instalada');
            }
            $end_date = date("c", strtotime($response->validade));
            $options->addEntry('license', 'expires', $end_date);
            $options->addEntry('license', 'delivery', true);
            app('system')->opcoes = json_encode($options->getValues());
            app('system')->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
