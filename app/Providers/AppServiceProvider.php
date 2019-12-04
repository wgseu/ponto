<?php

namespace App\Providers;

use App\Models\Empresa;
use App\Models\Sistema;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('system', function () {
            $sistema = Sistema::find('1');
            $sistema->loadOptions();
            return $sistema;
        });
        $this->app->singleton('settings', function () {
            return app('system')->options;
        });
        $this->app->singleton('business', function () {
            $empresa = Empresa::find('1');
            $empresa->loadOptions();
            return $empresa;
        });
        $this->app->singleton('company', function () {
            return app('business')->empresa;
        });
        $this->app->singleton('country', function () {
            $pais = app('business')->pais;
            $pais->loadEntries();
            return $pais;
        });
        $this->app->singleton('currency', function () {
            return app('country')->moeda;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
