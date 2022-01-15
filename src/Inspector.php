<?php

namespace Cerbero\EloquentInspector;

use Cerbero\EloquentInspector\Components\Properties;
use Cerbero\EloquentInspector\Components\Relationships;
use Cerbero\EloquentInspector\Components\UseStatements;

/**
 * The Eloquent inspector.
 *
 */
class Inspector
{
    /**
     * The inspector instances.
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * The `use` statements cache.
     *
     * @var array
     */
    protected $useStatements;

    /**
     * The properties cache.
     *
     * @var array
     */
    protected $properties;

    /**
     * The relationships cache.
     *
     * @var array
     */
    protected $relationships;

    /**
     * Instantiate the class.
     *
     * @param string $model
     */
    protected function __construct(protected string $model)
    {
    }

    /**
     * Statically instantiate the class
     *
     * @param string $model
     * @return static
     */
    public static function inspect(string $model): static
    {
        return static::$instances[$model] ??= new static($model);
    }

    /**
     * Clean up the given model information
     *
     * @param string|null $model
     * @return void
     */
    public static function flush(string $model = null): void
    {
        if ($model) {
            unset(static::$instances[$model]);
        } else {
            static::$instances = [];
        }
    }

    /**
     * Clean up information of the current instance
     *
     * @return void
     */
    public function forget(): void
    {
        unset(static::$instances[$this->model]);
    }

    /**
     * Retrieve the inspected model class
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Retrieve the `use` statements
     *
     * @return array
     */
    public function getUseStatements(): array
    {
        return $this->useStatements ??= UseStatements::of($this->model)->get();
    }

    /**
     * Retrieve the properties
     *
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties ??= Properties::of($this->model)->get();
    }

    /**
     * Retrieve the relationships
     *
     * @return array
     */
    public function getRelationships(): array
    {
        return $this->relationships ??= Relationships::of($this->model)->get();
    }
}
