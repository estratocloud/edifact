<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Segments\SegmentInterface;

interface MessageInterface
{
    /**
     * Get all the segments.
     *
     * @return iterable<SegmentInterface>
     */
    public function getAllSegments(): iterable;


    /**
     * Get all the segments that match the requested code.
     *
     * @return iterable<SegmentInterface>
     */
    public function getSegments(string $code): iterable;


    /**
     * Get the first segment that matches the requested code.
     */
    public function getSegment(string $code): ?SegmentInterface;


    /**
     * Add multiple segments to the message.
     *
     * @return $this
     */
    public function addSegments(SegmentInterface ...$segments): self;


    /**
     * Add a segment to the message.
     *
     * @return $this
     */
    public function addSegment(SegmentInterface $segment): self;


    /**
     * Serialize all the segments added to this object.
     */
    public function serialize(): string;
}
