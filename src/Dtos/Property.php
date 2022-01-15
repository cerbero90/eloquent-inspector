<?php

namespace Cerbero\EloquentInspector\Dtos;

/**
 * The property DTO.
 *
 */
class Property
{
    public string $name;
    public string $type;
    public string $dbType;
    public bool $nullable;
    public mixed $default = null;
}
