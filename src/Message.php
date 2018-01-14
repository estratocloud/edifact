<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Exceptions\InvalidArgumentException;
use Metroplex\Edifact\Segments\SegmentInterface;

/**
 * Represent an EDI message for both reading and writing.
 */
final class Message
{
    /**
     * @var SegmentInterface[] $segments The segments that make up this message.
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
            throw new InvalidArgumentException("Unable to read the file: {$file}");
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
        return static::fromSegments(...$segments);
    }


    /**
     * Create a new instance from an array of segments.
     *
     * @param SegmentInterface[] $segments The segments of the message
     *
     * @return static
     */
    public static function fromSegments(SegmentInterface ...$segments)
    {
        return (new static)->addSegments(...$segments);
    }


    /**
     * Get all the segments.
     *
     * @return SegmentInterface[]
     */
    public function getAllSegments()
    {
        return $this->segments;
    }


    /**
     * Get all the segments that match the requested code.
     *
     * @param string $code The code of the segment to return
     *
     * @return SegmentInterface[]
     */
    public function getSegments($code)
    {
        foreach ($this->getAllSegments() as $segment) {
            if ($segment->getSegmentCode() === $code) {
                yield $segment;
            }
        }
    }


    /**
     * Get the first segment that matches the requested code.
     *
     * @param string $code The code of the segment to return
     *
     * @return SegmentInterface
     */
    public function getSegment($code)
    {
        foreach ($this->getSegments($code) as $segment) {
            return $segment;
        }
    }


    /**
     * Add multiple segments to the message.
     *
     * @param SegmentInterface[] $segments The segments to add
     *
     * @return static
     */
    public function addSegments(SegmentInterface ...$segments)
    {
        foreach ($segments as $segment) {
            $this->addSegment($segment);
        }

        return $this;
    }


    /**
     * Add a segment to the message.
     *
     * @param SegmentInterface $segment The segment to add
     *
     * @return static
     */
    public function addSegment(SegmentInterface $segment)
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
        return (new Serializer)->serialize(...$this->getAllSegments());
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
