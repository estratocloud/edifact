<?php

namespace Metroplex\Edifact\Segments;

use Metroplex\Edifact\Control\CharactersInterface;

/**
 * Factory for producing segments.
 */
final class Factory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createSegment(CharactersInterface $characters, $name, ...$elements): SegmentInterface
    {
        return new Segment($name, ...$elements);
    }
}
