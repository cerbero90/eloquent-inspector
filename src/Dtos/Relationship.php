<?php

namespace Cerbero\EloquentInspector\Dtos;

/**
 * The relationship DTO.
 *
 */
class Relationship
{
    public string $name;
    public string $type;
    public string $class;
    public string $model;
    public bool $relatesToMany;
}
