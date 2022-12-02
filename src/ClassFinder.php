<?php

namespace Courier\ClassFinder;

use Attribute;
use Closure;
use Courier\ClassFinder\ClassResolver\ClassResolver;
use Courier\ClassFinder\ClassResolver\Psr4Resolver;
use Courier\ClassFinder\Filter\ClassAttributeFilter;
use Courier\ClassFinder\Filter\ClassConstantFilter;
use Courier\ClassFinder\Filter\ClassMethodFilter;
use Courier\ClassFinder\Filter\ClassFullyQualifiedNameFilter;
use Courier\ClassFinder\Filter\ClassNamespaceFilter;
use Courier\ClassFinder\Filter\ClassPropertyFilter;
use Courier\ClassFinder\Filter\ClassShortNameFilter;
use Courier\ClassFinder\Filter\Filter;
use Courier\ClassFinder\Reflection\ReflectionClassCollection;
use ReflectionClassConstant;

final class ClassFinder
{
    /** @var array<array-key,ClassResolver> $resolvers */
    private array $resolvers = [];

    /** @var array<array-key,Closure|Filter> $filters */
    private array $filters = [];

    public static function PSR4(): self
    {
        return new self([
            new Psr4Resolver()
        ]);
    }

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

    public function addResolver(ClassResolver $resolver): self
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    public function filter(Closure|Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function fullyQualifiedName(string $name): self
    {
        return $this->filter(new ClassFullyQualifiedNameFilter($name));
    }

    public function name(string $name): self
    {
        return $this->filter(new ClassShortNameFilter($name));
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
            $classes = $classes->merge(
                $resolver->resolve($directories)
            );
        }

        foreach ($this->filters as $filter) {
            $classes = $classes->filter($filter);
        }

        return $classes;
    }
}