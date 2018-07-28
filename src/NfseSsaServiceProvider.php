<?php

namespace Potelo\NfseSsa;

use Illuminate\Support\ServiceProvider;

class NfseSsaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([ __DIR__ . '/config/nfse-ssa.php' => config_path('nfse-ssa.php')]);

        $this->loadViewsFrom(__DIR__.'/templates', 'nfse-ssa');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/nfse-ssa.php', 'nfse-ssa'
        );
    }
}
