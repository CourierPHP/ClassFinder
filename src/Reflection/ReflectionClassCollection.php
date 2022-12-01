<?php

namespace Courier\ClassFinder\Reflection;

use ArrayAccess;
use Courier\ClassFinder\TestAttribute;
use Iterator;
use ReflectionClass;
use UnexpectedValueException;

#[TestAttribute]
final class ReflectionClassCollection implements ArrayAccess, Iterator
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

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): ReflectionClass
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (! $value instanceof ReflectionClass) {
            throw new UnexpectedValueException(
                "Value must be an instance of a [ReflectionClass]."
            );
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
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