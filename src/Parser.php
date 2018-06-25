<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\Characters as ControlCharacters;
use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Segments\Factory;
use Metroplex\Edifact\Segments\FactoryInterface;
use Metroplex\Edifact\Segments\SegmentInterface;
use function array_shift;
use function is_array;
use function ltrim;
use function substr;

/**
 * Parse EDI messages into an array of segments.
 */
final class Parser
{
    /**
     * @var FactoryInterface $factory A segment factory for creating segments.
     */
    private $factory;


    /**
     * Create a new instance.
     *
     * @param FactoryInterface $factory A segment factory for creating segments
     */
    public function __construct(FactoryInterface $factory = null)
    {
        if ($factory === null) {
            $factory = new Factory;
        }
        $this->factory = $factory;
    }


    /**
     * Parse the message into an array of segments.
     *
     * @param string $message The EDI message
     * @param ControlCharactersInterface $characters The control characters
     *
     * @return SegmentInterface[]
     */
    public function parse($message, ControlCharactersInterface $characters = null)
    {
        $tokenizer = new Tokenizer;

        $characters = $this->getControlCharacters($message, $characters);

        $tokens = $tokenizer->getTokens($message, $characters);

        $segments = $this->convertTokensToSegments($tokens, $characters);

        return $segments;
    }


    /**
     * Read (and remove) the UNA segment from the passed string.
     *
     * @param string $message The EDI message to extract the UNA from
     * @param ControlCharactersInterface $characters The control characters
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
        $chars = substr($message, 3, 6);

        # Remove the UNA segment from the original message
        $message = ltrim(substr($message, 9), "\r\n");

        $pos = 0;
        return $characters
            ->withComponentSeparator(substr($chars, $pos++, 1))
            ->withDataSeparator(substr($chars, $pos++, 1))
            ->withDecimalPoint(substr($chars, $pos++, 1))
            ->withEscapeCharacter(substr($chars, $pos++, 1))
            ->withReservedSpace(substr($chars, $pos++, 1))
            ->withSegmentTerminator(substr($chars, $pos++, 1));
    }


    /**
     * Convert the tokenized message into an array of segments.
     *
     * @param Token[] $tokens The tokens that make up the message
     * @param ControlCharactersInterface $characters The control characters
     *
     * @return SegmentInterface[]
     */
    private function convertTokensToSegments(array $tokens, ControlCharactersInterface $characters)
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
            $code = array_shift($segment);
            yield $this->factory->createSegment($characters, $code, ...$segment);
        }
    }
}
