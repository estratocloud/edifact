<?php

namespace Estrato\Edifact\Segments;

use Estrato\Edifact\Control\CharactersInterface;

/**
 * Factory for producing segments.
 */
final class Factory implements FactoryInterface
{
    public function createSegment(CharactersInterface $characters, $name, ...$elements): SegmentInterface
    {
        return new Segment($name, ...$elements);
    }
}
