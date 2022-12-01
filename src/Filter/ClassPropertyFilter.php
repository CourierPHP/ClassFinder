<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;
use ReflectionProperty;

class ClassPropertyFilter implements Filter
{
    public function __construct(
        private readonly string $property, private readonly ?int $filter
    ) {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        $properties = $reflectionClass->getProperties($this->filter);

        foreach ($properties as $property) {
            if ($property->getName() === $this->property) {
                return true;
            }
        }

        return false;
    }
}