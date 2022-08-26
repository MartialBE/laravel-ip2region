<?php

namespace Martialbe\LaravelIp2region;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Martialbe\LaravelIp2region\Kernel\Ip2Region;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->setupConfig();
        if ($this->app->runningInConsole()) {
            $this->commands([Console\Update::class]);
        }
        $this->app->singleton('ip2region', function ($app) {
            return new Ip2Region(config('ip2region.path'));
        });
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config/config.php');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__.'/config/config.php') => config_path('ip2region.php')
            ]);
        }
        $this->mergeConfigFrom($source, 'ip2region');
    }

}
