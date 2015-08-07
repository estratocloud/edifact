<?php

namespace Metroplex\Edifact\Tests;

use Metroplex\Edifact\Segment;

class SegmentTest extends \PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $segment = new Segment("OMD");
        $this->assertSame("OMD", $segment->getName());
    }


    public function testGetAllElements()
    {
        $elements = [
            "field1",
            ["field2", "extra"],
            "stuff",
        ];
        $segment = new Segment("OMD", ...$elements);
        $this->assertSame($elements, $segment->getAllElements());
    }


    public function testGetElement1()
    {
        $elements = [
            "field1",
            ["field2", "extra"],
            "stuff",
        ];
        $segment = new Segment("OMD", ...$elements);
        $this->assertSame("field1", $segment->getElement(0));
    }
    public function testGetElement2()
    {
        $elements = [
            "field1",
            ["field2", "extra"],
            "stuff",
        ];
        $segment = new Segment("OMD", ...$elements);
        $this->assertSame(["field2", "extra"], $segment->getElement(1));
    }
    public function testGetElement3()
    {
        $elements = [
            "field1",
            ["field2", "extra"],
            "stuff",
        ];
        $segment = new Segment("OMD", ...$elements);
        $this->assertNull($segment->getElement(7));
    }
}
