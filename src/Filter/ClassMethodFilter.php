<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;

class ClassMethodFilter implements Filter
{
    public function __construct(
        private readonly string $method, private readonly ?int $filter
    ) {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        $methods = $reflectionClass->getMethods($this->filter);

        foreach ($methods as $method) {
            if ($method->getName() === $this->method) {
                return true;
            }
        }

        return false;
    }
}