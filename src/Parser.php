<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\Characters as ControlCharacters;
use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Segments\Segment;

/**
 * Parse EDI messages into an array of segments.
 */
final class Parser
{

    /**
     * Parse the message into an array of segments.
     *
     * @param string $message The EDI message
     *
     * @return array
     */
    public function parse($message, ControlCharactersInterface $characters = null)
    {
        $tokenizer = new Tokenizer;

        $characters = $this->getControlCharacters($message, $characters);

        $tokens = $tokenizer->getTokens($message, $characters);

        $segments = $this->convertTokensToSegments($tokens);

        return $segments;
    }


    /**
     * Read (and remove) the UNA segment from the passed string.
     *
     * @param string $message The EDI message to extract the UNA from
     *
     * @return ControlCharactersInterface
     */
    private function getControlCharacters(&$message, ControlCharactersInterface $characters = null)
    {
        if ($characters === null) {
            $characters = new ControlCharacters;
        }

        if (substr($message, 0, 3) !== "UNA") {
            return $characters;
        }

        # Get the character definitions
        $chars = mb_substr($message, 3, 6);

        # Remove the UNA segment from the original message
        $message = ltrim(mb_substr($message, 9), "\r\n");

        $pos = 0;
        return $characters
            ->withComponentSeparator(mb_substr($chars, $pos++, 1))
            ->withDataSeparator(mb_substr($chars, $pos++, 1))
            ->withDecimalPoint(mb_substr($chars, $pos++, 1))
            ->withEscapeCharacter(mb_substr($chars, $pos++, 1))
            ->withReservedSpace(mb_substr($chars, $pos++, 1))
            ->withSegmentTerminator(mb_substr($chars, $pos++, 1));
    }


    /**
     * Convert the tokenized message into an array of segments.
     *
     * @param Token[] $tokens The tokens that make up the message
     *
     * @return Segment[]
     */
    private function convertTokensToSegments(array $tokens)
    {
        $segments = [];
        $currentSegment = -1;
        $inSegment = false;

        foreach ($tokens as $token) {

            # If we're in the middle of a segment, check if we've reached the end
            if ($inSegment) {
                if ($token->type === Token::TERMINATOR) {
                    $inSegment = false;
                    continue;
                }

            # If we're not in a segment, then start a new one now
            } else {
                $inSegment = true;
                $currentSegment++;
                $segments[$currentSegment] = [];
                $part = 0;
                $key = 0;
            }

            /**
             * Whenever we reach a data separator, we increment the $part counter to
             * move on to the next part of data, and reset our $key counter for
             * the elements within the part.
             */
            if ($token->type === Token::DATA_SEPARATOR) {
                $part++;
                $key = 0;
                continue;
            }

            /**
             * Whenever we reach a component separator, we just increment the $key
             * counter for the elements within the current part.
             */
            if ($token->type === Token::COMPONENT_SEPARATOR) {
                $key++;
                continue;
            }

            /**
             * If this isn't the first part, then backfill any missing parts.
             * This is because empty parts are not represented by a token,
             * so we need to simulate them here.
             */
            if ($part > 0) {
                for ($i = 0; $i < $part; $i++) {
                    if (!isset($segments[$currentSegment][$i])) {
                        $segments[$currentSegment][$i] = "";
                    }
                }
            }

            # If this is the first element within the part then just set it as a string.
            if ($key === 0) {
                $segments[$currentSegment][$part] = $token->value;
                continue;
            }

            /**
             * For the same as the parts, we need to backfill any empty elements.
             * We also use this code to append the element we are currently processing.
             */
            for ($i = 0; $i <= $key; $i++) {
                $value = ($i === $key) ? $token->value : "";

                # If there is an initial element set as a string, we need to convert it into an array before we append to it
                if (isset($segments[$currentSegment][$part]) && !is_array($segments[$currentSegment][$part])) {
                    $segments[$currentSegment][$part] = [$segments[$currentSegment][$part]];
                }

                # If this part does not exist, set it now
                if (!isset($segments[$currentSegment][$part][$i])) {
                    $segments[$currentSegment][$part][$i] = $value;
                }
            }
        }

        foreach ($segments as $segment) {
            $name = array_shift($segment);
            yield new Segment($name, ...$segment);
        }
    }
}
