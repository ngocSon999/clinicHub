<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $contractPath = app_path('Repositories/Contracts');

        foreach (File::files($contractPath) as $file) {
            $interface = $file->getFilenameWithoutExtension();

            if (
                !str_ends_with($interface, 'Interface')
                || $interface === 'BaseRepositoryInterface'
            ) {
                continue;
            }

            $repository = str_replace('Interface', '', $interface);

            $interfaceClass = "App\\Repositories\\Contracts\\{$interface}";
            $repositoryClass = "App\\Repositories\\{$repository}";

            if (
                interface_exists($interfaceClass)
                && class_exists($repositoryClass)
            ) {
                $this->app->bind($interfaceClass, $repositoryClass);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
