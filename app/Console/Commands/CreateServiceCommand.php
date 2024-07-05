<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateServiceCommand extends Command
{
    protected $signature = 'make:service {name : The name of the service} {repository : repository to be injected}';

    protected $description = 'Create a new service';

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryName = $this->argument('repository');
        $servicePath = app_path('Services');
        $repositoryPath = app_path('Repositories');
        $serviceNamespace = 'App\Services';
        $repositoryNamespace = 'App\Repositories';

        if (! is_dir($servicePath)) {
            mkdir($servicePath, 0777, true);
            $this->info("Created {$servicePath}");
        }

        $serviceFilePath = "{$servicePath}/{$name}.php";
        if (file_exists($serviceFilePath)) {
            $this->error("The service {$name} already exists!");

            return;
        }

        $repositoryFilePath = "{$repositoryPath}/{$repositoryName}.php";
        if (! file_exists($repositoryFilePath)) {
            $this->error("The repository {$repositoryName} does not exist!");

            return;
        }
        $repositoryVarName = lcfirst($repositoryName);

        $serviceTemplate = "<?php\n\nnamespace {$serviceNamespace};\n\nuse {$repositoryNamespace}\\{$repositoryName};\n\nclass {$name}\n{\n    protected {$repositoryName} \${$repositoryVarName};\n\n    public function __construct({$repositoryName} \${$repositoryName}Instance)\n    {\n        \$this->{$repositoryVarName} = \${$repositoryName}Instance;\n    }\n\n    // Your service class methods\n}\n";

        file_put_contents($serviceFilePath, $serviceTemplate);

        $this->info("Service {$name} created successfully.");
    }
}
