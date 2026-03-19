<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Keenops\LaravelTcbCms\Facades\TcbCms;
use Keenops\LaravelTcbCms\TcbCmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TcbCmsServiceProvider::class,
        ];
    }

    /**
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'TcbCms' => TcbCms::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('tcb-cms.api_key', 'test-api-key');
        $app['config']->set('tcb-cms.partner_code', 'TEST-PARTNER');
        $app['config']->set('tcb-cms.profile_id', '1234567890');
        $app['config']->set('tcb-cms.base_url', 'https://partners.tcbbank.co.tz');
        $app['config']->set('tcb-cms.reconciliation_base_url', 'https://partners.tcbbank.co.tz:8444');
        $app['config']->set('tcb-cms.logging.enabled', true);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
