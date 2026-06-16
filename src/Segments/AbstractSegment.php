<?php

namespace Estrato\Edifact\Segments;

/**
 * Represent a segment of an EDI message.
 */
abstract class AbstractSegment implements SegmentInterface
{
    private string $code;

    /**
     * @var array<mixed> $elements The data elements for this segment.
     */
    private array $elements;


    /**
     * Create a new instance.
     *
     * @param string $code The code of the segment.
     * @param mixed ...$elements The data elements for this segment.
     */
    public function __construct(string $code, mixed ...$elements)
    {
        $this->code = $code;
        $this->elements = $elements;
    }


    /**
     * Get the code of this segment.
     */
    public function getSegmentCode(): string
    {
        return $this->code;
    }


    /**
     * Get all the elements from the segment.
     *
     * @return array<mixed>
     */
    public function getAllElements(): array
    {
        return $this->elements;
    }


    /**
     * Get an element from the segment.
     */
    public function getElement(int $key): mixed
    {
        if (!isset($this->elements[$key])) {
            return null;
        }

        return $this->elements[$key];
    }
}
