<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;

class ClassShortNameFilter implements Filter
{
    public function __construct(private readonly string $name)
    {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getShortName() === $this->name;
    }
}