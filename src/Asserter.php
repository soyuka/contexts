<?php
declare(strict_types=1);

namespace Behatch;

use Behat\Mink\Exception\ExpectationException;

trait Asserter
{
    /**
     * @throws ExpectationException
     */
    protected function not(callable $callable, string $errorMessage): void
    {
        try {
            $callable();
        } catch (\Exception) {
            return;
        }

        throw new ExpectationException($errorMessage, $this->getSession()->getDriver());
    }

    /**
     * @throws ExpectationException
     */
    protected function assert($test, string $message): void
    {
        if ($test === false) {
            throw new ExpectationException($message, $this->getSession()->getDriver());
        }
    }

    /**
     * @throws ExpectationException
     */
    protected function assertContains($expected, $actual, string $message = null): void
    {
        $regex = '/' . \preg_quote($expected, '/') . '/ui';

        $this->assert(
            \preg_match($regex, $actual) > 0,
            $message ?: "The string '$expected' was not found."
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertNotContains($expected, $actual, string $message = null): void
    {
        $message = $message ?: "The string '$expected' was found.";

        $this->not(
            function () use ($expected, $actual) {
                $this->assertContains($expected, $actual);
            },
            $message
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertCount($expected, array $elements, string $message = null): void
    {
        $this->assert(
            (int)$expected === \count($elements),
            $message ?: \sprintf('%d elements found, but should be %d.', \count($elements), $expected)
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertEquals($expected, $actual, string $message = null): void
    {
        $this->assert(
            $expected === $actual,
            $message ?: "The element '$actual' is not equal to '$expected'"
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertSame($expected, $actual, string $message = null): void
    {
        $this->assert(
            $expected === $actual,
            $message ?: "The element '$actual' is not equal to '$expected'"
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertArrayHasKey($key, $array, string $message = null): void
    {
        $this->assert(
            isset($array[$key]),
            $message ?: "The array has no key '$key'"
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertArrayNotHasKey($key, $array, string $message = null): void
    {
        $message = $message ?: "The array has key '$key'";

        $this->not(
            function () use ($key, $array) {
                $this->assertArrayHasKey($key, $array);
            },
            $message
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertTrue($value, string $message = 'The value is false'): void
    {
        $this->assert($value, $message);
    }

    /**
     * @throws ExpectationException
     */
    protected function assertFalse($value, string $message = 'The value is true'): void
    {
        $this->not(
            function () use ($value) {
                $this->assertTrue($value);
            },
            $message
        );
    }

    /**
     * @throws ExpectationException
     */
    protected function assertNull($value, $message = 'The value is not null'): void
    {
        $this->assert($value === null, $message);
    }
}
