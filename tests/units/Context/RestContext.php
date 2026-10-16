<?php

namespace Behatch\Tests\Units\Context;

use Behat\Mink\Mink;
use Behatch\HttpCall\Request;

class RestContext extends \atoum
{
    public function testPrintTheCorrespondingCurlCommand(): void
    {
        // Server parameters of a BrowserKit request after "I add "X-Foo-Bar" header equal to "baz""
        $request = new class(new Mink()) extends Request {
            public function __call($name, $arguments)
            {
                return match ($name) {
                    'getMethod' => 'POST',
                    'getUri' => 'http://localhost:8080/rest/index.php',
                    'getParameters' => ['foo' => 'bar'],
                    'getServer' => [
                        'HTTP_USER_AGENT' => 'Symfony BrowserKit',
                        'HTTP_X_FOO_BAR' => 'baz',
                        'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
                        'HTTP_ACCEPT' => null,
                        'HTTP_HOST' => 'localhost:8080',
                        'HTTPS' => false,
                    ],
                    default => throw new \BadMethodCallException($name),
                };
            }
        };

        $this
            ->given($this->newTestedInstance($request))
            ->and(ob_start())
            ->and($this->testedInstance->printTheCorrespondingCurlCommand())
            ->then

            ->string(ob_get_clean())
                ->isEqualTo("curl -X POST --data 'foo=bar' -H 'User-Agent: Symfony BrowserKit' -H 'X-Foo-Bar: baz' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Host: localhost:8080' 'http://localhost:8080/rest/index.php'");
    }
}
