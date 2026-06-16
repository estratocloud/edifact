<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Estrato\Edifact\Exceptions\ParseException;
use Estrato\Edifact\Segments\SegmentInterface;

interface ParserInterface
{
    /**
     * Parse the message into an array of segments.
     *
     * @return iterable<SegmentInterface>
     * @throws ParseException
     */
    public function parse(string $message, ?ControlCharactersInterface $characters = null): iterable;
}
