<?php

namespace Courier\ClassFinder\Filter;

use Attribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;

class ClassAttributeFilter implements Filter
{
    public function __construct(
        private readonly string $attribute, private readonly int $target
    ) {}

    public function __invoke(ReflectionClass $reflectionClass): bool
    {
        if ($this->target & Attribute::TARGET_CLASS && $this->classHasAttribute($reflectionClass)) {
            return true;
        }

        if ($this->target & Attribute::TARGET_CLASS_CONSTANT && $this->classConstantHasAttribute($reflectionClass)) {
            return true;
        }

        if ($this->target & Attribute::TARGET_METHOD && $this->classMethodHasAttribute($reflectionClass)) {
            return true;
        }

        if ($this->target & Attribute::TARGET_PARAMETER && $this->classParameterHasAttribute($reflectionClass)) {
            return true;
        }

        if ($this->target & Attribute::TARGET_PROPERTY && $this->classPropertyHasAttribute($reflectionClass)) {
            return true;
        }

        return false;
    }

    private function classHasAttribute(ReflectionClass $reflectionClass): bool
    {
        $attributes = $reflectionClass->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF);

        return ! empty($attributes);
    }

    private function classConstantHasAttribute(ReflectionClass $reflectionClass): bool
    {
        /** @var array<array-key,ReflectionClassConstant> $constants */
        $constants = $reflectionClass->getConstants();

        foreach ($constants as $constant) {
            $attributes = $constant->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF);

            if (empty($attributes)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function classMethodHasAttribute(ReflectionClass $reflectionClass): bool
    {
        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            $attributes = $method->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF);

            if (empty($attributes)) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function classParameterHasAttribute(ReflectionClass $reflectionClass): bool
    {
        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            foreach ($method->getParameters() as $parameter) {
                $attributes = $parameter->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF);

                if (empty($attributes)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function classPropertyHasAttribute(ReflectionClass $reflectionClass): bool
    {
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes($this->attribute, ReflectionAttribute::IS_INSTANCEOF);

            if (empty($attributes)) {
                continue;
            }

            return true;
        }

        return false;
    }
}