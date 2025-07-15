<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Tests;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use ShabuShabu\Uid\Tests\App\Models\Contact;
use ShabuShabu\Uid\Tests\App\Models\User;
use ShabuShabu\Uid\UidServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            UidServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/app/database/migrations');
    }

    protected function defineEnvironment($app): void
    {
        tap($app['config'], static function (Repository $config) {
            $config->set('uid.prefixes', [
                'usr' => User::class,
                'con' => Contact::class,
            ]);
        });
    }
}
