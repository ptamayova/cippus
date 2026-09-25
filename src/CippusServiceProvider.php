<?php

declare(strict_types=1);

namespace Mdecode\Cippus;

use Illuminate\Support\ServiceProvider;
use Mdecode\Cippus\Console\InstallCommand;

class CippusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Cippus::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
        ]);
    }
}
