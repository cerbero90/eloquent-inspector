<?php

namespace Cerbero\EloquentInspector\Concerns;

use ReflectionClass;
use ReflectionMethod;
use SplFileObject;

/**
 * The trait to read a model file.
 *
 */
trait ReadsModel
{
    /**
     * Lazily read the model line by line.
     *
     * @param string $model
     * @return iterable
     */
    protected function readModel(string $model): iterable
    {
        $filename = (new ReflectionClass($model))->getFileName();
        $handle = fopen($filename, 'rb');

        while (($line = fgets($handle, 1024)) !== false) {
            yield $line;
        }

        fclose($handle);
    }

    /**
     * Lazily read the first line of the given model method
     *
     * @param ReflectionMethod $method
     * @return string
     */
    protected function readFirstLineOfMethod(ReflectionMethod $method): string
    {
        $file = new SplFileObject($method->getFileName(), 'rb');

        $file->seek($method->getStartLine() + 1);

        return $file->fgets();
    }
}
