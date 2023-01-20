<?php

namespace Behatch\Tests\Units\Context;

use Behat\Gherkin\Node\TableNode;
use Behatch\HttpCall\HttpCallResult;
use Behatch\HttpCall\HttpCallResultPool;

class JsonContext extends \atoum
{
    /**
     * @var HttpCallResultPool
     */
    private $httpCallResultPool;

    public function beforeTestMethod($methodName): void
    {
        $this->mockGenerator->orphanize('__construct');
        $httpCallResult = $this->newMockInstance(HttpCallResult::class);
        $httpCallResult->getMockController()->getValue = json_encode([
            'a string node' => 'some string',
            'another string node' => 'some other string',
            'a null node' => null,
            'a true node' => true,
            'a false node' => false,
            'a number node' => 3,
            'an array node' => [
                'one',
                'two',
                'three',
            ],
        ]);

        $this->httpCallResultPool = $this->newMockInstance(HttpCallResultPool::class);
        $this->httpCallResultPool->getMockController()->getResult = $httpCallResult;
    }

    public function testTheJsonNodeShouldBeEqualTo(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeEqualTo('a string node', 'some string'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeEqualTo('a string node', 'expectedstring');
            })
                ->hasMessage("The node 'a string node' value is '\"some string\"', 'expectedstring' expected");
    }

    public function testTheJsonNodesShouldBeEqualTo(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
                ->and($validTableNode = new TableNode([
                    1 => ['a string node', 'some string'],
                    2 => ['another string node', 'some other string'],
                ]))
            ->then

            ->if($this->testedInstance->theJsonNodesShouldBeEqualTo($validTableNode))

            ->exception(function (): void {
                $invalidTableNode = new TableNode([
                    1 => ['a string node', 'may the force'],
                    2 => ['another string node', 'be with you'],
                ]);
                $this->testedInstance->theJsonNodesShouldBeEqualTo($invalidTableNode);
            })
                ->hasMessage("The node 'a string node' value is '\"some string\"', 'may the force' expected\nThe node 'another string node' value is '\"some other string\"', 'be with you' expected");
    }

    public function testTheJsonNodeShouldMatch(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldMatch('a string node', '/some/'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldMatch('a string node', '/nomatch/');
            })
                ->hasMessage("The node 'a string node' value is '\"some string\"', '/nomatch/' pattern expected");
    }

    public function testTheJsonNodeShouldBeNull(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeNull('a null node'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeNull('a string node');
            })
                ->hasMessage("The node 'a string node' value is '\"some string\"', null expected");
    }

    public function testTheJsonNodeShouldNotBeNull(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldNotBeNull('a string node'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldNotBeNull('a null node');
            })
                ->hasMessage("The node 'a null node' value is null, non-null value expected");
    }

    public function testTheJsonNodeShouldBeTrue(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeTrue('a true node'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeTrue('a false node');
            })
                ->hasMessage("The node 'a false node' value is 'false', 'true' expected");
    }

    public function testTheJsonNodeShouldBeFalse(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeFalse('a false node'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeFalse('a true node');
            })
                ->hasMessage("The node 'a true node' value is 'true', 'false' expected");
    }

    public function testTheJsonNodeShouldBeEqualToTheString(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeEqualToTheString('a string node', 'some string'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeEqualToTheString('a string node', 'expected');
            })
                ->hasMessage("The node 'a string node' value is '\"some string\"', string 'expected' expected");
    }

    public function testTheJsonNodeShouldBeEqualToTheNumber(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldBeEqualToTheNumber('a number node', 3))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldBeEqualToTheNumber('a number node', 2);
            })
                ->hasMessage("The node 'a number node' value is '3', number '2' expected");
    }

    public function testTheJsonNodeShouldExist(): void
    {
        $this
            ->given($this->newTestedInstance($this->httpCallResultPool))
            ->then

            ->if($this->testedInstance->theJsonNodeShouldExist('a string node'))

            ->exception(function (): void {
                $this->testedInstance->theJsonNodeShouldExist('invalid key');
            })
                ->hasMessage("The node 'invalid key' does not exist.");
    }
}
