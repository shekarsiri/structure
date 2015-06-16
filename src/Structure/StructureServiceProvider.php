<?php namespace ShekarSiri\Structure;


use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use ShekarSiri\Structure\Console\Commands\StructureCommand;

class StructureServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['command.structure:make'] = $this->app->share(function ($app) {
            return new StructureCommand($app->make(Filesystem::class));
        });

        $this->commands('command.structure:make');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.structure:make'
        ];
    }


}