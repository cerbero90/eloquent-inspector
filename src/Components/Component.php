<?php

namespace Cerbero\EloquentInspector\Components;

/**
 * The abstract model component.
 *
 */
abstract class Component
{
    /**
     * Instantiate the class.
     *
     * @param string $model
     */
    public function __construct(protected string $model)
    {
    }

    /**
     * Statically instantiate the class
     *
     * @param string $model
     * @return static
     */
    public static function of(string $model): static
    {
        return new static($model);
    }

    /**
     * Retrieve the inspected model component
     *
     * @return mixed
     */
    abstract public function get(): mixed;
}
