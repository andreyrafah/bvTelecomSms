<?php

namespace Andreyrafah\BvTelecomSms\Tests;

use Andreyrafah\BvTelecomSms\BvTelecomSmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Andreyrafah\\BvTelecomSms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            BvTelecomSmsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_bvtelecomsms_table.php.stub';
        $migration->up();
        */
    }
}
