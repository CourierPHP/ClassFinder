<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;

class ClassNamespaceFilter implements Filter
{
    public function __construct(private readonly string $namespace)
    {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->getNamespaceName() === $this->namespace;
    }
}