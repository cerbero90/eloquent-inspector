<?php

namespace Cerbero\EloquentInspector\Components;

use Cerbero\EloquentInspector\Dtos\Property;
use Cerbero\EloquentInspector\Exceptions\UnknownType;
use Illuminate\Support\Facades\Schema;

/**
 * The properties component.
 *
 */
class Properties extends Component
{
    /**
     * The map between schema and PHP types.
     *
     * @var array
     */
    protected $typesMap = [
        'guid'     => 'string',
        'boolean'  => 'bool',
        'datetime' => 'Carbon\Carbon',
        'string'   => 'string',
        'json'     => 'string',
        'integer'  => 'int',
        'date'     => 'Carbon\Carbon',
        'smallint' => 'int',
        'text'     => 'string',
        'decimal'  => 'float',
        'bigint'   => 'int',
    ];

    /**
     * Retrieve the model properties
     *
     * @return array
     */
    public function get(): array
    {
        $properties = [];
        $model = $this->model::make();
        $table = $model->getTable();
        $connection = $model->getConnection();

        foreach (Schema::getColumnListing($table) as $name) {
            $column = $connection->getDoctrineColumn($table, $name);
            $properties[$name] = $property = new Property();
            $property->name = $name;
            $property->dbType = Schema::getColumnType($table, $name);
            $property->nullable = !$column->getNotnull();
            $property->default = $column->getDefault();
            $property->type = $this->typesMap[$property->dbType] ?? throw new UnknownType($this->model, $property);
        }

        return $properties;
    }
}
