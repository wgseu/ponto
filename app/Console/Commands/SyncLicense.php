<?php

namespace App\Console\Commands;

use App\Util\Filter;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Telefone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        $options = app('settings');
        $install = is_null($options->getEntry('license', 'expires'));
        $request = $client->get("$uri/api/licenca?prefixo=$prefix&token=$token&install=$install");
        $response = json_decode($request->getBody()->getContents());
        try {
            $result = [
                'delivery' => $response->delivery,
                'fiscal' => $response->fiscal,
                'prefix' => $prefix,
                'expires' => $response->validade,
                'blocked' => $response->blocked,
                'totem' => $response->totem,
                'reservation' => $response->reservas,
            ];
            $license = [
                'delivery' => $options->getEntry('license', 'delivery'),
                'fiscal' => $options->getEntry('license', 'fiscal'),
                'prefix' => $options->getEntry('license', 'prefix'),
                'expires' => $options->getEntry('license', 'expires'),
                'blocked' => $options->getEntry('license', 'blocked'),
                'totem' => $options->getEntry('license', 'totem'),
                'reservation' => $options->getEntry('license', 'reservation'),
            ];
            $diff = array_diff($result, $license);
            if (count($diff) == 0) {
                return;
            }
            if ($install) {
                $this->syncInfo($response);
            }
            $options->addEntry('license', 'prefix', $prefix);
            $options->addEntry('license', 'fiscal', $response->fiscal);
            $options->addEntry('license', 'expires', $response->validade);
            $options->addEntry('license', 'delivery', $response->delivery);
            $options->addEntry('license', 'blocked', $response->blocked);
            $options->addEntry('license', 'totem', $response->totem);
            $options->addEntry('license', 'reservation', $response->reservas);
            app('system')->opcoes = json_encode($options->getValues());
            app('system')->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function syncInfo($response)
    {
        DB::transaction(function () use ($response) {
            $user = Cliente::findOrFail('1');
            $user->update([
                'email' => $response->user->email,
                'nome' => $response->user->nome,
                'sobrenome' => $response->user->sobrenome,
                'genero' => $response->user->sexo == 'M' ? 'masculino' : 'feminino',
                'login' => $response->user->usuario,
                'tipo' => 'fisica',
                'cpf' => Filter::digits($response->user->cpf),
                'status' => $response->user->status,
                'senha' => 'Teste123'
            ]);
            $new_company = new Cliente();
            $new_company->fill([
                'nome' => $response->company->fantasia,
                'sobrenome' => $response->company->razao_social,
                'email' => $response->company->email,
                'cpf' => Filter::digits($response->company->cnpj),
                'tipo' => 'juridica',
            ]);
            $new_company->save();
            $user->update(['empresa_id' => $new_company->id]);
            $company = Empresa::findOrFail('1');
            $company->update([
                'empresa_id' => $new_company->id,
            ]);
            $country = app('country');
            $telephone = new Telefone();
            if (!is_null($response->company->fone1)) {
                $telephone->fill([
                    'cliente_id' => $new_company->id,
                    'pais_id' => $country->id,
                    'numero' => Filter::digits($response->company->fone1),
                    'principal' => 1,
                ]);
                $telephone->save();
            }
            if (!is_null($response->company->fone2)) {
                $phone2 = $telephone->replicate();
                $phone2->fill([
                    'cliente_id' => $new_company->id,
                    'pais_id' => $country->id,
                    'numero' => Filter::digits($response->company->fone2),
                    'principal' => 0,
                ]);
                $phone2->save();
            }
        });
        return true;
    }
}
