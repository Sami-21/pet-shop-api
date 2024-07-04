<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateInterfaceCommand extends Command
{
    protected $signature = 'make:interface {name : The name of the interface}';

    protected $description = 'Create a new interface';

    public function handle()
    {
        $name = $this->argument('name');
        $interfacePath = app_path('Interfaces');

        if (!is_dir($interfacePath)) {
            mkdir($interfacePath, 0777, true);
            $this->info("Created {$interfacePath}");
        }

        $interfaceFilePath = "{$interfacePath}/{$name}.php";
        if (file_exists($interfaceFilePath)) {
            $this->error("The interface {$name} already exists!");

            return;
        }

        $interfaceTemplate = "<?php\n\nnamespace App\Interfaces;
\n\ninterface {$name} \n{\n    // Your interface methods\n}\n";
        file_put_contents($interfaceFilePath, $interfaceTemplate);

        $this->info("Interface {$name} created successfully.");
    }
}