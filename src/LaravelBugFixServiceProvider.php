<?php

namespace Appslanka\LaravelBugFix;

use Appslanka\LaravelBugFix\Commands\LaravelBugFixCommand;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelBugFixServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-bug-fix')
            ->hasConfigFile('bug-fix')
            ->hasViews()
            ->hasMigration('create_laravel-bug-fix_table')
            ->hasCommand(LaravelBugFixCommand::class);
    }

    public function registeringPackage()
    {
        $this->app->singleton(LaravelBugFix::class, function ($app) {
            return new LaravelBugFix($app, $app['config']['bug-fix']);
        });

        // Bind the custom exception handler to the exception handler contract
        $this->app->singleton(ExceptionHandlerContract::class, LaravelBugFix::class);
    }
}
