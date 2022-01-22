<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Message;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;

class InAndOutTest extends TestCase
{
    /**
     * @return iterable<array>
     */
    public function messageProvider(): iterable
    {
        $path = __DIR__ . "/data";
        yield ["{$path}/multibyte.edi"];
        yield ["{$path}/order.edi"];
        yield ["{$path}/wikipedia.edi"];
    }


    /**
     * @dataProvider messageProvider
     */
    public function testFormat(string $file): void
    {
        $output = (string) Message::fromFile($file);

        $message = (string) file_get_contents($file);
        $expected = str_replace("\n", "", $message);

        $this->assertSame($expected, $output);
    }
}
