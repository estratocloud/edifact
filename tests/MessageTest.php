<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Message;
use Estrato\Edifact\Segments\Segment;
use PHPUnit\Framework\TestCase;

use function is_array;
use function iterator_to_array;

class MessageTest extends TestCase
{

    public function testFromFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to read the file: /no/such/file");
        error_reporting(\E_ALL ^ \E_WARNING);
        Message::fromFile("/no/such/file");
    }


    public function testCreateWithSegments(): void
    {
        $message = Message::fromSegments(new Segment("36CF"));
        $this->assertEquals([new Segment("36CF")], $message->getAllSegments());
    }


    public function testGetSegments(): void
    {
        $message = Message::fromSegments(
            new Segment("36CF", 1),
            new Segment("CPD"),
            new Segment("36CF", 2)
        );

        $result = $message->getSegments("36CF");
        $segments = is_array($result) ? $result : iterator_to_array($result);

        $this->assertEquals([
            new Segment("36CF", 1),
            new Segment("36CF", 2),
        ], $segments);
    }


    public function testGetSegmentsDoesntExist(): void
    {
        $message = new Message();

        $result = $message->getSegments("36CF");
        $segments = is_array($result) ? $result : iterator_to_array($result);

        $this->assertSame([], $segments);
    }


    public function testGetSegment(): void
    {
        $message = Message::fromSegments(
            new Segment("36CF", 1),
            new Segment("36CF", 2)
        );

        $segment = $message->getSegment("36CF");

        $this->assertEquals(new Segment("36CF", 1), $segment);
    }


    public function testGetSegmentDoesntExist(): void
    {
        $message = new Message();

        $segment = $message->getSegment("36CF");

        $this->assertNull($segment);
    }
}
