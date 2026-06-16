<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Control\Characters as ControlCharacters;
use Estrato\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Estrato\Edifact\Exceptions\ParseException;
use Estrato\Edifact\Segments\Factory;
use Estrato\Edifact\Segments\FactoryInterface;
use Estrato\Edifact\Segments\SegmentInterface;

use function array_shift;
use function is_array;
use function is_string;
use function ltrim;
use function substr;

/**
 * Parse EDI messages into an array of segments.
 */
final class Parser implements ParserInterface
{
    private FactoryInterface $factory;

    private TokenizerInterface $tokenizer;


    /**
     * Create a new instance.
     */
    public function __construct(?FactoryInterface $factory = null, ?TokenizerInterface $tokenizer = null)
    {
        if ($factory === null) {
            $factory = new Factory();
        }
        $this->factory = $factory;

        if ($tokenizer === null) {
            $tokenizer = new Tokenizer();
        }
        $this->tokenizer = $tokenizer;
    }


    /**
     * Parse the message into an array of segments.
     *
     * @return iterable<SegmentInterface>
     * @throws ParseException
     */
    public function parse(string $message, ?ControlCharactersInterface $characters = null): iterable
    {
        $characters = $this->getControlCharacters($message, $characters);

        $tokens = $this->tokenizer->getTokens($message, $characters);

        $segments = $this->convertTokensToSegments($tokens, $characters);

        return $segments;
    }


    /**
     * Read (and remove) the UNA segment from the passed string.
     */
    private function getControlCharacters(string &$message, ?ControlCharactersInterface $characters = null): ControlCharactersInterface
    {
        if ($characters === null) {
            $characters = new ControlCharacters();
        }

        if (substr($message, 0, 3) !== "UNA") {
            return $characters->withUNASegment(false);
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
     * @param array<Token> $tokens The tokens that make up the message
     *
     * @return iterable<SegmentInterface>
     * @throws ParseException
     */
    private function convertTokensToSegments(iterable $tokens, ControlCharactersInterface $characters): iterable
    {
        $currentSegment = null;
        $inSegment = false;

        $part = 0;
        $key = 0;
        foreach ($tokens as $token) {
            # If we're in the middle of a segment, check if we've reached the end
            if ($inSegment) {
                if ($token->type === Token::TERMINATOR) {
                    $inSegment = false;
                    continue;
                }

            # If we're not in a segment, then start a new one now
            } else {
                # Return the previous segment before starting a new one
                if ($currentSegment !== null) {
                    yield $this->produceSegment($currentSegment, $characters);
                }
                $inSegment = true;
                $currentSegment = [];
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
                    if (!isset($currentSegment[$i])) {
                        $currentSegment[$i] = "";
                    }
                }
            }

            # If this is the first element within the part then just set it as a string.
            if ($key === 0) {
                $currentSegment[$part] = $token->value;
                continue;
            }

            /**
             * For the same as the parts, we need to backfill any empty elements.
             * We also use this code to append the element we are currently processing.
             */
            for ($i = 0; $i <= $key; $i++) {
                $value = ($i === $key) ? $token->value : "";

                # If there is an initial element set as a string, we need to convert it into an array before we append to it
                if (isset($currentSegment[$part]) && !is_array($currentSegment[$part])) {
                    $currentSegment[$part] = [$currentSegment[$part]];
                }

                # If this part does not exist, set it now
                if (!isset($currentSegment[$part][$i])) {
                    $currentSegment[$part][$i] = $value;
                }
            }
        }

        if ($currentSegment !== null) {
            yield $this->produceSegment($currentSegment, $characters);
        }
    }


    /**
     * @param array<mixed> $elements
     *
     * @throws ParseException
     */
    private function produceSegment(array $elements, ControlCharactersInterface $characters): SegmentInterface
    {
        $code = array_shift($elements);
        if (!is_string($code)) {
            throw new ParseException("Invalid segment encountered, first element should be the name");
        }

        return $this->factory->createSegment($characters, $code, ...$elements);
    }
}
