<?php
declare(strict_types=1);

namespace Behatch\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\HttpCall\HttpCallResultPool;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;
use Behatch\Json\JsonSchema;

class JsonContext extends BaseContext
{
    protected const NODE_VALUE_IS = 'The node value is `%s`';

    protected JsonInspector $inspector;
    protected HttpCallResultPool $httpCallResultPool;

    public function __construct(HttpCallResultPool $httpCallResultPool, string $evaluationMode = 'javascript')
    {
        $this->inspector = new JsonInspector($evaluationMode);
        $this->httpCallResultPool = $httpCallResultPool;
    }

    /**
     * Checks, that the response is correct JSON
     *
     * @Then the response should be in JSON
     * @throws \JsonException
     */
    public function theResponseShouldBeInJson(): void
    {
        $this->getJson();
    }

    /**
     * Checks, that the response is not correct JSON
     *
     * @Then the response should not be in JSON
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theResponseShouldNotBeInJson(): void
    {
        $this->not(
            [$this, 'theResponseShouldBeInJson'],
            'The response is in JSON'
        );
    }

    /**
     * Checks, that given JSON node is equal to given value
     *
     * @Then the JSON node :node should be equal to :text
     * @throws \Exception
     */
    public function theJsonNodeShouldBeEqualTo($node, $text): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== $text) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON nodes are equal to givens values
     *
     * @Then the JSON nodes should be equal to:
     * @throws \Exception
     */
    public function theJsonNodesShouldBeEqualTo(TableNode $nodes): void
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldBeEqualTo($node, $text);
        }
    }

    /**
     * Checks, that given JSON node matches given pattern
     *
     * @Then the JSON node :node should match :pattern
     * @throws \Exception
     */
    public function theJsonNodeShouldMatch($node, $pattern): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if (\preg_match($pattern, $actual) === 0) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON node is null
     *
     * @Then the JSON node :node should be null
     * @throws \Exception
     */
    public function theJsonNodeShouldBeNull($node): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        $this->assertNull($actual, sprintf(self::NODE_VALUE_IS, json_encode($actual, JSON_THROW_ON_ERROR)));
    }

    /**
     * Checks, that given JSON node is not null.
     *
     * @Then the JSON node :node should not be null
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theJsonNodeShouldNotBeNull($node): void
    {
        $this->not(
            function () use ($node) {
                $this->theJsonNodeShouldBeNull($node);
            },
            \sprintf('The node %s should not be null', $node)
        );
    }

    /**
     * Checks, that given JSON node is true
     *
     * @Then the JSON node :node should be true
     * @throws \Exception
     */
    public function theJsonNodeShouldBeTrue($node): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== true) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON node is false
     *
     * @Then the JSON node :node should be false
     * @throws \Exception
     */
    public function theJsonNodeShouldBeFalse($node): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== false) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON node is equal to the given string
     *
     * @Then the JSON node :node should be equal to the string :text
     * @throws \Exception
     */
    public function theJsonNodeShouldBeEqualToTheString($node, $text): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== $text) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON node is equal to the given number
     *
     * @Then the JSON node :node should be equal to the number :number
     * @throws \Exception
     */
    public function theJsonNodeShouldBeEqualToTheNumber($node, $number): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== (float)$number && $actual !== (int)$number) {
            throw new \Exception(
                \sprintf(self::NODE_VALUE_IS, \json_encode($actual, JSON_THROW_ON_ERROR))
            );
        }
    }

    /**
     * Checks, that given JSON node has N element(s)
     *
     * @Then the JSON node :node should have :count element(s)
     * @throws \Exception
     */
    public function theJsonNodeShouldHaveElements($node, $count): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        $this->assertCount($count, (array)$actual);
    }

    /**
     * Checks, that given JSON node contains given value
     *
     * @Then the JSON node :node should contain :text
     * @throws \Exception
     */
    public function theJsonNodeShouldContain($node, $text): void
    {
        $json = $this->getJson();
        $actual = $this->inspector->evaluate($json, $node);

        $this->assertContains($text, (string)$actual);
    }

    /**
     * Checks, that given JSON nodes contains values
     *
     * @Then the JSON nodes should contain:
     * @throws \Exception
     */
    public function theJsonNodesShouldContain(TableNode $nodes): void
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node does not contain given value
     *
     * @Then the JSON node :node should not contain :text
     * @throws \Exception
     */
    public function theJsonNodeShouldNotContain($node, $text): void
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        $this->assertNotContains($text, (string)$actual);
    }

    /**
     * Checks, that given JSON nodes does not contain given value
     *
     * @Then the JSON nodes should not contain:
     * @throws \Exception
     */
    public function theJsonNodesShouldNotContain(TableNode $nodes): void
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldNotContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node exist
     *
     * @Then the JSON node :name should exist
     * @throws \Exception
     */
    public function theJsonNodeShouldExist($name)
    {
        $json = $this->getJson();

        try {
            $node = $this->inspector->evaluate($json, $name);
        } catch (\Exception) {
            throw new \Exception("The node '$name' does not exist.");
        }

        return $node;
    }

    /**
     * Checks, that given JSON node does not exist
     *
     * @Then the JSON node :name should not exist
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theJsonNodeShouldNotExist($name): void
    {
        $this->not(
            function () use ($name) {
                return $this->theJsonNodeShouldExist($name);
            },
            "The node '$name' exists."
        );
    }

    /**
     * @Then the JSON should be valid according to this schema:
     * @throws \JsonException
     * @throws \Exception
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $schema): void
    {
        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema($schema)
        );
    }

    /**
     * @Then the JSON should be invalid according to this schema:
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theJsonShouldBeInvalidAccordingToThisSchema(PyStringNode $schema): void
    {
        $this->not(
            function () use ($schema) {
                $this->theJsonShouldBeValidAccordingToThisSchema($schema);
            },
            'Expected to receive invalid json, got valid one'
        );
    }

    /**
     * @Then the JSON should be valid according to the schema :filename
     * @throws \JsonException
     * @throws \Exception
     */
    public function theJsonShouldBeValidAccordingToTheSchema($filename): void
    {
        $this->checkSchemaFile($filename);

        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema(
                \file_get_contents($filename),
                'file://' . \str_replace(DIRECTORY_SEPARATOR, '/', \realpath($filename))
            )
        );
    }

    /**
     * @Then the JSON should be invalid according to the schema :filename
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theJsonShouldBeInvalidAccordingToTheSchema($filename): void
    {
        $this->checkSchemaFile($filename);

        $this->not(
            function () use ($filename) {
                $this->theJsonShouldBeValidAccordingToTheSchema($filename);
            },
            "The schema was valid"
        );
    }

    /**
     * @Then the JSON should be equal to:
     * @throws \Exception
     */
    public function theJsonShouldBeEqualTo(PyStringNode $content): void
    {
        $actual = $this->getJson();

        try {
            $expected = new Json($content);
        } catch (\Exception) {
            throw new \Exception('The expected JSON is not a valid');
        }

        $this->assertSame(
            (string)$expected,
            (string)$actual,
            "The json is equal to:\n" . $actual->encode()
        );
    }

    /**
     * @Then print last JSON response
     * @throws \JsonException
     */
    public function printLastJsonResponse(): void
    {
        echo $this->getJson()->encode();
    }

    /**
     * Checks, that response JSON matches with a swagger dump
     *
     * @Then the JSON should be valid according to swagger :dumpPath dump schema :schemaName
     * @throws \JsonException
     * @throws \Exception
     */
    public function theJsonShouldBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName): void
    {
        $this->checkSchemaFile($dumpPath);

        $dumpJson = \file_get_contents($dumpPath);
        $schemas = \json_decode($dumpJson, true, 512, JSON_THROW_ON_ERROR);
        $definition = \json_encode($schemas['definitions'][$schemaName], JSON_THROW_ON_ERROR);
        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema(
                $definition
            )
        );
    }

    /**
     * Checks, that response JSON not matches with a swagger dump
     *
     * @Then the JSON should not be valid according to swagger :dumpPath dump schema :schemaName
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function theJsonShouldNotBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName): void
    {
        $this->not(
            function () use ($dumpPath, $schemaName) {
                $this->theJsonShouldBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName);
            },
            'JSON Schema matches but it should not'
        );
    }

    /**
     * @throws \JsonException
     */
    protected function getJson(): Json
    {
        return new Json($this->httpCallResultPool->getResult()->getValue());
    }

    private function checkSchemaFile($filename): void
    {
        if (\is_file($filename) === false) {
            throw new \RuntimeException(
                'The JSON schema doesn\'t exist'
            );
        }
    }
}
