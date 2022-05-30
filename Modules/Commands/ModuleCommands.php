<?php

namespace Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SebastianBergmann\Environment\Console;

class ModuleCommands extends Command
{

    use CommandTrait;
    protected $namespace = "Modules";
    protected $servicePath;
    protected $modulePath;

    private $module = null;
    private $ucModule = null;
    private $lcModule = null;
    private $underScoredName = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {moduleName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making module';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {

        parent::__construct();


        // $this->ucModule =

        // $this->moduleName = $this->argument('moduleName');
        // $this->namespace = 'Modules\\';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->module = $this->argument('moduleName');
        $this->ucModule = lcfirst($this->argument('moduleName'));
        $this->lcModule = strtolower($this->argument('moduleName'));

        // create undescored names
        $this->underScoredName = preg_replace('/([A-Z])/', "_$1", $this->ucModule);


        // Create module folder
        File::makeDirectory("Modules/{$this->module}");

        $this->modulePath = $this->fromModule("");
        $this->generateServiceProvider();
        $this->generateModel();
        $this->generateDatabaseDir();
        $this->generateHttpDir();
        $this->generateRepository();
        $this->generateControllerFile($this->module, null, $this->ucModule, $this->underScoredName);
        $this->generateResources();
        $this->generateRoutes();
        $this->info($this->module . " extracted \n");
        return true;
    }

    public function generateServiceProvider()
    {

        // Get service provider stub
        $serviceProviderStub = base_path('Modules/stubs/serviceProvider.stub');
        // get stub contents
        $serviceProvider = File::get($serviceProviderStub);
        File::makeDirectory("Modules/{$this->module}/Providers");
        $this->servicePath = $this->getStubPath($this->module);
        File::put($this->servicePath,  str_replace('{{module}}', $this->module, $serviceProvider));

        return true;
    }

    public function generateModel()
    {

        // Get service provider stub
        $serviceProviderStub = base_path('Modules/stubs/Model.stub');
        // get stub contents
        $model = File::get($serviceProviderStub);
        File::makeDirectory("Modules/{$this->module}/Models");
        $this->servicePath = $this->modelPath($this->module);
        File::put($this->servicePath,  str_replace('{{module}}', $this->module, $model));

        return true;
    }

    protected function modelPath()
    {
        return base_path("Modules/{$this->module}/Models/{$this->module}.php");
    }

    public function generateRepository()
    {

        // Get service provider stub
        $repositoryStub = base_path('Modules/stubs/Repository.stub');
        // get stub contents
        $repository = File::get($repositoryStub);
        // File::makeDirectory("Modules/{$this->module}/Http");
        File::makeDirectory("Modules/{$this->module}/Http/Repositories");
        $this->servicePath = $this->repositoryPath($this->module);
        // name all module as variables
        $replaceRepovars = str_replace('{{uCmodule}}', $this->ucModule, $repository);
        // create all permission

        $underScoredName = str_replace('{{underscoreModule}}', strtolower($this->underScoredName), $replaceRepovars);

        $replacePermissions = str_replace('{{lcModule}}', $this->lcModule, $underScoredName);
        File::put($this->servicePath,  str_replace('{{module}}', $this->module, $replacePermissions));

        return true;
    }

    protected function repositoryPath()
    {
        return base_path("Modules/{$this->module}/Http/Repositories/{$this->module}Repository.php");
    }

    protected function getStubPath()
    {
        return base_path("Modules/{$this->module}/Providers/{$this->module}ServiceProvider.php");
    }


    protected function generateDatabaseDir()
    {
        File::makeDirectory(base_path($this->fromModule("database")));
        File::makeDirectory(base_path($this->fromModule("database/migrations")));
        File::makeDirectory(base_path($this->fromModule("database/seeders")));
        File::makeDirectory(base_path($this->fromModule("database/fakers")));
    }

    protected function generateHttpDir()
    {
        File::makeDirectory(base_path($this->fromModule("Http")));
        File::makeDirectory(base_path($this->fromModule("Http/Requests")));
        File::makeDirectory(base_path($this->fromModule("Http/Controllers")));
    }

    protected function generateModelDir()
    {
        File::makeDirectory(base_path($this->fromModule("Model")));
    }

    public function generateResources()
    {
        File::makeDirectory(base_path("Modules/{$this->module}/views"));
    }
    public function generateRoutes()
    {
        File::makeDirectory(base_path($this->fromModule("routes")));
        $apiRoutePath = $this->getRouthPath('api');


        $apiRouteStub = $this->getRouteStub('Api');
        $replaceModule = str_replace('{{module}}', $this->module, $apiRouteStub);
        $replaceLcModule = str_replace('{{lcModule}}', $this->lcModule, $replaceModule);


        File::put($apiRoutePath, $replaceLcModule);

        $apiRoutePath = $this->getRouthPath('web');
        File::put($apiRoutePath, str_replace('{{module}}', $this->module, $this->getRouteStub('Web')));
    }

    public function getRouthPath($type)
    {
        return base_path($this->fromModule("/routes/{$type}.php"));
    }

    public function getRouteStub($type)
    {
        return File::get(base_path("Modules/stubs/{$type}RoutesStub.stub"));
    }
}
