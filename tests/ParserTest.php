<?php

namespace Metroplex\Edifact\Tests;

use Metroplex\Edifact\Parser;
use Metroplex\Edifact\Segment;
use Metroplex\Edifact\Tokenizer;
use Mockery;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser;
    }


    public function setupSpecialCharacters(&$message, Tokenizer $tokenizer = null)
    {
        if ($tokenizer === null) {
            $tokenizer = Mockery::mock(Tokenizer::class);
            $tokenizer->shouldReceive("setComponentSeparator")->once()->with("1");
            $tokenizer->shouldReceive("setDataSeparator")->once()->with("2");
            $tokenizer->shouldReceive("setDecimalPoint")->once()->with("3");
            $tokenizer->shouldReceive("setEscapeCharacter")->once()->with("4");
            $tokenizer->shouldReceive("setSegmentTerminator")->once()->with("6");
        }

        $class = new \ReflectionClass($this->parser);

        $method = $class->getMethod("setupSpecialCharacters");

        $method->setAccessible(true);

        return $method->invokeArgs($this->parser, [&$message, $tokenizer]);
    }


    public function testSetupSpecialCharacters1()
    {
        $message = "TEST";

        $tokenizer = Mockery::mock(Tokenizer::class);

        $this->setupSpecialCharacters($message, $tokenizer);

        $this->assertSame("TEST", $message);
    }


    public function testSetupSpecialCharacters2()
    {
        $message = "UNA123456";

        $this->setupSpecialCharacters($message);
        $this->assertSame("", $message);
    }


    public function testSetupSpecialCharacters3()
    {
        $message = "UNA123456TEST";

        $this->setupSpecialCharacters($message);
        $this->assertSame("TEST", $message);
    }


    public function testSetupSpecialCharacters4()
    {
        $message = "UNA123456\nTEST";

        $this->setupSpecialCharacters($message);
        $this->assertSame("TEST", $message);
    }


    public function testSetupSpecialCharacters5()
    {
        $message = "UNA123456\r\nTEST";

        $this->setupSpecialCharacters($message);
        $this->assertSame("TEST", $message);
    }


    private function assertSegments($message, array $segments)
    {
        $input = "UNA:+,? '\n";
        $input .= $message . "'\n";

        $result = $this->parser->parse($input);
        $result = iterator_to_array($result);

        $this->assertEquals($segments, $result);
    }


    public function testBasic1()
    {
        $this->assertSegments("RFF+PD:50515", [
            new Segment("RFF", ["PD", "50515"]),
        ]);
    }
    public function testBasic2()
    {
        $this->assertSegments("RFF+PD+50515", [
            new Segment("RFF", "PD", "50515"),
        ]);
    }


    public function testEscapeCharacter()
    {
        $this->assertSegments("ERC+10:The message does not make sense??", [
            new Segment("ERC", ["10", "The message does not make sense?"]),
        ]);
    }


    public function testEscapeComponentSeparator()
    {
        $this->assertSegments("ERC+10:Name?: Craig", [
            new Segment("ERC", ["10", "Name: Craig"]),
        ]);
    }


    public function testEscapeDataSeparator()
    {
        $this->assertSegments("DTM+735:?+0000:406", [
            new Segment("DTM", ["735", "+0000", "406"]),
        ]);
    }


    public function testEscapeDecimalPoint()
    {
        $this->assertSegments("QTY+136:12,235", [
            new Segment("QTY", ["136", "12,235"]),
        ]);
    }


    public function testEscapeSegmentTerminator()
    {
        $this->assertSegments("ERC+10:Craig?'s", [
            new Segment("ERC", ["10", "Craig's"]),
        ]);
    }


    public function testEscapeSequence()
    {
        $this->assertSegments("ERC+10:?:?+???' - ?:?+???' - ?:?+???'", [
            new Segment("ERC", ["10", ":+?' - :+?' - :+?'"]),
        ]);
    }
}
