<?php

namespace Courier\ClassFinder\Reflection;

use Iterator;
use ReflectionClass;

final class ReflectionClassCollection implements Iterator
{
    private int $key = 0;

    /** @var array<array-key,ReflectionClass> */
    private array $items = [];

    public function __construct(array $items = [])
    {
        $this->fill($items);
    }

    public function add(ReflectionClass $reflectionClass): self
    {
        $this->items[] = $reflectionClass;

        return $this;
    }

    public function empty(): self
    {
        $this->items = [];

        return $this;
    }

    public function fill(array $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * @param callable(ReflectionClass $class) $filter
     * @return $this
     */
    public function filter(callable $filter): self
    {
        return new ReflectionClassCollection(
            array_filter($this->items, $filter)
        );
    }

    public function merge(ReflectionClassCollection $collection): self
    {
        $this->items = array_unique(array_merge($collection->items, $this->items));

        return $this;
    }

    public function remove(ReflectionClass $reflectionClass): self
    {
        $key = array_search($reflectionClass, $this->items);

        if ($key !== false) {
            unset($this->items[$key]);
        }
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function current(): ReflectionClass
    {
        return $this->items[$this->key()];
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->items);
    }
}