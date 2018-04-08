<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Control\Tradacoms;
use Estrato\Edifact\Message;
use Estrato\Edifact\Parser;
use Estrato\Edifact\Serializer;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;

class InAndOutTest extends TestCase
{
    /**
     * @return iterable<array<string>>
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


    public function testTradacoms1(): void
    {
        $message = (string) file_get_contents(__DIR__ . "/data/tradacoms.edi");

        $characters = new Tradacoms();
        $segments = (new Parser())->parse($message, $characters);

        $output = (new Serializer($characters))->serialize(...$segments);
        $output = preg_replace("/^UNA.{5}'/", "", $output);

        $expected = str_replace("\n", "", $message);

        $this->assertSame($expected, $output);
    }
}
