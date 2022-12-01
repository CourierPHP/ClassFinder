<?php

namespace Courier\ClassFinder\Filter;

use ReflectionClass;

interface Filter
{
    public function __invoke(ReflectionClass $reflectionClass): bool;
}