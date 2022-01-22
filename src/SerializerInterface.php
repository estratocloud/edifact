<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Segments\SegmentInterface;

interface SerializerInterface
{
    /**
     * Serialize all the passed segments.
     *
     * @param SegmentInterface ...$segments The segments to serialize
     *
     * @return string
     */
    public function serialize(SegmentInterface ...$segments): string;
}
