<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeFilter extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter
                            {name : The class name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new filter file';

    /**
     * Class type that is being created.
     */
    protected $type = 'Filter';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('{{ class }', $this->argument('name'), $stub);
    }

    /**
     * Specify your Stub's location.
     */
    protected function getStub()
    {
        $basepath = app_path() . '/Console/Commands/stubs';
        return  $basepath . '/make-filter.stub';
    }


    /**
     * The root location where your new file should
     * be written to.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Filters';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the filter.'],
        ];
    }
}