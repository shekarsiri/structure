<?php

namespace ShekarSiri\Structure\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class StructureCommand extends Command
{
    use Generator;

    private $repo;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'structure:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes the application structure';

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $repo = $this->argument('namespace');
        $path = $this->laravel->basePath().'/';

        if (!$this->files->isDirectory($path.$repo)) {
            $this->proceed($repo, $path);
            $this->info('Created the structure successfully under '.$path.$repo);
        } else {
            $this->info('Directory already exists, terminating the command!');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['namespace', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
