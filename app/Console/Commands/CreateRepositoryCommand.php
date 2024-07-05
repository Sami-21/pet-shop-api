<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name : The name of the repository} {interface : Optional interface to be implemented}';

    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = $this->argument('name');
        $interfaceName = $this->argument('interface');
        $repositoryPath = app_path('Repositories');
        $interfacePath = app_path('Interfaces');
        $repositoryNamespace = 'App\Repositories';
        $interfaceNamespace = 'App\Interfaces';

        if (! is_dir($repositoryPath)) {
            mkdir($repositoryPath, 0777, true);
            $this->info("Created {$repositoryPath}");
        }

        $repositoryFilePath = "{$repositoryPath}/{$name}.php";
        if (file_exists($repositoryFilePath)) {
            $this->error("The repository {$name} already exists!");

            return;
        }

        $interfaceFilePath = "{$interfacePath}/{$interfaceName}.php";
        if (! file_exists($interfaceFilePath)) {
            $this->error("The interface {$interfaceName} does not exist!");

            return;
        }
        $repositoryTemplate = "<?php\n\nnamespace {$repositoryNamespace};\n\nuse {$interfaceNamespace}\\{$interfaceName};\n\nclass {$name} implements {$interfaceName}\n{\n  // Your repository class methods  \n}\n";

        file_put_contents($repositoryFilePath, $repositoryTemplate);

        $this->info("Repository {$name} created successfully.");
    }
}
