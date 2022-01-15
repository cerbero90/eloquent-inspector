<?php

namespace Cerbero\EloquentInspector\Components;

use Cerbero\EloquentInspector\Concerns\ReadsModel;
use Cerbero\EloquentInspector\Dtos\Relationship;
use Cerbero\EloquentInspector\Inspector;
use Illuminate\Database\Eloquent\Relations;
use ReflectionClass;
use ReflectionMethod;

/**
 * The relationships component.
 *
 */
class Relationships extends Component
{
    use ReadsModel;

    /**
     * The relationships map.
     *
     * @var array
     */
    protected $relationsMap = [
        'hasOne' => [
            'class' => Relations\HasOne::class,
            'relatesToMany' => false,
        ],
        'hasOneThrough' => [
            'class' => Relations\HasOneThrough::class,
            'relatesToMany' => false,
        ],
        'morphOne' => [
            'class' => Relations\MorphOne::class,
            'relatesToMany' => false,
        ],
        'belongsTo' => [
            'class' => Relations\BelongsTo::class,
            'relatesToMany' => false,
        ],
        'morphTo' => [
            'class' => Relations\MorphTo::class,
            'relatesToMany' => false,
        ],
        'hasMany' => [
            'class' => Relations\HasMany::class,
            'relatesToMany' => true,
        ],
        'hasManyThrough' => [
            'class' => Relations\HasManyThrough::class,
            'relatesToMany' => true,
        ],
        'morphMany' => [
            'class' => Relations\MorphMany::class,
            'relatesToMany' => true,
        ],
        'belongsToMany' => [
            'class' => Relations\BelongsToMany::class,
            'relatesToMany' => true,
        ],
        'morphToMany' => [
            'class' => Relations\MorphToMany::class,
            'relatesToMany' => true,
        ],
        'morphedByMany' => [
            'class' => Relations\MorphToMany::class,
            'relatesToMany' => true,
        ],
    ];

    /**
     * Retrieve the model relationships
     *
     * @return array
     */
    public function get(): array
    {
        $relationships = [];
        $relations = implode('|', array_keys($this->relationsMap));
        $regex = "/\\\$this->($relations)\W+([\w\\\]+)/";
        $reflection = new ReflectionClass($this->model);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->getFileName() != $reflection->getFileName()) {
                continue;
            }

            if (!preg_match($regex, $this->readFirstLineOfMethod($method), $matches)) {
                continue;
            }

            [, $relation, $relatedModel] = $matches;

            if (!$qualifiedModel = $this->qualifyModel($relatedModel, $reflection)) {
                continue;
            }

            $relationships[$method->name] = $relationship = new Relationship();
            $relationship->name = $method->name;
            $relationship->type = $relation;
            $relationship->class = $this->relationsMap[$relation]['class'];
            $relationship->model = $qualifiedModel;
            $relationship->relatesToMany = $this->relationsMap[$relation]['relatesToMany'];
        }

        return $relationships;
    }

    /**
     * Retrieve the fully qualified class name of the given model
     *
     * @param string $model
     * @param ReflectionClass $reflection
     * @return string|null
     */
    protected function qualifyModel(string $model, ReflectionClass $reflection): ?string
    {
        if (class_exists($model)) {
            return $model;
        }

        $useStatements = Inspector::inspect($this->model)->getUseStatements();

        if (isset($useStatements[$model])) {
            return $useStatements[$model];
        }

        return class_exists($class = $reflection->getNamespaceName() . "\\{$model}") ? $class : null;
    }
}
