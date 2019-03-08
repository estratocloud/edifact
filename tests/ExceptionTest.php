<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Exceptions\Exception;
use function glob;
use function pathinfo;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{

    public function exceptionProvider()
    {
        $files = glob(__DIR__ . "/../src/Exceptions/*.php");
        foreach ($files as $file) {
            yield [$file];
        }
    }
    /**
     * @dataProvider exceptionProvider
     */
    public function testExceptionInterface($file)
    {
        $className = pathinfo($file, \PATHINFO_FILENAME);
        $class = "Metroplex\\Edifact\\Exceptions\\{$className}";

        $reflection = new \ReflectionClass($class);
        $result = $reflection->implementsInterface(Exception::class);

        $this->assertTrue($result, "{$className} doesn't implement the exception interface");
    }
}
