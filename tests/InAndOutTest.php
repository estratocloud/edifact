<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Message;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;

class InAndOutTest extends TestCase
{

    public function messageProvider()
    {
        $path = __DIR__ . "/data";
        yield ["{$path}/multibyte.edi"];
        yield ["{$path}/order.edi"];
        yield ["{$path}/wikipedia.edi"];
    }
    /**
     * @dataProvider messageProvider
     */
    public function testFormat($file)
    {
        $output = (string) Message::fromFile($file);

        $message = (string) file_get_contents($file);
        $expected = str_replace("\n", "", $message);

        $this->assertSame($expected, $output);
    }
}
