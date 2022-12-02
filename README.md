This package provides an API for finding PHP classes of a particular composition within the specified directory. This allows you to find a class based on its name, namespace, properties, attributes, etc.

## Installation

__DON'T INSTALL THIS YET__

## Usage

The `ClassFinder` class construct accepts an array of class resolvers. Class resolvers do not handle the autoloading of PHP classes themselves, but resolve the fully qualified class name based on reading the file. These are then loaded into a `ReflectionClass` and returned in a collection.

Alternatively, you may use the static factory method `ClassFinder::PSR4()` to create an instance of the `ClassFinder` using the common PSR-4 resolver.

Currently available helper methods are:

- `fullyQualifiedName(string $name)` - Filters the classes down to only those that match the fully qualified name passed.
- `name(string $name)` - Filters the classes down to only those that match the short name passed.
- `namespace(string $namespace)` - Filters the classes down to only those that reside in the specified namespace.
- `hasAttribute(string $attribute, int $target = 63)` - Filters the classes down to only those that contain the specified attribute. You can filter these attributes further by specifying the `$target` parameter and passing one or more of the constants defined on `Attribute`.
- `hasConstant(string $constant, ?int $filter = null)` - Filters the classes down to only those that have constants matching the passed name. You can filter these constants further by specifying the `$filter` parameter and passing one or more of the constants defined on `ReflectionClassConstant`.
- `hasMethod(string $method, ?int $filter = null)` - Filters the classes down to only those that have methods matching the specified name. You can filter these methods by specifying the `$filter` parameter and passing one or more of the constants defined on `ReflectionMethod`.
- `hasProperty(string $property, ?int $filter = null)` - Filters the classes down to only those that have properties matching the specified name. You can filter these properties further by specifying the `$filter` parameter and passing one or more of the constants defined on `ReflectionProperty`.
- `filter(Closure|Filter $filter)` - Allows you to specify a custom filter by passing a closure or implementation of the `Filter` class. This closure will receive an instance of `ReflectionClass` and should return true if it should be kept in the collection, or false if it should not.

See below for some examples of how this might be used:

```php
use Courier\ClassFinder\ClassFinder;

// Find all classes named "ClassFinder" in the "src" directory:
ClassFinder::PSR4()
    ->name('ClassFinder')
    ->in('src');

// Find all classes that have the "TestingThis" attribute on a method in the "src" directory:
ClassFinder::PSR4()
    ->hasAttribute(TestingThis::class, Attribute::TARGET_METHOD)
    ->in('src');
```