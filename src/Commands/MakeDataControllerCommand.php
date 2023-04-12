<?php

namespace KY\AdminPanel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'adminpanel:make:datacontroller')]
class MakeDataControllerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'adminpanel:make:datacontroller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    protected $signature = 'adminpanel:make:datacontroller {name} {datatype : registered slug of DataType }';

    protected $type = 'DataController';
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/DataController.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'DataController.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceDataTypeSlug($stub)
            ->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name.'DataController');
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\AdminPanel\Controllers';
    }

    protected function replaceDataTypeSlug(&$stub)
    {
        $stub = str_replace('DummyDataTypeSlug',  Str::slug(Str::plural($this->argument('datatype') ?? $this->argument('name'))), $stub);

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        return array_merge($options, parent::getOptions());
    }
}
