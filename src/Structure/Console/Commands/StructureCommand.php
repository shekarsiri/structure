<?php namespace ShekarSiri\Structure\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class StructureCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "structure:make";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Makes the application structure";

    /**
     * @var Filesystem
     */
    private $files;


    /**
     * Create a new command instance.
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
        $repo = $this->option('namespace');
        $path = $this->laravel->basePath() . '/';

        if (!$this->files->isDirectory($path . $repo)) {

            $this->proceed($repo, $path);
            $this->info('Success!');
        } else {
            $this->info('Directory already exists, terminating the command!');
        }

    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['namespace', null, InputOption::VALUE_REQUIRED, 'The queue to listen on'],
        ];
    }


    private function proceed($repo, $path)
    {

        $this->createDirectories($repo, $path);


        $a = explode('/', $repo);
        $a = array_pop($a);

        //ServiceProvider
        $this->files->put($path . $repo . '/' . $a . 'ServiceProvider.php', $this->buildClass($repo . '/' . $a . 'ServiceProvider', 'provider'));

        //Model
        $this->files->put($path . $repo . '/' . $a . '.php', $this->buildClass($repo . '/' . $a, 'model'));

        //Service
        $this->files->put($path . $repo . '/' . $a . 'Service.php', $this->buildClass($repo . '/' . $a . 'Service', 'service'));

        //Facade
        $this->files->put($path . $repo . '/Facades/' . $a . '.php', $this->buildClass($repo . '/Facades/' . $a, 'facade'));

        //Repositories
        $this->files->put($path . $repo . '/Repositories/' . $a . 'Repository.php', $this->buildClass($repo . '/Repositories/' . $a . 'Repository', 'repository'));
        $this->files->put($path . $repo . '/Repositories/' . $a . 'RepositoryEloquent.php', $this->buildClass($repo . '/Repositories/' . $a . 'RepositoryEloquent', 'repository_eloquent'));


        //Jobs
        $this->files->put($path . $repo . '/Jobs/' . $a . 'Create.php', $this->buildClass($repo . '/Jobs/' . $a . 'Create', 'job'));
        $this->files->put($path . $repo . '/Jobs/' . $a . 'Update.php', $this->buildClass($repo . '/Jobs/' . $a . 'Update', 'job'));
        $this->files->put($path . $repo . '/Jobs/' . $a . 'Delete.php', $this->buildClass($repo . '/Jobs/' . $a . 'Delete', 'job'));

        //Events
        $this->files->put($path . $repo . '/Events/' . $a . 'CreatedEvent.php', $this->buildClass($repo . '/Events/' . $a . 'CreatedEvent', 'event'));
        $this->files->put($path . $repo . '/Events/' . $a . 'UpdatedEvent.php', $this->buildClass($repo . '/Events/' . $a . 'UpdatedEvent', 'event'));
        $this->files->put($path . $repo . '/Events/' . $a . 'DeletedEvent.php', $this->buildClass($repo . '/Events/' . $a . 'DeletedEvent', 'event'));

        $this->files->put($path . $repo . '/Providers/' . $a . 'EventServiceProvider.php', $this->buildClass($repo . '/Providers/' . $a . 'EventServiceProvider', 'event_service_provider'));

        //Requests
        $this->files->put($path . $repo . '/Requests/' . $a . 'CreateRequest.php', $this->buildClass($repo . '/Requests/' . $a . 'CreateRequest', 'request'));
        $this->files->put($path . $repo . '/Requests/' . $a . 'UpdateRequest.php', $this->buildClass($repo . '/Requests/' . $a . 'UpdateRequest', 'request'));


    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $d = $this->files->makeDirectory($path, 0777, true, true);
            if ($d) {
                $this->info('Directory created successfully.');
            }
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name, $stubName)
    {

        $stub = $this->files->get($this->getStub($stubName));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub($stub)
    {
        return $this->laravel->basePath() . '/vendor/shekarsiri/structure/src/Structure/stubs/' . $stub . '.stub';

    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $a = explode('/', $name);
        $a = array_pop($a);

        return str_replace('DummyClass', $a, $stub);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {

        $stub = str_replace(
            'DummyNamespace', $this->getNamespace($name), $stub
        );

        return $this;
    }

    /**
     * Get the full namespace name for a given class.
     *
     * @param  string $name
     * @return string
     */
    protected function  getNamespace($name)
    {
        //var_dump($name);

        $namespace = trim(implode('\\', array_slice(explode('/', $name), 0, -1)), '\\');
        //dd($namespace);
        return $namespace;
    }

    /**
     * @param $repo
     * @param $path
     */
    private function createDirectories($repo, $path)
    {
        $this->makeDirectory($path . $repo . '/Repositories');
        $this->makeDirectory($path . $repo . '/Events');
        $this->makeDirectory($path . $repo . '/Jobs');
        $this->makeDirectory($path . $repo . '/Listeners');
        $this->makeDirectory($path . $repo . '/Providers');
        $this->makeDirectory($path . $repo . '/Facades');
        $this->makeDirectory($path . $repo . '/Requests');
        $this->makeDirectory($path . $repo . '/Exceptions');
    }


}