<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Estrato\Edifact\Exceptions\ParseException;

/**
 * Convert EDI messages into tokens for parsing.
 */
interface TokenizerInterface
{
    /**
     * Convert the passed message into tokens.
     *
     * @return Token[]
     * @throws ParseException
     */
    public function getTokens(string $message, ControlCharactersInterface $characters): array;
}
