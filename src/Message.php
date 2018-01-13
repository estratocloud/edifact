<?php

namespace Metroplex\Edifact;

/**
 * Represent an EDI message for both reading and writing.
 */
final class Message
{
    /**
     * @var Segment[] $segments The segments that make up this message.
     */
    private $segments = [];


    /**
     * Create a new instance from a file.
     *
     * @param string $file The full path to a file that contains an EDI message
     *
     * @return static
     */
    public static function fromFile($file)
    {
        $message = file_get_contents($file);
        if ($message === false) {
            throw new \InvalidArgumentException("Unable to read the file: {$file}");
        }

        return static::fromString($message);
    }


    /**
     * Create a new instance from a string.
     *
     * @param string $string The EDI message content
     *
     * @return static
     */
    public static function fromString($string)
    {
        $segments = (new Parser)->parse($string);
        return static::fromSegments($segments);
    }


    /**
     * Create a new instance from an array of segments.
     *
     * @param Segment[] $segments The segments of the message
     *
     * @return static
     */
    public static function fromSegments($segments)
    {
        return (new static)->addSegments($segments);
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


    /**
     * Serialize all the segments added to this object.
     *
     * @return string
     */
    public function serialize()
    {
        return (new Serializer)->serialize($this->getAllSegments());
    }


    /**
     * Allow the object to be serialized by casting to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->serialize();
    }
}
