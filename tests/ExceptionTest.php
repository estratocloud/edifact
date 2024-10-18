<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Exceptions\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function glob;
use function is_array;
use function pathinfo;

class ExceptionTest extends TestCase
{
    /**
     * @return iterable<array<string>>
     */
    public function exceptionProvider(): iterable
    {
        $files = glob(__DIR__ . "/../src/Exceptions/*.php");
        assert(is_array($files));

        foreach ($files as $file) {
            yield [$file];
        }
    }


    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptionInterface(string $file): void
    {
        $className = pathinfo($file, \PATHINFO_FILENAME);
        $class = "Estrato\\Edifact\\Exceptions\\{$className}";

        $reflection = new \ReflectionClass($class);
        $result = $reflection->implementsInterface(Exception::class);

        $this->assertTrue($result, "{$className} doesn't implement the exception interface");
    }
}
