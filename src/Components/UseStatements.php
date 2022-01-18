<?php

namespace Cerbero\EloquentInspector\Components;

use Cerbero\EloquentInspector\Concerns\ReadsModel;

/**
 * The use statements component.
 *
 */
class UseStatements extends Component
{
    use ReadsModel;

    /**
     * Retrieve the model `use` statements
     *
     * @return array
     */
    public function get(): array
    {
        $useStatements = [];

        foreach ($this->readModel($this->model) as $line) {
            if (strpos($line, 'class') === 0) {
                break;
            } elseif (strpos($line, 'use') === 0) {
                preg_match('/([\w\\\_]+)(?:\s+as\s+([\w_]+))?;/i', $line, $matches);

                $segments = explode('\\', $matches[1]);
                $alias = $matches[2] ?? end($segments);
                $useStatements[$alias] = $matches[1];
            }
        }

        return $useStatements;
    }
}
