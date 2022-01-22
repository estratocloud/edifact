<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Control\Characters;
use Estrato\Edifact\Segments\Segment;
use Estrato\Edifact\Segments\SegmentInterface;
use Estrato\Edifact\Serializer;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    /**
     * @var Serializer $serializer The instance we are testing.
     */
    private $serializer;

    public function setUp(): void
    {
        $this->serializer = new Serializer();
    }


    /**
     * @param string $expected
     * @param array<SegmentInterface> $segments
     */
    private function assertSegments(string $expected, array $segments): void
    {
        $expected = "UNA:+,? '" . $expected . "'";

        $message = $this->serializer->serialize(...$segments);

        $this->assertEquals($expected, $message);
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


    public function testReservedSpace(): void
    {
        $characters = (new Characters())->withReservedSpace("*");
        $serializer = new Serializer($characters);
        $message = $serializer->serialize();
        $this->assertSame("UNA:+,?*'", $message);
    }


    public function testEscapeSequence(): void
    {
        $this->assertSegments("ERC+10:?:?+???' - ?:?+???' - ?:?+???'", [
            new Segment("ERC", ["10", ":+?' - :+?' - :+?'"]),
        ]);
    }
}
