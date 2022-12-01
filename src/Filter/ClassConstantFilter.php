<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;
use ReflectionClassConstant;

class ClassConstantFilter implements Filter
{
    public function __construct(
        private readonly string $constant, private readonly ?int $filter
    ) {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        /** @var array<array-key,ReflectionClassConstant> $constants */
        $constants = $reflectionClass->getConstants($this->filter);

        foreach ($constants as $constant) {
            if ($constant->getName() === $this->constant) {
                return true;
            }
        }

        return false;
    }
}