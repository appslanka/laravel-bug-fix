<?php

namespace Appslanka\LaravelBugFix;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Appslanka\LaravelBugFix\Commands\LaravelBugFixCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-bug-fix_table')
            ->hasCommand(LaravelBugFixCommand::class);
    }
}
