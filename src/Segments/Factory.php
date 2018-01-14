<?php

namespace Metroplex\Edifact\Segments;

use Metroplex\Edifact\Control\CharactersInterface;

/**
 * Factory for producing segments.
 */
final class Factory implements FactoryInterface
{
    /**
     * Create a new Segment instance.
     *
     * @param ControlCharactersInterface $characters The control characters
     * @param string $name The name of the segment
     * @param array $elements The data elements for this segment
     *
     * @return SegmentInterface
     */
    public function createSegment(CharactersInterface $characters, $name, ...$elements)
    {
        return new Segment($name, ...$elements);
    }
}
