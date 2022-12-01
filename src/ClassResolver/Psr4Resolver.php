<?php

namespace Courier\ClassFinder\ClassResolver;

use Courier\ClassFinder\Reflection\ReflectionClassCollection;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

class Psr4Resolver implements ClassResolver
{
    public function resolve(array|string $directories): ReflectionClassCollection
    {
        $files = Finder::create()->name('*.php')->in($directories);
        $collection = new ReflectionClassCollection;

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $contents = $file->getContents();
            $fromPos = strpos($contents, 'namespace') + 9;
            $toPos = strpos($contents, ';', $fromPos);
            $namespace = trim(substr($contents, $fromPos, ($toPos - $fromPos)));
            $fqdnClass = "{$namespace}\\{$file->getFilenameWithoutExtension()}";

            try {
                $collection->add(new ReflectionClass($fqdnClass));
            } catch(ReflectionException) {};
        }

        return $collection;
    }
}