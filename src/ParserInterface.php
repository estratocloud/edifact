<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Exceptions\ParseException;
use Metroplex\Edifact\Segments\SegmentInterface;

interface ParserInterface
{


    /**
     * Parse the message into an array of segments.
     *
     * @param string $message The EDI message
     * @param ControlCharactersInterface|null $characters The control characters
     *
     * @return iterable<SegmentInterface>
     * @throws ParseException
     */
    public function parse(string $message, ControlCharactersInterface $characters = null): iterable;
}
