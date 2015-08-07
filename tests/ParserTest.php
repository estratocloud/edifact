<?php

namespace Metroplex\Edifact\Tests;

use Metroplex\Edifact\Parser;
use Metroplex\Edifact\Tokenizer;
use Mockery;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

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
}
