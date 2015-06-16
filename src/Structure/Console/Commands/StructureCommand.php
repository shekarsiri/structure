<?php namespace Structure\Console\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class StructureCommand extends Command
{
    protected $name = "structure:make";
    protected $description = "Makes the application structure";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dd('TEST');
    }

    protected function getOptions()
    {
        return [
            ['namespace', null, InputOption::VALUE_REQUIRED, 'Namespace']
        ];
    }


}