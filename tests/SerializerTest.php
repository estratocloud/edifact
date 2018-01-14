<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Segments\Segment;
use Metroplex\Edifact\Serializer;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    private $serializer;

    public function setUp()
    {
        $this->serializer = new Serializer;
    }

    private function assertSegments($expected, array $segments)
    {
        $expected = "UNA:+,? '" . $expected . "'";

        $message = $this->serializer->serialize(...$segments);

        $this->assertEquals($expected, $message);
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
