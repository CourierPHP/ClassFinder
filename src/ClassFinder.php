<?php

namespace Courier\ClassFinder;

use Closure;
use Courier\ClassFinder\ClassResolver\ClassResolver;
use Courier\ClassFinder\Filter\ClassAttributeFilter;
use Courier\ClassFinder\Filter\ClassConstantFilter;
use Courier\ClassFinder\Filter\ClassMethodFilter;
use Courier\ClassFinder\Filter\ClassNameFilter;
use Courier\ClassFinder\Filter\ClassNamespaceFilter;
use Courier\ClassFinder\Filter\ClassPropertyFilter;
use Courier\ClassFinder\Filter\Filter;
use Courier\ClassFinder\Reflection\ReflectionClassCollection;

final class ClassFinder
{
    /** @var array<array-key,ClassResolver> $resolvers */
    private array $resolvers = [];

    /** @var array<array-key,Closure|Filter> $filters */
    private array $filters = [];

    public function __construct(array $resolvers = [])
    {
        $this->setResolvers($resolvers);
    }

    public function setResolvers(array $resolvers): self
    {
        $this->resolvers = [];

        return $this->addResolvers($resolvers);
    }

    public function addResolvers(array $resolvers): self
    {
        foreach ($resolvers as $resolver) {
            $this->addResolver($resolver);
        }

        return $this;
    }

    public function addResolver($resolver): self
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    public function filter(Closure|Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function name(string $name): self
    {
        return $this->filter(new ClassNameFilter($name));
    }

    public function namespace(string $namespace): self
    {
        return $this->filter(new ClassNamespaceFilter($namespace));
    }

    public function hasAttribute(string $attribute, int $target = 63): self
    {
        return $this->filter(new ClassAttributeFilter($attribute, $target));
    }

    public function hasConstant(string $constant, ?int $filter = null): self
    {
        return $this->filter(new ClassConstantFilter($constant, $filter));
    }

    public function hasMethod(string $method, ?int $filter = null): self
    {
        return $this->filter(new ClassMethodFilter($method, $filter));
    }

    public function hasProperty(string $property, ?int $filter = null): self
    {
        return $this->filter(new ClassPropertyFilter($property, $filter));
    }

    public function in(string|array $directories): ReflectionClassCollection
    {
        $classes = new ReflectionClassCollection;

        foreach ($this->resolvers as $resolver) {
            $classes->merge(
                $resolver->resolve($directories)
            );
        }

        foreach ($this->filters as $filter) {
            $classes = $classes->filter($filter);
        }

        return $classes;
    }
}