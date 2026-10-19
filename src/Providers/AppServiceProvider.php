<?php

namespace NjDigitalLab\Laranonce\Providers;

use Illuminate\Support\ServiceProvider;
use NjDigitalLab\Laranonce\Commands\PruneNoncesCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            PruneNoncesCommand::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../../database/migrations' => database_path('migrations'),
            ], 'laranonce-migrations');
        }
    }
}
