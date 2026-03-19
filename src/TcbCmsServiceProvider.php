<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms;

use Illuminate\Support\ServiceProvider;
use Keenops\LaravelTcbCms\Client\TcbCmsClient;
use Keenops\LaravelTcbCms\Contracts\TcbCmsClientInterface;

class TcbCmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tcb-cms.php',
            'tcb-cms'
        );

        $this->app->bind(TcbCmsClientInterface::class, TcbCmsClient::class);

        $this->app->singleton(TcbCms::class, function ($app) {
            return new TcbCms($app->make(TcbCmsClientInterface::class));
        });

        $this->app->alias(TcbCms::class, 'tcb-cms');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/tcb-cms.php' => config_path('tcb-cms.php'),
        ], 'tcb-cms-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'tcb-cms-migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/tcb-cms.php');
    }
}
