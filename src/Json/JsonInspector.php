<?php

namespace Behatch\Json;

use Symfony\Component\PropertyAccess\PropertyAccessor;

class JsonInspector
{
    private $evaluationMode;

    private $accessor;

    public function __construct($evaluationMode)
    {
        $magicMethods = \defined(PropertyAccessor::class.'::DISALLOW_MAGIC_METHODS')
            ? PropertyAccessor::MAGIC_GET | PropertyAccessor::MAGIC_SET
            : false;
        $throwException = \defined(PropertyAccessor::class.'::DO_NOT_THROW')
            ? PropertyAccessor::THROW_ON_INVALID_INDEX | PropertyAccessor::THROW_ON_INVALID_PROPERTY_PATH
            : true;

        $this->evaluationMode = $evaluationMode;
        $this->accessor = new PropertyAccessor($magicMethods, $throwException);
    }

    public function evaluate(Json $json, $expression)
    {
        if ('javascript' === $this->evaluationMode) {
            $expression = str_replace('->', '.', $expression);
        }

        try {
            return $json->read($expression, $this->accessor);
        } catch (\Exception $e) {
            throw new \Exception("Failed to evaluate expression '$expression'");
        }
    }

    public function validate(Json $json, JsonSchema $schema)
    {
        $validator = new \JsonSchema\Validator();

        $resolver = new \JsonSchema\SchemaStorage(new \JsonSchema\Uri\UriRetriever(), new \JsonSchema\Uri\UriResolver());
        $schema->resolve($resolver);

        return $schema->validate($json, $validator);
    }
}
