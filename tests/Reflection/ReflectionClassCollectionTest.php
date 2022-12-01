<?php

namespace Courier\Tests\ClassFinder;

use Courier\ClassFinder\Reflection\ReflectionClassCollection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnexpectedValueException;

class ReflectionClassCollectionTest extends TestCase
{
    public function test_items_are_added_and_removed_from_collection()
    {
        $collection = new ReflectionClassCollection;
        $reflection = new ReflectionClass(static::class);

        $collection->add($reflection);

        $this->assertSame($collection[0], $reflection);
        $this->assertSame(1, $collection->count());

        $collection->remove($reflection);

        $this->assertFalse(isset($collection[0]));
        $this->assertSame(0, $collection->count());
    }

    public function test_that_filling_a_collection_adds_an_array_of_items()
    {
        $collection = new ReflectionClassCollection;
        $array = [
            new ReflectionClass(static::class),
            new ReflectionClass(ReflectionClassCollection::class),
        ];

        $collection->fill($array);

        $this->assertSame($collection[0], $array[0]);
        $this->assertSame($collection[1], $array[1]);
    }

    public function test_that_collections_can_be_filtered()
    {
        $collection1 = new ReflectionClassCollection([
            new ReflectionClass(self::class),
            new ReflectionClass(ReflectionClassCollection::class),
        ]);

        $collection2 = $collection1->filter(function (ReflectionClass $class) {
            return $class->getName() === self::class;
        });

        $this->assertNotEquals($collection1, $collection2);

        $this->assertTrue(isset($collection1[0]));
        $this->assertTrue(isset($collection1[1]));
        $this->assertTrue(isset($collection2[0]));
        $this->assertFalse(isset($collection2[1]));

        $this->assertEquals(new ReflectionClass(self::class), $collection2[0]);
    }

    public function test_that_collections_can_be_merged()
    {
        $collection1 = new ReflectionClassCollection([
            new ReflectionClass(self::class),
        ]);

        $collection2 = new ReflectionClassCollection([
            new ReflectionClass(ReflectionClassCollection::class),
        ]);

        $collection3 = $collection1->merge($collection2);

        $this->assertTrue(isset($collection3[0]));
        $this->assertTrue(isset($collection3[1]));

        $this->assertSame($collection1[0], $collection3[0]);
        $this->assertSame($collection2[0], $collection3[1]);
    }

    public function test_that_offset_can_be_set()
    {
        $collection = new ReflectionClassCollection;

        $collection[1] = new ReflectionClass(self::class);

        $this->assertTrue(isset($collection[1]));
    }

    public function test_that_offset_cannot_be_set_if_value_is_not_reflection_class()
    {
        $this->expectException(UnexpectedValueException::class);

        $collection = new ReflectionClassCollection;

        $collection[0] = 'test string';
    }

    public function test_that_offset_can_be_unset()
    {
        $collection = new ReflectionClassCollection([
            new ReflectionClass(self::class),
            new ReflectionClass(ReflectionClassCollection::class),
        ]);

        unset($collection[0]);

        $this->assertFalse(isset($collection[0]));
        $this->assertTrue(isset($collection[1]));
    }

    public function test_that_collection_can_be_iterated()
    {
        $collection = new ReflectionClassCollection([
            new ReflectionClass(self::class),
            new ReflectionClass(ReflectionClassCollection::class),
        ]);

        foreach ($collection as $class) {
            $this->assertInstanceOf(ReflectionClass::class, $class);
        }
    }
}