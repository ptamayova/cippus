<?php

declare(strict_types=1);

namespace Mdecode\Cippus\Tests;

use Mdecode\Cippus\CippusServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public string $applicationPath;

    protected function getPackageProviders($app): array
    {
        return [
            CippusServiceProvider::class,
        ];
    }
}
