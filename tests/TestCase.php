<?php

namespace IgniterLabs\Webhook\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Igniter\Flame\ServiceProvider::class,
            \IgniterLabs\Webhook\Extension::class,
        ];
    }
}
