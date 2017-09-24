<?php

namespace Yourify\Providers;

use Yourify\Console\Commands\ModuleMakeCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class ModuleServiceProvider extends ServiceProvider
{
    protected $files;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if(is_dir(app_path().'/Modules/')){
            $modules = config("modules.enable") ?: array_map('class_basename', $this->files->directories(app_path().'/Modules/'));
            foreach ($modules as $module){
                if(!$this->app->routesAreCched()){
                    $route_files = [
                        app_path().'/Modules/'.$module.'/routes.php',
                        app_path().'/Modules/'.$module.'/routes/web.php',
                        app_path().'/Modules/'.$module.'/routes/api.php',
                    ];
                    foreach ($route_files as $route_file){
                        if($this->files->exists($route_file)){
                            include  $route_file;
                        }
                    }
                }
                $helper = app_path().'/Modules/'.$module.'/helper.php';
                $views  = app_path().'/Modules/'.$module.'/Views';
                $trans  = app_path().'/Modules/'.$module.'/Translations';

                if($this->files->exists($helper)) {
                    include_once $helper;
                }
                if($this->files->isDirectory($views)){
                    $this->loadViewsFrom($views, $module);
                }
                if($this->files->isDirectory($trans)){
                    $this->loadTranslationsFrom($trans, $module);
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->files = new Filesystem();
        $this->registerMakeCommand();
    }

    protected function registerMakeCommand()
    {
        $this->commands('modules.make');
        $bind_method = method_exists($this->app, 'bindShared') ? 'bindShared' : 'singleton';
        $this->app->{$bind_method}('modules.make', function ($app) {
            return new ModuleMakeCommand($this->files);
        });
    }
}
