<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Exceptions\InvalidArgumentException;
use Estrato\Edifact\Exceptions\ParseException;
use Estrato\Edifact\Segments\SegmentInterface;

/**
 * Represent an EDI message for both reading and writing.
 */
final class Message implements MessageInterface
{
    /**
     * @var array<SegmentInterface> $segments The segments that make up this message.
     */
    private array $segments = [];


    /**
     * Create a new instance from a file.
     *
     * @param string $file The full path to a file that contains an EDI message
     *
     * @throws ParseException
     */
    public static function fromFile(string $file): self
    {
        $message = file_get_contents($file);
        if ($message === false) {
            throw new InvalidArgumentException("Unable to read the file: {$file}");
        }

        return self::fromString($message);
    }


    /**
     * Create a new instance from a string.
     *
     * @throws ParseException
     */
    public static function fromString(string $string): self
    {
        $segments = (new Parser())->parse($string);
        return self::fromSegments(...$segments);
    }


    /**
     * Create a new instance from an array of segments.
     */
    public static function fromSegments(SegmentInterface ...$segments): self
    {
        return (new self())->addSegments(...$segments);
    }


    /**
     * Get all the segments.
     *
     * @return array<SegmentInterface>
     */
    public function getAllSegments(): array
    {
        return $this->segments;
    }


    /**
     * Get all the segments that match the requested code.
     *
     * @return iterable<SegmentInterface>
     */
    public function getSegments(string $code): iterable
    {
        foreach ($this->getAllSegments() as $segment) {
            if ($segment->getSegmentCode() === $code) {
                yield $segment;
            }
        }
    }


    /**
     * Get the first segment that matches the requested code.
     */
    public function getSegment(string $code): ?SegmentInterface
    {
        foreach ($this->getSegments($code) as $segment) {
            return $segment;
        }

        return null;
    }


    /**
     * Add multiple segments to the message.
     *
     * @return $this
     */
    public function addSegments(SegmentInterface ...$segments): MessageInterface
    {
        foreach ($segments as $segment) {
            $this->addSegment($segment);
        }

        return $this;
    }


    /**
     * Add a segment to the message.
     *
     * @return $this
     */
    public function addSegment(SegmentInterface $segment): MessageInterface
    {
        $this->segments[] = $segment;

        return $this;
    }


    /**
     * Serialize all the segments added to this object.
     */
    public function serialize(): string
    {
        return (new Serializer())->serialize(...$this->getAllSegments());
    }


    /**
     * Allow the object to be serialized by casting to a string.
     */
    public function __toString()
    {
        return $this->serialize();
    }
}
