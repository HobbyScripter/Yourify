<?php

namespace Yourify\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMakeCommand extends GeneratorCommand
{
    /**
     *  Laravel Version
     *
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $signature = 'make:module';
    /**
     * @var string
     */
    protected $description = 'Create a new Module (Folder structure)';
    /**
     * @var string
     */
    protected $type = 'Module';
    /**
     * @var string
     */
    protected $currentStub;

    /**
     *
     * @return void
     */
    public function fire()
    {
        $app = app();
        $this->version = (int) str_replace('.','',$app->version());

        if($this->files->exists(app_path().'/Modules'.$this->getNameInput())){
             $this->error($this->type.' already exists');
        }

        $this->generate('controller');
        $this->generate('model');
        $this->generate('view');

        if(!$this->option('no-translation')){
            $this->generate('translation');
        }

        if ($this->version < 530){
            $this->generate('routes');
        }else{
            $this->generate('web');
            $this->generate('api');
        }

        $this->generate('helper');
        if(!$this->option('no-migration')){
            $table = str_plural(snake_case(studly_case($this->getNameInput())));
            $this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table]);
        }

        $this->info($this->type.' created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->currentStub;
    }

    protected function generate($type)
    {
        switch ($type){
            case 'controller':
                $filename = studly_case($this->getNameInput()).ucfirst($type);
                break;
            case 'model':
                $filename = studly_case($this->getNameInput());
                break;
            case 'view':
                $filename = 'index.blade';
                break;

            case 'translation':
                $filename = 'example';
                break;

            case 'routes':
                $filename = 'routes';
                break;
            case 'web':
                $filename = 'web';
                $folder = 'routes\\';
                break;
            case 'api':
                $filename = 'api';
                $folder = 'routes\\';
                break;

            case 'helper':
                $filename = 'helper';
                break;
        }
        if(!isset($folder)){
            $folder = ($type != 'routes' && $type != 'helper') ? ucfirst($type).'s\\'. ($type === 'translation' ? 'en\\':'') : '';
        }

        $qualtifyClass = method_exists($this, 'qualifyClass') ? 'qualifyClass' : 'parseName';
        $name = $this->$qualtifyClass('Modules\\'.studly_case(ucfirst($this->getNameInput()))).'\\'.$folder.$filename;
        if($this->files->exists($path = $this->getPath($name))){
            $this->error($this->type.' already exits!');
        }
        $this->currentStub = __DIR__.'/stubs/'.$type.'.stub';

        $this->makeDirectory($path);
        $this->files->put($path,$this->buildClass($name));
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceName($stub, $this->getNameInput())->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function replaceName(&$stub, $name)
    {
        $stub = str_replace('DummyTitle', $name, $stub);
        $stub = str_replace('DummyUCTitle', ucfirst(studly_case($name)), $stub);
        return $this;
    }

    protected function replaceClass($stub, $name)
    {
        $class = class_basename($name);
        return str_replace('DummyClass', $class, $stub);
    }

    protected function getArguments()
    {
        return array(
            ['name', InputArgument::REQUIRED,'Module name'],
        );
    }

    protected function getOptions()
    {
        return array(
            ['no-migration', null, InputOption::VALUE_NONE, 'Do not create new migration files.'],
            ['no-translation', null, InputOption::VALUE_NONE, 'Do not create module translation filesystem.'],
        );
    }
}
