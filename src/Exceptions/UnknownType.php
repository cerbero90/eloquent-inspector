<?php

namespace Cerbero\EloquentInspector\Exceptions;

use Cerbero\EloquentInspector\Dtos\Property;
use InvalidArgumentException;

/**
 * The unknown type exception.
 *
 */
class UnknownType extends InvalidArgumentException
{
    /**
     * Instantiate the class.
     *
     * @param string $model
     * @param Property $property
     */
    public function __construct(public string $model, public Property $property)
    {
        $propertyName = $model . '::$' . $property->name;

        parent::__construct("Unable to map the type '{$property->dbType}' of the property {$propertyName}");
    }
}
