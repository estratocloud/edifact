<?php

namespace Metroplex\Edifact;

/**
 * Represent an EDI message.
 */
class Message
{
    /**
     * @var Segment[] $segments The segments that make up this message.
     */
    protected $segments = [];


    /**
     * Create a new instance.
     *
     * @param string $message The EDI message
     */
    public function __construct($input = null)
    {
        if (is_string($input)) {
            $segments = (new Parser)->parse($input);
            $this->addSegments($segments);
        } elseif ($input !== null) {
            $this->addSegments($input);
        }
    }


    /**
     * Get all the segments.
     *
     * @return Segment[]
     */
    public function getAllSegments()
    {
        return $this->segments;
    }


    /**
     * Get all the segments that match the requested name.
     *
     * @param string $name The name of the segment to return
     *
     * @return Segment[]
     */
    public function getSegments($name)
    {
        foreach ($this->getAllSegments() as $segment) {
            if ($segment->getName() === $name) {
                yield $segment;
            }
        }
    }


    /**
     * Get the first segment that matches the requested name.
     *
     * @param string $name The name of the segment to return
     *
     * @return Segment
     */
    public function getSegment($name)
    {
        foreach ($this->getSegments($name) as $segment) {
            return $segment;
        }
    }


    /**
     * Add multiple segments to the message.
     *
     * @param Segment[] $segments The segments to add
     *
     * @return static
     */
    public function addSegments($segments)
    {
        foreach ($segments as $segment) {
            $this->addSegment($segment);
        }

        return $this;
    }


    /**
     * Add a segment to the message.
     *
     * @param Segment $segment The segment to add
     *
     * @return static
     */
    public function addSegment(Segment $segment)
    {
        $this->segments[] = $segment;

        return $this;
    }
}
