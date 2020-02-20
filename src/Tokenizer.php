<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Exceptions\ParseException;
use function in_array;
use function strlen;
use function substr;

/**
 * Convert EDI messages into tokens for parsing.
 */
final class Tokenizer
{
    /**
     * Convert the passed message into tokens.
     *
     * @param string $message The EDI message
     * @param ControlCharactersInterface $characters
     *
     * @return Token[]
     * @throws ParseException
     */
    public function getTokens(string $message, ControlCharactersInterface $characters): array
    {
        $escapeCharacter = $characters->getEscapeCharacter();
        $componentSeparator = $characters->getComponentSeparator();
        $dataSeparator = $characters->getDataSeparator();
        $segmentTerminator = $characters->getSegmentTerminator();

        $position = 0;
        $size = strlen($message);
        $tokens = [];
        $buffer = "";
        $isEscaped = false;
        while ($position < $size) {
            $char = substr($message, $position, 1);

            if ($isEscaped) {
                $isEscaped = false;
                $buffer .= $char;
            } elseif ($char === $componentSeparator) {
                if (strlen($buffer) !== 0) {
                    $tokens[] = new Token(Token::CONTENT, $buffer);
                    $buffer = "";
                }
                $tokens[] = new Token(Token::COMPONENT_SEPARATOR, $char);
            } elseif ($char === $dataSeparator) {
                if (strlen($buffer) !== 0) {
                    $tokens[] = new Token(Token::CONTENT, $buffer);
                    $buffer = "";
                }
                $tokens[] = new Token(Token::DATA_SEPARATOR, $char);
            } elseif ($char === $segmentTerminator) {
                if (strlen($buffer) !== 0) {
                    $tokens[] = new Token(Token::CONTENT, $buffer);
                    $buffer = "";
                }
                $tokens[] = new Token(Token::TERMINATOR, $char);

                while (in_array(substr($message, $position + 1, 1), ["\r", "\n"], true)) {
                    ++$position;
                }
            } elseif ($char === $escapeCharacter) {
                $isEscaped = true;
            } else {
                $buffer .= $char;
            }

            ++$position;
        }

        if (strlen($buffer) !== 0 || $isEscaped) {
            throw new ParseException("Unexpected end of EDI message");
        }

        return $tokens;
    }
}
