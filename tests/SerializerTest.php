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

    /**
     * @dataProvider segments
     */
    public function testSerializer($expected, $segments)
    {
        $this->assertSegments($expected, $segments);
    }

    public function segments()
    {
        return [
            [
                "DTM+735:?+0000:406",
                [
                    new Segment("DTM", ["735", "+0000", "406"])
                ]
            ],
            [
                "QTY+136:12,235",
                [
                    new Segment("QTY", ["136", "12,235"]),
                ]
            ],
            [
                "ERC+10:The message does not make sense??",
                [
                    new Segment("ERC", ["10", "The message does not make sense?"]),
                ]
            ],
            [
                "RFF+PD:50515",
                [
                    new Segment("RFF", ["PD", "50515"])
                ]
            ],
            [
                "RFF+PD+50515",
                [
                    new Segment("RFF", "PD", "50515"),
                ]
            ]
        ];
    }
}
