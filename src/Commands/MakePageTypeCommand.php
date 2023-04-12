<?php

namespace KY\AdminPanel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'adminpanel:make:pagetype')]
class MakePageTypeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'adminpanel:make:pagetype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    protected $signature = 'adminpanel:make:pagetype {name}';

    protected $type = 'PageType';
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/PageType.stub';
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

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'PageType.php';
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

        return $this->replaceAttributes($stub,$name)
            ->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name.'PageType');
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\AdminPanel\PageTypes';
    }

    protected function replaceAttributes(&$stub,$name)
    {
        $stub = str_replace('DummyTitle', Str::title($this->argument('name')), $stub);
        $stub = str_replace('DummySlug',Str::slug($this->argument('name')), $stub);

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = [
        ];

        return array_merge($options, parent::getOptions());
    }
}
