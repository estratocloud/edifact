<?php

namespace Estrato\Edifact\Segments;

/**
 * Represent a segment of an EDI message.
 */
interface SegmentInterface
{
    /**
     * Get the code of this segment.
     */
    public function getSegmentCode(): string;


    /**
     * Get all the elements from the segment.
     *
     * @return array<array<string>|string>
     */
    public function getAllElements(): array;


    /**
     * Get an element from the segment.
     *
     * @return mixed Returns null if the element does not exist
     */
    public function getElement(int $key): mixed;
}
