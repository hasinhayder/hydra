<?php

namespace Modules\Commands;

use Illuminate\Support\Facades\File;

trait CommandTrait
{
    protected function generateControllerFile($controllerName = null, $module = null, $ucModule = null, $underScoredName = null)
    {
        $controllerName = $controllerName ?? $this->module;
        $module = $module ?? $controllerName;
        $controllerStub = File::get(base_path("Modules/stubs/ControllerStub.stub"));
        $controllerPath = base_path($this->fromModule("Http/Controllers/{$controllerName}Controller.php"));
        $replaceModule = str_replace('{{module}}', $module, $controllerStub);
        $replaceClass = str_replace('{{class}}', $controllerName, $replaceModule);
        $replaceRepositoryName = str_replace('{{repoName}}', lcfirst($this->module), $replaceClass);
        $ucModuleReplace = str_replace('{{uCmodule}}', $ucModule, $replaceRepositoryName);
        $underScoreModuleReplace = str_replace('{{underscoreModule}}', strtolower($underScoredName), $ucModuleReplace);
        File::put($controllerPath, $underScoreModuleReplace);
    }


    public function fromModule($path)
    {
        return "Modules/{$this->module}/" . $path;
    }

    public function generateRequest($controllerName, $module = null)
    {
        $controllerName = $controllerName ?? $this->module;
        $module = $module ?? $controllerName;

        $requestStub = File::get(base_path("Modules/stubs/RequestsStub.stub"));
        $requestPath = base_path($this->fromModule("Http/Requests/{$controllerName}Request.php"));

        $replaceModule = str_replace('{{module}}', $module, $requestStub);
        $replaceClass = str_replace('{{class}}', $controllerName, $replaceModule);

        File::put($requestPath, $replaceClass);
    }
}
