<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Exceptions\Exception;
use Symfony\Component\Finder\Finder;

class ExceptionTest extends \PHPUnit_Framework_TestCase
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
