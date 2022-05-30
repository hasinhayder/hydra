<?php

namespace Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeController extends Command
{

    use CommandTrait;
    protected $namespace = "Modules";
    protected $servicePath;
    protected $modulePath;

    private $module = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:controller {className} {--m|module=}';

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->module = trim($this->option('module'), "=");
        if (!$this->module) {
            $this->error("No module selected use -m or --module option to set module");
            return;
        }
        $className = $this->argument('className');

        $this->generateControllerFile($className, $this->module);
        $this->info("Controller created successfully");
    }
}
