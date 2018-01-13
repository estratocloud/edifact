<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Message;
use Metroplex\Edifact\Segment;

class MessageTest extends \PHPUnit_Framework_TestCase
{

    public function testFromFile()
    {
        $this->setExpectedException("InvalidArgumentException", "Unable to read the file: /no/such/file");
        error_reporting(\E_ALL ^ \E_WARNING);
        Message::fromFile("/no/such/file");
    }


    public function testCreateWithSegments()
    {
        $message = Message::fromSegments([
            new Segment("36CF"),
        ]);
        $this->assertEquals([
            new Segment("36CF"),
        ], $message->getAllSegments());
    }


    public function testGetSegments()
    {
        $message = Message::fromSegments([
            new Segment("36CF", 1),
            new Segment("CPD"),
            new Segment("36CF", 2),
        ]);

        $result = $message->getSegments("36CF");
        $segments = iterator_to_array($result);

        $this->assertEquals([
            new Segment("36CF", 1),
            new Segment("36CF", 2),
        ], $segments);
    }


    public function testGetSegmentsDoesntExist()
    {
        $message = new Message;

        $result = $message->getSegments("36CF");
        $segments = iterator_to_array($result);

        $this->assertSame([], $segments);
    }


    public function testGetSegment()
    {
        $message = Message::fromSegments([
            new Segment("36CF", 1),
            new Segment("36CF", 2),
        ]);

        $segment = $message->getSegment("36CF");

        $this->assertEquals(new Segment("36CF", 1), $segment);
    }


    public function testGetSegmentDoesntExist()
    {
        $message = new Message;

        $segment = $message->getSegment("36CF");

        $this->assertNull($segment);
    }
}
