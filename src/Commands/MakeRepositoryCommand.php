<?php

namespace KY\AdminPanel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'adminpanel:make:repository')]
class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'adminpanel:make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    protected $signature = 'adminpanel:make:repository {name} {model : app model name}';

    protected $type = 'Repository';
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/Repository.stub';
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

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'Repository.php';
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


        return $this->replaceModel($stub)
            ->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name.'Repository');
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\AdminPanel\Repositories';
    }

    /**
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function replaceModel(&$stub)
    {

        $stub = str_replace('DummyModelNamespace', 'App\Models\\'.($this->argument('model')??$this->argument('name')), $stub);
        $stub = str_replace('DummyModel',($this->argument('model')??$this->argument('name')), $stub);

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
