<?php

namespace Behatch\HttpCall;

use Behat\Behat\Context\Argument\ArgumentResolver;

class HttpCallResultPoolResolver implements ArgumentResolver
{
    private array $dependencies;

    public function __construct(/* ... */)
    {
        $this->dependencies = [];

        foreach (\func_get_args() as $param) {
            $this->dependencies[\get_class($param)] = $param;
        }
    }

    public function resolveArguments(\ReflectionClass $classReflection, array $arguments)
    {
        if (null !== ($constructor = $classReflection->getConstructor())) {
            foreach ($constructor->getParameters() as $parameter) {
                if (!($type = $parameter->getType()) instanceof \ReflectionNamedType) {
                    continue;
                }

                if ($type->isBuiltin()) {
                    continue;
                }

                $class = new \ReflectionClass($type->getName());

                if (!isset($this->dependencies[$class->name])) {
                    continue;
                }

                $arguments[$parameter->name] = $this->dependencies[$class->name];
            }
        }

        return $arguments;
    }
}
