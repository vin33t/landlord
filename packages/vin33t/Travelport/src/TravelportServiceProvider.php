<?php

namespace vin33t\Travelport;


use Illuminate\Support\ServiceProvider;

class TravelportServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $logs_path = __DIR__.'/../logs/travelport.log';
        $this->publishes([
            $logs_path => storage_path('logs/Travelport.log'),
        ], 'Travelport-logs');

        $dist = __DIR__.'/../config/travelport.php';
        $this->publishes([
            $dist => config_path('Travelport.php'),
        ],'Travelport-config');

        $this->mergeConfigFrom($dist, 'Travelport');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Travelport::class, function($app){

            $config = $app['config']->get('Travelport');

            if(!$config){
                throw new \RuntimeException('missing Travelport configuration section');
            }

            if(!isset($config['TARGETBRANCH'])){
                throw new \RuntimeException('missing Travelport configuration: `TARGETBRANCH`');
            }

            if(!isset($config['CREDENTIALS'])){
                throw new \RuntimeException('missing Travelport configuration: `CREDENTIALS`');
            }

            if(!isset($config['PROVIDER'])){
                throw new \RuntimeException('missing Travelport configuration: `PROVIDER`');
            }

            return new Travelport($config);
        });


        $this->app->alias(Travelport::class, 'Travelport-api');
    }

}
