<?php

namespace Metroplex\Edifact\Segments;

/**
 * Represent a segment of an EDI message.
 */
interface SegmentInterface
{
    /**
     * Get the name of this segment.
     *
     * @return string
     */
    public function getName();


    /**
     * Get all the elements from the segment.
     *
     * @return array
     */
    public function getAllElements();


    /**
     * Get an element from the segment.
     *
     * @param int $key The element to get
     *
     * @return mixed
     */
    public function getElement($key);
}
