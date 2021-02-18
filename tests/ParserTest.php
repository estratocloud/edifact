<?php

namespace Metroplex\EdifactTests;

use duncan3dc\ObjectIntruder\Intruder;
use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Parser;
use Metroplex\Edifact\Segments\Segment;
use Mockery;
use PHPUnit\Framework\TestCase;

use function is_array;
use function iterator_to_array;

class ParserTest extends TestCase
{
    /**
     * @var Parser $parser The instance we are testing.
     */
    private $parser;

    public function setUp(): void
    {
        $this->parser = new Parser();
    }


    public function getControlCharacters(&$message, ControlCharactersInterface $characters = null)
    {
        if ($characters === null) {
            $characters = Mockery::mock(ControlCharactersInterface::class);
            $characters->shouldReceive("withComponentSeparator")->once()->with("1")->andReturn($characters);
            $characters->shouldReceive("withDataSeparator")->once()->with("2")->andReturn($characters);
            $characters->shouldReceive("withDecimalPoint")->once()->with("3")->andReturn($characters);
            $characters->shouldReceive("withEscapeCharacter")->once()->with("4")->andReturn($characters);
            $characters->shouldReceive("withReservedSpace")->once()->with("5")->andReturn($characters);
            $characters->shouldReceive("withSegmentTerminator")->once()->with("6")->andReturn($characters);
        }

        $parser = new Intruder($this->parser);

        return $parser->_call("getControlCharacters", $message, $characters);
    }


    public function testGetControlCharacters1(): void
    {
        $message = "TEST";

        $characters = Mockery::mock(ControlCharactersInterface::class);

        $this->getControlCharacters($message, $characters);

        $this->assertSame("TEST", $message);
    }


    public function testGetControlCharacters2(): void
    {
        $message = "UNA123456";

        $this->getControlCharacters($message);
        $this->assertSame("", $message);
    }


    public function testGetControlCharacters3(): void
    {
        $message = "UNA123456TEST";

        $this->getControlCharacters($message);
        $this->assertSame("TEST", $message);
    }


    public function testGetControlCharacters4(): void
    {
        $message = "UNA123456\nTEST";

        $this->getControlCharacters($message);
        $this->assertSame("TEST", $message);
    }


    public function testGetControlCharacters5(): void
    {
        $message = "UNA123456\r\nTEST";

        $this->getControlCharacters($message);
        $this->assertSame("TEST", $message);
    }


    private function assertSegments($message, array $segments)
    {
        $input = "UNA:+,? '\n";
        $input .= $message . "'\n";

        $result = $this->parser->parse($input);
        $result = is_array($result) ? $result : iterator_to_array($result);

        $this->assertEquals($segments, $result);
    }


    public function testBasic1(): void
    {
        $this->assertSegments("RFF+PD:50515", [
            new Segment("RFF", ["PD", "50515"]),
        ]);
    }
    public function testBasic2(): void
    {
        $this->assertSegments("RFF+PD+50515", [
            new Segment("RFF", "PD", "50515"),
        ]);
    }


    public function testEscapeCharacter(): void
    {
        $this->assertSegments("ERC+10:The message does not make sense??", [
            new Segment("ERC", ["10", "The message does not make sense?"]),
        ]);
    }


    public function testEscapeComponentSeparator(): void
    {
        $this->assertSegments("ERC+10:Name?: Craig", [
            new Segment("ERC", ["10", "Name: Craig"]),
        ]);
    }


    public function testEscapeDataSeparator(): void
    {
        $this->assertSegments("DTM+735:?+0000:406", [
            new Segment("DTM", ["735", "+0000", "406"]),
        ]);
    }


    public function testEscapeDecimalPoint(): void
    {
        $this->assertSegments("QTY+136:12,235", [
            new Segment("QTY", ["136", "12,235"]),
        ]);
    }


    public function testEscapeSegmentTerminator(): void
    {
        $this->assertSegments("ERC+10:Craig?'s", [
            new Segment("ERC", ["10", "Craig's"]),
        ]);
    }


    public function testEscapeSequence(): void
    {
        $this->assertSegments("ERC+10:?:?+???' - ?:?+???' - ?:?+???'", [
            new Segment("ERC", ["10", ":+?' - :+?' - :+?'"]),
        ]);
    }
}
