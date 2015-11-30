<?php

namespace ShekarSiri\Structure\Console\Commands;

trait Generator
{
    private function proceed($repo, $path)
    {
        $this->createDirectories($repo, $path);

        $a = explode('/', $repo);
        $a = array_pop($a);

        $this->repo = $repo;

        //ServiceProvider
        $this->files->put($path.$repo.'/'.$a.'ServiceProvider.php', $this->buildClassNew('service-provider'));

        //Model
        $this->files->put($path.$repo.'/'.$a.'.php', $this->buildClass($repo.'/'.$a, 'model'));

        //Service
        $this->files->put($path.$repo.'/'.$a.'Service.php', $this->buildClass($repo.'/'.$a.'Service', 'service'));

        //Facade
        $this->files->put($path.$repo.'/Facades/'.$a.'.php', $this->buildClass($repo.'/Facades/'.$a, 'facade'));

        //Repositories
        $this->files->put($path.$repo.'/Repositories/'.$a.'Repository.php', $this->buildClass($repo.'/Repositories/'.$a.'Repository', 'repository'));
        $this->files->put($path.$repo.'/Repositories/'.$a.'RepositoryEloquent.php', $this->buildClass($repo.'/Repositories/'.$a.'Repository', 'repository_eloquent'));

        //Jobs
        $this->files->put($path.$repo.'/Jobs/'.$a.'Create.php', $this->buildClass($repo.'/Jobs/'.$a.'Create', 'job'));
        $this->files->put($path.$repo.'/Jobs/'.$a.'Update.php', $this->buildClass($repo.'/Jobs/'.$a.'Update', 'job'));
        $this->files->put($path.$repo.'/Jobs/'.$a.'Delete.php', $this->buildClass($repo.'/Jobs/'.$a.'Delete', 'job'));

        //Events
        $this->files->put($path.$repo.'/Events/'.$a.'CreatedEvent.php', $this->buildClass($repo.'/Events/'.$a.'CreatedEvent', 'event'));
        $this->files->put($path.$repo.'/Events/'.$a.'UpdatedEvent.php', $this->buildClass($repo.'/Events/'.$a.'UpdatedEvent', 'event'));
        $this->files->put($path.$repo.'/Events/'.$a.'DeletedEvent.php', $this->buildClass($repo.'/Events/'.$a.'DeletedEvent', 'event'));

        $this->files->put($path.$repo.'/Providers/'.$a.'EventServiceProvider.php', $this->buildClass($repo.'/Providers/'.$a.'EventServiceProvider', 'event_service_provider'));

        //Requests
        $this->files->put($path.$repo.'/Requests/'.$a.'CreateRequest.php', $this->buildClass($repo.'/Requests/'.$a.'CreateRequest', 'request'));
        $this->files->put($path.$repo.'/Requests/'.$a.'UpdateRequest.php', $this->buildClass($repo.'/Requests/'.$a.'UpdateRequest', 'request'));
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $d = $this->files->makeDirectory($path, 0777, true, true);
            if ($d) {
                //$this->info('Created a directory ' . $path);
            }
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name, $stubName)
    {
        $stub = $this->files->get($this->getStub($stubName));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Build the class with the given name.
     *
     * @param $type
     *
     * @return mixed|string
     */
    protected function buildClassNew($type)
    {
        $a = explode('/', $this->repo);
        $a = array_pop($a);

        $stub = '';

        switch ($type) {
            case 'service-provider':
                //Get the STUB
                $stub = $this->files->get($this->getStub('provider'));

                $this->replaceNamespace($stub, $this->repo.'/');

                $stub = str_replace('DummyClass', $a.'ServiceProvider', $stub);
                $stub = str_replace('DummyModel', $a, $stub);
                $stub = str_replace('DummyUse', $this->getNamespace($this->repo.'/'), $stub);
                $stub = str_replace('DummyRepository', $a.'Repository', $stub);

                break;
        }

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub($stub)
    {
        return $this->laravel->basePath().'/vendor/shekarsiri/structure/src/Structure/stubs/'.$stub.'.stub';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
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
     * @param string $stub
     * @param string $name
     *
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
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
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
        $this->makeDirectory($path.$repo.'/Repositories');
        $this->makeDirectory($path.$repo.'/Events');
        $this->makeDirectory($path.$repo.'/Jobs');
        $this->makeDirectory($path.$repo.'/Listeners');
        $this->makeDirectory($path.$repo.'/Providers');
        $this->makeDirectory($path.$repo.'/Facades');
        $this->makeDirectory($path.$repo.'/Requests');
        $this->makeDirectory($path.$repo.'/Exceptions');
    }
}
