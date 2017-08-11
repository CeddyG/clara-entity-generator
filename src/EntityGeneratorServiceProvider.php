<?php
namespace CeddyG\ClaraEntityGenerator;

use Illuminate\Support\ServiceProvider;

/**
 * Description of EntityServiceProvider
 *
 * @author CeddyG
 */
class EntityGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish services
        $sApp = realpath(__DIR__.'/app');

        $this->publishes([
            $sApp => base_path().'/app',
        ], 'services');

        // Publish stubs
        $sRessources = realpath(__DIR__.'/ressources');

        $this->publishes([
            $sRessources => base_path().'/ressources',
        ], 'stubs');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
