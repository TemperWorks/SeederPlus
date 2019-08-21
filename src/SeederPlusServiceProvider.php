<?php

namespace Temper\SeederPlus;

use Illuminate\Support\ServiceProvider;
use Temper\SeederPlus\console\DeleteSnapshotCommand;
use Temper\SeederPlus\console\ResetSnapshotCommand;
use Temper\SeederPlus\console\SeedCommand;
use Temper\SeederPlus\console\SetupDatabaseCommand;
use Temper\SeederPlus\console\SetupRelationCommand;
use Temper\SeederPlus\console\SnapshotDatabaseCommand;
use Temper\SeederPlus\console\SnapshotsCommand;

class SeederPlusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'seederplus');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'seederplus');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/seederplus.php' => config_path('seederplus.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/seederplus'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/seederplus'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/seederplus'),
            ], 'lang');*/

            // Registering package commands.
             $this->commands([
                 SeedCommand::class,
                 SetupDatabaseCommand::class,
                 SnapshotDatabaseCommand::class,
                 ResetSnapshotCommand::class,
                 SnapshotsCommand::class,
                 DeleteSnapshotCommand::class,
                 SetupRelationCommand::class
             ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/seederplus.php', 'seederplus');

        // Register the main class to use with the facade
//        $this->app->singleton('seederplus', function () {
//            return new SeederPlus;
//        });
    }
}
