<?php

namespace Metroplex\Edifact\Segments;

/**
 * Represent a segment of an EDI message.
 */
abstract class AbstractSegment implements SegmentInterface
{
    /**
     * @var string $code The code of the segment.
     */
    private $code;

    /**
     * @var array $elements The data elements for this segment.
     */
    private $elements;


    /**
     * Create a new instance.
     *
     * @param string $code The code of the segment.
     * @param mixed ...$elements The data elements for this segment.
     */
    public function __construct(string $code, ...$elements)
    {
        $this->code = $code;
        $this->elements = $elements;
    }


    /**
     * Get the code of this segment.
     *
     * @return string
     */
    public function getSegmentCode(): string
    {
        return $this->code;
    }


    /**
     * Get all the elements from the segment.
     *
     * @return array
     */
    public function getAllElements(): array
    {
        return $this->elements;
    }


    /**
     * Get an element from the segment.
     *
     * @param int $key The element to get
     *
     * @return mixed|null
     */
    public function getElement(int $key)
    {
        if (!isset($this->elements[$key])) {
            return null;
        }

        return $this->elements[$key];
    }


    /**
     * @inheritDoc
     */
    public function setElement($key, $value)
    {
        $this->elements[$key] = $value;
    }
}
