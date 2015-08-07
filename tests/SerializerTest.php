<?php

namespace Metroplex\Edifact\Tests;

use Metroplex\Edifact\Segment;
use Metroplex\Edifact\Serializer;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    protected $serializer;

    public function setUp()
    {
        $this->serializer = new Serializer;
    }

    protected function assertSegments($expected, array $segments)
    {
        $expected = "UNA:+,? '" . $expected . "'";

        $message = $this->serializer->serialize($segments);

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
}
