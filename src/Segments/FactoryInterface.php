<?php

namespace Estrato\Edifact\Segments;

use Estrato\Edifact\Control\CharactersInterface;

/**
 * Factory for producing segments.
 */
interface FactoryInterface
{
    /**
     * Create a new instance of the relevant class type.
     *
     * @param CharactersInterface $characters The control characters
     * @param string $name The name of the segment
     * @param mixed ...$elements The data elements for this segment
     *
     * @return SegmentInterface
     */
    public function createSegment(CharactersInterface $characters, $name, ...$elements): SegmentInterface;
}
