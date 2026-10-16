<?php

namespace Behatch\Tests\Units\Context;

use Behat\Gherkin\Node\PyStringNode;

class SystemContext extends \atoum
{
    public function testCommandShouldSucceed(): void
    {
        $this
            ->given($this->newTestedInstance())
            ->and($this->testedInstance->iExecute('true'))
            ->then

            ->if($this->testedInstance->commandShouldSucceed())

            ->exception(function (): void {
                $this->testedInstance->iExecute('exit 2');
                $this->testedInstance->commandShouldSucceed();
            })
                ->hasMessage('Command should succeed 2');
    }

    public function testCommandShouldFail(): void
    {
        $this
            ->given($this->newTestedInstance())
            ->and($this->testedInstance->iExecute('exit 2'))
            ->then

            ->if($this->testedInstance->commandShouldFail())

            ->exception(function (): void {
                $this->testedInstance->iExecute('true');
                $this->testedInstance->commandShouldFail();
            })
                ->hasMessage('Command should fail 0');
    }

    public function testOutputShouldBe(): void
    {
        $this
            ->given($this->newTestedInstance())
            ->and($this->testedInstance->iExecute("printf 'Hello world\\nHow are you?\\n'"))
            ->then

            ->if($this->testedInstance->outputShouldBe(new PyStringNode(['Hello world', 'How are you?'], 1)))
            ->and($this->testedInstance->outputShouldBe(new PyStringNode(['Hello world', 'How are you?', 'Fine'], 1)))

            ->exception(function (): void {
                $this->testedInstance->outputShouldBe(new PyStringNode(['Hello John', 'How are you?'], 1));
            })
                ->hasMessage("instead of\nHello world\nHow are you?")

            ->exception(function (): void {
                $this->testedInstance->outputShouldBe(new PyStringNode(['Hello world'], 1));
            })
                ->hasMessage("instead of\nHello world\nHow are you?");
    }

    public function testOutputShouldNotBe(): void
    {
        $this
            ->given($this->newTestedInstance())
            ->and($this->testedInstance->iExecute("printf 'Hello world\\nHow are you?\\n'"))
            ->then

            ->if($this->testedInstance->outputShouldNotBe(new PyStringNode(['Hello John', 'How are you?'], 1)))
            ->and($this->testedInstance->outputShouldNotBe(new PyStringNode(['Hello world'], 1)))

            ->exception(function (): void {
                $this->testedInstance->outputShouldNotBe(new PyStringNode(['Hello world', 'How are you?'], 1));
            })
                ->hasMessage('Output should not be')

            ->exception(function (): void {
                $this->testedInstance->outputShouldNotBe(new PyStringNode(['Hello world', 'How are you?', 'Fine'], 1));
            })
                ->hasMessage('Output should not be');
    }
}
