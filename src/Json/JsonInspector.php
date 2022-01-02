<?php
declare(strict_types=1);

namespace Behatch\Json;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class JsonInspector
{
    private string $evaluationMode;

    private PropertyAccessor $accessor;

    public function __construct(string $evaluationMode)
    {
        $this->evaluationMode = $evaluationMode;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @throws \Exception
     */
    public function evaluate(Json $json, $expression)
    {
        if ($this->evaluationMode === 'javascript') {
            $expression = \str_replace('->', '.', $expression);
        }

        try {
            return $json->read($expression, $this->accessor);
        } catch (\Exception) {
            throw new \Exception("Failed to evaluate expression '$expression'");
        }
    }

    /**
     * @throws \Exception
     */
    public function validate(Json $json, JsonSchema $schema): bool
    {
        $validator = new \JsonSchema\Validator();

        $resolver = new \JsonSchema\SchemaStorage(new \JsonSchema\Uri\UriRetriever, new \JsonSchema\Uri\UriResolver);
        $schema->resolve($resolver);

        return $schema->validate($json, $validator);
    }
}
