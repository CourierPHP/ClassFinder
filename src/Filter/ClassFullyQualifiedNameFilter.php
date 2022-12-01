<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;

class ClassFullyQualifiedNameFilter implements Filter
{
    public function __construct(private readonly string $name)
    {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getName() === $this->name;
    }
}