<?php
namespace CeddyG\ClaraEntityGenerator;

use Illuminate\Support\ServiceProvider;

use CeddyG\ClaraEntityGenerator\Generator\EntityGenerator;
use CeddyG\ClaraEntityGenerator\app\Console\Commands\GenerateEntityCommand;

/**
 * Description of EntityServiceProvider
 *
 * @author CeddyG
 */
class EntityGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishesConfig();
		$this->publishesBlueprints();
		$this->publishesTranslations();
        $this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->publishesView();
    }
    
    /**
	 * Publish config file.
	 * 
	 * @return void
	 */
	private function publishesConfig()
	{
		$sConfigPath = __DIR__ . '/../config';
        if (function_exists('config_path')) 
		{
            $sPublishPath = config_path();
        } 
		else 
		{
            $sPublishPath = base_path();
        }
		
        $this->publishes([$sConfigPath => $sPublishPath], 'clara.entity.config');  
	}
	
	private function publishesBlueprints()
	{
        $sResources = __DIR__.'/../resources/blueprints';

        $this->publishes([
            $sResources => resource_path('blueprints'),
        ], 'clara.entity.blueprints');
	}

	private function publishesTranslations()
	{
		$sTransPath = __DIR__.'/../resources/lang';

        $this->publishes([
			$sTransPath => resource_path('lang/vendor/clara-entity'),
			'clara.entity.trans'
		]);
        
		$this->loadTranslationsFrom($sTransPath, 'clara-entity');
    }

	private function publishesView()
	{
        $sResources = __DIR__.'/../resources/views';

        $this->publishes([
            $sResources => resource_path('views/vendor/clara-entity'),
        ], 'clara.entity.views');
        
        $this->loadViewsFrom($sResources, 'clara-entity');
	}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'command.entity.generate',
            function ($app) {
                return new GenerateEntityCommand();
            }
        );

        $this->commands([
            'command.entity.generate'
        ]);
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/clara.entity.php', 'clara.entity'
        );
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/clara.entity.generators.php', 'clara.entity.generators'
        );
        
        $this->app->singleton('clara.entity.generator', function ($app) 
		{
            $aConfig = config('clara.entity.generators');
            
            $aGenerators = [];            
            foreach ($aConfig as $aGenerator)
            {
                $aGenerators[$aGenerator['name']] = new $aGenerator['class']();
            }
            
            return new EntityGenerator($aGenerators);
        });
    }
}
