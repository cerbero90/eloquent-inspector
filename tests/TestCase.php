<?php

namespace Cerbero\EloquentInspector;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * The package test suite.
 *
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app->config->set('database.default', 'testbench');
        $app->config->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
