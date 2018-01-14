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
     * @param array $elements The data elements for this segment.
     */
    public function __construct($code, ...$elements)
    {
        $this->code = $code;
        $this->elements = $elements;
    }


    /**
     * Get the code of this segment.
     *
     * @return string
     */
    public function getSegmentCode()
    {
        return $this->code;
    }


    /**
     * Get all the elements from the segment.
     *
     * @return array
     */
    public function getAllElements()
    {
        return $this->elements;
    }


    /**
     * Get an element from the segment.
     *
     * @param int $key The element to get
     *
     * @return mixed
     */
    public function getElement($key)
    {
        if (!isset($this->elements[$key])) {
            return;
        }

        return $this->elements[$key];
    }
}
