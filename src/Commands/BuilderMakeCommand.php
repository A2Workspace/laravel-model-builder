<?php

namespace A2Workspace\ModelBuilder\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class BuilderMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:builder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '建立一個新的模型生成器 (Model Builder)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($this->option('subtask')) {
            $stub = '/stubs/builder.subtask.stub.php';
        }

        return __DIR__ . ($stub ?: '/stubs/builder.stub.php');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Builders';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $model = $this->parseModel($name);

        $replace = [];
        $replace['DummyModel'] = $model;
        $replace['$dummy'] = '$' . Str::camel($model);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * @param  string  $name
     * @return string
     */
    private function parseModel($name)
    {
        return str_replace('Builder', '', class_basename($name));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['subtask', 's', InputOption::VALUE_NONE, '建立子生成器'],
        ];
    }
}
