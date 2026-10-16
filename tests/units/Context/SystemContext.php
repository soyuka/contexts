<?php

namespace Behatch\Tests\Units\Context;

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
}
