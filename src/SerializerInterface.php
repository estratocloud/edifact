<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Segments\SegmentInterface;

interface SerializerInterface
{
    /**
     * Serialize all the passed segments.
     */
    public function serialize(SegmentInterface ...$segments): string;
}
