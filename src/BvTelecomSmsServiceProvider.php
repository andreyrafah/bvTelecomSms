<?php

namespace Andreyrafah\BvTelecomSms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Andreyrafah\BvTelecomSms\Commands\BvTelecomSmsCommand;

class BvTelecomSmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bvtelecomsms')
            ->hasConfigFile()
            ->hasMigration('create_bvtelecomsms_table');
    }
}
