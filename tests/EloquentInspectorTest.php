<?php

namespace Cerbero\EloquentInspector;

use Cerbero\EloquentInspector\Providers\EloquentInspectorServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * The package test suite.
 *
 */
class EloquentInspectorTest extends TestCase
{
    /**
     * Retrieve the package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            EloquentInspectorServiceProvider::class,
        ];
    }
}
