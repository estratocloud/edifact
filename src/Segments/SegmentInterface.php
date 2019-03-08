<?php

namespace Metroplex\Edifact\Segments;

/**
 * Represent a segment of an EDI message.
 */
interface SegmentInterface
{
    /**
     * Get the code of this segment.
     *
     * @return string
     */
    public function getSegmentCode(): string;


    /**
     * Get all the elements from the segment.
     *
     * @return array
     */
    public function getAllElements(): array;


    /**
     * Get an element from the segment.
     *
     * @param int $key The element to get
     *
     * @return mixed
     */
    public function getElement(int $key);
}
