<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Segments\SegmentInterface;

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
     * @param string $code The code of the segment to return
     *
     * @return iterable<SegmentInterface>
     */
    public function getSegments(string $code): iterable;


    /**
     * Get the first segment that matches the requested code.
     *
     * @param string $code The code of the segment to return
     *
     * @return SegmentInterface|null
     */
    public function getSegment(string $code): ?SegmentInterface;


    /**
     * Add multiple segments to the message.
     *
     * @param SegmentInterface ...$segments The segments to add
     *
     * @return $this
     */
    public function addSegments(SegmentInterface ...$segments): self;


    /**
     * Add a segment to the message.
     *
     * @param SegmentInterface $segment The segment to add
     *
     * @return $this
     */
    public function addSegment(SegmentInterface $segment): self;


    /**
     * Serialize all the segments added to this object.
     *
     * @return string
     */
    public function serialize(): string;
}
