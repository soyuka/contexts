<?php

namespace Behatch\Context\ContextClass;

use Behat\Behat\Context\ContextClass\ClassResolver as BaseClassResolver;

class ClassResolver implements BaseClassResolver
{
    public function supportsClass($contextClass)
    {
        return str_starts_with($contextClass, 'behatch:context:');
    }

    public function resolveClass($contextClass)
    {
        $className = preg_replace_callback('/(^\w|:\w)/', function ($matches) {
            return str_replace(':', '\\', strtoupper($matches[0]));
        }, $contextClass);

        return $className.'Context';
    }
}
