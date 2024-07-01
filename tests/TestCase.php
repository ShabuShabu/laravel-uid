<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ShabuShabu\Uid\UidServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            UidServiceProvider::class,
        ];
    }
}
