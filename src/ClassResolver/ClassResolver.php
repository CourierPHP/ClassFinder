<?php

namespace Courier\ClassFinder\ClassResolver;

use Courier\ClassFinder\Reflection\ReflectionClassCollection;

interface ClassResolver
{
    public function resolve(string|array $directories): ReflectionClassCollection;
}